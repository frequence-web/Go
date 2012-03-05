<?php

namespace Go\Exception;

use Go\Exception;

/**
 * Go exception base class
 */
class EnvironmentNotFound extends Exception
{
    public function __construct($env)
    {
        parent::__construct(sprintf('The environment %s doesn\'t exists', $env));
    }
}
