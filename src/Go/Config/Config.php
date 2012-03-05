<?php

namespace Go\Config;

use \Gaufrette\File;

abstract class Config implements ConfigInterface
{
    /**
     * The config array
     *
     * @var array
     */
    protected $config = array();

    /**
     * @var \Gaufrette\File|null
     */
    protected $file;

    /**
     * Constructs and load configuration
     *
     * @param \Gaufrette\File|null $file
     */
    function __construct(File $file = null)
    {
        $this->file = $file;
        if (null !== $file) {
            $this->load($file);
        }
    }

    /**
     * Destructor, dumps the configuration
     */
    public function __destruct()
    {
        if (null !== $this->file) {
            $this->dump($this->file);
        }
    }

    /**
     * Adds values to the config
     *
     * @param array $values
     * @return Config
     */
    public function add(array $values)
    {
        $this->config = $this->merge($this->config, $values);

        return $this;
    }

    /**
     * get a config value
     *
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;
        for ($i = 0 ; $i < count($keys) ; $i++) {
            if (!isset($value[$keys[$i]])) {
                return $default;
            }
            $value = $value[$keys[$i]];
        }

        return $value;
    }

    /**
     * Sets a config value
     *
     * @param $key
     * @param $value
     * @return ConfigInterface
     */
    public function set($key, $value)
    {
        $this->add($this->setArray($key, $value));
    }

    /**
     * Returns the config array
     *
     * @return array|mixed
     */
    public function getAll()
    {
        return $this->config;
    }

    /**
     * Explode keys like foo.bar.a into multi-dimensional arrays
     *
     * @param $key
     * @param $value
     * @return array
     */
    protected function setArray($key, $value)
    {
        $pos = strpos($key, '.');
        if (false === $pos) {
            return array($key => $value);
        } else {
            return array(
                substr($key, 0, $pos) => $this->setArray(substr($key, $pos + 1), $value)
            );
        }
    }

    /**
     * Deep merges configuration.
     *
     * @param array $ar1
     * @param array $ar2
     * @return array
     */
    protected function merge(array $ar1, array $ar2)
    {
        $result = $ar1;
        foreach ($ar2 as $key => $value) {
            if (is_array($value) && isset($result[$key]) && is_array($result[$key])) {
                $result[$key] = $this->merge($result[$key], $value);
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

}
