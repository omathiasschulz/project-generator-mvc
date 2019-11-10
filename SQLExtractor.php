<?php

class SQLExtractor
{
    const TYPES_NORMAL = ['decimal', 'tinyint', 'int', 'bigint', 'float', 'double', 'date', 'time', 'datetime', 'year'];
    const TYPES_WITH_CONFIG_ONE = ['varchar', 'decimal'];
    const TYPES_WITH_CONFIG_TWO = ['decimal'];

    /**
     * Método principal que retornará o nome do banco, as tabelas e os atributos de cada tabela
     * Retorna um array, no qual a primeira posição determina se a operação foi realizada 
     * com sucesso ou não, a segunda posição é a resposta
     */
    public static function getSQLData($sSqlName)
    {
        $sSql = self::getSQLFromFile($sSqlName);

        $aDatabaseName = self::getDatabaseName($sSql);
        if (!$aDatabaseName[0])
            return $aDatabaseName;
        $aTables = self::getTables($sSql);
        if (!$aTables[0])
            return $aTables;
        
        
        $aFormattedDatabase = [
            'nome'    => $aDatabaseName[1],
            'tabelas' => $aTables[1]
        ];

        return [true, $aFormattedDatabase];
    }
    
    /**
     * Método responsável por buscar a SQL a partir do arquivo especificado
     */
    private function getSQLFromFile($sSqlName)
    {
        $sCompletePath = __DIR__ . DIRECTORY_SEPARATOR . $sSqlName;
        if (file_exists($sCompletePath))
            return file_get_contents($sCompletePath);
        return false;
    }

    /**
     * Método responsável por buscar o nome do database
     */
    private function getDatabaseName($sSql)
    {
        $sPattern = "/(?i)^[[:space:]]*create[[:space:]]+database[[:space:]]+([a-zA-Z0-9]\w+)[[:space:]]*;/";
        preg_match_all($sPattern, $sSql, $aMatches);
        return isset($aMatches[1][0]) ? $aMatches[1][0] : [false, 'Erro ao buscar o nome do Database'];
    }

    /**
     * Método responsável por buscar as tabelas do database
     */
    private function getTables($sSql)
    {
        $sPattern = "/(?i);[[:space:]]*create[[:space:]]+table[[:space:]]+([a-zA-Z0-9]\w+)[[:space:]]*(\([^;]+)/";
        preg_match_all($sPattern, $sSql, $aMatches);
        $aTablesName = $aMatches[1];
        $aTablesAttributes = $aMatches[2];

        $sTypes = self::generateTypeAttributes();
        $sTypes = self::generateTypeNotNull($sTypes);

        foreach ($aTablesAttributes as $key => $sTableAttributes) {
            echo 'TABELA: ' . $aTablesName[$key];
            if (is_null($aTablesName[$key]))
                return [false, 'Erro ao buscar o nome de uma das tabelas'];
            $aFormattedTables[] = [
                'nome' => $aTablesName[$key],
                'atributos' => self::getTableAttributes($sTableAttributes, $sTypes)
            ];
        }
        return [false, $aFormattedTables];
    }

    /**
     * Método responsável por retornar um array com os atributos passados na string
     */
    private function getTableAttributes($sAttributes, $sTypes)
    {
        // Remove os parênteses iniciais e finais
        // $sAttributes = trim($sAttributes);
        $sAttributes = substr(trim($sAttributes), 1, strlen($sAttributes) - 2);

        $aPrimaryKeys = self::getTablePrimaryKey($sAttributes);
        if ($aPrimaryKeys) {
            $aFormattedAttributes[] = ['chaves_primarias' => $aPrimaryKeys];
            $sPattern = "/(?i)\,[[:space:]]*primary[[:space:]]+key\(([a-zA-Z0-9\_\-\,[:space:]]+)\)$/";
            $sAttributes = preg_split($sPattern, $sAttributes)[0];
        }

        echo $sAttributes;
        // Realiza um split na vírgula 
        // Entretanto não pode dar split na virgula de atributos como decimal(3,3)
        $aAttributes = preg_split("/(?<=[^0-9])\,/", $sAttributes);
        // var_dump($aAttributes); echo '<br>';

        // echo '<pre>' , var_dump($variable) , '</pre>';

        // $aFormattedAttributes = [];
        foreach ($aAttributes as $key => $sAttribute) {
            if ($key === count($aAttributes) - 1) {
                
            }
            $aFormattedAttributes[] = self::getTableOneAttribute($sAttribute, $sTypes);
        }
        return $aFormattedAttributes;
    }
    
    /**
     * Método responsável por verificar se possui chave primária
     * Caso possuir, pega as chaves primárias, se não pega o atributo
     */
    private function getTablePrimaryKey($sAttribute)
    {
        // echo '<br>AAAAAAAAAAAAAAAAAAAAa<br>';
        $sAttribute = trim($sAttribute);
        // if (strpos($a, 'are') !== false) {
        //     echo 'true';
        // }
        // primary key(chave_primaria, chave_primaria2)

        // $sAttribute = trim($sAttribute);
        $sPattern = "/(?i)\,[[:space:]]*primary[[:space:]]+key\(([a-zA-Z0-9\_\-\,[:space:]]+)\)$/";
        preg_match_all($sPattern, $sAttribute, $aMatches);

        return !is_null($aMatches[1][0]) ? explode(',', $aMatches[1][0]) : false;
    }

    private function getTableOneAttribute($sAttribute, $sTypes)
    {
        $sAttribute = trim($sAttribute);
        $sPattern = "/(?i)^([a-zA-Z0-9_-]+)[[:space:]]+" . $sTypes . "$/";
        preg_match_all($sPattern, $sAttribute, $aMatches);

        // echo '<br><br>';
        // var_dump($sPattern);
        // echo '<br><br>';

        // echo '<br><br>';
        // var_dump($aMatches);
        // echo '<br>';

        // $sNomeVariavel = $aMatches[1][0];
        // echo '<br>$sNomeVariavel: ' . $sNomeVariavel;
        // $sTipoVariavel = $aMatches[2][0];
        // echo '<br>$sTipoVariavel: ' . $sTipoVariavel;
        // $sNotNull = $aMatches[9][0];
        // echo '<br>$sNotNull: ' . $sNotNull;
        return [
            'nome'  => $aMatches[1][0],
            'tipo'  => $aMatches[2][0],
            'not null' => !is_null($aMatches[9][0]) ? true : false
        ];
    }

    /**
     * Método responsável por montar a string em regex com os tipos de variáveis
     * permitidos especificadas nas constantes:
     * TYPES_NORMAL          => Tipo de variável normal, ex: int
     * TYPES_WITH_CONFIG_ONE => Tipo de variável que aceita um parâmetro de configuração, ex: varchar(50)
     * TYPES_WITH_CONFIG_TWO => Tipo de variável que aceita dois parâmetros de configuração, ex: decimal(2,3)
     */
    private function generateTypeAttributes()
    {
        $sTypesNormal = implode('|', self::TYPES_NORMAL);
        $sTypesConfigOne = implode('|', self::TYPES_WITH_CONFIG_ONE);
        $sTypesConfigTwo = implode('|', self::TYPES_WITH_CONFIG_TWO);

        return '('
            // variavel
            . '((' . $sTypesNormal . '))'
            // variavel (parametro01)
            . '|((' . $sTypesConfigOne . ')[[:space:]]*\([[:space:]]*[0-9]+[[:space:]]*\))'
            // variavel (parametro01, parametro02)
            . '|((' . $sTypesConfigOne . ')[[:space:]]*\([[:space:]]*[0-9]+[[:space:]]*\,[[:space:]]*[0-9]+[[:space:]]*\))'
            . ')';
    }

    /**
     * Método responsável por gerar a configuração not null para os tipos
     * passados como parâmetro
     * Retorna um regex com a nova configuração
     */
    private function generateTypeNotNull($sTypes)
    {
        return $sTypes . '([[:space:]]*|[[:space:]]+not[[:space:]]+null[[:space:]]*)';
    }
}