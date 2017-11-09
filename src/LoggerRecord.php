<?php
/**
 * Created by PhpStorm.
 * User: r.shvets
 * Date: 08/11/2017
 * Time: 14:48
 */

namespace Diag;


interface LoggerRecord
{

    public function getId():int;

    public function getBody(): string;
}