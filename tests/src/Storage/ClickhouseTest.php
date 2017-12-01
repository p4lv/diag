<?php

namespace Tests\Diag\Storage;

use ClickhouseClient\Client\Client;
use ClickhouseClient\Client\Config;
use Diag\Exception\MissingRecord;
use Diag\Exception\NotImplemented;
use Diag\Record;
use Diag\Storage\Clickhouse;
use Diag\Storage\Sqlite;
use PHPUnit\Framework\TestCase;

class ClickhouseTest extends TestCase
{
    /** @var  Clickhouse */
    protected $storage;
    /** @var  Client */
    protected $client;

    public function setUp()
    {
        global $container;

        $this->client = $container->get(\ClickhouseClient\Client\Client::class);
        $this->storage = $container->get(\Diag\Storage\Clickhouse::class);
        $this->storage->setup();
    }

    public function tearDown()
    {
        $this->client->system('DROP TABLE log_table');
    }

    public function testPersistInsert()
    {
        $message = 'test message';
        $record = new Record(['id' => uniqid(), 'message' => $message]);
        $result = $this->storage->insert($record);
        $this->assertEquals(true, $result);
    }

    public function testPersistBatch()
    {
        $batch = [];
        $message = 'test message';
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $result = $this->storage->batch($batch);
        $this->assertEquals(true, $result);
    }

    public function testPersistBatchNonDiagRecord()
    {
        $this->expectException(\RuntimeException::class);

        $batch = [];
        $message = 'test message';
        $batch[] = new Record(['message' => $message]);
        $batch[] = ['message' => $message];
        $batch[] = new Record(['message' => $message]);
        $result = $this->storage->batch($batch);
    }

    public function testFetchGet()
    {
        $id = uniqid();
        $message = 'test message';
        $record = new Record(['id' => $id, 'message' => $message]);
        $result = $this->storage->insert($record);
        $this->assertEquals(true, $result);

        $record = $this->storage->get($id);
        $this->assertInstanceOf(Record::class, $record);
    }

    public function testFetchGetAfterBatch()
    {
        $message = 'test message';
        $record = new Record(['message' => $message]);
        $result = $this->storage->insert($record);
        $this->assertEquals(true, $result);

        $batch = [];
        $message = 'test message';
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $result = $this->storage->batch($batch);
        $this->assertEquals(true, $result);

        $record = $this->storage->get($batch[2]->getId());
        $this->assertInstanceOf(Record::class, $record);
        $record = $this->storage->get($batch[4]->getId());
        $this->assertInstanceOf(Record::class, $record);
        $record = $this->storage->get($batch[3]->getId());
        $this->assertInstanceOf(Record::class, $record);
    }

    public function testFetchGetMissingRecord()
    {
        $this->expectException(MissingRecord::class);
        $record = $this->storage->get(55555);
    }

    public function testFetchSearch()
    {
        $this->expectException(NotImplemented::class);

        $batch = [];
        $message = 'test message';
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $result = $this->storage->batch($batch);

        $records = $this->storage->search(['eventType' => 'general']);
    }

    public function testFetchLast()
    {
        $batch = [];
        $message = 'test message';
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message]);
        $result = $this->storage->batch($batch);
        $this->assertEquals(true, $result);

        $records = $this->storage->last(5);
        $this->assertEquals(true, is_array($records));
        $this->assertEquals(5, count($records));
    }

    public function testFetchLastAfterId()
    {
        $batch = [];
        $message = 'test message';
        $batch[] = new Record(['id' => uniqid(), 'message' => $message, 'createdAt' => '2017-05-11 22:11:15']);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message, 'createdAt' => '2017-05-11 22:11:15']);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message, 'createdAt' => '2017-05-11 22:11:15']);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message, 'createdAt' => '2017-05-11 22:11:22']);
        $batch[] = new Record(['id' => uniqid(), 'message' => $message, 'createdAt' => '2017-05-11 22:11:34']);
        $result = $this->storage->batch($batch);
        $this->assertEquals(true, $result);

        $records = $this->storage->last(5, new Record(['createdAt' => '2017-05-11 22:11:17']));
        $this->assertEquals(true, is_array($records));
        $this->assertEquals(3, count($records));
    }
}
