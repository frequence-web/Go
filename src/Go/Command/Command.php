<?php

namespace Go\Command;

use \Symfony\Component\Console\Command\Command as BaseCommand,
    \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface,
    \Symfony\Component\Yaml\Yaml;

use \Gaufrette\Filesystem,
    \Gaufrette\Adapter\Local;

use \Go\Exception\NotAGoDirectoryException;

abstract class Command extends BaseCommand
{
    protected $cwd;

    /**
     * @var \Gaufrette\Filesystem
     */
    protected $filesystem;

    protected $config;

    protected $systemConfig;

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir(getcwd() . '/.go')) {
            throw new NotAGoDirectoryException;
        }

        $this->cwd = getcwd();
        $this->filesystem = new \Gaufrette\Filesystem(new Local($this->cwd));
    }

    protected function getConfig($env)
    {
        if (null === $this->config) {
            $this->config = Yaml::parse($this->filesystem->read($this->getSetting('config_dir').'/deploy.yml'));
        }

        if (!isset($this->config[$env])) {
            throw new \InvalidArgumentException(sprintf('The env %s does not exists', $env));
        }

        return $this->config[$env];
    }

    protected function getSetting($setting)
    {
        if (null === $this->systemConfig) {
            $this->systemConfig = Yaml::parse($this->filesystem->read('.go/config.yml'));
        }

        return $this->systemConfig[$setting];
    }

}
