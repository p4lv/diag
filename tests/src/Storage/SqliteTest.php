<?php
/**
 * Created by PhpStorm.
 * User: r.shvets
 * Date: 10/11/2017
 * Time: 12:31
 */

namespace Tests\Diag\Storage;

use Diag\Record;
use Diag\Severity;
use Diag\Storage\CanPersist;
use Diag\Storage\Sqlite;
use PHPUnit\Framework\TestCase;

class SqliteTest extends TestCase
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


    public function testStore()
    {
        $sqlite = new Sqlite($this->pdo);

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
