<?php

namespace App;

use App\Contracts\{
    ControllerInterface, RequestInterface, ResponseInterface, ViewInterface
};
use App\Exceptions\WrongParameterException;
use App\Http\Response;

/**
 * Class Controller
 *
 * @package App
 */
class Controller implements ControllerInterface
{
    const TEMPLATES_CONFIG_KEY = 'templates';

    const USERS = ['Alex', 'Mary', 'John', 'Helga','Alice'];

    /** @var RequestInterface $request */
    protected $request;

    /**
     * Controller constructor.
     *
     * @param \App\Contracts\RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @param $template
     * @param array $params
     * @return \App\Contracts\ResponseInterface
     */
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

    /**
     * @return array
     */
    protected function getUsers() : array
    {
        return self::USERS;
    }

    /**
     * @return \App\Contracts\ResponseInterface
     */
    protected function getResponse() : ResponseInterface
    {
        return new Response(Response::CODE_OK);
    }

    /**
     * @return \App\Contracts\ViewInterface
     */
    protected function getView() : ViewInterface
    {
        $config = $this->request->getApp()->getConfig();
        $templatesLayer = $config->get(self::TEMPLATES_CONFIG_KEY);
        return new View($templatesLayer);
    }

    /**
     * @param $key
     * @return mixed
     */
    protected function checkPost($key)
    {
        $value = $this->request->post($key);
        if(is_null($value)) {
            throw new WrongParameterException($key ." isn't defined in the request");
        }

        return $value;
    }
}