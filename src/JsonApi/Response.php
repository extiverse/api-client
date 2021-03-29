<?php

namespace Extiverse\Api\JsonApi;

class Response extends \GuzzleHttp\Psr7\Response
{
    protected array $attributes = [];

    public function withAttribute(string $attribute, $value)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    public function getAttribute(string $attribute, $default = null)
    {
        return $this->attributes[$attribute] ?? $default;
    }
}