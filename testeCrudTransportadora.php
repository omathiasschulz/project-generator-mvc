<?php

require_once('./vendor/autoload.php');

use app\model\dto\Produto;
use app\model\dao\ProdutoDAO;
use app\model\bo\ProdutoBO;


$produtoBO = new ProdutoBO(new ProdutoDAO());


// echo("\n\nINSERIR\n");
// $produto = (new Produto())
//     ->setDescricao('Produto teste')
//     ->setValorBruto(15.1234)
//     ->setValorLiquido(123456.123456)
//     ->setPeso(10.6)
//     ->setPesoEmbalado(10.5)
//     ->setDataFabricacao(new DateTime())
//     ->setDataCompra(new DateTime());
// echo $produtoBO->inserir($produto);


// echo ("\n\nBUSCAR UM\n");
// $produto = (new Produto())->setCodigo(1);
// echo $produtoBO->buscarUm($produto);


// echo ("\n\nALTERAR\n");
// $produto = (new Produto())
//     ->setCodigo(1)
//     ->setDescricao('Produto teste - Nova descricao')
//     ->setValorBruto(200.4321)
//     ->setValorLiquido(654.654)
//     ->setPeso(18)
//     ->setPesoEmbalado(17)
//     ->setDataFabricacao(new DateTime())
//     ->setDataCompra(new DateTime());
// echo $produtoBO->atualizar($produto);


// echo ("\n\nBUSCAR TODOS\n");
// var_dump($produtoBO->buscarTodos());


// echo ("\n\nDELETAR\n");
// $produto = (new Produto())->setCodigo(1);
// echo $produtoBO->deletar($produto);
