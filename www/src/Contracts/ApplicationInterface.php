<?php

namespace App\Contracts;

interface ApplicationInterface
{
    public function __construct(ConfigInterface $config);

    public function run() : ResponseInterface;

    public function getConfig() : ConfigInterface;
}