<?php

require_once "vendor/autoload.php";
require_once "core/bootstrap.php";

use generator\Generate;

$result = Generate::start();

echo $result[1];


// // TESTE SQL EXTRACTOR

// use helpers\SQLExtractor;

// $aDatabase = SQLExtractor::getSQLData('../sql.sql');

// echo $aDatabase;


// // TESTE STRING BUILDER
// use helpers\StringBuilder;

// $string = new StringBuilder();
// $string->append("\n<?php");
// $string->append("\n    Mathias");
// $string->append("\n    Artur");
// $string->append("\n    Schulz");

// echo $string;