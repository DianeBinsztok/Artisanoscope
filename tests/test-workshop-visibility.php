<?php

namespace Tests;

require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;

class HelloTest extends PHPUnit_Framework_TestCase
{
    public function hello_test(){
        //Expected
        $expected = "hello";

        //Actual
        $results = "hello";
        $actual = $results;

        //Assert
        $this->assertSame($expected, $actual);
    }
}