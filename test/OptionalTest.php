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
        $optional = Optional::empty();
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
        $this->assertTrue($optional->isPresent());
    }

    public function testFilterFalse()
    {
        $optional = Optional::of('value');
        $optional = $optional->filter(function ($value) {
            return strlen($value) < 0;
        });
        $this->assertFalse($optional->isPresent());
    }

    public function testMap()
    {
        $optional = Optional::of('value');
        $optional = $optional->map(function ($value) {
            return "${value} mapped";
        });
        $this->assertEquals('value mapped', $optional->get());
    }

    public function testMapReturnsOptionalEmpty()
    {
        $optional = Optional::empty();
        $optional = $optional->map(function ($value) {
            return "${value} mapped";
        });
        $this->assertFalse($optional->isPresent());
    }

    public function testFlatMap()
    {
        $optional = Optional::of('value');
        $optional = $optional->flatMap(function ($value) {
            return Optional::of("${value} mapped");
        });
        $this->assertEquals('value mapped', $optional->get());
    }

    public function testFlatMapReturnsOptionalEmpty()
    {
        $optional = Optional::empty();
        $optional = $optional->flatMap(function ($value) {
            return Optional::of("${value} mapped");
        });
        $this->assertFalse($optional->isPresent());
    }

    public function testFlatMapThrowsException()
    {
        $this->expectException(Exception::class);
        $optional = Optional::of('value');
        $optional->flatMap(function () {
            return null;
        });
    }

    public function testOrElseReturnsValue()
    {
        $value = 'value';
        $optional = Optional::of($value);
        $this->assertEquals($value, $optional->orElse('else'));
    }

    public function testOrElseReturnsElse()
    {
        $optional = Optional::empty();
        $else = 'else';
        $this->assertEquals($else, $optional->orElse($else));
    }

    public function testOrElseGetReturnsValue()
    {
        $value = 'value';
        $optional = Optional::of($value);
        $result = $optional->orElseGet(function () {
            return 'else';
        });
        $this->assertEquals($value, $result);
    }

    public function testOrElseGetReturnsElseGet()
    {
        $optional = Optional::empty();
        $else = 'else';
        $result = $optional->orElseGet(function () use ($else) {
            return $else;
        });
        $this->assertEquals($else, $result);
    }

    public function testOrElseThrowReturnsValue()
    {
        $value = 'value';
        $optional = Optional::of($value);
        $result = $optional->orElseThrow(function () {
            throw new Exception('error');
        });
        $this->assertEquals($value, $result);
    }

    public function testOrElseThrowThrowsException()
    {
        $optional = Optional::empty();
        $this->expectException(Exception::class);
        $optional->orElseThrow(function () {
            throw new Exception('error');
        });
    }

    public function testToStringWhenValueIsNotNull()
    {
        $value = 'value';
        $optional = Optional::of($value);
        $string = $optional->toString();
        $this->assertEquals("Optional[${value}]", $string);
    }

    public function testToStringWhenValueIsNull()
    {
        $optional = Optional::empty();
        $string = $optional->toString();
        $this->assertEquals('Optional.empty', $string);
    }
}
