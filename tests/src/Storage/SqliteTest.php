<?php

namespace Tests\Diag\Storage;

use Diag\Record;
use Diag\Severity;
use Diag\Storage\CanPersist;
use Diag\Storage\Sqlite;
use PHPUnit\Framework\TestCase;

class SqliteTest extends TestCase
{
    protected $sqlite;

    public function setUp()
    {
        global $container;
        $this->sqlite = new Sqlite(new \PDO($container->getParameter('database.dsn') ));
        $this->sqlite->setup();
    }

    public function testStore()
    {
        $sqlite = $this->sqlite;

        static::assertInstanceOf(CanPersist::class, $sqlite);


        $originalRecordData = ['message' => 'I AM A HERO'];
        $record = new Record($originalRecordData);

        $sqlite->insert($record);

        $row = $sqlite->last(10);
        static::assertCount(1, $row);
        $oldRecord = array_shift($row);
        static::assertEquals($oldRecord['message'], $originalRecordData['message']);

        $sqlite->insert(new Record(['message' => 'Test me 2', 'severity' => Severity::FATAL, 'projectId' => 666]));


        static::assertCount(2,$sqlite->last(10));
    }
}
