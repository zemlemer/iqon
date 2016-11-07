<?php

namespace App;

use App\Contracts\ConfigLayerInterface;
use App\Exceptions\WrongParameterException;

class ConfigLayer implements ConfigLayerInterface
{
    protected $keys = [];
    protected $data = [];
    protected $position = 0;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->keys = array_keys($data);
    }

    public function current()
    {
        return $this->data[$this->key()];
    }

    public function get(string $key)
    {
        if(!array_key_exists($key, $this->data)) {
            throw new WrongParameterException("Data for key ". $key ." isn't set");
        }

        return $this->data[$key];
    }

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

    public function valid() {
        return isset($this->data[$this->key()]);
    }
}