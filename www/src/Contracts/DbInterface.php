<?php

namespace App\Contracts;

interface DbInterface
{
    const
        CONFIG_KEY_DSN = 'dsn',
        CONFIG_KEY_USERNAME = 'username',
        CONFIG_KEY_PASSWORD = 'password',
        CONFIG_KEY_OPTIONS = 'options';

    public static function getInstance() : DbInterface;

    public function setConnectionParams(ConfigLayerInterface $connectionParams) : self;

    public function query(string $sql, array $bindings);

    public function getLastID();
}