<?php

class Helpers
{
    /**
     * Método que cria/sobreescreve um file com o conteúdo desejado
     */
    public static function writeFile($filename, $content)
    {
        $fp = fopen($filename, 'w');
        fwrite($fp, $content);
        fclose($fp);
    }
}