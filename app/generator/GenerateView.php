<?php

namespace generator;

use helpers\Helpers;
use helpers\StringBuilder;

class GenerateView
{
    /**
     * Método responsável por gerar as view
     */
public function create($aTables)
    {   
        Helpers::createFolder('app/view');
        Helpers::writeFile('app/view/baseHtml.phtml', file_get_contents(__DIR__ . '/defaultViews/baseHtml.phtml'));
        Helpers::writeFile('app/view/footer.phtml', '');
        Helpers::writeFile('app/view/index.phtml', '');
        Helpers::writeFile('app/view/header.phtml', self::content($aTables));
        
        
        foreach ($aTables as $oTable) {
            Helpers::createFolder('app/view/'.$oTable->nome);
            // Remove a primeira posição do array, que são as chaves primárias
            $aAttributes = $oTable->atributos;
            $oPrimaryKeys = array_shift($aAttributes);
            $aPrimaryKeys = $oPrimaryKeys->chaves_primarias;

            Helpers::writeFile('app/view/'.$oTable->nome.'/atualizar.phtml', self::att($aAttributes, $aTables[0]->nome));
            Helpers::writeFile('app/view/'.$oTable->nome.'/cadastrar.phtml', self::cad($aAttributes, $aTables[0]->nome));
            Helpers::writeFile('app/view/'.$oTable->nome.'/listar.phtml', self::list($aAttributes, $aTables[0]->nome, $aPrimaryKeys));
            Helpers::writeFile('app/view/'.$oTable->nome.'/visualizar.phtml', self::visu($aAttributes, $aTables[0]->nome));

            
            

            for ($i=1; $i < count($aAttributes); $i++) { 
                echo('');
            }
        }
    }

private function content($tables){
        $cad='';
        $visu='';
        for ($i=0; $i < count($tables); $i++) { 
            $cad ='
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/'.$tables[$i]->nome.'/cadastrar">'.$tables[$i]->nome.'</a>
                </div>';
            $visu='
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="/'.$tables[$i]->nome.'/listar">'.$tables[$i]->nome.'</a>
            </div>';
        }
        $string = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>'.$tables[0]->nome.'</title>
    <meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>


	<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="home.php"></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarText">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="/" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
				Home
				</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Cadastros
                </a>'.$cad.'
            </li>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Visualizar
            </a>'.$visu.'
            </li>
        </ul>
    </div>
    </nav>';

    return $string;
    }

private function att($table, $nome){
    $campos = new StringBuilder();
    for ($i=1; $i < count($table); $i++) { 
        $campos->append('    
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="'.$table[$i]->nome.'">'.$table[$i]->nome.'</label>
                <input type="'.self::tipoCampo($table[$i]->tipo).'" value="'.self::validaGet($nome, $table[$i]).'" class="form-control" id="'.$table[$i]->nome.'" name="'.$table[$i]->nome.'" placeholder="'.$table[$i]->nome.'" '.self::verificaCampos($i, $table[$i]->not_null).'>
            </div>
        </div>');
            
    }
    
    $string = '<div class="container">
    <br><h1>Alterar '.ucfirst($nome).'</h1><br>

    <form action="/'.$nome.'/alterar" method="POST">'.$campos.'
    <button type="submit" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus-circle"></i>Salvar</button>
    </form>

    </div><br><br><br><br>';
    return $string;
}
private function cad($table, $nome){
    $campos = new StringBuilder();
    
    for ($i=1; $i < count($table); $i++) { 
        $campos->append('    
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="'.$table[$i]->nome.'">'.$table[$i]->nome.'</label>
                <input type="'.self::tipoCampo($table[$i]->tipo).'" class="form-control" id="'.$table[$i]->nome.'" name="'.$table[$i]->nome.'" placeholder="'.$table[$i]->nome.'" '.self::verificaCampos($i, $table[$i]->not_null).'>
            </div>
        </div>');
    }

    $string = '<div class="container">
    <br><h1>Cadastrar '.ucfirst($nome).'</h1><br>
    
    <form action="/'.$nome.'/inserir" method="POST">'.$campos.'
    <button type="submit" class="btn btn-sm btn-primary float-right"><i class="fa fa-plus-circle"></i> Adicionar</button>
    </form>

    </div><br><br><br><br>';

    return $string;
}
private function list($table, $nome, $pk){
    $campos = new StringBuilder();
    $th = new StringBuilder();
    
    if (count($table) > 4) {
        for ($i=0; $i < 5; $i++) { 
            $th->append("\n\t\t\t".'<th>'.ucfirst($table[$i]->nome).'</th>');
            $campos->append('<td onclick="location.href = \'/'.$nome.'/<?php echo $'.$nome.'->get'.ucfirst($table[0]->nome).'(); ?>/visualizar\';"><?php echo $'.$nome.'->'.self::validaGetVisualizar($table[$i]).'?> </td>
            ');
        }
    }

    $string = '<div class="container">
    <br><h1>'.ucfirst($nome).'</h1><br>
    <div class="table-wrapper">

    <table class="table table-striped table-hover">
    <thead>
        <tr>'.
        $th.'
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($this->view->many'.ucfirst($nome).' as $'.$nome.'): ?>
    <tr style="cursor: pointer;">'.$campos.'
    <td>
    <input type="button" onclick="location.href=\'/'.$nome.'/<?php echo $'.$nome.'->get'.ucfirst($pk[0]).'(); ?>/atualizar\';" class="btn btn-sm btn-primary" value="Atualizar" />
    <input type="button" onclick="location.href=\'/'.$nome.'/<?php echo $'.$nome.'->get'.ucfirst($pk[0]).'(); ?>/deletar\';" class="btn btn-sm btn-success" value="Deletar" />
    </td>
    </tr>
<?php endforeach; ?>
</tbody>

</table>

</div>
</div>';

    return $string;
}
private function visu($table, $nome){
    $campos = new StringBuilder();
    for ($i=1; $i < count($table); $i++) { 
        $campos->append('    
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="'.$table[$i]->nome.'">'.$table[$i]->nome.'</label>
                <input type="text" value="' . self::validaGet($nome, $table[$i]) . '" class="form-control" id="'.$table[$i]->nome.'" name="'.$table[$i]->nome.'" placeholder="'.$table[$i]->nome.'" readonly>
            </div>
        </div>');
            
    }
    $string = '<div class="container">
    <br><h1>Visualizar '.ucfirst($nome).'</h1><br>

    <form action="/'.$nome.'/visualizar" method="POST">'.$campos;

    return $string;
}

private function verificaCampos($pos, $nulo){
    if ($pos==0) {
        if ($nulo) {
            return 'readonly required';
        }else{
            return 'readonly';
        }
    }elseif ($nulo){
        return 'required';
    }
}

private function tipoCampo($tipoVar){
    if ((strpos($tipoVar, 'varchar') !== false) || (strpos($tipoVar,'tinyint') !== false)) {
        return 'text';
    }elseif((strpos($tipoVar,'decimal') !== false) || $tipoVar == 'int' || $tipoVar == 'float' || $tipoVar == 'double' || $tipoVar == 'bigint'){
        return 'number';
    }elseif ($tipoVar == 'datetime' || $tipoVar == 'date'){
        return 'date';
    }
}

private function validaGet($nome, $table){
    if ($table->tipo == 'datetime' || $table->tipo == 'date') {
        return '<?= ($this->view->'.$nome.'->get'.ucfirst($table->nome).'() != "") ? $this->view->'.$nome.'->get'.ucfirst($table->nome).'()->format("Y-m-d") : "" ?>';
    } else {
        return '<?= $this->view->'.$nome.'->get'.ucfirst($table->nome).'() ?>';
    }

}
private function validaGetVisualizar($table){
    if ($table->tipo == 'datetime' || $table->tipo == 'date') {
        return 'get'.ucfirst($table->nome).'()->format(\'d-m-Y\')';
    } else {
        return 'get'.ucfirst($table->nome).'()';
    }

}
}
