<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 11/28/17
 * Time: 11:34 PM
 */

namespace Diag;


class DateTime extends \DateTime
{
    public function __toString()
    {
        return $this->format('Y-m-d H:i:s');
    }
}