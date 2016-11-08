<?php

namespace App\Contracts;

/**
 * Interface ConfigInterface
 *
 * @package App\Contracts
 */
interface ConfigInterface
{
    /**
     * ConfigInterface constructor.
     *
     * @param array $data
     */
    public function __construct(array $data);

    /**
     * @param string $key
     * @return \App\Contracts\ConfigLayerInterface
     */
    public function get(string $key) : ConfigLayerInterface;
}
