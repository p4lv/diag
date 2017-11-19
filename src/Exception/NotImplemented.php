<?php

namespace Diag\Exception;


class NotImplemented extends \RuntimeException
{
    protected $code = 500666;
    protected $message = 'This method was not implemented yet.';
}
