<?php

namespace Fadaa\ReadyTests;
use Illuminate\Support\Facades\Facade;

class ReadyTestsServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ready-tests-service';
    }
}
