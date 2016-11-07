<?php

namespace App\Contracts;

interface ViewAdapterInterface
{
    public function assign($name, $value) : self;

    public function render(string $tpl, array $params) : string;
}