<?php
/**
 * Created by PhpStorm.
 * User: r.shvets
 * Date: 08/11/2017
 * Time: 14:47
 */

namespace Diag;


interface DiagRecord
{

    public function getId():int;
    public function getMessage():string;

}