<?php

namespace Solt9029\PhpOptional;

use Exception;

class Optional
{
    private $value;

    private static function initialize($value)
    {
        if (null === $value) {
            throw new Exception('value is null');
        }
        $optional = new self();
        $optional->value = $value;

        return $optional;
    }

    public static function empty()
    {
        $optional = new self();
        $optional->value = null;

        return $optional;
    }

    public static function of($value)
    {
        return self::initialize($value);
    }

    public static function ofNullable($value)
    {
        if (null === $value) {
            return self::empty();
        }

        return self::of($value);
    }

    public function get()
    {
        if (null === $this->value) {
            throw new Exception('value is null');
        }

        return $this->value;
    }

    public function isPresent()
    {
        return null !== $this->value;
    }

    public function ifPresent($consumer)
    {
        if (null !== $this->value) {
            $consumer();
        }
    }

    public function filter($predicate)
    {
        if (true === $predicate($this->value)) {
            return $this;
        }

        return self::empty();
    }

    public function map($mapper)
    {
        if (!$this->isPresent()) {
            return self::empty();
        }

        return $this->ofNullable($mapper($this->value));
    }

    public function flatMap($mapper)
    {
        if (!$this->isPresent()) {
            return self::empty();
        }
        $result = $mapper($this->value);
        if (null === $result) {
            throw new Exception('result is null');
        }

        return $result;
    }

    public function orElse($other)
    {
        if (null !== $this->value) {
            return $this->value;
        }

        return $other;
    }

    public function orElseGet($other)
    {
        if (null !== $this->value) {
            return $this->value;
        }

        return $this->orElse($other());
    }

    public function orElseThrow($exception)
    {
        if (null !== $this->value) {
            return $this->value;
        }

        throw $exception();
    }

    public function toString()
    {
        $value = $this->value;
        if (null !== $value) {
            return "Optional[${value}]";
        }

        return 'Optional.empty';
    }
}
