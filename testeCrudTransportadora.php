<?php

require_once('./vendor/autoload.php');

use app\model\dto\Produto;
use app\model\dao\ProdutoDAO;
use app\model\bo\ProdutoBO;


// TESTES COM O SQL DA TRANSPORTADORA: 
// create database transportadora;
// create table produto (
//     codigo int not null auto_increment,
//     descricao varchar(100) not null,
//     valor decimal not null,
//     peso float,
//     primary key(codigo)
// );


echo("\n\nINSERIR\n");
$o = new ProdutoDAO();
$produtoBO = new ProdutoBO($o);

$produto = (new Produto())
    ->setDescricao('Produto teste')
    ->setValor(150)
    ->setPeso(10);

echo $produtoBO->inserir($produto);


echo ("\n\nBUSCAR UM\n");
$produtoBO = new ProdutoBO(new ProdutoDAO());

$produto = (new Produto())->setCodigo(1);

echo $produtoBO->buscarUm($produto);


echo ("\n\nALTERAR\n");
$produtoBO = new ProdutoBO(new ProdutoDAO());

$produto = (new Produto())
    ->setCodigo(1)
    ->setDescricao('Produto teste - nova descrição')
    ->setValor(200)
    ->setPeso(20);

echo $produtoBO->atualizar($produto);


echo ("\n\nBUSCAR TODOS\n");
$produtoBO = new ProdutoBO(new ProdutoDAO());

var_dump($produtoBO->buscarTodos());


echo ("\n\nDELETAR\n");
$produtoBO = new ProdutoBO(new ProdutoDAO());

$produto = (new Produto())->setCodigo(1);

echo $produtoBO->deletar($produto);



