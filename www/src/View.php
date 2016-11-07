<?php

namespace App;

use App\Contracts\ConfigLayerInterface;
use App\Contracts\ViewAdapterInterface;
use App\Contracts\ViewInterface;

class View implements ViewInterface
{
    const ADAPTER_CONFIG_KEY = 'adapter';

    /** @var ViewInterface */
    protected $adapter;

    protected $layer;

    public function __construct(ConfigLayerInterface $layer)
    {
        $this->layer = $layer;
        $this->adapter = $this->getAdapter();
    }

    public function getAdapter() : ViewAdapterInterface
    {
        $className = $this->layer->get(self::ADAPTER_CONFIG_KEY);

        return new $className($this->layer);
    }

    public function assign($name, $value) : ViewAdapterInterface
    {
        return $this->adapter->assign($name, $value);
    }

    public function render(string $tpl, array $params = []) : string
    {
        return $this->adapter->render($tpl, $params);
    }
}