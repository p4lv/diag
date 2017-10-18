<?php

namespace Diag\Storage;

use Diag\Record;

class Sqlite implements CanPersist, CanFetch
{
    private $engine;

    public function __construct(\PDO $engine)
    {
        $this->engine = $engine;
    }

    public function insert(Record $data) : bool
    {
        // TODO: Implement save() method.

        $sql = "insert iton ";
    }

    public function get($id): Record
    {
        // TODO: Implement get() method.
    }

    public function search(array $filters): array
    {
        // TODO: Implement search() method.
    }

    public function update(Record $data) : bool
    {
        // TODO: Implement update() method.
    }
}