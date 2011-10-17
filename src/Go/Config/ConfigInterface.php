<?php

namespace Go\Config;

use \Gaufrette\File;

interface ConfigInterface
{
    /**
     * Loads the configuration
     *
     * @abstract
     * @param $filename
     * @return ConfigInterface
     */
    public function load(File $file);

    /**
     * Dumps the configuration
     *
     * @abstract
     * @param $filename
     * @return ConfigInterface
     */
    public function dump(File $file);

    /**
     * Add values to the config
     *
     * @abstract
     * @param array $values
     * @return ConfigInterface
     */
    public function add(array $values);

    /**
     * get a config value
     *
     * @abstract
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Sets a config value
     *
     * @abstract
     * @param $key
     * @param $value
     * @return ConfigInterface
     */
    public function set($key, $value);

    /**
     * Returns the config array
     * 
     * @abstract
     * @return array|mixed
     */
    public function getAll();
}
