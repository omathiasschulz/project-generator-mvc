<?php

namespace generator;

use core\AbsController;
use helpers\Helpers;
use helpers\StringBuilder;

class GenerateController 
{
    /**
     * Método responsável por gerar os controllers
     */
    public function create($aTabelas)
    {
        // Helpers::createFolder('app/controller');

        foreach ($aTabelas as $key => $oTabela) {
            // var_dump($oTabela);
            // $controller = "<?php\n"
            //     . "\nclass LaunchController extends AbsController \n{";
            
            // $controller .= "\n}";
            // $controller = new StringBuilder();
            // $controller->append("<?php\n");
            // $controller->append("\nclass LaunchController extends AbsController \n{");
            // $controller->append("\n");
            // $controller->append("\n} ");
            // Helpers::writeFile('app/controller/' . ucfirst($oTabela->nome) .'Controller.php', $controller);

            Helpers::createClass(
                ucfirst($oTabela->nome)."Controller", 
                "    //corpo da classe", 
                'app/controller/',
                [
                    "core\\AbsController",
                    "core\\Redirecionador"
                ],
                'AbsController'
            );
        }
        // $routes .= "\nreturn \$route;\n";

    }

    public function defaultMethods()
    {

    }

    // /**
    // * Método responsável por levar para a tela de cadastro
    // */
    // public function cadastrar() {}

    // /**
    // * Método responsável por inserir ou atualizar um registro do banco
    // */
    // public function inserir($request) {}
    
    // /**
    // * Método responsável por levar a tela de visualização de um registro
    // */
    // public function visualizar($id) {}
    
    // /**
    // * Método responsável por levar a tela de atualização de um registro
    // */
    // public function atualizar($id) {}
    
    // /**
    // * Método responsável por deletar um registro
    // */
    // public function deletar($id) {}

    // /**
    //  * Método responsável por levar a rota de visualização do grid de registros
    //  */
    // public function listar() {}



/**
     * Método que gera os controllers
     */
    private function getControllerFiles($routes)
    {
        foreach ($routes as $route) {
            $atualNameFile = 'app/controller/' . $route->controller . '.php';
            if (file_exists($atualNameFile)) {
                $stringFile = file_get_contents($atualNameFile, 'r');
                $stringFile = substr($stringFile, 0, -1);
                $stringFile .= 
'    public function ' . $route->method . '() {
        echo "Página ' . $route->method . '";
    }

}';
            } else {
                $stringFile = 
'<?php

class ' . $route->controller . ' {

    public function ' . $route->method . '() {
        echo "Página ' . $route->method . '";
    }
    
}';
            }
            Helpers::writeFile('app/controller/' . $route->controller . '.php', $stringFile);
        }
    }}