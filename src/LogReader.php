<?php

namespace Diag;


use Diag\Storage\CanFetch;

class LogReader
{
    private $storage;

    public function __construct(CanFetch $storage)
    {
        $this->storage = $storage;
    }

    public function getLast($numberOfRecords)
    {
        return $this->storage->last($numberOfRecords);
    }

    public function search(array $array): array
    {
        return $this->storage->search($array);
    }

    public function get($id): DiagRecord
    {
        return $this->storage->get($id);
    }
}
