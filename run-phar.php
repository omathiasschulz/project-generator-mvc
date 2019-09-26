<?php

$pharFile = 'app.phar';

// Deleta o phar caso exista
if (file_exists($pharFile))
    unlink($pharFile);
if (file_exists($pharFile . '.gz'))
    unlink($pharFile . '.gz');

// Criação do phar
$phar = new Phar($pharFile);

// Inicia a transação do phar
$phar->startBuffering();

// Adicionar os arquivos que serão utilizados no phar
$phar->buildFromDirectory(__DIR__);

// File que será executado ao executar o phar
$defaultStub = $phar->setDefaultStub('start.php');

// Finaliza a transação
$phar->stopBuffering();

// Adiciona o phar no formato gz
$phar->compressFiles(Phar::GZ);

// Comando que permite executar o phar
chmod(__DIR__ . '/app.phar', 0777);

echo "$pharFile successfully created" . PHP_EOL;

