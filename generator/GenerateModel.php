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
        $oBody = new StringBuilder();
        $oBody->append(self::generateDtoAttributes($aAtributos));
        foreach ($aAtributos as $oAtributo) {
            $oBody->append(self::generateDtoGet($oAtributo->nome))
                ->append(self::generateDtoSet($oAtributo->nome));
        }
        $oBody->append(self::generateDtoToString($sNomeTabela, $aAtributos));
        
        Helpers::createClass(
            ucfirst($sNomeTabela),
            $oBody,
            'app/model/dto/'
        );
    }

    /**
     * Método responsável por gerar os atributos da classe
     */
    private function generateDtoAttributes($aAtributos)
    {
        $oBody = new StringBuilder();
        foreach ($aAtributos as $oAtributo) {
            $oBody->appendNL("private \$" . $oAtributo->nome . ";");
        }
        return $oBody;
    }

    /**
     * Método responsável por gerar o método get de um atributo
     */
    private function generateDtoGet($sAtributo)
    {
        $oBody = new StringBuilder();
        $oBody->append("return \$this->" . $sAtributo . ";");
        return Helpers::createMethod("get" . ucfirst($sAtributo), null, $oBody);
    }

    /**
     * Método responsável por gerar o método set de um atributo
     */
    private function generateDtoSet($sAtributo)
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("\$this->" . $sAtributo . " = \$" . $sAtributo . ";")
            ->append("return \$this;");
        return Helpers::createMethod("set" . ucfirst($sAtributo), "\$" . $sAtributo, $oBody);
    }

    /**
     * Método responsável por gerar o método __toString
     */
    private function generateDtoToString($sNomeTabela, $aAtributos)
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("return '### " . ucfirst($sNomeTabela) . " <'");
        foreach ($aAtributos as $oAtributo) {
            $oBody->appendNL(
                ". ' | " . $oAtributo->nome . " = ' . " . "\$this->get" . ucfirst($oAtributo->nome) . "()"
            );
        }
        $oBody->append(". ' | >';");
        return Helpers::createMethod('__toString', null, $oBody);
    }

    /**
     * Método responsável por gerar as classes dao de cada tabela
     */
    private function generateDao($sNomeTabela, $aAtributos)
    {
        $aFields = self::generateDaoFields($sNomeTabela, $aAtributos);

        $oBody = new StringBuilder();
        $oBody->appendNL("const NOME_TABELA = '" . $sNomeTabela . "';")
            ->append(self::generateDaoInsert($sNomeTabela, $aAtributos, $aFields));
        
        Helpers::createClass(
            ucfirst($sNomeTabela . "DAO"),
            $oBody,
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
        $oBody = new StringBuilder();
        $oBody->appendNL("try {")
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

        return Helpers::createMethod('insert', "\$".$sNomeTabela, $oBody);
    }
}
