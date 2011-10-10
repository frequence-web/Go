<?php

namespace Go\Deployer\Strategy;

use \Go\Deployer\Deployer;

interface StrategyInterface
{
    public function deploy(Deployer $deployer, $go);
}
