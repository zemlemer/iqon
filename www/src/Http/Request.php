<?php

namespace App\Http;

use App\Contracts\ApplicationInterface;
use App\Contracts\RequestInterface;

class Request implements RequestInterface
{
    /**
     * @var ApplicationInterface $app
     */
    protected $app;

    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    public function getApp() : ApplicationInterface
    {
        return $this->app;
    }

    public function getUrl() : string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function post($key)
    {
        return $_POST[$key] ?? null;
    }

    public function get($key)
    {
        return $_GET[$key] ?? null;
    }
}