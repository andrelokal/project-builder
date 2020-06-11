<?php
class RecursiveClass extends DAO{

	public $tree = array();

	public function __construct(){}
	
	//setters, getters, isset, unset mágicos
	public function __set($pname, $pvalue) { $this->$pname = $pvalue; }
	public function __get($pname) { return $this->$pname; }
	public function __isset($pname) { return isset($this->$pname); }
	public function __unset($pname) { unset($this->$pname); }
	
	
	public function recursiveTable($parentName, $class , $parentID = ''){	
		$list = array(); //variavel auxiliar
		if($parentID){
			$collectionThis = parent::doFind($class,"where ".$parentName." = ".$parentID ." and statusID > 0");
		}else{
			$collectionThis = parent::doFind($class,"where ".$parentName." = 0 OR ".$parentName." IS NULL");
		}
		
		foreach($collectionThis as $row){			
			$idName = $class->idName;			
			$row->children  = $this->recursiveTable( $parentName, $class, $row->$idName );		
			if($parentID){
				$list[] = $row;
			}else{	
				$this->tree[] = $row;
			}			
		}
		
		if( $list )
			return $list;
		
	}
	
}
?>