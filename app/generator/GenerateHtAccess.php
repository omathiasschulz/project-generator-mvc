<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateHtAccess
{
    /**
     * Método responsável por gerar o .htaccess
     */
    public static function create()
    {
        $oBody = self::getBody();
        Helpers::writeFile('.htaccess', $oBody);
    }

    /**
     * Método que gera a string que será gravada no .htaccess
     */
    private function getBody()
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("RewriteEngine ON")
            ->appendNL("RewriteCond %{REQUEST_FILENAME} !-f")
            ->appendNL("RewriteCond %{REQUEST_FILENAME} !-d")
            ->appendNL("RewriteRule ^(.*)$ index.php [NC,L,QSA]");

        return $oBody;
    }
}
