<?php

namespace Go\Deployer;

use \OOSSH\SSH2\Connection;

abstract class Deployer
{
    protected $config;

    /**
     * @var \OOSSH\SSH2\Connection
     */
    protected $ssh;

    function __construct($config)
    {
        $this->config = $config;
    }

    abstract function preDeploy();

    abstract function postDeploy();

    public function deploy($strategy, $go)
    {
        $this->preDeploy($go);
        $strategy->deploy($this, $go);
        $this->postDeploy($go);
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

    protected function getSshAuthentication()
    {
        throw new \RuntimeException('You must override the getSshAuthentication method to use SSH');
    }

    protected function initSsh()
    {
        if (null === $this->ssh) {
            $this->ssh = new Connection($this->getHost(), $this->getPort());
            $this->ssh->authenticate($this->getSshAuthentication());
        }
    }

}
