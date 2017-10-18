<?php

namespace Diag\Storage;


use Diag\Record;

class Clickhouse implements CanPersist
{
    public function __construct($engine)
    {
        $this->engine = $engine;
    }

    public function save(Record $data)
    {
        // TODO: Implement save() method.
    }

    public function get($id): Record
    {
        // TODO: Implement get() method.
    }

    public function search(array $filters): array
    {
        // TODO: Implement search() method.
    }

    public function insert(Record $data) : bool
    {
        // TODO: Implement insert() method.
    }

    public function update(Record $data) : bool
    {
        // TODO: Implement update() method.
    }
}