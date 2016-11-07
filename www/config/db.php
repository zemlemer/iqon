<?php

return [
    'provider' => \App\DB\MySQL::class,
    'dsn' => 'mysql:host=localhost;port=3306;dbname=homestead',
    'username' => 'homestead',
    'password' => 'secret',
    'options'  => array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ),
];