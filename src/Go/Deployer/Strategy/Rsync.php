<?php

namespace Go\Deployer\Strategy;

use \Symfony\Component\Process\Process,
    \Symfony\Component\Console\Output\OutputInterface;

use \Go\Deployer\Deployer,
    \Go\Config\ConfigInterface;

class Rsync implements StrategyInterface
{
    protected $output;

    function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }


    public function deploy(ConfigInterface $config, $env, $go)
    {
        $commandLine  = 'rsync -azC --force --delete --progress -e "ssh -p'.$config->get($env.'.port').'"';

        foreach ($config->get($env.'.exclude') as $exclude) {
            $commandLine .= ' --exclude='.$exclude;
        }

        $commandLine .= ' '.getcwd().'/ ';
        $commandLine .= $config->get($env.'.user').'@'.$config->get($env.'.host').':'.$config->get($env.'.remote_dir');

        if ($go !== true) {
            $commandLine .= ' --dry-run';
        }

        $that = $this;
        $process = new Process($commandLine, getcwd());
        $process->run(function($type, $data) use ($that) {
            $that->getOutput()->write($data);
        });
    }

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }


}
