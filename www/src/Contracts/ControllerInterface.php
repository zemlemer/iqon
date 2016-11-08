<?php

namespace App\Contracts;

/**
 * Interface ControllerInterface
 *
 * @package App\Contracts
 */
interface ControllerInterface
{
    /**
     * ControllerInterface constructor.
     *
     * @param \App\Contracts\RequestInterface $request
     */
    public function __construct(RequestInterface $request);

    /**
     * @param $template
     * @param array $params
     * @return \App\Contracts\ResponseInterface
     */
    public function buildResponse($template, $params = []) : ResponseInterface;
}
