<?php

namespace RomanNumerals;

interface IContainer
{
    /**
     * Binds existing object or closure
     *
     * @param string $name object identifier
     * @param mixed $binding can be 1) Closure 2) existing object
     */

    public function bind($name, $binding);

    /**
     * Creates an object which exists in container
     *
     * @param $name
     * @return mixed
     * @throws InvalidArgumentException
     */

    public function make($name);
}
