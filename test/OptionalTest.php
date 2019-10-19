<?php

require_once 'vendor/autoload.php';

use Solt9029\PhpOptional\Optional;

class OptionalTest extends PHPUnit\Framework\TestCase
{
    public function testSample()
    {
        $this->assertEquals('sample', Optional::sample());
    }
}
