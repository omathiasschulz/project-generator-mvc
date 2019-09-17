<?php


// $filename = 'autoload.json';

// if (file_exists($filename)) {
//     echo "O arquivo $filename existe";
//     $fp = fopen('autoload.json', 'w');
//     fwrite($fp, json_encode($response));
//     fclose($fp);



// } else {
//     echo "O arquivo $filename não existe";
// }


// $json = json_decode(file_get_contents('autoload.json'));

// foreach ($json->folders as $folder) {
//     $path = explode('/', $folder);
//     $require = "";
//     foreach($path as $key => $class) {
//         $require .= $class . DIRECTORY_SEPARATOR;
//     }
//     $require .= $nomeClasse . ".php";

//     echo ' | ' . $require;
//     if (file_exists($require)) {
//         require_once($require);
//     }
// }


$json = json_decode(file_get_contents('autoload.json'));
$folders = '[';
foreach ($json->folders as $folder)
    $folders .= '"' . $folder . '", ';
$folders = substr($folders, 0, strlen($folders) - 2) . ']';
echo $folders;


$autoload = ''
    . 'spl_autoload_register(function ($nomeClasse) {'
    . '    $folders = array("classes", "conf", "dao");'
    . '    foreach ($folders as $folder) {'
    . '        if (file_exists($folder.DIRECTORY_SEPARATOR.$nomeClasse.".class.php")) {'
    . '            require_once($folder.DIRECTORY_SEPARATOR.$nomeClasse.".class.php");'
    . '        }'
    . '    }'
    . '});';











// var_dump($json);

// $arrayFolders = [];
// foreach ($json->folders as $folder) {
//     $arrayFolders[] = $folder;
//     $path = explode('/', $folder);
//     $require = "";
//     foreach($path as $key => $class) {
//         $require .= $class . DIRECTORY_SEPARATOR;
//     }
//     $require .= $nomeClasse . ".php";

//     echo ' | ' . $require;
//     if (file_exists($require)) {
//         require_once($require);
//     }
// }



// spl_autoload_register(function ($nomeClasse)
// {
//     $folders = array("classes", "conf", "dao");
//     foreach ($folders as $folder) {
//         if (file_exists($folder.DIRECTORY_SEPARATOR.$nomeClasse.".class.php")) {
//             require_once($folder.DIRECTORY_SEPARATOR.$nomeClasse.".class.php");
//         }
//     }
// });



// Criar: autoload.php, Conexao.php, pastas