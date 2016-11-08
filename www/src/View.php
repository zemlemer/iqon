<?php

namespace App;

use App\Contracts\ConfigLayerInterface;
use App\Contracts\ViewAdapterInterface;
use App\Contracts\ViewInterface;

/**
 * Class View
 *
 * @package App
 */
class View implements ViewInterface
{
    /**
     *
     */
    const ADAPTER_CONFIG_KEY = 'adapter';

    /** @var ViewInterface */
    protected $adapter;

    /** @var \App\Contracts\ConfigLayerInterface */
    protected $layer;

    /**
     * View constructor.
     *
     * @param \App\Contracts\ConfigLayerInterface $layer
     */
    public function __construct(ConfigLayerInterface $layer)
    {
        $this->layer = $layer;
        $this->adapter = $this->getAdapter();
    }

    /**
     * @return \App\Contracts\ViewAdapterInterface
     */
    public function getAdapter() : ViewAdapterInterface
    {
        $className = $this->layer->get(self::ADAPTER_CONFIG_KEY);

        return new $className($this->layer);
    }

    /**
     * @param $name
     * @param $value
     * @return \App\Contracts\ViewAdapterInterface
     */
    public function assign($name, $value) : ViewAdapterInterface
    {
        return $this->adapter->assign($name, $value);
    }

    /**
     * @param string $tpl
     * @param array $params
     * @return string
     */
    public function render(string $tpl, array $params = []) : string
    {
        return $this->adapter->render($tpl, $params);
    }
}