<?php

namespace Go\Config;

use \Gaufrette\File;

abstract class Config implements ConfigInterface
{
    protected $config = array();

    protected $file;

    function __construct(File $file = null)
    {
        $this->file = $file;
        if (null !== $file) {
            $this->load($file);
        }
    }

    public function __destruct()
    {
        if (null !== $this->file) {
            $this->dump($this->file);
        }
    }

    /**
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
        $value = $this->config[$keys[0]];
        for ($i = 1 ; $i < count($keys) ; $i++) {
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
