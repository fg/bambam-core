<?php namespace Bambam\Base;

use Bambam\Exception\ThemeException;
use Slim\View as SlimView;

class View extends SlimView
{
    protected $layout;

    private $themePath;

    public function __construct($themePath = 'defaultba')
    {
        parent::__construct();

        $this->setThemePath($themePath);

        return $this;
    }

    private function setThemePath($themePath)
    {
        if (! is_dir(APP_BASE_PATH . '/themes/' . $themePath)) {
            throw new ThemeException('Theme folder is not defined.');
        }

        $this->themePath = $themePath;
        $this->setTemplatesDirectory(APP_BASE_PATH . '/themes/' . $themePath);
    }

    public function getThemePath()
    {
        return $this->themePath;
    }
}