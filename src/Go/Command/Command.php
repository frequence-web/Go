<?php

namespace Go\Command;

use \Symfony\Component\Console\Command\Command as BaseCommand,
    \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface;

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

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!is_dir(getcwd() . '/.go')) {
            throw new NotAGoDirectoryException;
        }

        $this->cwd = getcwd();
        $this->filesystem = new \Gaufrette\Filesystem(new Local($this->cwd));
    }
}
