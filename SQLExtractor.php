<?php

class SQLExtractor
{
    const TYPES_NORMAL = ['tinyint', 'int', 'bigint', 'float', 'double', 'date', 'time', 'datetime', 'year'];
    const TYPES_WITH_CONFIG_ONE = ['varchar', 'decimal'];
    const TYPES_WITH_CONFIG_TWO = ['decimal'];

    /**
     * Método principal que retornará o nome do banco, as tabelas e os atributos de cada tabela
     */
    public static function getSQLData($sSqlName)
    {
        $sSql = self::getSQLFromFile($sSqlName);
        $sDatabaseName = self::getDatabaseName($sSql);

        self::getTables($sSql);


        // echo ('sDatabaseName: ' . $sDatabaseName);
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
        return $aMatches[1][0];
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
            echo '<br><br>=> Tabela ' . $aTablesName[$key] . '<br>';
            self::getTableAttributes($sTableAttributes, $sTypes);
        }
    }

    /**
     * Método responsável por retornar um array com os atributos passados na string
     */
    private function getTableAttributes($sAttributes, $sTypes)
    {
        // Remove os parênteses iniciais e finais
        $sAttributes = trim($sAttributes);
        $sAttributes = substr($sAttributes, 1, strlen($sAttributes) - 2);
        echo $sAttributes . '<br>';
        
        $aAttributes = explode(',', $sAttributes);
        var_dump($aAttributes); echo '<br>';
        foreach ($aAttributes as $key => $sAttribute) {
            if ($key === count($aAttributes) - 1) {
                
            } else {
                self::getTableOneAttribute($sAttribute, $sTypes);
            }
        }

        
    }
    
    private function getTablePrimaryKey($sAttribute)
    {
        // $sAttribute = trim($sAttribute);
        // $sPattern = "/(?i)^([a-zA-Z0-9_-]+)[[:space:]]+" . $sTypes . "$/";
        // preg_match_all($sPattern, $sAttribute, $aMatches);

        // // var_dump($aMatches);
        // $sNomeVariavel = $aMatches[1][0];
        // $sTipoVariavel = $aMatches[2][0];

    }

    private function getTableOneAttribute($sAttribute, $sTypes)
    {
        $sAttribute = trim($sAttribute);
        $sPattern = "/(?i)^([a-zA-Z0-9_-]+)[[:space:]]+" . $sTypes . "$/";
        preg_match_all($sPattern, $sAttribute, $aMatches);

        // var_dump($aMatches);
        $sNomeVariavel = $aMatches[1][0];
        $sTipoVariavel = $aMatches[2][0];

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
        return $sTypes . '([[:space:]]*|[[:space:]]+not null[[:space:]]*)';
    }
}