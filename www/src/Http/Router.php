<?php
namespace App\Http;

use App\Contracts\ConfigInterface;
use App\Contracts\ControllerInterface;
use App\Contracts\RequestInterface;
use App\Contracts\ResponseInterface;
use App\Contracts\RouterInterface;
use App\Exceptions\NotFoundException;

class Router implements RouterInterface
{
    const ROUTES_CONFIG_KEY = 'routes';

    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Роутинг самый простой,
     * в реальных условиях, естесственно, надо делать универсальнее
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function process(RequestInterface $request) : ResponseInterface
    {
        $routes = $this->config->get(self::ROUTES_CONFIG_KEY);

        foreach ($routes as $rule => $actor) {
            $matchedParameters = $this->checkRule($rule, $request);
            if($matchedParameters !== false) {
                list($controllerClass, $method) = $actor;
                $controller = $this->getControllerByName($controllerClass, $request);
                if(is_array($matchedParameters)) {
                    return call_user_func_array([$controller, $method], $matchedParameters);
                } else {
                    return $controller->$method();
                }
            }
        }

        throw new NotFoundException("Page not found");
    }

    /**
     * @param string $rule
     * @param RequestInterface $request
     * @return bool
     */
    protected function checkRule(string $rule, RequestInterface $request)
    {
        $url = $request->getUrl();
        $pattern = "|". $rule ."|i";
        preg_match($pattern, $url, $matches);

        return count($matches) > 0;
    }

    protected function getControllerByName(string $className, RequestInterface $request) : ControllerInterface
    {
        return new $className($request);
    }
}