<?php

require_once('SQLExtractor.php');

// Método principal que retornará o nome do banco, as tabelas e os atributos de cada tabela
// Retorna um array, no qual a primeira posição determina se a operação foi realizada 
// com sucesso ou não, a segunda posição é a resposta
$aDatabase = SQLExtractor::getSQLData('sql.sql');

echo '<pre>' , var_dump($aDatabase) , '</pre>';
