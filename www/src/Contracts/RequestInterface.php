<?php

namespace App\Contracts;

interface RequestInterface
{
    public function __construct(ApplicationInterface $app);

    public function getApp() : ApplicationInterface;

    public function getUrl() : string;

    public function post($key);

    public function get($key);
}