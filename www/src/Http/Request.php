<?php

namespace App\Http;

use App\Contracts\ApplicationInterface;
use App\Contracts\RequestInterface;

/**
 * Class Request
 *
 * @package App\Http
 */
class Request implements RequestInterface
{
    /** @var ApplicationInterface $app */
    protected $app;

    /**
     * Request constructor.
     *
     * @param \App\Contracts\ApplicationInterface $app
     */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }

    /**
     * @return \App\Contracts\ApplicationInterface
     */
    public function getApp() : ApplicationInterface
    {
        return $this->app;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @param $key
     * @return string | null
     */
    public function post($key)
    {
        return $_POST[$key] ?? null;
    }

    /**
     * @param $key
     * @return string | null
     */
    public function get($key)
    {
        return $_GET[$key] ?? null;
    }
}
