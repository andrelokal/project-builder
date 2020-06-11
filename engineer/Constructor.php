<?php
shell_exec('rm -rf ../model ../view ../control ../index.php ');

mkdir('../model');
mkdir('../view');
mkdir('../control');
shell_exec('chmod 777 -R ../*');


$obj1  = new EntityGenerator('../model');
$obj2  = new ViewGenerator('../view');
$obj3  = new ControllerGenerator('../control');

$result = $obj1->getTables();
$prop = "Tables_in_".$obj1->db;

foreach($result as $value){
            $obj1->generateByTable($value->$prop);
            $obj2->generateByTable($value->$prop);
            $obj3->generateByTable($value->$prop);
}

if(is_array($result)){
	$obj1->doExecuteSQL("DROP TABLE IF EXISTS modulo");
	$obj1->doExecuteSQL("CREATE TABLE modulo (id INT NOT NULL AUTO_INCREMENT, text varchar(255),pai varchar(255),statusID varchar(255),link varchar(255), PRIMARY KEY (id), INDEX fk_modulo_modulo1_idx (pai ASC))");
    $obj1->doExecuteSQL("INSERT INTO modulo (text,pai,statusID,link) VALUES('Sistema',0,1,null)");
    $obj1->doExecuteSQL("INSERT INTO modulo (text,pai,statusID,link) VALUES('Configuracoes',0,1,null)");
    foreach($result as $value){
        if($value->$prop == 'modulo'){
            $obj1->doExecuteSQL("INSERT INTO modulo (text,pai,statusID,link) VALUES('".ucfirst($value->$prop)."',2,1,'".ucfirst($value->$prop)."View.php')");
        }else{
            $obj1->doExecuteSQL("INSERT INTO modulo (text,pai,statusID,link) VALUES('".ucfirst($value->$prop)."',1,1,'".ucfirst($value->$prop)."View.php')");
        }
    }
}

copy('ArquivosPadrao/HomeView.php', '../view/HomeView.php');;
copy('ArquivosPadrao/ModuloView.php', '../view/ModuloView.php');
copy('ArquivosPadrao/index.php', '../index.php');
copy('ArquivosPadrao/Modulo.php', '../model/Modulo.php');
copy('ArquivosPadrao/RecursiveClass.php', '../model/RecursiveClass.php');
copy('ArquivosPadrao/moduloController.php', '../control/moduloController.php');
copy('ArquivosPadrao/DAO.php', '../model/DAO.php');
copy('config.php', '../util/config.php');
copy('config.ini', '../util/config.ini');

//copiando diretorios 
copy_dir('ArquivosPadrao/bootstrap', '../view/bootstrap');
copy_dir('ArquivosPadrao/easyui', '../view/easyui');
copy_dir('ArquivosPadrao/javaScript', '../view/javaScript');


function copy_dir($origem, $dest){
	shell_exec('cp -R '.$origem.' '.$dest);
	shell_exec('chmod 777 -R '.$dest);
    return true;
}

?>

<p>Congratulations everything is ok, for your security delete the folder engineer</p>
<h2>Finished</h2>

<?php die(); ?>