<h3>First make sure that you already have the tables and database are created, then configure the config.ini file in the engineer folder .</h3>
<h4>Important: be sure to give permission 777 in the root directory of the project</h4>
<?php
if(isset($_REQUEST['action'])){
	include("config.php");
	include("ArquivosPadrao/DAO.php");
	include('Constructor.php');
}

if(@!file_exists ('config.ini'))
	die('you have to define the file config.ini');

if(@file_exists ('../model/DAO.php'))
	die('the builder has already been executed');

shell_exec('rm -rf ../util ');
mkdir('../util');
shell_exec('chmod 777 -R ../*');
shell_exec('cp config.php ../util/config.php');
shell_exec('cp config.ini ../util/config.ini');


?>
<br><br><br>

<hr> To create your project press the « Play » <hr>
<br><br><br>
<form action="">
  <input type="hidden" name="action" value="ok">
  <input type="submit" value="Play">
</form>