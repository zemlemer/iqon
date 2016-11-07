<?php

namespace App\Contracts;

interface ViewInterface extends ViewAdapterInterface
{
    public function __construct(ConfigLayerInterface $templatesConfig);

    public function getAdapter() : ViewAdapterInterface;
}
