<?php

namespace App\Contracts;

/**
 * Interface ViewInterface
 *
 * @package App\Contracts
 */
interface ViewInterface extends ViewAdapterInterface
{
    /**
     * ViewInterface constructor.
     *
     * @param \App\Contracts\ConfigLayerInterface $templatesConfig
     */
    public function __construct(ConfigLayerInterface $templatesConfig);

    /**
     * @return \App\Contracts\ViewAdapterInterface
     */
    public function getAdapter() : ViewAdapterInterface;
}
