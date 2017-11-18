<?php

namespace Diag\Controller;


use Diag\DataMapper;
use Diag\DiagResponse;
use Diag\LogReader;
use Diag\Record;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Api
{
    private $logReader;
    private $dataMapper;

    public function __construct(DataMapper $dataMapper, LogReader $logReader)
    {
        $this->logReader = $logReader;
        $this->dataMapper = $dataMapper;
    }

    private $container;

    public function setContainer(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getList(Request $request): JsonResponse
    {
        $data = $this->logReader->getLast($request->get('limit', 10));
        $response = new JsonResponse($data);
        return $response;
    }

    public function postRecords(Request $request): JsonResponse
    {
        $this->dataMapper->store($request->request->all());
        $response = new JsonResponse(['status' => 'ok']);

        return $response;
    }

    public function postRecord(Request $request): JsonResponse
    {
        $record = new Record($request->request->all());

        $this->dataMapper->store($record);

        $response = new JsonResponse($record->toArray());
        return $response;
    }

    public function getRecord(Request $request): JsonResponse
    {
        $id = $request->get('id');

        $response = new DiagResponse($this->logReader->get($id));
        return $response;
    }
}
