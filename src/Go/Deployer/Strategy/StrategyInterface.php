<?php

namespace Go\Deployer\Strategy;

use Go\Config\ConfigInterface;

interface StrategyInterface
{
    public function deploy(ConfigInterface $config, $env, $go);
}
