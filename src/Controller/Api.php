<?php

namespace Diag\Controller;


use Diag\DiagResponse;
use Diag\Storage\CanPersist;

class Api
{
    public function __construct(CanPersist $storage)
    {
    }

    public function getList(array $filters)
    {
    }

    public function postRecords(): DiagResponse
    {

    }

    public function postRecord(): DiagResponse
    {
    }

    public function getRecord(): DiagResponse
    {
    }
}
