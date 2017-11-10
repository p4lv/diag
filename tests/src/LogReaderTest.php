<?php

namespace Tests\Diag;

use Diag\DataMapper;
use Diag\LogReader;
use Diag\Record;
use Diag\Storage\Sqlite;
use PHPUnit\Framework\TestCase;

class LogReaderTest extends TestCase
{

    /** @var  \PDO */
    protected $pdo;

    public function setUp()
    {
        static $pdo = null;

//        var_dump(getenv('dsn'));

        if($pdo === null) {
            $pdo = new \PDO(getenv('dsn'));
            $pdo->exec("
CREATE TABLE table_log
(
  id        INTEGER,
  message   TEXT,
  severity  INTEGER,
  eventType TEXT,
  projectId INT,
  createdAt TEXT,
  version   INT
);
CREATE UNIQUE INDEX table_log_id_uindex
  ON table_log (id);
");
        }

        $this->pdo = $pdo;


    }

    public function testReader()
    {
        $sqlite = new Sqlite($this->pdo);
        $reader = new LogReader($sqlite);

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




//        var_dump($result);
    }

    private function addStubData($count = 10)
    {
        $mapper = new DataMapper(new Sqlite($this->pdo));
        for($i = 0; $i < $count ; $i++) {
            $record = new Record(
                [
                    'id' => $i,
                    'message' => file_get_contents('https://baconipsum.com/api/?type=meat-and-filler'),
                    'eventType' => 'test',
                ]
            );
            $mapper->store($record);
        }

    }
}
