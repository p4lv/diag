<?php

namespace Diag\Exception;


use RuntimeException;

class MissingRecord extends RuntimeException
{
    protected $message = 'Missing Record';
    protected $code = 404001;
}
