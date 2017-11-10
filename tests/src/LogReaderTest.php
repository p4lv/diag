<?php

namespace Tests\Diag;

use Diag\Config;
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
        $this->sqlite = new Sqlite(new Config());
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
        $mapper = new DataMapper(new Sqlite(new Config()));
        for($i = 0; $i < $count ; $i++) {
            $record = new Record(
                [
                    'id' => $i,
                    'message' => file_get_contents('https://baconipsum.com/api/?type=meat-and-filler') ?? 'default message',
                    'eventType' => 'test',
                ]
            );
            $mapper->store($record);
        }

    }
}
