<?php

class View
{
    private $basePath;
    private $viewExt;
    private $viewVars = array();

    public function __construct($base)
    {
        $this->basePath = rtrim(strtr($base, '\\', '/'), "/");
        $this->viewExt = '.view.php';
    }

    public function set($key, $val)
    {
        $this->viewVars[$key] = $val;
    }

    public function render($view)
    {
        $filepath = realpath($this->basePath . '/' . $view . $this->viewExt);
        $dir = dirname($filepath);
        if (0 === strcmp($dir, $this->basePath)) {
            extract($this->viewVars);
            ob_start();
            include($filepath);
            return ob_get_clean();
        }
        return '';
    }
}
