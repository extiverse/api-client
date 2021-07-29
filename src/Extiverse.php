<?php

namespace Extiverse\Api;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Dotenv\Dotenv;
use Extiverse\Api\JsonApi\Parser\DocumentParser;
use Extiverse\Api\JsonApi\Repositories\UserRepository;
use Extiverse\Api\JsonApi\Types\TypeMapper;
use Extiverse\Api\JsonApi\Types\User\User;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Utils;
use Psr\SimpleCache\CacheInterface;
use Swis\JsonApi\Client\Client as SwisClient;
use Swis\JsonApi\Client\DocumentClient;
use Swis\JsonApi\Client\Interfaces\DocumentClientInterface;
use Swis\JsonApi\Client\Interfaces\TypeMapperInterface;
use Swis\JsonApi\Client\Parsers\ResponseParser;

class Extiverse
{
    static protected ?self $instance = null;
    protected array $clients = [];
    protected ?bool $testing = null;
    protected ?string $token = null;
    protected ?CacheInterface $cache = null;
    protected ?User $me = null;

    private function __construct()
    {
        Dotenv::createMutable([__DIR__ . '/../'])->safeLoad();
    }

    public function getClient(string $onbehalfOf = null): DocumentClientInterface
    {
        $token = $onbehalfOf ?: $this->getToken();

        if (! array_key_exists($token, $this->clients)) {
            $this->generateClient($token);
        }

        return $this->clients[$token];
    }

    protected function generateClient(string $token)
    {
        $http = $this->generateGuzzleClient($token);

        $this->clients[$token] = new DocumentClient(
            new SwisClient($http),
            new ResponseParser(DocumentParser::create(new TypeMapper))
        );
    }

    public function generateGuzzleClient(string $token = null, bool $api = true)
    {
        $token = $token ?? $this->getToken();

        $headers = [
            'Accept' => 'application/json, application/vnd.api+json',
            'Authorization' => 'Bearer ' . $token,
            'User-Agent' => 'Extiverse-api-client',
            'X-Extiverse-By' => $this->getToken() !== $token
                ? $this->authoredBy()
                : null
        ];

        if ($token) {
            $headers['Authorization'] = "Bearer $token";
            $headers['X-Extiverse-By'] = $this->getToken() !== $token ? $this->authoredBy() : null;
        }

        $baseUri = $this->getTesting()
            ? 'http://extiverse.test/'
            : 'https://extiverse.com/';

        if ($api) $baseUri .= "api/v1/";

        return new Client([
            'base_uri' => $baseUri,
            'headers' => $headers,
            'verify' => $this->getTesting() === false,
            'timeout' => 5,
            'connect_timeout' => 2,
            'http_errors' => true,
        ]);
    }

    public function setClient(Client $client, string $token): self
    {
        $this->clients[$token] = $client;

        return $this;
    }

    public function setTesting(bool $testing = true): self
    {
        $this->testing = $testing;

        return $this;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    protected function getToken(): ?string
    {
        if (! $this->token) {
            $this->token = env('TOKEN');
        }

        return $this->token;
    }

    protected function getTesting(): bool
    {
        if ($this->testing === null && env('TESTING')) {
            $this->testing = boolval(env('TESTING'));
        }

        return $this->testing ?? false;
    }

    public static function instance(): self
    {
        if (! static::$instance) {
            static::$instance = new Extiverse;
        }

        return static::$instance;
    }

    public function setCache(CacheInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    public function getCache(): CacheInterface
    {
        if (! $this->cache) {
            $this->cache = new ArrayCachePool(1000);
        }

        return $this->cache;
    }

    private function authoredBy(): ?string
    {
        if (! $this->me) {
            $this->me = (new UserRepository)->me();
        }

        return $this->me
            ? "{$this->me->id} - {$this->me->nickname}"
            : null;
    }
}
