<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 11/10/17
 * Time: 10:44 PM
 */

namespace Diag;


class DiagRecordIterator extends \ArrayIterator
{
    public function __construct(array $array = array(), $flags = 0)
    {
        foreach($array as $item) {
            if (!($item instanceof DiagRecord)) {
                throw new \LogicException('argument $array should consist of ' . DiagRecord::class . ' class objects');
            }
        }
        parent::__construct($array, $flags);
    }
}