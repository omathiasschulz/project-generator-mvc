<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateModelDto
{
    /**
     * Método responsável por gerar a classe dto de uma tabela
     */
    public function create($sTableName, $aTableAttributes, $aTypesData)
    {
        $oBody = new StringBuilder();
        $oBody->append(self::generateDtoAttributes($aTableAttributes));
        foreach ($aTableAttributes as $oAttribute) {
            $oBody->append(self::generateDtoGet($oAttribute->nome))
                ->append(self::generateDtoSet($oAttribute->nome));
        }
        $oBody->append(self::generateDtoToString($sTableName, $aTableAttributes, $aTypesData));
        
        Helpers::createClass(
            ucfirst($sTableName),
            $oBody,
            'app/model/dto/'
        );
    }

    /**
     * Método responsável por gerar os atributos da classe
     */
    private function generateDtoAttributes($aAttributes)
    {
        $oBody = new StringBuilder();
        foreach ($aAttributes as $oAttribute) {
            $oBody->appendNL("private \$" . $oAttribute->nome . ";");
        }
        return $oBody;
    }

    /**
     * Método responsável por gerar o método get de um atributo
     */
    private function generateDtoGet($sAttribute)
    {
        $oBody = new StringBuilder();
        $oBody->append("return \$this->" . $sAttribute . ";");
        return Helpers::createMethod("get" . ucfirst($sAttribute), null, $oBody);
    }

    /**
     * Método responsável por gerar o método set de um atributo
     */
    private function generateDtoSet($sAttribute)
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("\$this->" . $sAttribute . " = \$" . $sAttribute . ";")
            ->append("return \$this;");
        return Helpers::createMethod("set" . ucfirst($sAttribute), "\$" . $sAttribute, $oBody);
    }

    /**
     * Método responsável por gerar o método __toString
     */
    private function generateDtoToString($sName, $aAttributes, $aTypesData)
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("return '### " . ucfirst($sName) . " <'");
        foreach ($aAttributes as $oAttribute) {
            if (in_array($oAttribute->tipo, $aTypesData)) {
                $oBody->appendNL(
                    "\t. ' | " . $oAttribute->nome . " = ' . " . "\$this->get" . ucfirst($oAttribute->nome) . "()->format('Y-m-d H:i:s')"
                );
            } else {
                $oBody->appendNL(
                    "\t. ' | " . $oAttribute->nome . " = ' . " . "\$this->get" . ucfirst($oAttribute->nome) . "()"
                );
            }
        }
        $oBody->append("\t. ' | >';");
        return Helpers::createMethod('__toString', null, $oBody);
    }
}
