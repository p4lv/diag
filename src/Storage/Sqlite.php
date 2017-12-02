<?php

namespace Diag\Storage;

use DateTimeImmutable;
use Diag\DiagRecord;
use Diag\Exception\MissingRecord;
use Diag\Exception\StorageFlushError;
use PDO;
use Diag\Record;

class Sqlite implements CanPersist, CanFetch, CanCleanUp, CanSetUp
{
    private $engine;
    private $cleanupInterval;
    private $logTable;

    public function __construct(PDO $engine, $logTable = 'log_table', $cleanupInterval = 'P1M')
    {
        $this->engine = $engine;
        $this->logTable = $logTable;
        $this->cleanupInterval = $cleanupInterval;
    }

    public function last(int $numberOfElements = 10, DiagRecord $beforeRecord = null): array
    {
        $sql = "select id, message, severity, eventType, projectId, createdAt, version
from {$this->logTable} ";

        if (null !== $beforeRecord) {
            $sql .= " where id < :beforeId ";
        }
        $sql .= " order by id desc limit {$numberOfElements}";
        $stm = $this->engine->prepare($sql);

        $params = [];
        if (null !== $beforeRecord) {
            $params['beforeId'] = (int) $beforeRecord->getId();
        }
        $stm->execute($params);
        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insert(DiagRecord $data): bool
    {

        $sql = "insert into {$this->logTable} (
message,
severity,
eventType,
projectId,
createdAt,
version
) VALUES (
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
        $sql = "SELECT id, message, severity, eventType, projectId, createdAt, version 
                FROM {$this->logTable}
                WHERE id = :id ";
        $stm = $this->engine->prepare($sql);
        $stm->bindParam(':id', $id, \PDO::PARAM_INT);
        $stm->execute();

        $stm->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, Record::class);
        $record = $stm->fetch();
        if (!$record) {
            throw new MissingRecord;
        }
        return $record;
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
            } else {
                throw new \RuntimeException('items passed to "batch" method should be instanceof ' . Record::class);
            }
        }

        return true;
    }

    public function count(): int
    {
        $sql = "SELECT count(*)
                FROM {$this->logTable}
                ";
        $stm = $this->engine->prepare($sql);
        $stm->execute();
        $row = $stm->fetch(PDO::FETCH_NUM);

        return (int)$row[0];
    }

    public function cleanup(DateTimeImmutable $now = null): bool
    {
        if ($now === null) {
            $now = new DateTimeImmutable();
        }
        $stm = $this->engine->prepare(
            "
                DELETE FROM {$this->logTable}
                WHERE createdAt < :cleanUpFromDate
            "
        );
        return $stm->execute(
            [
                'cleanUpFromDate' => $now
                    ->add(new \DateInterval($this->cleanupInterval))
                    ->format('Y-m-d H:i:s')
            ]
        );
    }

    public function setup(): bool
    {
        $this->engine->exec("DROP TABLE IF EXISTS {$this->logTable}");
        $this->engine->exec("
CREATE TABLE {$this->logTable}
(
  id        INTEGER PRIMARY KEY AUTOINCREMENT,
  message   TEXT,
  severity  INTEGER,
  eventType TEXT,
  createdAt TEXT,
  projectId INT,
  version   INT
);
CREATE UNIQUE INDEX {$this->logTable}_id_uindex
  ON {$this->logTable} (id);
");
        return true;
    }
}
