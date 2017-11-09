<?php

namespace Diag;

class DiagResponse
{
    private $statusCode;
    private $content;

    public function __construct($content, $statusCode = 200)
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
    }

    public function __toString()
    {
        return json_encode([
            'status' => $this->statusCode,
            'result' => $this->content
        ]);
    }
}