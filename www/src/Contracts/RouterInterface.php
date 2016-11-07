<?php

namespace App\Contracts;

interface RouterInterface
{
    public function process(RequestInterface $request) : ResponseInterface;
}