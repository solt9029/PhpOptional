<?php

require_once 'vendor/autoload.php';

use Solt9029\PhpOptional\Optional;

class OptionalTest extends PHPUnit\Framework\TestCase
{
    public function testEmpty()
    {
        $optional = Optional::empty();
        $this->assertFalse($optional->isPresent());
    }

    public function testOf()
    {
        $optional = Optional::of('value');
        $this->assertTrue($optional->isPresent());

        $this->expectException(Exception::class);
        Optional::of(null);
    }

    public function testOfNullable()
    {
        $optional = Optional::ofNullable('value');
        $this->assertTrue($optional->isPresent());

        $optional = Optional::ofNullable(null);
        $this->assertFalse($optional->isPresent());
    }

    public function testGet()
    {
        $value = 'value';
        $optional = Optional::of($value);
        $this->assertEquals($value, $optional->get());
    }

    public function testGetThrowsException()
    {
        $this->expectException(Exception::class);
        $optional = Optional::ofNullable(null);
        $optional->get();
    }

    public function testIsPresentTrue()
    {
        $optional = Optional::of('value');
        $this->assertTrue($optional->isPresent());
    }

    public function testIsPresentFalse()
    {
        $optional = Optional::ofNullable(null);
        $this->assertFalse($optional->isPresent());
    }

    public function testIfPresentCalled()
    {
        $optional = Optional::of('value');
        $isCalled = false;
        $optional->ifPresent(function () use (&$isCalled) {
            $isCalled = true;
        });
        $this->assertTrue($isCalled);
    }

    public function testIfPresentNotCalled()
    {
        $optional = Optional::ofNullable(null);
        $isCalled = false;
        $optional->ifPresent(function () use (&$isCalled) {
            $isCalled = true;
        });
        $this->assertFalse($isCalled);
    }

    public function testFilterTrue()
    {
        $optional = Optional::of('value');
        $optional = $optional->filter(function ($value) {
            return strlen($value) > 0;
        });
        $this->assertTrue($optional->filter(function ($value) {
            return strlen($value) > 0;
        })->isPresent());
    }

    public function testFilterFalse()
    {
        $optional = Optional::of('value');
        $optional = $optional->filter(function ($value) {
            return strlen($value) < 0;
        });
        $this->assertFalse($optional->isPresent());
    }
}
