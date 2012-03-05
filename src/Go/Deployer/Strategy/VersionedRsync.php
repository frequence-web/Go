<?php

namespace Go\Deployer\Strategy;

use Go\Config\ConfigInterface,
    Go\Deployer\Deployer;

use Symfony\Component\Console\Output\OutputInterface;

class VersionedRsync extends Rsync
{
    protected $deployer;
    protected $version;

    public function __construct(Deployer $deployer, OutputInterface $output, ConfigInterface $systemConfig)
    {
        parent::__construct($output, $systemConfig);

        $this->deployer = $deployer;
    }

    public function deploy(ConfigInterface $config, $env, $go)
    {
        $this->version  = $this->systemConfig->get('rsync.remote-version.'.$env, 0) + 1;

        if ($this->version > 1) {
            $this->deployer->copy(
                parent::getRemotePath($config, $env).($this->version - 1),
                parent::getRemotePath($config, $env).$this->version,
                true
            );
        }

        parent::deploy($config, $env, $go);

        $this->systemConfig->set('rsync.remote-version.'.$env, $this->version);
    }

    protected function getRemotePath(ConfigInterface $config, $env)
    {
        return parent::getRemotePath($config, $env).$this->version;
    }
}
