<?php

namespace Diag\Storage;


use Diag\DiagRecord;

interface CanPersist
{
    public function batch(array $data): bool;

    public function insert(DiagRecord $data): bool;
}