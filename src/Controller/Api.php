<?php

namespace Diag\Controller;


use Diag\DiagRecord;
use Diag\DiagRecordIterator;
use Diag\DiagResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Api
{
    public function getList(array $filters): JsonResponse
    {
        $response = new JsonResponse([]);
        return $response;
    }

    public function postRecords(Request $request): JsonResponse
    {
        $response = new JsonResponse([]);
        return $response;
    }

    public function postRecord(Request $request): JsonResponse
    {
        $response = new JsonResponse([]);
        return $response;
    }

    public function getRecord(Request $request): JsonResponse
    {
        $response = new JsonResponse([]);
        return $response;
    }
}
