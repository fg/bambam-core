<?php namespace Bambam\Service;

use Slim\Helper\Set as Container;

abstract class AbstractService
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __consturct(Container $container)
    {
        $this->container = $container;
    }
}