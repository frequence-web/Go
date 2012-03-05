<?php

namespace Go\Deployer;

use Symfony\Component\Console\Output\OutputInterface;

use OOSSH\SSH2\Connection;

use Go\Deployer\Strategy\StrategyInterface,
    Go\Config\ConfigInterface,
    Go\Exception\EnvironmentNotFound;

abstract class Deployer
{
    /**
     * @var \Go\Config\ConfigInterface
     */
    protected $config;

    /**
     * @var \Go\Config\ConfigInterface
     */
    protected $systemConfig;

    /**
     * The environment to deploy (a string, like "production" or "server-1")
     *
     * @var string
     */
    protected $env;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \OOSSH\SSH2\Connection
     */
    protected $ssh;

    public function __construct(ConfigInterface $config, ConfigInterface $systemConfig, $env, OutputInterface $output)
    {
        $this->config       = $config;
        $this->systemConfig = $systemConfig;
        $this->output       = $output;
        $this->env          = $env;

        if (null === $this->config->get($env)) {
            throw new EnvironmentNotFound($env);
        }
    }

    public function deploy($go)
    {
        if (false !== $go) {
            $this->preDeploy($go);
        }
        $this->getStrategy()->deploy($this->config, $this->env, $go);

        if (false !== $go) {
            $this->postDeploy($go);
        }
    }

    abstract protected function getStrategy();

    abstract protected function preDeploy();

    abstract protected function postDeploy();

    protected function getSshAuthentication()
    {
        throw new \RuntimeException('You must override the getSshAuthentication method to use SSH');
    }

    /**
     * @return \OOSSH\SSH2\Connection
     */
    protected function getSsh()
    {
        if (null === $this->ssh) {
            $this->ssh = new Connection($this->getHost(), $this->getPort());
            $this->ssh
                ->connect()
                ->authenticate($this->getSshAuthentication());
        }

        return $this->ssh;
    }

    protected function exec($command)
    {
        $that = $this;
        $this->getSsh()->exec($command, function($stdio, $stderr) use ($that) {
            $that->addOutput($stdio);
            $that->addOutput($stderr);
        });

        return $this;
    }

    protected function sudo($command)
    {
        return $this->exec('sudo '.$command);
    }

    protected function symfony($command)
    {
        return $this->exec('php symfony '.$command);
    }

    public function addOutput($output)
    {
        $this->output->write($output);
    }

}
