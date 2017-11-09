<?php

namespace Diag\Storage;


use Diag\Record;

interface CanFetch
{
    public function get($id) : Record;

    public function search(array $filters) : array;

    public function last($count, ?int $beforeID = null) : array ;
}