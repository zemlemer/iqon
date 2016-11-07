<?php

namespace App\Contracts;

interface ControllerInterface
{
    public function __construct(RequestInterface $request);

    public function buildResponse($template, $params = []) : ResponseInterface;
}