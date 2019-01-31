<?php
/**
 * Created by PhpStorm.
 * User: feng
 * Date: 2019/1/30
 * Time: 10:59
 */
namespace LaravelRdkafka\Rdkafka\Facades;

use Illuminate\Support\Facades\Facade;

class Rdkafka extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rdkafka';
    }
}