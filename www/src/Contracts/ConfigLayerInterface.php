<?php

namespace App\Contracts;

/**
 * Interface ConfigLayerInterface
 *
 * @package App\Contracts
 */
interface ConfigLayerInterface extends \Iterator
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);
}
