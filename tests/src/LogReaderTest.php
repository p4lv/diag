<?php

namespace Tests\Diag;

use Diag\DataMapper;
use Diag\LogReader;
use Diag\Record;
use Diag\Storage\Sqlite;
use PHPUnit\Framework\TestCase;

class LogReaderTest extends TestCase
{
    protected $sqlite;

    public function setUp()
    {
        $pdo = new \PDO('sqlite::memory:');
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->sqlite = new Sqlite($pdo);
        $this->sqlite->setup();
    }

    public function testReader()
    {
        $reader = new LogReader($this->sqlite);

        $result = $reader->getLast(10);

        static::assertTrue(is_array($result));
        static::assertTrue(empty($result));

        $this->addStubData(10);

        $result = $reader->getLast(10);
        static::assertCount(10, $result);
        $this->addStubData(10);
        $result = $reader->getLast(10);
        static::assertCount(10, $result);

        $result = $reader->getLast(1);
        static::assertCount(1, $result);


        $result = $reader->getLast(15);
        static::assertCount(15, $result);
    }

    private function addStubData($count = 10)
    {
        $mapper = new DataMapper($this->sqlite);
        for($i = 0; $i < $count ; $i++) {
            $record = new Record(
                [
                    'message' => 'Here SHould be faker',
                    'eventType' => 'test',
                ]
            );
            $mapper->store($record);
        }

    }
}
