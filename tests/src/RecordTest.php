<?php
/**
 * Created by PhpStorm.
 * User: r.shvets
 * Date: 09/11/2017
 * Time: 23:30
 */

namespace Tests\Diag;

use Diag\Record;
use Diag\Severity;
use PHPUnit\Framework\TestCase;

class RecordTest extends TestCase
{

    public function testEmptyConstruction()
    {
        $record = new Record();

        self::assertTrue(is_array($record->toArray()));
        self::assertEquals($record->getSeverity(), Severity::LOG);
        self::assertEquals(
            ['message' => '', 'severity' => Severity::LOG, 'eventType' => 'general', 'projectId' => 0, 'version' => 0, 'createdAt'=>date('Y-m-d H:i:s')],
            $record->toArray()
        );
    }

    public function testArrayConstruction()
    {
        $preset = [
            'message' => 'Test Message',
            'severity' => Severity::CRITICAL,
            'eventType' => 'new',
            'projectId' => 42,
            'createdAt' => date('Y-m-d H:i:s'),
            'version' => 7,
        ];

        $record = new Record($preset);

        self::assertTrue(is_array($record->toArray()));
        self::assertEquals(
            $preset,
            $record->toArray()
        );

        self::assertTrue($record->getId() === null);
    }
}
