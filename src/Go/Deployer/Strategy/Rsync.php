<?php

namespace Go\Deployer\Strategy;

use Symfony\Component\Process\Process,
    Symfony\Component\Console\Output\OutputInterface;

use Go\Deployer\Deployer,
    Go\Config\ConfigInterface;

class Rsync implements StrategyInterface
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Go\Config\ConfigInterface
     */
    protected $systemConfig;

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    public function __construct(OutputInterface $output, ConfigInterface $systemConfig)
    {
        $this->output = $output;
        $this->systemConfig = $systemConfig;
    }

    /**
     * Deploys your application via rsync
     *
     * @param \Go\Config\ConfigInterface $config
     * @param $env
     * @param $go
     */
    public function deploy(ConfigInterface $config, $env, $go)
    {
        $commandLine  = 'rsync -az --force --delete --progress -e "ssh -p'.$config->get($env.'.port').'"';
        $commandLine .= ' --exclude-from='.$this->prepareExcludeFile($config->get($env.'.exclude'));
        $commandLine .= ' '.getcwd().'/ ';
        $commandLine .= $config->get($env.'.user').'@'.$config->get($env.'.host').':'.$this->getRemotePath($config, $env);

        if ($go !== true) {
            $commandLine .= ' --dry-run';
        }

        $this->getOutput()->writeln(sprintf('<info> >> %s</info>', $commandLine));

        $that = $this;
        $process = new Process($commandLine, getcwd());
        $process->run(function($type, $data) use ($that) {
            $that->getOutput()->writeln($data);
        });
    }

    /**
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    public function getOutput()
    {
        return $this->output;
    }

    protected function getRemotePath(ConfigInterface $config, $env)
    {
        return $config->get($env.'.remote_dir').'/';
    }

    /**
     * Write excludes files in a rsync_exclude file
     *
     * @param array $excludes
     * @return string
     */
    private function prepareExcludeFile(array $excludes)
    {
        $excludeFilePath = $this->systemConfig->get('config_dir').'/rsync_exlude.txt';
        file_put_contents($excludeFilePath, implode("\n", $excludes));

        return $excludeFilePath;
    }
}
