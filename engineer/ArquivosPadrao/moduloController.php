<?php

require_once("../util/config.php");

if(isset($_REQUEST['action'])){
	$action = $_REQUEST['action'];
}


$modulo = new Modulo();

switch($action){
    case 'get_modulo':
	
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 20;
		$offset = ($page-1)*$rows;
		
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		
		$collection = array();
		foreach($modulo->selectAll($offset,$rows) as $objmodulo){
			$arr = array("id" => $objmodulo->id,
						 "text" => $objmodulo->text,
						 "pai" => $objmodulo->pai,
						 "statusID" => $objmodulo->statusID,
						 "link" => $objmodulo->link);
			array_push($collection,$arr);
		}
		$arrJson["total"] = $modulo->getTotalRows()->TOTAL;
		$arrJson["rows"] = $collection;
		$arrJson["columns"] =  array( array('field'=>'id','title'=>'Modulo Id','width'=>50),
									  array('field'=>'text','title'=>'Modulo','width'=>50),
									  array('field'=>'pai','title'=>'Superior Id','width'=>50),
									  array('field'=>'statusID','title'=>'Status','width'=>50),
									  array('field'=>'link','title'=>'Link','width'=>50)
									);
		
        print json_encode($arrJson);
    break;
    
    case 'save':
        foreach($_REQUEST as $key => $value){
			$_REQUEST[$key] = $value == "" ? "null" : $value;
		}
		$modulo->populationClass($_REQUEST);
		$return = $modulo->save();
        print json_encode(array('success' => $return,'errorMsg' => $modulo->getError()));
    break;
	
    
    case 'delete':
		$modulo->populationClass($_REQUEST);
		$return = $modulo->delete();
        print json_encode(array('success' => $return,'errorMsg' => $modulo->getError()));
    break;
    
    case 'update':
        foreach($_REQUEST as $key => $value){
			$_REQUEST[$key] = $value == "" ? "null" : $value;
		}
        $modulo->populationClass($_REQUEST);
		$return = $modulo->save();
		print json_encode(array('success' => $return,'errorMsg' => $modulo->getError()));
    break;
	
	case 'getRecursiveList':
        $rec = new RecursiveClass();
		$rec->recursiveTable("pai", $modulo);
		print json_encode($rec->tree);
    break;
	
}


