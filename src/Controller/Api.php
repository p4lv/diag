<?php

namespace Diag\Controller;


use Diag\DiagRecord;
use Diag\DiagRecordIterator;
use Diag\DiagResponse;
use Diag\Storage\CanPersist;

class Api
{
    public function getList(array $filters)
    {
    }

    public function postRecords(DiagRecordIterator $records): DiagResponse
    {
    }

    public function postRecord(DiagRecord $record): DiagResponse
    {
    }

    public function getRecord(): DiagResponse
    {
    }
}
