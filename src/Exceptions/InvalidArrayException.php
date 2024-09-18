<?php

namespace Fadaa\ReadyTests\Exceptions;

use Exception;

class InvalidArrayException extends Exception
{
    protected $message = 'Paramaters must be an array.';

    public function __construct($message = null)
    {
        parent::__construct($message ?? $this->message);
    }
}