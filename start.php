<?php

// Geração do composer
require_once "generator/GenerateComposer.php";

GenerateComposer::create();


// Geração do projeto
require_once "vendor/autoload.php";

use generator\Generate;

$result = Generate::start();

echo $result[1];
