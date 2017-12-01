<?php


namespace Diag\Storage;

use ClickhouseClient\Client\Client as ClickhouseClient;
use Diag\DiagRecord;
use Diag\Exception\MissingRecord;
use Diag\Exception\NotImplemented;
use Diag\Record;

class Clickhouse implements CanPersist, CanFetch, CanSetUp
{
    private $client;
    private $logTable;

    public function __construct(ClickhouseClient $client, $logTable = 'log_table')
    {
        $this->client = $client;
        $this->logTable = $logTable;
    }

    public function get($id): DiagRecord
    {
        $sql = "
          select id, message, severity, eventType, projectId, createdAt, version
          from {$this->logTable}
          where id = '" . (string) $id .  "' LIMIT 1";

        $response = $this->client->query($sql);
        $records = $response->getContent()['data'];

        if(!$records) {
            throw new MissingRecord;
        }

        return new Record($records[0]);
    }

    public function search(array $filters): array
    {
        // TODO: Implement search() method.
        throw new NotImplemented;
    }

    public function last(int $count, DiagRecord $beforeRecord = null): array
    {
        $sql = "
          select id, message, severity, eventType, projectId, createdAt, version
          from {$this->logTable} 
        ";

        if (null !== $beforeRecord) {
            $sql .= " where createdAt <  '" . (string) $beforeRecord->getCreatedAt() . "'";
        }
        $sql .= " order by createdAt desc limit " . $count;

        $response = $this->client->query($sql);
        $records = $response->getContent()['data'];

        return $records;
    }

    public function batch(array $data): bool
    {
        foreach ($data as $record) {
            if (!($record instanceof Record)) {
                throw new \RuntimeException('items passed to "batch" method should be instanceof ' . Record::class);
            }
        }
        try {
            $this->client->writeRows('INSERT INTO ' . $this->logTable,
                array_map(function (Record $record) {
                    return $record->toArray();
                }, $data)
            );
            return true;
        } catch (\Throwable $ex) {
            return false;
        }
    }

    public function insert(DiagRecord $data): bool
    {
        try {
            $this->client->writeRows('INSERT INTO ' . $this->logTable,
                [
                    $data->toArray()
                ]
            );
            return true;
        } catch (\Throwable $ex) {
            return false;
        }
    }

    public function count(): int
    {
        $sql = "SELECT count(id)
                FROM {$this->logTable}
                ";
        $stm = $this->client->prepare($sql);

        $stm->execute();

        if (!$stm->rowCount()) {
            throw new MissingRecord;
        }

        $row = $stm->fetch(PDO::FETCH_NUM);

        return (int)$row[0];
    }

    public function setup(): bool
    {
        try {
            $this->client->system("DROP TABLE IF EXISTS {$this->logTable}");
            $this->client->system("
                CREATE TABLE {$this->logTable}
                (
                  id        String,
                  message   String,
                  severity  UInt8,
                  eventType String,
                  createdAt DateTime,
                  createdAtDt Date DEFAULT toDate(createdAt),
                  projectId UInt8,
                  version   UInt16
                ) Engine=MergeTree(createdAtDt, (id, createdAt), 8192);
            ");
            return true;
        } catch (\Throwable $ex) {
            return false;
        }
    }
}
