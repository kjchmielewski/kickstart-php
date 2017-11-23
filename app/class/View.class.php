<?php

class View
{
    private $base;
    private $ext;
    private $vars = array();

    public function __construct($base)
    {
        $this->base = $base;
        $this->ext = '.view.php';
    }

    public function set($key, $val)
    {
        $this->vars[$key] = $val;
    }

    public function render($view)
    {
        extract($this->vars);
        ob_start();
        include ($this->base . $view . $this->ext);
        return ob_get_clean();
    }
}
