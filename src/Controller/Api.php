<?php

namespace Diag\Controller;


use Diag\DataMapper;
use Diag\LogReader;
use Diag\Record;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $dataMapper = new DataMapper(new \Diag\Storage\Clickhouse);
        $dataMapper->store($request->request->all());

        $response = new JsonResponse(['status'=>'ok']);
//        $response = new JsonResponse($record->toArray());
        return $response;
    }

    public function postRecord(Request $request): JsonResponse
    {
        $record = new Record($request->request->all());

        $dataMapper = new DataMapper(new \Diag\Storage\Clickhouse);
        $dataMapper->store($record);

        $response = new JsonResponse($record->toArray());
        return $response;
    }

    public function getRecord(Request $request): JsonResponse
    {
        $dataReader = new LogReader(new \Diag\Storage\Clickhouse);
        $response = new JsonResponse($dataReader->get($request->get('id')));
        return $response;
    }
}
