<?php

namespace Go\Deployer;

class Deployer
{
    protected $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    public function deploy($strategy)
    {
        return $strategy->deploy($this);
    }

    public function getHost()
    {
        if (!isset($this->config['host'])) {
            throw new \InvalidArgumentException('You must define an host');
        }

        return $this->config['host'];
    }

    public function getUser()
    {
        if (!isset($this->config['user'])) {
            throw new \InvalidArgumentException('You must define an user');
        }

        return $this->config['user'];
    }

    public function getRemoteDir()
    {
        if (!isset($this->config['remote_dir'])) {
            throw new \InvalidArgumentException('You must define a remote_dir');
        }

        return $this->config['remote_dir'];
    }

    public function getPort()
    {
        if (!isset($this->config['port'])) {
            throw new \InvalidArgumentException('You must define a port');
        }

        return $this->config['port'];
    }

    public function getExclude()
    {
        if (!isset($this->config['exclude'])) {
            return array();
        }

        return $this->config['exclude'];
    }

}
