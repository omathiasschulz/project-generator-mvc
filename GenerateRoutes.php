<?php

require_once('Helpers.php');

class GenerateRoutes
{
    /**
     * Método responsável por gerar as rotas
     */
    public static function create($aRoutes)
    {
        $stringRoutes = self::getRoutesPhp($aRoutes);
        Helpers::createFolder('app');
        Helpers::writeFile('app/routes.php', $stringRoutes);

        $stringCoreRoute = self::getCoreRoutes();
        Helpers::createFolder('core');
        Helpers::writeFile('core/Route.php', $stringCoreRoute);

        $stringCoreControllerUtil = self::getCoreControllerUtil();
        Helpers::writeFile('core/ControllerUtil.php', $stringCoreControllerUtil);

        $stringHtAccess = self::getHtAccess();
        Helpers::writeFile('.htaccess', $stringHtAccess);

        // Helpers::createFolder('controller');
        // self::getControllerFiles($aRoutes);
    }

    /**
     * Monta a string a partir dos folders do array
     */
    private function getStringFolders($aFolders)
    {
        $result = '';
        foreach ($aFolders as $folder) {
            // Um folder pode possuir vários pacotes
            $aPaths = explode('/', $folder);
            $sFolder = '';
            foreach ($aPaths as $path) {
                $sFolder .= $path . DIRECTORY_SEPARATOR; // São remontados de acordo com o SO
            }
            $sFolder = substr($sFolder, 0, strlen($sFolder) - 1);
            $result .= '"' . $sFolder . '", ';
        }
        return $result;
    }

    /**
     * Monta a string de folders em formato de array
     */
    private function getFolders($aFolders, $aStandartFolders)
    {
        $result = '[';
        if ($aStandartFolders)
            $result .= self::getStringFolders($aStandartFolders);
        
        if ($aFolders)
            $result .= self::getStringFolders($aFolders);
        
        if (strlen($result) > 1)
            $result = substr($result, 0, strlen($result) - 2);
        $result .= ']';
        return $result;
    }

    /**
     * Método que gera a string que será gravada no autoload.php
     */
    private function getAutoload($folders)
    {
        return 
'<?php

spl_autoload_register(function ($nomeClasse) {
    $folders = ' . $folders . ';
    foreach ($folders as $folder) {
        if (file_exists($folder.DIRECTORY_SEPARATOR.$nomeClasse.".php")) {
            require_once($folder.DIRECTORY_SEPARATOR.$nomeClasse.".php");
        }
    }
});';
    }

    /**
     * Método que gera a string que será gravada no routes.php
     */
    private function getRoutesPhp($routes)
    {
        $stringRoutes = '<?php' . PHP_EOL . PHP_EOL;
        foreach ($routes as $route) {
            $stringRoutes .= 
                '$routes[] = ["' . $route->url . '", "' . $route->controller . '@' . $route->method . '"];' . PHP_EOL;
        }
        $stringRoutes .= PHP_EOL . 'return $routes;';
        return $stringRoutes;
    }

    /**
     * Método que gera a string que será gravada no core/Route.php
     */
    private function getCoreRoutes()
    {
        return 
'<?php

class Route
{
    private $routes;

    public function __construct(array $routes)
    {
        $this->setRoutes($routes);
        $this->getController();
    }

    /**
     * Método responsável por buscar o nome do projeto
     * Permitindo que não seja obrigatório alterar o conf file do xampp
     */
    private function getProjectName()
    {
        return "/". explode("/", $_SERVER["REQUEST_URI"])[1];
    }

    /**
     * Seta as rotas
     */
    private function setRoutes($routes)
    {
        foreach($routes as $route){
            $target = explode("@", $route[1]);
            $newRoutes[] = [$this->getProjectName() . $route[0], $target[0], $target[1]];
        }   
        $this->routes = $newRoutes;
    }

    /**
     * Método que busca a rota digitada no navegador
     */
    private function getURL()
    {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    /**
     * Método responsável por buscar nas rotas do sistema uma determinada rota
     */
    private function getControllerAndAction()
    {
        $url = $this->getUrl();
        $urlArray = explode("/", $url);

        foreach($this->routes as $route) {
            $routeArray = explode("/", $route[0]);
            $aParam = [];
            // Verifica se a rota possui paramêtro
            for ($i = 0; $i < count($routeArray); $i++) {
                if(strpos($routeArray[$i], "{") !== false && count($urlArray) == count($routeArray)){
                    $routeArray[$i] = $urlArray[$i]; // Adiciona o paramêtro na rota para ver se são iguais
                    $aParam[] = $urlArray[$i]; // Adiciona o parâmetro no aParam
                }
            }
            $route[0] = implode("/", $routeArray); // junta todos os elementos com / denovo
            // Se a rota atual for igual a gerada então foi encontrado
            if($url == $route[0])
                return [$route[1], $route[2], $aParam]; //controller, action, param
        }
    }

    /**
     * Seta os parâmetros a partir de um request get ou post
     */
    public function getRequisicao(){
        $obj = new \stdClass;

        foreach ($_GET as $key => $value){
            @$obj->get->$key = $value;
        }

        foreach ($_POST as $key => $value){
            @$obj->post->$key = $value;
        }
        
        return $obj;
    }

    /**
     * Método responsável por buscar o controller caso possua
     * Se não vai para a página de página não encontrada
     */
    private function getController()
    {
        $configs = $this->getControllerAndAction();
        if($configs){
            $nameController = $configs[0];
            $nameMethod = $configs[1];
            $parameters = $configs[2];
            $controller = ControllerUtil::newController($nameController);
            
            switch(count($parameters)){
                case 1:
                    $controller->$nameMethod($parameters[0], $this->getRequisicao());
                    break;
                case 2:
                    $controller->$nameMethod($parameters[0], $parameters[1], $this->getRequisicao());
                    break;
                case 3:
                    $controller->$nameMethod($parameters[0], $parameters[1], $parameters[2], $this->getRequisicao());
                    break;
                default:
                    $controller->$nameMethod($this->getRequisicao());
                    break;
            }
        }else{
            ControllerUtil::page404();
        }
    }
}';
    }

    /**
     * Método que gera a string que será gravada no core/ControllerUtil.php
     */
    private function getCoreControllerUtil()
    {
        return 
'<?php

class ControllerUtil
{

    /**
     * Método para instanciar o controller
     */
    public static function newController($controller)
    {
        $oController = "app\\\\controller\\\\" . $controller;
        return new $oController;
    }

    /**
     * Rota para página não encontrada
     * Por padrão em app/view/404.phtml
     */
    public static function page404()
    {
        if(file_exists(__DIR__ . "/../app/view/404.phtml")) {
            return require_once __DIR__ . "/../app/view/404.phtml";
        } else {
            echo "Error 404: Page not found!";
        }
    }
}';
    }

    /**
     * Método que gera a string que será gravada no .htaccess
     */
    private function getHtAccess()
    {
        return 
'RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [NC,L,QSA]';
    }

    // /**
    //  * Método que gera a string que será gravada no .htaccess
    //  */
    // private function getControllerFiles($routes)
    // {
    //     foreach ($routes as $route) {

    //     }
    //     Helpers::writeFile('core/Route.php', $stringCoreRoute);
    // }
}
