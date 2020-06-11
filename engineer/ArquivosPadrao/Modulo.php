<?php
class Modulo extends DAO{
	
	private $table = "modulo";
	private $idName = "id";
	private $id;
	private $text;
	private $pai;
	private $statusID;
	private $link;
	private $children = array(); // Auxiliar por se tratar de uma Lista Dinâmica Encadeada
	
	public function __construct(){}
			
	//setters, getters, isset, unset mágicos
	public function __set($pname, $pvalue) { $this->$pname = $pvalue; }
	public function __get($pname) { return $this->$pname; }
	public function __isset($pname) { return isset($this->$pname); }
	public function __unset($pname) { unset($this->$pname); }
	
		
	public function selectAll($offset,$rows){
		$this->id = "";
		$collectionThis = parent::doFind($this, "limit ".$offset.",".$rows."");
		return $collectionThis;
	}
	
	public function selectById($id){
		$this->id = $id;
		parent::doFind($this);
		return $this;
	}
	
	public function selectByName($nome){
		$collectionThis = parent::doFind($this,"where text like '%".$nome."%'");
		return $collectionThis;
	}
	
	public function save(){
		$result = parent::doSave($this);
		return $result;
	}
	
	public function delete(){
		if($this->id > 0){
			$result = parent::doDelete($this);
			return $result;
		}else{
			return false;
		}
	}
	
	public function populationClass($arrDados){
		parent::doPopulationClass($this,$arrDados);
	}
	
}
?>