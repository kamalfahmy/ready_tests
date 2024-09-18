<?php

namespace Fadaa\ReadyTests\Exceptions;

use Exception;

class InvalidDateFormatException extends Exception
{
    protected $message = 'The provided date format is invalid.';

    public function __construct($message = null)
    {
        parent::__construct($message ?? $this->message);
    }
}