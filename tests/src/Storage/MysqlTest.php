<?php

namespace Tests\Diag\Storage;

use Diag\Record;
use Diag\Severity;
use Diag\Storage\CanPersist;
use Diag\Storage\Mysql;
use Diag\Storage\Sqlite;
use PHPUnit\Framework\TestCase;

class MysqlTest extends TestCase
{
    /** @var  Sqlite */
    protected $mariadb;

    public function setUp()
    {
        global $container;

        $this->mariadb = new Mysql(
            new \PDO('mysql:host='.$container->getParameter('mariadb.host').';port='.$container->getParameter('mariadb.database').';dbname='.$container->getParameter('mariadb.database'),
            $container->getParameter('mariadb.user'),
            $container->getParameter('mariadb.password')
            )
        );
        $this->mariadb->setup();
    }

    public function testStore()
    {
        $sqlite = $this->mariadb;

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
