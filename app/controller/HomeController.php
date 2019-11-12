<?php

namespace app\controller;

use core\AbsController;

class HomeController extends AbsController{

    public function index(){
        // $this->requisitarView('index', 'baseHtml');
        echo 'HomeController@index';
    }

}
