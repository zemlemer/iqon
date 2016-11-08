<?php

namespace App\Contracts;

/**
 * Interface ViewAdapterInterface
 *
 * @package App\Contracts
 */
interface ViewAdapterInterface
{
    /**
     * @param $name
     * @param $value
     * @return \App\Contracts\ViewAdapterInterface
     */
    public function assign($name, $value) : self;

    /**
     * @param string $tpl
     * @param array $params
     * @return string
     */
    public function render(string $tpl, array $params) : string;
}