<?php

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

use function PHPUnit\Framework\assertTrue;

class SimpleTest extends TestCase 
{
    function test_simple(){
        $test = strstr('hello/world/adlf.jpg','/');
        dump($test);
        assertTrue(true);
    }
}