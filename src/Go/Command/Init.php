<?php

namespace Go\Command;

use \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface,
    \Symfony\Component\Console\Input\InputArgument,
    \Symfony\Component\Yaml\Yaml;

use \Gaufrette\Filesystem,
    \Gaufrette\Adapter\Local;

use \Go\Exception\AlreadyAGoDirectoryException;

class Init extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDefinition(array(
                new InputArgument('dir', InputArgument::OPTIONAL, 'The go config dir name', 'config/go')
            ))
            ->setDescription('Initialize Go')
            ->setHelp(<<<EOF
The <info>init</info> initialize the current directory as a Go directory

<info>go init</info>
<info>go init "config/deploy/go"</info>
EOF
        );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (is_dir(getcwd() . '/.go')) {
            throw new AlreadyAGoDirectoryException;
        }

        $this->cwd = getcwd();
        $this->filesystem = new Filesystem(new Local($this->cwd));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->filesystem->write('.go/config.yml', Yaml::dump(array(
           'config_dir' => $input->getArgument('dir')
        )));

        $this->filesystem->write(
            $input->getArgument('dir').'/deploy.yml',
            file_get_contents(__DIR__.'/../../../data/skeleton/deploy.yml')
        );

        $this->filesystem->write(
            $input->getArgument('dir').'/Deploy.php',
            file_get_contents(__DIR__.'/../../../data/skeleton/Deploy.php')
        );
    }
}
