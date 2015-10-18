<?php

namespace RomanNumerals;

class Application
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var IContainer
     */
    private $container;

    public function __construct(IContainer $container, IRouter $router)
    {
        $this->container = $container;
        $this->router    = $router;
    }

    public function start()
    {
        $this->router->dispatch($this->container);
    }
}
