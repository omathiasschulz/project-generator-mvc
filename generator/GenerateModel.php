<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateModel
{
    /**
     * Método responsável por gerar os models
     */
    public function create($aTabelas)
    {
        foreach ($aTabelas as $key => $oTabela) {
            // Remove a primeira posição do array, que são as chaves primárias
            array_shift($oTabela->atributos);
            self::generateDto($oTabela->nome, $oTabela->atributos);
        }
    }

    /**
     * Método responsável por gerar as classes dto de cada tabela
     */
    private function generateDto($sNomeTabela, $aAtributos)
    {
        $sBody = self::generateDtoAttributes($aAtributos);
        foreach ($aAtributos as $oAtributo) {
            $sBody .= ""
                . self::generateDtoGet($oAtributo->nome)
                . self::generateDtoSet($oAtributo->nome);
        }
        $sBody .= self::generateDtoToString($sNomeTabela, $aAtributos);
        
        Helpers::createClass(
            ucfirst($sNomeTabela),
            $sBody,
            'app/model/dto/'
        );
    }

    /**
     * Método responsável por gerar os atributos da classe
     */
    private function generateDtoAttributes($aAtributos)
    {
        $sBody = new StringBuilder();
        foreach ($aAtributos as $oAtributo) {
            $sBody->append("\tprivate \$" . $oAtributo->nome . ";\n");
        }
        return $sBody;
    }

    /**
     * Método responsável por gerar o método get de um atributo
     */
    private function generateDtoGet($sAtributo)
    {
        $sBody = new StringBuilder();
        $sBody->append("\t\treturn \$this->" . $sAtributo . ";");
        return Helpers::createMethod("get" . ucfirst($sAtributo), null, $sBody);
    }

    /**
     * Método responsável por gerar o método set de um atributo
     */
    private function generateDtoSet($sAtributo)
    {
        $sBody = new StringBuilder();
        $sBody->append("\t\t\$this->" . $sAtributo . " = \$" . $sAtributo . ";\n");
        $sBody->append("\t\treturn \$this;");
        return Helpers::createMethod("set" . ucfirst($sAtributo), "\$" . $sAtributo, $sBody);
    }

    /**
     * Método responsável por gerar o método __toString
     */
    private function generateDtoToString($sNomeTabela, $aAtributos)
    {
        $sBody = new StringBuilder();
        $sBody->append("\t\treturn '### " . ucfirst($sNomeTabela) . " <'\n");
        foreach ($aAtributos as $oAtributo) {
            $sBody->append(
                "\t\t\t. ' | " . $oAtributo->nome . " = ' . " . "\$this->get" . ucfirst($oAtributo->nome) . "()\n"
            );
        }
        $sBody->append("\t\t\t. ' | >';");
        return Helpers::createMethod('__toString', null, $sBody);
    }
}
