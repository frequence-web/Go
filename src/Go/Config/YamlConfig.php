<?php

namespace Go\Config;

use \Gaufrette\File;

use \Symfony\Component\Yaml\Yaml;

class YamlConfig extends Config
{
    /**
     * Loads the configuration
     *
     * @param $filename
     * @return ConfigInterface
     */
    public function load(File $file)
    {
        $this->config = Yaml::parse($file->getContent());

        return $this;
    }

    /**
     * Dumps the configuration
     *
     * @param $filename
     * @return ConfigInterface
     */
    public function dump(File $file)
    {
        $file->setContent(Yaml::dump($this->config));

        return $this;
    }

}