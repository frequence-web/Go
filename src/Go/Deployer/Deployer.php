<?php

namespace Go\Deployer;

use Symfony\Component\Console\Output\OutputInterface;

use OOSSH\SSH2\Connection;

use Go\Deployer\Strategy\StrategyInterface,
    Go\Config\ConfigInterface,
    Go\Exception\EnvironmentNotFound;

/**
 * The base deployer class.
 * This class will be overided by the user class
 */
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

    /**
     * @param \Go\Config\ConfigInterface $config
     * @param \Go\Config\ConfigInterface $systemConfig
     * @param $env
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
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

    /**
     * Deploys your application.
     *
     * @param $go
     */
    public function deploy($go)
    {
        $this->preDeploy($go);
        if (method_exists($this, $method = 'preDeploy'.ucfirst($this->env))) {
            $this->$method($go);
        }

        $this->getStrategy()->deploy($this->config, $this->env, $go);

        $this->postDeploy($go);
        if (method_exists($this, $method = 'postDeploy'.ucfirst($this->env))) {
            $this->$method($go);
        }
    }

    /**
     * Returns the deploy strategy
     *
     * @abstract
     * @return \Go\Deployer\Strategy\StrategyInterface
     */
    abstract protected function getStrategy();

    /**
     * Commands executed before deployment
     *
     * @abstract
     */
    abstract protected function preDeploy($go);

    /**
     * Commands executed after deployment
     *
     * @abstract
     */
    abstract protected function postDeploy($go);

    protected function getSshAuthentication()
    {
        throw new \RuntimeException('You must override the getSshAuthentication method to use SSH');
    }

    /**
     * @return \OOSSH\SSH2\Connection
     */
    public function getSsh()
    {
        if (null === $this->ssh) {
            $this->ssh = new Connection($this->config->get($this->env.'.host'), $this->config->get($this->env.'.port'));
            $this->ssh
                ->connect()
                ->authenticate($this->getSshAuthentication());
        }

        return $this->ssh;
    }

    public function exec($command)
    {
        $this->addOutput(sprintf('<info> >> %s</info>', $command));
        $that = $this;
        $this->getSsh()->exec($command, function($stdio, $stderr) use ($that) {
            $that
                ->addOutput($stdio)
                ->addOutput($stderr);
        });

        return $this;
    }

    public function copy($from, $to, $recursive = false)
    {
        return $this->exec(sprintf('cp -p%s %s %s', $recursive ? 'r' : '', $from, $to));
    }

    public function sudo($command)
    {
        return $this->exec('sudo '.$command);
    }

    public function addOutput($output)
    {
        if ($output) {
            $this->output->writeln($output);
        }

        return $this;
    }

}
