<?php

namespace App\Contracts;

interface ConfigLayerInterface extends \Iterator
{
    public function get(string $key);
}