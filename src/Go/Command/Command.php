<?php

namespace Go\Command;

use Symfony\Component\Console\Command\Command as BaseCommand,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Yaml\Yaml;

use Gaufrette\Filesystem,
    Gaufrette\Adapter\Local;

use Go\Exception\NotAGoDirectoryException,
    Go\Config\JsonConfig,
    Go\Config\YamlConfig;

/**
 * The base Go command.
 * Providers some tools for init, deploy, etc. commands
 */
abstract class Command extends BaseCommand
{
    protected $cwd;

    /**
     * @var \Gaufrette\Filesystem
     */
    protected $filesystem;

    /**
     * The config handler
     *
     * @var \Go\Config\ConfigInterface
     */
    protected $config;

    /**
     * The system config handlers
     *
     * @var \Go\Config\ConfigInterface
     */
    protected $systemConfig;

    /**
     * Command initialization
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @throws \Go\Exception\NotAGoDirectoryException
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir(getcwd() . '/.go')) {
            throw new NotAGoDirectoryException;
        }

        $this->cwd = getcwd();
        $this->filesystem = new \Gaufrette\Filesystem(new Local($this->cwd));
        $this->systemConfig = new JsonConfig($this->filesystem->get('.go/config.json'));
        $this->config = new YamlConfig($this->filesystem->get($this->systemConfig->get('config_dir').'/deploy.yml'));
    }
}
