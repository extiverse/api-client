<?php

namespace Extiverse\Api;

use Dotenv\Dotenv;
use GuzzleHttp\Client;

class Extiverse
{
    static protected self $instance;
    protected array $clients = [];
    protected ?bool $testing;
    protected ?string $token;

    protected function __construct()
    {
        (Dotenv::create(__DIR__ . '/../'))->load();
    }

    public function getClient(?string $onbehalfOf): Client
    {
        $token = $onbehalfOf ?: $this->getToken();

        if (array_key_exists($token, $this->clients)) {
            $this->clients[$token] = new Client([
                'base_uri' => $this->testing ? 'http://extiverse.test/api/v1' : 'https://extiverse.com/api/v1',
                'headers' => [
                    'Accept' => 'application/json, application/vnd.api+json',
                    'Authorization' => 'Bearer ' . $token,
                    'User-Agent' => 'Extiverse-api-client',
                ],
                'verify' => $this->getTesting() === false,
                'timeout' => 5,
                'connect_timeout' => 2,
            ]);
        }

        return $this->clients[$token];
    }

    public function setClient(Client $client, string $token): self
    {
        $this->clients[$token] = $client;

        return $this;
    }

    /**
     * Enables testing features during development.
     *
     * @param bool $testing
     * @return $this
     */
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

        return $this->testing;
    }

    public static function instance(): self
    {
        if (! static::$instance) {
            static::$instance = new self;
        }

        return static::$instance;
    }
}