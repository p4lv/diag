<?php
/**
 * Created by PhpStorm.
 * User: r.shvets
 * Date: 17/10/2017
 * Time: 17:21
 */

namespace Diag\Storage;


use Diag\Record;

interface CanPersist
{
    public function batch(array $data): bool;

    public function insert(Record $data): bool;
}