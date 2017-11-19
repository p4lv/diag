<?php

namespace Diag;


class DiagRecordIterator extends \ArrayIterator
{
    public function __construct(array $array = array(), $flags = 0)
    {
        foreach ($array as $item) {
            if (!($item instanceof DiagRecord)) {
                throw new \LogicException('argument $array should consist of ' . DiagRecord::class . ' class objects');
            }
        }
        parent::__construct($array, $flags);
    }
}