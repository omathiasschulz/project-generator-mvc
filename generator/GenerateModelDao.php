<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateModelDao
{
    /**
     * Método responsável por gerar a classe dao de uma tabela
     */
    public function create($sTabelaNome, $aTabelaAtributos, $aTabelaChavesPrimarias)
    {
        $aFields = self::generateDaoFields($sTabelaNome, $aTabelaAtributos);

        $oBody = new StringBuilder();
        $oBody->appendNL("const NOME_TABELA = '" . $sTabelaNome . "';")
            ->append(self::generateDaoInsert($sTabelaNome, $aFields))
            ->append(self::generateDaoUpdate($sTabelaNome, $aFields))
            ->append(self::generateDaoDelete($sTabelaNome, $aTabelaChavesPrimarias));
        
        Helpers::createClass(
            ucfirst($sTabelaNome . "DAO"),
            $oBody,
            "app/model/dao/",
            [
                "app\\model\\dto\\".ucfirst($sTabelaNome),
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

    /**
     * Método responsável por gerar as strins dos respectivos campos
     */
    private function generateDaoFieldsPrimaryKey($sNomeTabela, $aChavesPrimarias)
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

    /**
     * Método responsável por gerar o método dao insert
     */
    private function generateDaoInsert($sNomeTabela, $aFields)
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

    /**
     * Método responsável por gerar o método dao update
     */
    private function generateDaoUpdate($sNomeTabela, $aFields)
    {
        $oBody = new StringBuilder();

        return Helpers::createMethod('update', "\$".$sNomeTabela, $oBody);
    }

    /**
     * Método responsável por gerar o método dao delete
     */
    private function generateDaoDelete($sNomeTabela, $aChavesPrimarias)
    {
        $oBody = new StringBuilder();

        $oBody->appendNL("try {")
            ->appendNL("\$pdo = Conexao::conectar();")
            ->append("\$sql = 'DELETE FROM ' . self::NOME_TABELA . ' WHERE ");

        foreach ($aChavesPrimarias as $chave)
            $oBody->append($chave . " = :" . $chave . " AND ");
        $oBody->subString(0, strlen($oBody)-4)
            ->appendNL("';")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);");
        
        foreach ($aChavesPrimarias as $chave)
            $oBody->appendNL("\$stmt->bindParam(':" . $chave . "', \$" . $chave . ");");
        
        foreach ($aChavesPrimarias as $chave)
            $oBody->appendNL("\$" . $chave . " = \$" . $sNomeTabela . "->get" . ucfirst($chave) . "();");
        
        $oBody->appendNL("\nreturn \$stmt->execute();")
            ->appendNL("} catch (PDOException \$e) {")
            ->appendNL("echo 'Erro ao Excluir -> ' . \$e->getMessage();")
            ->appendNL("} finally {")
            ->appendNL("\$pdo = null;")
            ->append("}");

        return Helpers::createMethod('delete', "\$".$sNomeTabela, $oBody);
    }
}
