<?php

namespace Go\Command;

use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputArgument;

class Tag extends Command
{
    protected function configure()
    {
        $this
            ->setName('tag')
            ->setDefinition(array(
                new InputArgument('version', InputArgument::OPTIONAL, 'The tag name (version)', null)
            ))
            ->setDescription('Clear the cache')
            ->setHelp(<<<EOF
The <info>tag</info> command make a deploy tag into the SCM

<info>go tag</info>
<info>go tag v2.0.1</info>
EOF
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
    }
}