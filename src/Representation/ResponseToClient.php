<?php

namespace App\Representation;

use JsonSerializable;

class ResponseToClient implements JsonSerializable
{
    private string|null $message;
    private array|null|string $response;
    private int|null $code;

    public function __construct(array|null|string $response, int|null $code,string|null $message)
    {
        $this->message = $message;
        $this->response = $response;
        $this->code = $code;
    }

    public function jsonSerialize(): array
    {
        return [
            'header' => [
                'code' => $this->code,
                'message' => $this->message,
            ],
            'response' => $this->response,
        ];
    }
}
