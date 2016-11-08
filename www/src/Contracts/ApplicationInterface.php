<?php

namespace App\Contracts;

/**
 * Interface ApplicationInterface
 *
 * @package App\Contracts
 */
interface ApplicationInterface
{
    /**
     * ApplicationInterface constructor.
     *
     * @param \App\Contracts\ConfigInterface $config
     */
    public function __construct(ConfigInterface $config);

    /**
     * @return \App\Contracts\ResponseInterface
     */
    public function run() : ResponseInterface;

    /**
     * @return \App\Contracts\ConfigInterface
     */
    public function getConfig() : ConfigInterface;
}
