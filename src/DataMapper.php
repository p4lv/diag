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

    public function store(Record $obj) : bool
    {
        if ($obj->getId()) {
            return $this->storage->update($obj);
        }

        return $this->storage->insert($obj);
    }

}