<?php
abstract class DAO {

    private   $conn;
    private   $host;
    private   $user;
    private   $pass;
	public    $db;
    private   $error         = "";
    private   $arrAttributes = array();
    public    $arrProperties = array();
    
    //Cria a conexao com o banco de dados
    protected function doConnect(){
        
        $conf = parse_ini_file(PATH_ROOT."util/config.ini");

        $this->host = $conf['HOST'];
        $this->user = $conf['USER'];
        $this->pass = $conf['PASS'];
        $this->db   = $conf['DB'];

        if(!isset( $this->conn )){
            try {
                $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db, $this->user, $this->pass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch ( Exception $e ) {
                $this->error = $e->getMessage();
                die($this->error);
                return false;
            }
        }    
    }
    
    //Recupera erros de execucao dos metodos
    public function getError(){
        return $this->error;
    }
    
    //Recupera Attributes do objeto
    private function getAttributesClass($obj){
        $reflect = new ReflectionObject($obj);
        foreach ($reflect->getProperties(ReflectionProperty::IS_PUBLIC + ReflectionProperty::IS_PROTECTED + ReflectionProperty::IS_PRIVATE) as $prop) {
            array_push($this->arrAttributes, $prop->getName());
        }        
    }
    
    //Converter Data do Banco MySQL para formato PTbr Data
    private function dataConvert($propertyField, $obField){
        if(strtoupper($propertyField) == "TIMESTAMP" || strtoupper($propertyField) == "DATE" || strtoupper($propertyField) == "DATETIME"){
            $date = date_create($obField);
            return date_format($date, 'd/m/Y');
        }else{
            return $obField;
        }
    }

    //Recupera Propriedades da Tabela
    protected function getProperties($obj){
        $this->doConnect();
        $st = $this->conn->prepare("show full columns from ".$obj->table);
        $st->execute();
        $this->arrProperties = $st->fetchAll(PDO::FETCH_ASSOC);
    }
    
    //Recupera dados do banco e retorna um array de objetos ou um unico objeto pupulado
    protected function doFind($obj, $where = "",$self = ""){
        $this->doConnect();
        $this->getAttributesClass($obj);
        $this->getProperties($obj);
        $id = $obj->idName;
        $arrayObj = array();

        if($obj->$id <= 0 || $where != ""){
            $st = $this->conn->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM ".$obj->table." ".$where);
            $st->execute();
            $result = $st->fetchAll(PDO::FETCH_ASSOC);
            foreach($result as $key => $column){
                $className = get_class($obj);
                //$newObj = new $className; 
                $newObj = new StdClass;
                foreach($this->arrProperties as $property ){
                    $field = $property["Field"];                    
                    $newObj->$field = $column[$field];
                    
                    //Fun��o verifica se o campo é data, se for converte no formato correto
                    $newObj->$field = $this->dataConvert($property["Type"], $newObj->$field);    
                }                
                array_push($arrayObj,$newObj);    
            }
            return $arrayObj;
        }else{
            $st = $this->conn->prepare("SELECT * FROM ".$obj->table." WHERE ".$id." = ".$obj->$id);
            $st->execute();
            $result = $st->fetchAll(PDO::FETCH_ASSOC);
            $newObj = $obj; 
            foreach($result as $key => $column){
                foreach($this->arrProperties as $property ){
                    $field = $property["Field"];
                    $newObj->$field = $column[$field];
                    $obj->$field = $column[$field]; //aplica alteracoes no objeto passado
                    
                    //Funcao verifica se o campo data, se for converte no formato correto
                    $newObj->$field = $this->dataConvert($property["Type"], $newObj->$field);
                    $obj->$field = $this->dataConvert($property["Type"], $newObj->$field); //aplica alteracoes no objeto passado
                }                                
            }
            return $newObj;
        }
    }
        
    //Metodo para salvar e atualizar objetos no banco de dados
    protected function doSave($obj){
        $this->doConnect();
        $this->getAttributesClass($obj);
        $this->getProperties($obj);
        $id = $obj->idName;
        $cont = 0;
        $fieldNames = "";
        $fieldValues = "";
        $setFields = "";
        
        if($obj->$id <= 0){
            
            foreach($this->arrProperties as $property ){
                $field = $property["Field"];
                if($cont > 0){
                    $fieldNames .= ", ";
                    $fieldValues .= ", ";
                }
                
                //formata o campo para data
                if(strtoupper($property["Type"]) == "TIMESTAMP" || strtoupper($property["Type"]) == "DATE" || strtoupper($property["Type"]) == "DATETIME"){
                     $obj->$field = strtoupper($obj->$field) != 'NULL' ? "STR_TO_DATE('".$obj->$field."', '%d/%m/%Y')" : ''; 
                     $fieldValues .= $obj->$field;
                     $fieldNames .= $field;
                     $cont++;
                }else{
                    if($obj->idName != $field){
                        $obj->$field = strtoupper($obj->$field) != 'NULL' ? $obj->$field : "";
                        $fieldNames .= $field;
                        $fieldValues .= "'".$obj->$field."'";                    
                        $cont++;
                    }    
                }
    
            }
            
            $query = "INSERT INTO ".$obj->table." (".$fieldNames.") VALUES (".$fieldValues.")";
            //error_log($query);
            
        }else{
            
            foreach($this->arrProperties as $property ){
                $field = $property["Field"];

                //verifica se o campo possui valor
                if(strlen($obj->$field) > 0){
                    if($cont > 0){
                        $setFields .= ", ";                    
                    }
                    //formata o campo para data
                    if(strtoupper($property["Type"]) == "TIMESTAMP" || strtoupper($property["Type"]) == "DATE" || strtoupper($property["Type"]) == "DATETIME"){
                         $obj->$field = strtoupper($obj->$field) != 'NULL' ? "STR_TO_DATE('".$obj->$field."', '%d/%m/%Y')" : '';
                         $setFields .= $field." = ".$obj->$field;
                         $cont++;
                    }else{
                        if($obj->idName != $field){
                            $obj->$field = strtoupper($obj->$field) != 'NULL' ? $obj->$field : "";
                            $setFields .= $field." = '".$obj->$field."'";
                            $cont++;
                        }
                    }
                    
                    
                }
                    
            }
            
            $query = "UPDATE ".$obj->table." SET ". $setFields. " WHERE ".$id." = ".$obj->$id;
            //error_log($query);
        }

        try{        
            $st = $this->conn->prepare($query);
            //$this->conn->beginTransaction(); 
            $st->execute();            
            //verifica se a atualizacao de um Insert ou Update para retornar o ID correto
            $obj->$id = $obj->$id <= 0 ? $this->conn->lastInsertId() : $obj->$id;        
            //$this->conn->commit();
            return true;                        
        }catch (PDOExecption $e) {
            //$this->conn->rollback();
            $this->error = $e->getMessage();
            return false;
        }
    }
    
    // Metodo para deletar objetos no banco de dados
    protected function doDelete($obj){
        $id = $obj->idName;    
        if($obj->$id > 0){
            $this->doConnect();
            $query = "DELETE FROM ".$obj->table." WHERE ".$id." = ".$obj->$id;        
            try{                
                $st = $this->conn->prepare($query);
                //$this->conn->beginTransaction(); 
                $st->execute();
                //$this->conn->commit();
                return true;            
            }catch (PDOExecption $e) {
                //$this->conn->rollback();
                $this->error = $e->getMessage();
                return false;
            }        
        }else{
            $this->error = "Objeto Selecionado não possui ID";
            return false;    
        }
    }
    
    //Método de Preenchimento da classe
    protected function doPopulationClass($obj,$array){
        $this->getAttributesClass($obj);
        foreach($this->arrAttributes as $field){
            foreach($array as $key => $value){
                if($key == $field){
                    $obj->$field = $value;
                }
            }
        }        
    }
    
    protected function doExecuteSelect($SQL){
        $this->doConnect();
        
        $arrayObj = array();
        
        try{                
            $st = $this->conn->prepare($SQL);
            $st->execute();
            $result = $st->fetchAll(PDO::FETCH_ASSOC);
            $count = count($result);
            
            $newObj = "";
            
            foreach($result as $row){
                $newObj = new StdClass;
                foreach($row as $key => $property ){
                    $newObj->$key = $property;
                }
                array_push($arrayObj,$newObj);
            }
            
            if($count > 1){
                return $arrayObj;
            }

            return $newObj;
            
        }catch (PDOExecption $e) {
            $this->error = $e->getMessage();
            return false;
        }
        
    }
    
    public function doExecuteSQL($SQL){
        $this->doConnect();
        $st = $this->conn->prepare($SQL);
        if($st->execute())
            return true;
        
        return false;
        
    }
    
    //Retorna a Quantidade de registros da ultima query executada, sem limit ou offset
    public function getTotalRows(){
        $query = "SELECT FOUND_ROWS() as TOTAL";
        return $this->doExecuteSelect($query);
    }
    
    public function getTables(){
        return $this->doExecuteSelect('SHOW TABLES');
    }
    
    public function getConstraint($table){
        $query = "SELECT DISTINCT COLUMN_NAME AS COLUNA, 
                         REFERENCED_COLUMN_NAME AS REFCOLUNA, 
                         REFERENCED_TABLE_NAME AS REFTABELA 
                    FROM information_schema.KEY_COLUMN_USAGE
                   WHERE TABLE_NAME = '{$table}' 
                     AND REFERENCED_TABLE_NAME IS NOT NULL";
        
        return $this->doExecuteSelect($query);
    }
    
    
    
    
}
