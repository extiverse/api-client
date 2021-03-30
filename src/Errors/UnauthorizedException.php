<?php

namespace Extiverse\Api\Errors;

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Str;

class UnauthorizedException extends \Exception
{
    private int $status;
    private Response $response;

    public function __construct(int $status, Response $response)
    {
        $this->status = $status;
        $this->response = $response;

        parent::__construct($this->setMessage());
    }

    private function setMessage()
    {
        return "HTTP response error {$this->status}. Invalid token.";
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}