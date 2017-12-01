<?php

namespace Tests\Diag\Storage;

use Diag\Exception\MissingRecord;
use Diag\Record;
use Diag\Storage\Sqlite;
use PHPUnit\Framework\TestCase;

class SqliteTest extends TestCase
{
    /** @var  Sqlite */
    protected $storage;

    public function setUp()
    {
        global $container;
        $this->storage = new Sqlite(new \PDO($container->getParameter('database.dsn') ));
        $this->storage->setup();
    }

    public function testPersistInsert()
    {
        $message = 'test message!!!!!';
        $record = new Record(['message' => $message]);
        $result = $this->storage->insert($record);
        $this->assertEquals(true, $result);
        $newRecord = $this->storage->get(1);
        $this->assertEquals($record->getMessage(), $newRecord->getMessage());
        $this->assertEquals(1, $this->storage->count());
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
        $message = 'test message';
        $record = new Record(['message' => $message]);
        $result = $this->storage->insert($record);
        $this->assertEquals(true, $result);

        $record = $this->storage->get(1);
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
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $batch[] = new Record(['message' => $message]);
        $result = $this->storage->batch($batch);
        $this->assertEquals(true, $result);

        $record = $this->storage->get(2);
        $this->assertInstanceOf(Record::class, $record);
        $record = $this->storage->get(4);
        $this->assertInstanceOf(Record::class, $record);
        $record = $this->storage->get(3);
        $this->assertInstanceOf(Record::class, $record);
    }

    public function testFetchGetMissingRecord()
    {
        $this->expectException(MissingRecord::class);
        $record = $this->storage->get(55555);
    }

    public function testFetchSearch()
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

        $records = $this->storage->search(['eventType' => 'general']);
        $this->assertEquals(true, is_array($records));
        $this->assertEquals(5, count($records));
    }

    public function testFetchLast()
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

        $records = $this->storage->last(5);
        $this->assertEquals(true, is_array($records));
        $this->assertEquals(5, count($records));
    }

    public function testFetchLastAfterId()
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

        $records = $this->storage->last(5, new Record(['id' => 4]));
        $this->assertEquals(true, is_array($records));
        $this->assertEquals(3, count($records));
    }
}
