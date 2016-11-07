<?php

namespace App;

use App\Contracts\{
    ControllerInterface, RequestInterface, ResponseInterface, ViewInterface
};
use App\Exceptions\WrongParameterException;
use App\Http\Response;

class Controller implements ControllerInterface
{
    const TEMPLATES_CONFIG_KEY = 'templates';

    const USERS = ['Alex', 'Mary', 'John', 'Helga','Alice'];

    /**
     * @var RequestInterface $request
     */
    protected $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function buildResponse($template, $params = []) : ResponseInterface
    {
        $params = array_merge($params, ['users' => $this->getUsers()]);
        $view = $this->getView();
        $result = $view->render($template, $params);
        $response = $this
            ->getResponse()
            ->setBody($result);

        return $response;
    }

    protected function getUsers() : array
    {
        return self::USERS;
    }

    protected function getResponse() : ResponseInterface
    {
        return new Response(Response::CODE_OK);
    }

    protected function getView() : ViewInterface
    {
        $config = $this->request->getApp()->getConfig();
        $templatesLayer = $config->get(self::TEMPLATES_CONFIG_KEY);
        return new View($templatesLayer);
    }

    protected function checkPost($key)
    {
        $value = $this->request->post($key);
        if(is_null($value)) {
            throw new WrongParameterException($key ." isn't defined in the request");
        }

        return $value;
    }
}