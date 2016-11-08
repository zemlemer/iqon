<?php

namespace App;

use App\Contracts\ConfigLayerInterface;
use App\Exceptions\WrongParameterException;

/**
 * Class ConfigLayer
 *
 * @package App
 */
class ConfigLayer implements ConfigLayerInterface
{
    /** @var array */
    protected $keys = [];

    /** @var array */
    protected $data = [];
    /** @var int */
    protected $position = 0;

    /**
     * ConfigLayer constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->keys = array_keys($data);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->key()];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if(!array_key_exists($key, $this->data)) {
            throw new WrongParameterException("Data for key ". $key ." isn't set");
        }

        return $this->data[$key];
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->keys[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return bool
     */
    public function valid() {
        return isset($this->data[$this->key()]);
    }
}