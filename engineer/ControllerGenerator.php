<?php
class ControllerGenerator extends DAO{
    
    public $path;
    
    public function __construct($path){
        $this->path = $path;
    }
    
    
    public function generateByTable($table){
        
        $newObj = new StdClass;
        
        $newObj->table = $table;
        
        $this->getProperties($newObj);

        $resultConstraint = $this->getConstraint($table);
        
        $fields1 = "";
        $fields2 = "";
        
        $numAt = count($this->arrProperties);
        $count = 1;
        foreach($this->arrProperties as $atributo){
            
            $coma = $count == $numAt ? ');':',';
            
            if($count == 1){
                
                $fields1.='
                        $arr = array("'.$atributo['Field'].'" => $obj'.ucfirst($table).'->'.$atributo['Field'].',';

            }else{
                
                
                
                $fields1.='
                                 "'.$atributo['Field'].'" => $obj'.ucfirst($table).'->'.$atributo['Field'].$coma;
            }
            if($atributo['Key'] == 'PRI'){
                
            }else{
                $coma = $coma == ',' ? $coma : '';
                $fields2 .= '
                array("field"=>"'.$atributo['Field'].'","title"=>"'.ucfirst($atributo['Field']).'","width"=>60, "align"=>"center")'.$coma;    
            }
            
                                                        
            $count++;   
        }

        $content = '      
<?php

        require_once("../util/config.php");

        if(isset($_REQUEST["action"])){
            $action = $_REQUEST["action"];
        }


        $'.$table.' = new '.ucfirst($table).'();

        switch($action){
            case "get_data":

                $page = isset($_REQUEST["page"]) ? intval($_REQUEST["page"]) : 1;
                $rows = isset($_REQUEST["rows"]) ? intval($_REQUEST["rows"]) : 20;
                $offset = ($page-1)*$rows;
                
                $id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;
            
                $collection = array();
                $array_'.$table.' = $'.$table.'->selectAll($offset,$rows);
                
                $idV = $'.$table.'->idName;
                if(@$array_'.$table.'->$idV) $array_'.$table.' = array($array_'.$table.');
                
                foreach($array_'.$table.' as $obj'.ucfirst($table).'){
                    '.$fields1.'
                    array_push($collection,$arr);
                }
                $arrJson["total"] = $'.$table.'->getTotalRows()->TOTAL;
                $arrJson["rows"] = $collection;
                $arrJson["columns"] =  array(
                                        '.$fields2.'
                                        );
                    
                print json_encode($arrJson);
            break;
            
            case "save":
                foreach($_REQUEST as $key => $value){
                    $_REQUEST[$key] = $value == "" ? "null" : $value;
                }
                $'.$table.'->populationClass($_REQUEST);
                $return = $'.$table.'->save();
                print json_encode(array("success" => $return,"errorMsg" => $'.$table.'->getError()));
            break;
            
            
            case "delete":
                $'.$table.'->populationClass($_REQUEST);
                $return = $'.$table.'->delete();
                print json_encode(array("success" => $return,"errorMsg" => $'.$table.'->getError()));
            break;
            
            case "update":
                foreach($_REQUEST as $key => $value){
                    $_REQUEST[$key] = $value == "" ? "null" : $value;
                }
                $_REQUEST["id"] = $_REQUEST["id"];
                $'.$table.'->populationClass($_REQUEST);
                $return = $'.$table.'->save();
                print json_encode(array("success" => $return,"errorMsg" => $'.$table.'->getError()));
            break;
            
        }
?>
        ';
        
        
        
        file_put_contents($this->path.'/'.$table."Controller.php",$content, FILE_APPEND);
        
        
        
        
        
        
    }

    
    
    
    
}
?>
