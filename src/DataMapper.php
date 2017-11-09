<?php

namespace Diag;


use Diag\Storage\CanPersist;

class DataMapper
{
    private $storage;

    public function __construct(CanPersist $storage)
    {
        $this->storage = $storage;
    }

    public function store($obj): bool
    {
        if ($obj instanceof Record) {
            return $this->storage->insert($obj);
        }

        if (is_array($obj)) {
            return $this->storage->batch($obj);
        }

        throw new \LogicException('Unknown issue in storage persistence');
    }
}
