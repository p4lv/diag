<?php


namespace Diag\Storage;

use ClickhouseClient\Client\Client as ClickhouseClient;
use Diag\DiagRecord;
use Diag\Record;

class Clickhouse implements CanPersist, CanFetch, CanSetUp
{
    private $client;
    /** @var  string */
    private $logTable;

    public function __construct(ClickhouseClient $client, $logTable = 'log_table')
    {
        $this->client = $client;
        $this->logTable = $logTable;
    }

    public function get($id): DiagRecord
    {
        // TODO: Implement get() method.
    }

    public function search(array $filters): array
    {
        // TODO: Implement search() method.
    }

    public function last($count, ?int $beforeID = null): array
    {
        $sql = "
          select id, message, severity, eventType, projectId, createdAt, version
          from {$this->logTable} 
        ";

        if ($beforeID !== null) {
            $sql .= " where id <  " . $beforeID;
        }
        $sql .= " order by id desc limit " . $count;

        $response = $this->client->query($sql);
        $records = $response->getContent()['data'];

        return $records;
    }

    public function batch(array $data): bool
    {
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
