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
        foreach ($aTabelas as $oTabela) {
            // Remove a primeira posição do array, que são as chaves primárias
            array_shift($oTabela->atributos);
            self::generateDto($oTabela->nome, $oTabela->atributos);
            self::generateDao($oTabela->nome, $oTabela->atributos);
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

    /**
     * Método responsável por gerar as classes dao de cada tabela
     */
    private function generateDao($sNomeTabela, $aAtributos)
    {
        $aFields = self::generateDaoFields($sNomeTabela, $aAtributos);

        $sBody = new StringBuilder();
        $sBody->appendNL("const NOME_TABELA = '" . $sNomeTabela . "';")
            ->append(self::generateDaoInsert($sNomeTabela, $aAtributos, $aFields));
        
        Helpers::createClass(
            ucfirst($sNomeTabela . "DAO"),
            $sBody,
            "app/model/dao/",
            [
                "app\\model\\dto\\".ucfirst($sNomeTabela),
                "app\conexao\\Conexao",
                "app\\model\\interfaces\\IGenericDB",
                "PDO"
            ],
            null,
            "IGenericDB"
        );
    }

    /**
     * Método responsável por gerar as strins dos respectivos campos
     */
    private function generateDaoFields($sNomeTabela, $aAtributos)
    {
        $sFieldsInsert = new StringBuilder(" . '(");
        $sFieldsValues = new StringBuilder(" . 'VALUES (");
        $sFieldsBind = new StringBuilder();
        $sFieldsGet = new StringBuilder();
        foreach ($aAtributos as $oAtributo) {
            $sFieldsInsert->append($oAtributo->nome . ", ");
            $sFieldsValues->append(":" . $oAtributo->nome . ", ");
            $sFieldsBind->appendNL("\$stmt->bindParam(':" . $oAtributo->nome . "', \$" . $oAtributo->nome . ", PDO::PARAM_STR);");
            $sFieldsGet->appendNL("\$" . $oAtributo->nome . " = \$" . $sNomeTabela . "->get" . ucfirst($oAtributo->nome) . "();");
        }
        $sFieldsInsert->subString(0, strlen($sFieldsInsert)-2)
                    ->append(")' ");
        $sFieldsValues->subString(0, strlen($sFieldsValues)-2)
                    ->append(")'; ");

        return [
            'sFieldsInsert' => $sFieldsInsert,
            'sFieldsValues' => $sFieldsValues,
            'sFieldsBind'   => $sFieldsBind,
            'sFieldsGet'    => $sFieldsGet
        ];
    }

    private function generateDaoInsert($sNomeTabela, $aAtributos, $aFields)
    {
        $sBody = new StringBuilder();
        $sBody->appendNL("try {")
            ->appendNL("\$sql = 'INSERT INTO' . self::NOME_TABELA")
            ->appendNL($aFields['sFieldsInsert'])
            ->appendNL($aFields['sFieldsValues'])
            ->appendNL("\$pdo = Conexao::conectar();")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);")
            ->appendNL($aFields['sFieldsBind'])
            ->appendNL($aFields['sFieldsGet'])
            ->appendNL("return \$stmt->execute();")
            ->appendNL("} catch (PDOException \$e) {")
            ->appendNL("echo 'Erro ao Inserir -> ' . \$e->getMessage();")
            ->appendNL("} finally {")
            ->appendNL("\$pdo = null;")
            ->append("}");

        return Helpers::createMethod('insert', "\$".$sNomeTabela, $sBody);
    }
}
