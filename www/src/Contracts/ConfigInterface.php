<?php

namespace App\Contracts;

interface ConfigInterface
{
    public function __construct(array $data);

    public function get(string $key) : ConfigLayerInterface;
}