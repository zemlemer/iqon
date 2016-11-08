<?php

namespace App;

use App\Contracts\ConfigInterface;
use App\Exceptions\WrongParameterException;
use App\Contracts\ConfigLayerInterface;

/**
 * Class Config
 *
 * @package App
 */
class Config implements ConfigInterface
{
    /**
     * Config constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $layerName => $layerData) {
            $this->data[$layerName] = new ConfigLayer($layerData);
        }
    }

    /**
     * @param string $key
     * @return \App\Contracts\ConfigLayerInterface
     */
    public function get(string $key) : ConfigLayerInterface
    {
        if(!array_key_exists($key, $this->data)) {
            throw new WrongParameterException("Config layer ". $key ." doesn't exist");
        }

        return $this->data[$key];
    }
}