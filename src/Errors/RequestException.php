<?php

namespace Extiverse\Api\Errors;

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Str;

class RequestException extends \Exception
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
        $message = "HTTP response error {$this->status}.";

        $message .= Str::limit($this->response->getBody()->getContents(), 1000);

        return $message;
    }
}