<?php

namespace Tests\Diag\Storage;

use Diag\Exception\MissingRecord;
use Diag\Record;
use Diag\Severity;
use Diag\Storage\CanPersist;
use Diag\Storage\Mysql;
use PHPUnit\Framework\TestCase;

class MysqlTest extends TestCase
{
    /** @var  Mysql */
    protected $mariadb;

    public function setUp()
    {
        global $container;

        $pdo = new \PDO('mysql:host=' . $container->getParameter('mariadb.host') . ';port=' . $container->getParameter('mariadb.port') . ';dbname=' . $container->getParameter('mariadb.database'),
            $container->getParameter('mariadb.user'),
            $container->getParameter('mariadb.password')
        );

        $this->mariadb = new Mysql($pdo);
        $this->mariadb->setup();
    }

    public function testInsert()
    {
        $record = new Record(['message' => time().'testMessage'.' '.__METHOD__]);
        $result = $this->mariadb->insert($record);
        $this->assertEquals(true, $result);

        $lastRecord = $this->mariadb->last(1);

        $this->assertEquals($record->getMessage(), $lastRecord[0]['message']);

    }

    public function testGetByUnknownId()
    {
        $this->expectException(MissingRecord::class);
        $this->mariadb->get(9999999999);
    }

    public function testByKnownId()
    {
        $lastRecord = $this->mariadb->last(1);
        $this->assertEquals([], $lastRecord);
        $record = new Record(['message' => time().'testMessage'.' '.__METHOD__]);
        $result = $this->mariadb->insert($record);
        $this->assertTrue($result);

        $recordNumber1 = $this->mariadb->get(1);
        $this->assertTrue($recordNumber1 instanceof Record);

        $this->assertEquals($record->getMessage(), $recordNumber1->getMessage());
    }

    public function testStore()
    {
        $sqlite = $this->mariadb;

        static::assertInstanceOf(CanPersist::class, $sqlite);

        $t = 'test'.time();
        $originalRecordData = ['message' => $t];
        $record = new Record($originalRecordData);

        $sqlite->insert($record);

        $this->assertEquals(1, $this->mariadb->count());

        $row = $sqlite->last(10);
        static::assertCount(1, $row);
        $oldRecord = array_shift($row);
        static::assertEquals($oldRecord['message'], $originalRecordData['message']);

        $sqlite->insert(new Record(['message' => 'Test me 2', 'severity' => Severity::FATAL, 'projectId' => 666]));

        static::assertCount(2, $sqlite->last(10));
    }
}
