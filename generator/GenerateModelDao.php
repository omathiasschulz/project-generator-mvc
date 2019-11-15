<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateModelDao
{
    /**
     * Método responsável por gerar a classe dao de uma tabela
     */
    public function create($sTableName, $aTableAttributes, $aTablePrimaryKeys)
    {
        $aFields = self::generateDaoFields($sTableName, $aTableAttributes);
        $sFieldsPrimaryKey = self::generateDaoFieldsPrimaryKey($sTableName, $aTablePrimaryKeys);

        $oBody = new StringBuilder();
        $oBody->appendNL("const NOME_TABELA = '" . $sTableName . "';")
            ->append(self::generateDaoInserir($sTableName, $aFields))
            ->append(self::generateDaoAtualizar($sTableName, $aFields))
            ->append(self::generateDaoDeletar($sTableName, $sFieldsPrimaryKey))
            ->append(self::generateDaoBuscarUm($sTableName, $aFields['oFieldsSet'], $sFieldsPrimaryKey))
            ->append(self::generateDaoBuscarTodos($sTableName, $aFields['oFieldsSet']));
        
        Helpers::createClass(
            ucfirst($sTableName . "DAO"),
            $oBody,
            "app/model/dao/",
            [
                "app\\model\\dto\\".ucfirst($sTableName),
                "app\conexao\\Conexao",
                "app\\model\\interfaces\\IGeneric",
                "PDO"
            ],
            null,
            "IGeneric"
        );
    }

    /**
     * Método responsável por gerar as strins dos respectivos campos
     */
    private function generateDaoFields($sName, $aAttributes)
    {
        $oFieldsInsert = new StringBuilder(" . '(");
        $oFieldsValues = new StringBuilder(" . 'VALUES (");
        $oFieldsBind = new StringBuilder();
        $oFieldsGet = new StringBuilder();
        $oFieldsSet = new StringBuilder();
        foreach ($aAttributes as $oAttribute) {
            $oFieldsInsert->append($oAttribute->nome . ", ");
            $oFieldsValues->append(":" . $oAttribute->nome . ", ");
            $oFieldsBind->appendNL("\$stmt->bindParam(':" . $oAttribute->nome . "', \$" . $oAttribute->nome . ", PDO::PARAM_STR);");
            $oFieldsGet->appendNL("\$" . $oAttribute->nome . " = \$" . $sName . "->get" . ucfirst($oAttribute->nome) . "();");
            $oFieldsSet->appendNL("\t->set" . ucfirst($oAttribute->nome) . "(\$linha[" . $oAttribute->nome . "])");
        }
        $oFieldsInsert->subString(0, strlen($oFieldsInsert)-2)
                    ->append(")' ");
        $oFieldsValues->subString(0, strlen($oFieldsValues)-2)
                    ->append(")'; ");

        return [
            'oFieldsInsert' => $oFieldsInsert,
            'oFieldsValues' => $oFieldsValues,
            'oFieldsBind'   => $oFieldsBind,
            'oFieldsGet'    => $oFieldsGet,
            'oFieldsSet'    => $oFieldsSet
        ];
    }

    /**
     * Método responsável por gerar as strins das chaves primárias
     */
    private function generateDaoFieldsPrimaryKey($sName, $aPrimaryKeys)
    {
        $oFieldsWhere = new StringBuilder();
        $oFieldsBind = new StringBuilder();
        $oFieldsGet = new StringBuilder();
        foreach ($aPrimaryKeys as $sPrimaryKey) {
            $oFieldsWhere->append($sPrimaryKey . " = :" . $sPrimaryKey . " AND ");
            $oFieldsBind->appendNL("\$stmt->bindParam(':" . $sPrimaryKey . "', \$" . $sPrimaryKey . ");");
            $oFieldsGet->appendNL("\$" . $sPrimaryKey . " = \$" . $sName . "->get" . ucfirst($sPrimaryKey) . "();");
        }
        $oFieldsWhere->subString(0, strlen($oFieldsWhere)-4);

        return [
            'oFieldsWhere' => $oFieldsWhere,
            'oFieldsBind'  => $oFieldsBind,
            'oFieldsGet'   => $oFieldsGet
        ];
    }

    /**
     * Método responsável por gerar o método dao inserir
     */
    private function generateDaoInserir($sName, $aFields)
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("try {")
            ->appendNL("\$sql = 'INSERT INTO' . self::NOME_TABELA")
            ->appendNL($aFields['oFieldsInsert'])
            ->appendNL($aFields['oFieldsValues'])
            ->appendNL("\$pdo = Conexao::conectar();")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);")
            ->appendNL($aFields['oFieldsBind'])
            ->appendNL($aFields['oFieldsGet'])
            ->appendNL("return \$stmt->execute();")
            ->appendNL("} catch (PDOException \$e) {")
            ->appendNL("echo 'Erro ao Inserir -> ' . \$e->getMessage();")
            ->appendNL("} finally {")
            ->appendNL("\$pdo = null;")
            ->append("}");

        return Helpers::createMethod('inserir', "\$".$sName, $oBody);
    }

    /**
     * Método responsável por gerar o método dao atualizar
     */
    private function generateDaoAtualizar($sName, $aFields)
    {
        $oBody = new StringBuilder();

        return Helpers::createMethod('atualizar', "\$".$sName, $oBody);
    }

    /**
     * Método responsável por gerar o método dao deletar
     */
    private function generateDaoDeletar($sName, $sFieldsPrimaryKey)
    {
        $oBody = new StringBuilder();

        $oBody->appendNL("try {")
            ->appendNL("\$pdo = Conexao::conectar();")
            ->append("\$sql = 'DELETE FROM ' . self::NOME_TABELA . ' WHERE ")
            ->append($sFieldsPrimaryKey['oFieldsWhere'])
            ->appendNL("';")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);")
            ->append($sFieldsPrimaryKey['oFieldsBind'])
            ->append($sFieldsPrimaryKey['oFieldsGet'])
            ->appendNL("\nreturn \$stmt->execute();")
            ->appendNL("} catch (PDOException \$e) {")
            ->appendNL("echo 'Erro ao Excluir -> ' . \$e->getMessage();")
            ->appendNL("} finally {")
            ->appendNL("\$pdo = null;")
            ->append("}");

        return Helpers::createMethod('deletar', "\$".$sName, $oBody);
    }

    /**
     * Método responsável por gerar o método dao buscar um
     */
    private function generateDaoBuscarUm($sName, $sFieldsSet, $sFieldsPrimaryKey)
    {
        $oBody = new StringBuilder();

        $oBody->appendNL("try {")
            ->appendNL("\$pdo = Conexao::conectar();")
            ->append("\$sql = 'SELECT * FROM ' . self::NOME_TABELA . ' WHERE ")
            ->append($sFieldsPrimaryKey['oFieldsWhere'])
            ->appendNL("';")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);")
            ->append($sFieldsPrimaryKey['oFieldsBind'])
            ->appendNL($sFieldsPrimaryKey['oFieldsGet'])
            ->appendNL("\$stmt->execute();")
            ->appendNL("\$result = [];")
            ->appendNL("while (\$linha = \$stmt->fetch(PDO::FETCH_ASSOC)) {")
            ->appendNL("\$result[] = (new " . ucfirst($sName) . "())")
            ->append($sFieldsSet)
            ->appendNL(";\n}")
            ->appendNL("return \$result;")
            ->appendNL("} catch (PDOException \$e) {")
            ->appendNL("echo 'Erro ao Buscar um -> ' . \$e->getMessage();")
            ->appendNL("} finally {")
            ->appendNL("\$pdo = null;")
            ->append("}");

        return Helpers::createMethod('buscarUm', "\$".$sName, $oBody);
    }

    /**
     * Método responsável por gerar o método dao buscar todos
     */
    private function generateDaoBuscarTodos($sName, $sFieldsSet)
    {
        $oBody = new StringBuilder();

        $oBody->appendNL("try {")
            ->appendNL("\$pdo = Conexao::conectar();")
            ->appendNL("\$sql = 'SELECT * FROM ' . self::NOME_TABELA;")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);\n")
            ->appendNL("\$stmt->execute();")
            ->appendNL("\$result = [];")
            ->appendNL("while (\$linha = \$stmt->fetch(PDO::FETCH_ASSOC)) {")
            ->appendNL("\$result[] = (new " . ucfirst($sName) . "())")
            ->append($sFieldsSet)
            ->appendNL(";\n}")
            ->appendNL("return \$result;")
            ->appendNL("} catch (PDOException \$e) {")
            ->appendNL("echo 'Erro ao Buscar todos -> ' . \$e->getMessage();")
            ->appendNL("} finally {")
            ->appendNL("\$pdo = null;")
            ->append("}");

        return Helpers::createMethod('buscarTodos', null, $oBody);
    }
}
