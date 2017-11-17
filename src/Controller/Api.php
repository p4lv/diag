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
    private $container;

    public function setContainer(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getList(Request $request): JsonResponse
    {
        /** @var LogReader $dataReader */
        $dataReader = $this->container->get(LogReader::class);
        $data = $dataReader->getLast($request->get('limit', 10));
        $response = new JsonResponse($data);
        return $response;
    }

    public function postRecords(Request $request): JsonResponse
    {
        $dataMapper = $this->container->get(DataMapper::class);
        $dataMapper->store($request->request->all());

        $response = new JsonResponse(['status' => 'ok']);
//        $response = new JsonResponse($record->toArray());
        return $response;
    }

    public function postRecord(Request $request): JsonResponse
    {
        $record = new Record($request->request->all());

        $dataMapper = $this->container->get(DataMapper::class);
        $dataMapper->store($record);

        $response = new JsonResponse($record->toArray());
        return $response;
    }

    public function getRecord(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $dataReader = $this->container->get(LogReader::class);

        $response = new DiagResponse($dataReader->get($id));
        return $response;
    }
}
