<?php
/**
 * Created by PhpStorm.
 * User: bogdans
 * Date: 11/10/17
 * Time: 11:03 PM
 */

namespace Diag\Storage;


interface CanCleanUp
{
    public function cleanup(\DateTime $now = null);
}