<?php
namespace App;

use App\Contracts\{
    ApplicationInterface, ConfigInterface, DbInterface, RequestInterface, ResponseInterface, RouterInterface
};
use App\DB\QueryBuilder;
use App\Exceptions\NotFoundException;
use App\Http\{
    Request, Response, Router
};

/**
 * Class Application
 *
 * @package App
 */
class Application implements ApplicationInterface
{
    const
        DB_CONFIG_KEY = 'db',
        DB_PROVIDER_CONFIG_KEY = 'provider';

    /** @var array $config */
    protected $config = [];

    /**
     * @var RequestInterface $request
     */
    protected $request;

    /**
     * Application constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
        $this->setDbConnector();
    }

    /**
     * @return \App\Contracts\ConfigInterface
     */
    public function getConfig() : ConfigInterface
    {
        return $this->config;
    }

    /**
     * Основной метод приложения.
     * Обрабатывается запрос, при помощи роутера ищутся контроллер и экшен.
     * @return ResponseInterface
     */
    public function run() : ResponseInterface
    {
        $request  = $this->getRequest();
        $router   = $this->getRouter();
        try{
            $response = $router->process($request);
        } catch (NotFoundException $e) {
            $response = $this->error(Response::CODE_NOT_FOUND);
        }

        $response->sendHeaders();

        return $response;
    }

    /**
     * @return \App\Contracts\RouterInterface
     */
    protected function getRouter() : RouterInterface
    {
        return  new Router($this->config);
    }

    /**
     * @return \App\Contracts\RequestInterface
     */
    protected function getRequest() : RequestInterface
    {
        if(!$this->request) {
            $this->request = new Request($this);
        }

        return $this->request;
    }

    /**
     * @param $code
     * @return \App\Contracts\ResponseInterface
     */
    protected function error($code) : ResponseInterface
    {
        return new Response($code);
    }

    /**
     * Согласно настройкам приложения устанавливается коннектор для бд
     * @return Application
     */
    protected function setDbConnector() : self
    {
        $dbLayer = $this->config->get(static::DB_CONFIG_KEY);
        /**
         * @var DbInterface $dbProviderClass
         */
        $dbProviderClass = $dbLayer->get(static::DB_PROVIDER_CONFIG_KEY);
        $dbProvider = $dbProviderClass::getInstance()->setConnectionParams($dbLayer);
        QueryBuilder::setProvider($dbProvider);

        return $this;
    }
}
