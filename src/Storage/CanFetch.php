<?php

namespace Diag\Storage;



use Diag\DiagRecord;

interface CanFetch
{
    public function get($id) : DiagRecord;

    public function search(array $filters) : array;

    public function last(int $count, DiagRecord $beforeRecord = null) : array ;
}