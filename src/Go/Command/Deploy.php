<?php

namespace Go\Command;

use \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface,
    \Symfony\Component\Console\Input\InputArgument,
    \Symfony\Component\Console\Input\InputOption,
    \Symfony\Component\Yaml\Yaml;

use \Gaufrette\Filesystem,
    \Gaufrette\Adapter\Local;

use \Go\Exception\AlreadyAGoDirectoryException;

class Deploy extends Command
{
    protected function configure()
    {
        $this
            ->setName('deploy')
            ->setDefinition(array(
                new InputArgument('env', InputArgument::REQUIRED, 'The environment to deploy'),
                new InputOption('go', null, InputOption::VALUE_NONE, 'Dry run if not')
            ))
            ->setDescription('Deploy your application')
            ->setHelp(<<<EOF
The <info>deploy</info> command deploys your application in the given environment

<info>go deploy production</info>
<info>go deploy production --go</info>
EOF
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        require_once $this->getSetting('config_dir').'/Deploy.php';
        $deployer = new \Go\Deployer\Deploy($this->getConfig($input->getArgument('env')));
        $deployer->deploy(new \Go\Deployer\Strategy\Rsync($output), $input->getOption('go'));
    }
}
