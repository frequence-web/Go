<?php

namespace Go\Deployer\Strategy;

use \Symfony\Component\Process\Process,
    \Symfony\Component\Console\Output\OutputInterface;

use \Go\Deployer\Deployer;

class Rsync implements StrategyInterface
{
    protected $output;

    function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }


    public function deploy(Deployer $deployer)
    {
        $commandLine  = 'rsync --dry-run -azC --force --delete --progress -e "ssh -p'.$deployer->getPort().'"';

        foreach ($deployer->getExclude() as $exclude) {
            $commandLine .= ' --exclude='.$exclude;
        }

        $commandLine .= ' '.getcwd().'/ ';
        $commandLine .= $deployer->getUser().'@'.$deployer->getHost().':'.$deployer->getRemoteDir();

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
