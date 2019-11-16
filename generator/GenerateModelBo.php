<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateModelBo
{
    /**
     * Método responsável por gerar a classe bo de uma tabela
     */
    public function create($sTableName)
    {
        $classDAO = ucfirst($sTableName . "DAO");

        $oBody = new StringBuilder();
        $oBody->appendNL("private \$" . $classDAO . ";\n")
            ->appendNL("public function __construct(IGeneric \$" . $classDAO . "){")
            ->appendNL("\$this->" . $classDAO . " = \$" . $classDAO . ";")
            ->appendNL("}")
            ->append(self::generateBOInserir($sTableName))
            ->append(self::generateBOAtualizar($sTableName))
            ->append(self::generateBODeletar($sTableName))
            ->append(self::generateBOBuscarUm($sTableName))
            ->append(self::generateBOBuscarTodos($sTableName));
        
        Helpers::createClass(
            ucfirst($sTableName . "BO"),
            $oBody,
            "app/model/bo/",
            [
                "app\\model\\dto\\".ucfirst($sTableName),
                "app\\model\\interfaces\\IGeneric",
            ],
            null,
            "IGeneric"
        );
    }

    /**
     * Método responsável por gerar o método bo inserir
     */
    private function generateBoInserir($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append("return \$this->" . $sName . "DAO->inserir(\$" . $sName . ");");

        return Helpers::createMethod('inserir', ucfirst($sName)." \$".$sName, $oBody);
    }

    /**
     * Método responsável por gerar o método bo atualizar
     */
    private function generateBoAtualizar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append("return \$this->" . $sName . "DAO->atualizar(\$" . $sName . ");");

        return Helpers::createMethod('atualizar', ucfirst($sName)." \$".$sName, $oBody);
    }

    /**
     * Método responsável por gerar o método bo deletar
     */
    private function generateBoDeletar($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append("return \$this->" . $sName . "DAO->deletar(\$" . $sName . ");");

        return Helpers::createMethod('deletar', ucfirst($sName)." \$".$sName, $oBody);
    }

    /**
     * Método responsável por gerar o método bo buscar um
     */
    private function generateBoBuscarUm($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append("return \$this->" . $sName . "DAO->buscarUm(\$" . $sName . ");");

        return Helpers::createMethod('buscarUm', ucfirst($sName)." \$".$sName, $oBody);
    }

    /**
     * Método responsável por gerar o método bo buscar todos
     */
    private function generateBoBuscarTodos($sName)
    {
        $oBody = new StringBuilder();
        $oBody->append("return \$this->" . $sName . "DAO->buscarTodos();");

        return Helpers::createMethod('buscarTodos', null, $oBody);
    }
}
