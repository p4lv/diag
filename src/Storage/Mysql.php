<?php

namespace Diag\Storage;

use DateInterval;
use DateTimeImmutable;
use Diag\DiagRecord;
use Diag\Exception\MissingRecord;
use Diag\Exception\StorageFlushError;
use PDO;
use Diag\Record;

class Mysql implements CanPersist, CanFetch, CanCleanUp, CanSetUp
{
    private $engine;
    private $cleanupInterval;
    private $logTable;

    public function __construct(PDO $engine, $logTable = 'log_table', $cleanupInterval = 'P1M')
    {
        $this->engine = $engine;
        $this->engine->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->logTable = $logTable;
        $this->cleanupInterval = $cleanupInterval;
    }

    public function last(int $numberOfElements = 10, DiagRecord $beforeRecord = null): array
    {
        $sql = "select id, message, severity, eventType, projectId, createdAt, version
from {$this->logTable} ";

        if (null !== $beforeRecord) {
            $sql .= ' where id < :beforeId ';
        }
        $sql .= " order by id desc limit {$numberOfElements}";
        $stm = $this->engine->prepare($sql);

        if (null !== $beforeRecord) {
            $stm->bindParam(':beforeId', $beforeRecord->getId(), PDO::PARAM_INT);
        }

        $stm->execute();
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        return $result;
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
        $stm->bindParam(':id', $id, PDO::PARAM_INT);

        $stm->execute();

        if (!$stm->rowCount()) {
            throw new MissingRecord;
        }

        $stm->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, Record::class);

        return $stm->fetch();
    }

    public function search(array $filters): array
    {
        $response = [];
        if(!\count($filters)) {
            return $response;
        }

        $keys = array_keys((new Record())->toArray());
        $keys[] = 'id';
        $sanitizedF = [];

        $sql = "select * from {$this->logTable} where ";
        foreach ($filters as $k => $v) {
            if (\in_array($k, $keys, true)) {
                $sanitizedF[$k] = $v;
                $sql .= " {$k} = :{$k} ";
            }
        }

        if (\count($sanitizedF)) {
            $stm = $this->engine->prepare($sql);
            $stm->execute($sanitizedF);
            $response = $stm->fetchAll(PDO::FETCH_ASSOC);
        }

        return $response;
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

    public function count(): int
    {
        $sql = "SELECT count(id)
                FROM {$this->logTable}
                ";
        $stm = $this->engine->prepare($sql);

        $stm->execute();

        if (!$stm->rowCount()) {
            throw new MissingRecord;
        }

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
                    ->add(new DateInterval($this->cleanupInterval))
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
  id        INTEGER PRIMARY KEY AUTO_INCREMENT,
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
