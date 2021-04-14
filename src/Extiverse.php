<?php

namespace Extiverse\Api;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Dotenv\Dotenv;
use Extiverse\Api\JsonApi\Item;
use Extiverse\Api\Requests\User;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Utils;
use Psr\SimpleCache\CacheInterface;

class Extiverse
{
    static protected ?self $instance = null;
    protected array $clients = [];
    protected ?bool $testing = null;
    protected ?string $token = null;
    protected ?CacheInterface $cache = null;
    protected ?Item $me = null;

    private function __construct()
    {
        Dotenv::createMutable([__DIR__ . '/../'])->safeLoad();
    }

    public function getClient(string $onbehalfOf = null): Client
    {
        $token = $onbehalfOf ?: $this->getToken();

        if (! array_key_exists($token, $this->clients)) {
            $this->clients[$token] = new Client([
                'base_uri' => $this->getTesting() ? 'http://extiverse.test/api/v1/' : 'https://extiverse.com/api/v1/',
                'headers' => [
                    'Accept' => 'application/json, application/vnd.api+json',
                    'Authorization' => 'Bearer ' . $token,
                    'User-Agent' => 'Extiverse-api-client',
                    'X-Extiverse-By' => $this->getToken() !== $token
                        ? $this->authoredBy()
                        : null
                ],
                'verify' => $this->getTesting() === false,
                'timeout' => 5,
                'connect_timeout' => 2,
                'handler' => $this->defaultMiddlewareStack(),
                'http_errors' => true,
            ]);
        }

        return $this->clients[$token];
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

    private function defaultMiddlewareStack(): HandlerStack
    {
        $stack = new HandlerStack;
        $stack->setHandler(Utils::chooseHandler());

        $stack->push(Middleware::mapResponse(new Guzzle\JsonApiParserMiddleware));

        return $stack;
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
            $this->me = (new User)->me();
        }

        return $this->me
            ? "{$this->me->id} - {$this->me->nickname}"
            : null;
    }
}