<?php

namespace App\Contracts;

/**
 * Interface RequestInterface
 *
 * @package App\Contracts
 */
interface RequestInterface
{
    /**
     * RequestInterface constructor.
     *
     * @param \App\Contracts\ApplicationInterface $app
     */
    public function __construct(ApplicationInterface $app);

    /**
     * @return \App\Contracts\ApplicationInterface
     */
    public function getApp() : ApplicationInterface;

    /**
     * @return string
     */
    public function getUrl() : string;

    /**
     * @param $key
     * @return mixed
     */
    public function post($key);

    /**
     * @param $key
     * @return mixed
     */
    public function get($key);
}
