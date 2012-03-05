<?php

namespace Go\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Yaml\Yaml;

use Gaufrette\Filesystem,
    Gaufrette\Adapter\Local;

use Go\Exception\AlreadyAGoDirectoryException,
    Go\Deployer\Deployer,
    Go\Deployer\Deploy as AppDeployer;

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
        require_once $this->systemConfig->get('config_dir').'/Deploy.php';

        $deployer = new AppDeployer($this->config, $this->systemConfig, $input->getArgument('env'), $output);
        $deployer->deploy($input->getOption('go'));
    }
}
