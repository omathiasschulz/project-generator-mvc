<?php

namespace core;

class ControllerUtil
{

    /**
     * Método para instanciar o controller
     */
    public static function newController($controller)
    {
        $objController = "app\\controller\\" . $controller;
        return new $objController;
    }

    /**
     * Rota para página não encontrada
     * Por padrão em app/view/404.phtml
     */
    public static function page404()
    {
        if(file_exists(__DIR__ . "/../app/view/404.phtml")) {
            return require_once __DIR__ . "/../app/view/404.phtml";
        } else {
            echo "Error 404: Page not found!";
        }
    }
}
