<?php

namespace RomanNumerals;

use \InvalidArgumentException;

/**
 * Very simple IoC container that 1) holds objects that are ready for use or needs to be created 2) creates those objects
 *
 * @package RomanNumerals
 */
class Container implements IContainer
{
    /**
     * Holds closure with object creation logic and already pre-created objects
     *
     * @var array
     */
    protected $objects = [];

    /**
     * Binds existing object or closure
     *
     * @param string $name object identifier
     * @param mixed $binding
     */
    public function bind($name, $binding)
    {
        $this->objects[$name] = $binding;
    }

    /**
     * Creates an object which exists in container
     *
     * @param $name
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function make($name)
    {
        if (array_key_exists($name, $this->objects)) {
            $binding = $this->objects[$name];
            try {
                if (is_callable($binding)) {
                    $object = $binding($this);
                } elseif (is_object($binding)) {
                    $object = $binding;
                } else {
                    throw new InvalidArgumentException("$binding is not a callable neither it is pre-created object.");
                }
                return $object;
            } catch (\Exception $e) {
                throw new InvalidArgumentException("Unable to resolve $name: ".$e->getMessage(), $e->getCode());
            }
        } else {
            throw new InvalidArgumentException("$name not found in Container.");
        }
    }
}
