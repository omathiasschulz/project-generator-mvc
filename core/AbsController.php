<?php

namespace core;

abstract class AbsController
{

    protected $view;
    private $caminhoDaView;
    private $caminhoDoLayout;

    public function __construct()
    {
        $this->view = new \stdClass;
    }

    protected function requisitarView($caminhoDaView, $caminhoDoLayout = null)
    {
        $this->caminhoDaView = $caminhoDaView;
        $this->caminhoDoLayout = $caminhoDoLayout;
        if($caminhoDoLayout) {
            $this->conteudoLayout();
        }else {
            $this->conteudoView();
        }
    }

    protected function conteudoView()
    {
        if(file_exists(__DIR__ . "/../app/view/{$this->caminhoDaView}.phtml")) {
            require_once(__DIR__ . "/../app/view/{$this->caminhoDaView}.phtml");
        }else {
            echo "Error: View path not found!";
        }
    }

    protected function conteudoLayout()
    {
        if(file_exists(__DIR__ . "/../app/view/{$this->caminhoDoLayout}.phtml")) {
            require_once(__DIR__ . "/../app/view/{$this->caminhoDoLayout}.phtml");
        }else {
            echo "Error: Layout path not found!";
        }
    }
}
