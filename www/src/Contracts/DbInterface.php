<?php

namespace App\Contracts;

/**
 * Interface DbInterface
 *
 * @package App\Contracts
 */
interface DbInterface
{
    const
        CONFIG_KEY_DSN = 'dsn',
        CONFIG_KEY_USERNAME = 'username',
        CONFIG_KEY_PASSWORD = 'password',
        CONFIG_KEY_OPTIONS = 'options';

    /**
     * @return \App\Contracts\DbInterface
     */
    public static function getInstance() : DbInterface;

    /**
     * @param \App\Contracts\ConfigLayerInterface $connectionParams
     * @return \App\Contracts\DbInterface
     */
    public function setConnectionParams(ConfigLayerInterface $connectionParams) : self;

    /**
     * @param string $sql
     * @param array $bindings
     * @return mixed
     */
    public function query(string $sql, array $bindings);

    /**
     * @return mixed
     */
    public function getLastID();
}
