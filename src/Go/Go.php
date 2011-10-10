<?php

namespace Go;

use \Symfony\Component\Console\Application;

class Go extends Application
{
  const VERSION = '1.0.0-dev';

  public function __construct()
  {
      parent::__construct('Go deployment tool', self::VERSION);

      $this->add(new Command\Tag);
      $this->add(new Command\Init);
  }
}