<?php


namespace App\Libraries;


use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class ApiResponse
{
    private $body;
    private $status = 200;
    private $response;

    public function __construct($body = null)
    {
        $this->body = new Collection($body ?? []);
        $this->response = new Response();
    }

    public function status(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function message($message): self
    {
        $this->body->put('message', $message);
        return $this;
    }

    public function setData($data): self
    {
        $this->body->put('data', $data);

        return $this;
    }

    public function success($message = null): self
    {
        $this->body->put('success', true);

        if ( !is_null($message) ) {
            $this->message($message);
        }

        return $this;
    }

    public function fail($message = null): self
    {
        $this->body->put('success', false);

        $this->status(400);
        $this->body->put('status', $this->status);

        if ( !is_null($message) ) {
            $this->message($message);
        }

        return $this;
    }


    private function buildResponse()
    {
        $this->body->put('status', $this->status);

        return $this->response
            ->setStatusCode($this->status)
            ->setContent($this->body);
    }

    public function toJson()
    {
        return $this->buildResponse()->header('Content-type', 'application/json');
    }


}
