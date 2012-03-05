<?php

namespace Go\Deployer\Strategy;

use Go\Config\ConfigInterface;

/**
 * Deployment strategy interface.
 * Strategies must implement this interface.
 */
interface StrategyInterface
{
    public function deploy(ConfigInterface $config, $env, $go);
}
