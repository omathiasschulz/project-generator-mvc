<?php

require_once('Helpers.php');

class GenerateAutoload
{
    const AUTOLOAD_NAME = '../autoload.php';

    /**
     * Método responsável por gerar o autoload
     */
    public static function create($aFolders, $aStandartFolders)
    {
        $folders = self::getFolders($aFolders, $aStandartFolders);
        $autoload = self::getAutoload($folders);
        Helpers::writeFile(self::AUTOLOAD_NAME, $autoload);
    }

    /**
     * Monta a string a partir dos folders do array
     */
    private function getStringFolders($aFolders)
    {
        $result = '';
        foreach ($aFolders as $folder) {
            // Um folder pode possuir vários pacotes
            $aPaths = explode('/', $folder);
            $sFolder = '';
            foreach ($aPaths as $path) {
                $sFolder .= $path . DIRECTORY_SEPARATOR; // São remontados de acordo com o SO
            }
            $sFolder = substr($sFolder, 0, strlen($sFolder) - 1);
            $result .= '"' . $sFolder . '", ';
        }
        return $result;
    }

    /**
     * Monta a string de folders em formato de array
     */
    private function getFolders($aFolders, $aStandartFolders)
    {
        $result = '[';
        if ($aStandartFolders)
            $result .= self::getStringFolders($aStandartFolders);
        
        if ($aFolders)
            $result .= self::getStringFolders($aFolders);
        
        if (strlen($result) > 1)
            $result = substr($result, 0, strlen($result) - 2);
        $result .= ']';
        return $result;
    }

    /**
     * Método que gera a string que será gravada no autoload.php
     */
    private function getAutoload($folders)
    {
        return 
'<?php

spl_autoload_register(function ($nomeClasse) {
    $folders = ' . $folders . ';
    foreach ($folders as $folder) {
        if (file_exists($folder.DIRECTORY_SEPARATOR.$nomeClasse.".php")) {
            require_once($folder.DIRECTORY_SEPARATOR.$nomeClasse.".php");
        }
    }
});';
    }
}
