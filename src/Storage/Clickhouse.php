<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 11/13/17
 * Time: 11:38 PM
 */

namespace Diag\Storage;

use ClickhouseClient\Client\Client as ClickhouseClient;
use ClickhouseClient\Client\Config as ClickhouseClientConfig;
use Diag\Config;
use Diag\DiagRecord;
use Diag\Record;

class Clickhouse implements CanPersist, CanFetch, CanSetUp
{
    const STORAGE = 'Clickhouse';

    /** @var  ClickhouseClient */
    private $client;
    /** @var  string */
    private $logTable;

    public function __construct(Config $config)
    {
        $options = $config->getStorage(self::STORAGE);
        $config = new ClickhouseClientConfig(
            // basic connection information
            ['host' => $options['host'], 'port' => $options['port'], 'protocol' => 'http'],
            // settings
            ['database' => $options['database']],
            // credentials
            ['user' => $options['user'], 'password' => $options['password']],
            // additional CURL options
            [ CURLOPT_TIMEOUT => 30 ]
        );
        $this->client = new ClickhouseClient($config);
        $this->logTable = $options['log_table'];
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
//        return array_map(function(array $record) { return new Record($record); }, $records);
        return $records;
    }

    public function batch(array $data): bool
    {
        try {
            $this->client->writeRows('INSERT INTO ' . $this->logTable,
                array_map(function(Record $record) { return $record->toArray(); }, $data)
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