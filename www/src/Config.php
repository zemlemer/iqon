<?php

namespace App;

use App\Contracts\ConfigInterface;
use App\Exceptions\WrongParameterException;
use App\Contracts\ConfigLayerInterface;

class Config implements ConfigInterface
{
    public function __construct(array $data)
    {
        foreach ($data as $layerName => $layerData) {
            $this->data[$layerName] = new ConfigLayer($layerData);
        }
    }

    public function get(string $key) : ConfigLayerInterface
    {
        if(!array_key_exists($key, $this->data)) {
            throw new WrongParameterException("Config layer ". $key ." doesn't exist");
        }

        return $this->data[$key];
    }
}