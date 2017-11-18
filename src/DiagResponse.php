<?php

namespace Diag;

use Symfony\Component\HttpFoundation\JsonResponse;

class DiagResponse extends JsonResponse
{
    public function __construct($data = null, int $status = 200, array $headers = [], bool $json = false)
    {
        if ($data instanceof DiagRecord) {
            $data = $data->toArray();
        }
        parent::__construct($data, $status, $headers, $json);
    }
}
