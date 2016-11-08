<?php

namespace App\ViewAdapters;

use App\Contracts\{
    ConfigLayerInterface,
    ViewAdapterInterface
};

/**
 * Class TwigAdapter
 *
 * @package App\ViewAdapters
 */
class TwigAdapter implements ViewAdapterInterface
{
    const
        CONFIG_KEY_PATH = 'path',
        CONFIG_KEY_COMPILED_PATH = 'compiled_path',
        CONFIG_KEY_DEBUG = 'debug';

    /** @var array */
    protected $vars = [];

    /** @var \Twig_Loader_Filesystem */
    protected $loader;

    /** @var \Twig_Environment */
    protected $twig;

    /**
     * TwigAdapter constructor.
     *
     * @param \App\Contracts\ConfigLayerInterface $tplConfig
     */
    public function __construct(ConfigLayerInterface $tplConfig)
    {
        $template_path          = $tplConfig->get(self::CONFIG_KEY_PATH);
        $template_compiled_path = $tplConfig->get(self::CONFIG_KEY_COMPILED_PATH);

        if ($template_compiled_path && !file_exists($template_compiled_path)) {
            mkdir($template_compiled_path, 0777);
        }
        $this->loader = new \Twig_Loader_Filesystem($template_path);
        $this->twig   = new \Twig_Environment($this->loader, array(
            'cache'      => $template_compiled_path,
            'debug'      => $tplConfig->get(self::CONFIG_KEY_DEBUG),
            'autoescape' => false,
        ));
    }

    /**
     * @param $name
     * @param $value
     * @return ViewAdapterInterface
     */
    public function assign($name, $value) : ViewAdapterInterface
    {
        $this->vars[$name] = $value;

        return $this;
    }

    /**
     * @param string $tpl
     * @param array $params
     * @return string
     */
    public function render(string $tpl, array $params = []) : string
    {
        $this->vars = array_merge($this->vars, $params);
        $template = $this->twig->loadTemplate($tpl . '.twig');

        return $template->render($this->vars);
    }
}