<?php

namespace helpers;

class Helpers
{
    const GLOBAL_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Project' . DIRECTORY_SEPARATOR;

    /**
     * Método que cria/sobreescreve um file com o conteúdo desejado
     */
    public static function writeFile($filename, $content)
    {
        $completeFilename = self::GLOBAL_PATH . $filename;
        $fp = fopen($completeFilename, 'w');
        fwrite($fp, $content);
        fclose($fp);
    }
    /**
     * Método que cria um folder caso ainda não exista
     * Deve ser passado o diretório a partir da pasta principal do projeto
     */
    public static function createFolder($pathFolder)
    {
        $completePathFolder = self::GLOBAL_PATH . $pathFolder;
        if (!file_exists($completePathFolder)) {
            mkdir($completePathFolder, 0777, true);
        }
    }

    /**
     * Método responsável por criar uma classe a partir dos parâmetros apresentados
     * 
     * @param $sName => Nome da classe
     * @param $sBody => Conteúdo da classe
     * @param $sPath => Caminho no qual a classe será criada (a partir da pasta inicial do projeto)
     * @param $aComposerUses => Caminho e a classe que são utilizadas dentro da classe que está
     *                          sendo criada. Se tiver extends e implements deve ser específicado 
     *                          também. Exemplo: [helpers\\StringBuilder, helpers\\Helpers];
     * @param $sExtendClass => Classe pai da classe atual
     * @param $sImplementClass => Classe que será implementada pela classe atual
     */
    public static function createClass
    (
        $sName, 
        $sBody, 
        $sPath, 
        $aComposerUses = null,
        $sExtendClass = null, 
        $sImplementClass = null,
        $sType = "class"
    ) {
        $namespace = str_replace("/", "\\", substr($sPath, 0, strlen($sPath) - 1));
        
        $class = new StringBuilder();
        $class->appendNL("<?php")
            ->appendNL("\nnamespace " . $namespace . ";");
        if (!is_null($aComposerUses)) {
            $class->append("\n");
            foreach ($aComposerUses as $use) {
                $class->appendNL("use " . $use . ";");
            }
        }
        $class->append("\n" . $sType ." " . $sName);
        if (!is_null($sExtendClass)) {
            $class->append(" extends " . $sExtendClass);
        }
        if (!is_null($sImplementClass)) {
            $class->append(" implements " . $sImplementClass);
        }
        $class->appendNL("\n{")
            ->appendNL($sBody)
            ->appendNL("}")
            ->generateIdentation();
        
        Helpers::createFolder($sPath);
        Helpers::writeFile($sPath . $sName .'.php', $class);
    }

    /**
     * Método responsável por criar um método
     */
    public static function createMethod($sName, $sAttributes, $sBody, $sVisibility = 'public')
    {
        $method = new StringBuilder();
        $method->appendNL("\n" . $sVisibility . " function " . $sName . "(" . $sAttributes . ")")
            ->appendNL("{")
            ->appendNL($sBody)
            ->appendNL("}");
        return $method;
    }
}