<?php

namespace Go\Config;

use \Gaufrette\File;

class JsonConfig extends Config
{
    /**
     * Loads the configuration
     *
     * @param $filename
     * @return ConfigInterface
     */
    public function load(File $file)
    {
        $this->config = json_decode($file->getContent(), true);

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
        $file->setContent(json_encode($this->config));

        return $this;
    }

}