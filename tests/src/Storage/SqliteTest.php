<?php
/**
 * Created by PhpStorm.
 * User: r.shvets
 * Date: 10/11/2017
 * Time: 12:31
 */

namespace Tests\Diag\Storage;

use Diag\Config;
use Diag\Record;
use Diag\Severity;
use Diag\Storage\CanPersist;
use Diag\Storage\Sqlite;
use PHPUnit\Framework\TestCase;

class SqliteTest extends TestCase
{
    /** @var  Sqlite */
    protected $sqlite;

    public function setUp()
    {
        $this->sqlite = new Sqlite(new \PDO('sqlite::memory:'));
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
