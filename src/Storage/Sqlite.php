<?php

namespace Diag\Storage;

use Diag\DiagRecord;
use Diag\Exception\StorageFlushError;
use Diag\Record;

class Sqlite implements CanPersist, CanFetch
{
    private $engine;
    private $logTable;

    public function __construct(\PDO $engine, $logTable = 'table_log')
    {
        $this->engine = $engine;
        $this->engine->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->logTable = $logTable;
    }

    public function last($numberOfElements = 10, ?int $beforeId = null): array
    {
        $sql = "select id, message, severity, eventType, projectId, createdAt, version
from {$this->logTable} ";

        if ($beforeId) {
            $sql .= " where id < :beforeId ";
        }
        $sql .= " order by id desc limit {$numberOfElements}";
        $stm = $this->engine->prepare($sql);
        if ($beforeId) {
            $stm->bindParam(':beforeId', $beforeId, \PDO::PARAM_INT);
        }

        $stm->execute();
        $result = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function insert(DiagRecord $data): bool
    {

        $sql = "insert into {$this->logTable} (
id,
message,
severity,
eventType,
projectId,
createdAt,
version
) VALUES (
NULL,
:message,
:severity,
:eventType,
:projectId,
:createdAt,
:version
)";
        $stm = $this->engine->prepare($sql);
        return $stm->execute($data->toArray());

    }

    public function get($id): DiagRecord
    {
        $sql = "select id, message, severity, eventType, projectId, createdAt, version from "
            . $this->logTable
            . " where id = :id";
        $stm = $this->engine->prepare($sql);
        $stm->bindParam(':id', $id, \PDO::PARAM_INT);

        $stm->execute();
//        return $stm->fetchObject(Record::class);
        $row = $stm->fetch(\PDO::FETCH_ASSOC);

        return new Record($row);
    }

    public function search(array $filters): array
    {
        $record = new Record();
        $keys = array_keys($record->toArray());
        $keys[] = 'id';

        $sanitizedF = [];

        $sql = "select * from {$this->logTable} where ";
        foreach ($filters as $k => $v) {
            if (in_array($k, $keys)) {
                $sanitizedF[$k] = $v;
                $sql .= " {$k} = :{$k}";
            }

        }


        if (count($sanitizedF)) {
            $stm = $this->engine->prepare($sql);
            $stm->execute($sanitizedF);
            return $stm->fetchAll(\PDO::FETCH_ASSOC);
        }

        return [-1];
    }

    public function batch(array $data): bool
    {
        foreach ($data as $record) {
            if ($record instanceof Record) {
                $result = $this->insert($record);
                if (!$result) {
                    throw new StorageFlushError;
                }
            }
        }

        return true;
    }
}