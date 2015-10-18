<?php

namespace RomanNumerals;

interface IRouter
{
    /**
     * Dispatches request to a controller
     *
     * @param IContainer $container
     * @return mixed
     */
    public function dispatch(IContainer $container);
}
