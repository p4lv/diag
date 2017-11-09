<?php

namespace Diag;


interface LoggerRecord
{

    public function getId():int;

    public function getBody(): string;
}