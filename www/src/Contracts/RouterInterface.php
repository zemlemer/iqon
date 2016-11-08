<?php

namespace App\Contracts;

/**
 * Interface RouterInterface
 *
 * @package App\Contracts
 */
interface RouterInterface
{
    /**
     * @param \App\Contracts\RequestInterface $request
     * @return \App\Contracts\ResponseInterface
     */
    public function process(RequestInterface $request) : ResponseInterface;
}