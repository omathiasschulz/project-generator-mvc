<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateModelDao
{
    /**
     * Método responsável por gerar a classe dao de uma tabela
     */
    public function create
    (
        $sTableName, 
        $aTableAttributes, 
        $aTablePrimaryKeys, 
        $aTypesData
    ) {
        $aFieldsNotPK = self::generateDaoFieldsNotPrimaryKey($sTableName, $aTableAttributes, $aTablePrimaryKeys, $aTypesData);
        $aFieldsPK = self::generateDaoFieldsPrimaryKey($sTableName, $aTablePrimaryKeys);

        $oBody = new StringBuilder();
        $oBody->appendNL("const NOME_TABELA = '" . $sTableName . "';")
            ->append(self::generateDaoInserir($sTableName, $aFieldsNotPK))
            ->append(self::generateDaoAtualizar($sTableName, $aFieldsNotPK, $aFieldsPK))
            ->append(self::generateDaoDeletar($sTableName, $aFieldsPK))
            ->append(self::generateDaoBuscarUm($sTableName, $aFieldsNotPK['oFieldsSet'], $aFieldsPK))
            ->append(self::generateDaoBuscarTodos($sTableName, $aFieldsNotPK['oFieldsSet'], $aFieldsPK['oFieldsSet']));
        
        Helpers::createClass(
            ucfirst($sTableName . "DAO"),
            $oBody,
            "app/model/dao/",
            [
                "app\\model\\dto\\".ucfirst($sTableName),
                "app\conexao\\Conexao",
                "app\\model\\interfaces\\IGeneric",
                "PDO",
                "Datetime"
            ],
            null,
            "IGeneric"
        );
    }

    /**
     * Método responsável por gerar as strins dos campos que não são chaves primárias
     */
    private function generateDaoFieldsNotPrimaryKey
    (
        $sName, 
        $aAttributes, 
        $aPrimaryKeys, 
        $aTypesData
    ) {
        $oFieldsInsert = new StringBuilder(); // Campos do sql insert
        $oFieldsInsertValues = new StringBuilder(); // Campos do sql insert values
        $oFieldsBind = new StringBuilder(); // Campos do bind pdo
        $oFieldsGet = new StringBuilder(); // Campos do get pdo
        $oFieldsUpdate = new StringBuilder(); // Campos do sql update set
        $oFieldsSet = new StringBuilder(); // Campos do set pdo

        foreach ($aAttributes as $oAttribute) {
            if (!in_array($oAttribute->nome, $aPrimaryKeys)) {
                $oFieldsInsert->append($oAttribute->nome . ", ");
                $oFieldsInsertValues->append(":" . $oAttribute->nome . ", ");
                $oFieldsBind->appendNL("\$stmt->bindParam(':" . $oAttribute->nome . "', \$" . $oAttribute->nome . ", PDO::PARAM_STR);");
                $oFieldsUpdate->append($oAttribute->nome . " = :" . $oAttribute->nome . ", ");
                
                // Validação especial para o tipo data
                if (in_array($oAttribute->tipo, $aTypesData)) {
                    $oFieldsGet->appendNL("\$" . $oAttribute->nome . " = (\$" . $sName . "->get" . ucfirst($oAttribute->nome) . "() != \"\") ? \$" . $sName . "->get" . ucfirst($oAttribute->nome) . "()->format('Y-m-d H:i:s') : '';");
                    $oFieldsSet->appendNL("\t->set" . ucfirst($oAttribute->nome) . "(new Datetime(\$linha['" . $oAttribute->nome . "']))");
                } else {
                    $oFieldsGet->appendNL("\$" . $oAttribute->nome . " = \$" . $sName . "->get" . ucfirst($oAttribute->nome) . "();");
                    $oFieldsSet->appendNL("\t->set" . ucfirst($oAttribute->nome) . "(\$linha['" . $oAttribute->nome . "'])");
                }
            }
        }
        $oFieldsInsert->subString(0, strlen($oFieldsInsert)-2);
        $oFieldsInsertValues->subString(0, strlen($oFieldsInsertValues)-2);
        $oFieldsUpdate->subString(0, strlen($oFieldsUpdate)-2);

        return [
            'oFieldsInsert'       => $oFieldsInsert,
            'oFieldsInsertValues' => $oFieldsInsertValues,
            'oFieldsBind'         => $oFieldsBind,
            'oFieldsGet'          => $oFieldsGet,
            'oFieldsUpdate'       => $oFieldsUpdate,
            'oFieldsSet'          => $oFieldsSet
        ];
    }

    /**
     * Método responsável por gerar as strins dos campos que são chaves primárias
     */
    private function generateDaoFieldsPrimaryKey($sName, $aPrimaryKeys)
    {
        $oFieldsWhere = new StringBuilder(); // Campos do sql update where
        $oFieldsBind = new StringBuilder(); // Campos do bind pdo
        $oFieldsGet = new StringBuilder(); // Campos do get pdo
        $oFieldsSet = new StringBuilder(); // Campos do set pdo

        foreach ($aPrimaryKeys as $sPrimaryKey) {
            $oFieldsWhere->append($sPrimaryKey . " = :" . $sPrimaryKey . " AND ");
            $oFieldsBind->appendNL("\$stmt->bindParam(':" . $sPrimaryKey . "', \$" . $sPrimaryKey . ");");
            $oFieldsGet->appendNL("\$" . $sPrimaryKey . " = \$" . $sName . "->get" . ucfirst($sPrimaryKey) . "();");
            $oFieldsSet->appendNL("\t->set" . ucfirst($sPrimaryKey) . "(\$linha['" . $sPrimaryKey . "'])");
        }
        $oFieldsWhere->subString(0, strlen($oFieldsWhere)-4);

        return [
            'oFieldsWhere' => $oFieldsWhere,
            'oFieldsBind'  => $oFieldsBind,
            'oFieldsGet'   => $oFieldsGet,
            'oFieldsSet'   => $oFieldsSet
        ];
    }

    /**
     * Método responsável por gerar o método dao inserir
     */
    private function generateDaoInserir($sName, $aFieldsNotPK)
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("try {")
            ->append("\$sql = 'INSERT INTO ' . self::NOME_TABELA")
            ->appendNL(" . ' (" . $aFieldsNotPK['oFieldsInsert'] . ")'")
            ->append(" . ' VALUES")
            ->appendNL(" (" . $aFieldsNotPK['oFieldsInsertValues'] . ")';")
            ->appendNL("\$pdo = Conexao::startConnection();")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);")
            ->appendNL($aFieldsNotPK['oFieldsBind'])
            ->appendNL($aFieldsNotPK['oFieldsGet'])
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
    private function generateDaoAtualizar($sName, $aFieldsNotPK, $aFieldsPK)
    {
        $oBody = new StringBuilder();
        $oBody->appendNL("try {")
            ->appendNL("\$sql = 'UPDATE ' . self::NOME_TABELA")
            ->appendNL(" . ' SET " . $aFieldsNotPK['oFieldsUpdate'] . "'")
            ->appendNL(" . ' WHERE " . $aFieldsPK['oFieldsWhere'] . "';")
            ->appendNL("\$pdo = Conexao::startConnection();")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);")
            ->append($aFieldsPK['oFieldsBind'])
            ->appendNL($aFieldsNotPK['oFieldsBind'])
            ->append($aFieldsPK['oFieldsGet'])
            ->appendNL($aFieldsNotPK['oFieldsGet'])
            ->appendNL("return \$stmt->execute();")
            ->appendNL("} catch (PDOException \$e) {")
            ->appendNL("echo 'Erro ao Atualizar -> ' . \$e->getMessage();")
            ->appendNL("} finally {")
            ->appendNL("\$pdo = null;")
            ->append("}");

        return Helpers::createMethod('atualizar', "\$".$sName, $oBody);
    }

    /**
     * Método responsável por gerar o método dao deletar
     */
    private function generateDaoDeletar($sName, $aFieldsPK)
    {
        $oBody = new StringBuilder();

        $oBody->appendNL("try {")
            ->appendNL("\$pdo = Conexao::startConnection();")
            ->appendNL("\$sql = 'DELETE FROM ' . self::NOME_TABELA")
            ->appendNL(" . ' WHERE " . $aFieldsPK['oFieldsWhere'] . "';")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);")
            ->appendNL($aFieldsPK['oFieldsBind'])
            ->appendNL($aFieldsPK['oFieldsGet'])
            ->appendNL("return \$stmt->execute();")
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
    private function generateDaoBuscarUm($sName, $sFieldsSet, $aFieldsPK)
    {
        $oBody = new StringBuilder();

        $oBody->appendNL("try {")
            ->appendNL("\$pdo = Conexao::startConnection();")
            ->appendNL("\$sql = 'SELECT * FROM ' . self::NOME_TABELA")
            ->appendNL(" . ' WHERE " . $aFieldsPK['oFieldsWhere'] . "';")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);")
            ->appendNL($aFieldsPK['oFieldsBind'])
            ->appendNL($aFieldsPK['oFieldsGet'])
            ->appendNL("\$stmt->execute();")
            ->appendNL("\$result = '';")
            ->appendNL("while (\$linha = \$stmt->fetch(PDO::FETCH_ASSOC)) {")
            ->appendNL("\$result = (new " . ucfirst($sName) . "())")
            ->appendNL($aFieldsPK['oFieldsSet'] . $sFieldsSet . ";\n}")
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
    private function generateDaoBuscarTodos($sName, $sFieldsSetNotPK, $sFieldsSetPK)
    {
        $oBody = new StringBuilder();

        $oBody->appendNL("try {")
            ->appendNL("\$pdo = Conexao::startConnection();")
            ->appendNL("\$sql = 'SELECT * FROM ' . self::NOME_TABELA;")
            ->appendNL("\$stmt = \$pdo->prepare(\$sql);\n")
            ->appendNL("\$stmt->execute();")
            ->appendNL("\$result = [];")
            ->appendNL("while (\$linha = \$stmt->fetch(PDO::FETCH_ASSOC)) {")
            ->appendNL("\$result[] = (new " . ucfirst($sName) . "())")
            ->append($sFieldsSetPK . $sFieldsSetNotPK)
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
