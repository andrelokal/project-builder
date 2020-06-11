<?php
class ViewGenerator extends DAO{
    
    public $path;
    
    public function __construct($path){
        $this->path = $path;
    }
    
    
    public function generateByTable($table){
        
        $newObj = new StdClass;
        
        $newObj->table = $table;
        
        $this->getProperties($newObj);

        $resultConstraint = $this->getConstraint($table);
        
        $fields = "";
        
        foreach($this->arrProperties as $atributo){
            if($atributo['Key'] == 'PRI' || $atributo['Extra'] != ""){


            }else{
                
                $required = $atributo['Null'] == 'NO' ? 'true':'false';
                
                $fields.='
                <div class="fitem">
                    <label>'.ucfirst($atributo['Field']).':</label>
                    <input name="'.$atributo['Field'].'" class="easyui-textbox" required="'.$required.'">
                </div>'."\n";

        }    
        }

        $content = '      
        <!-- Grid de '.ucfirst($table).' -->
        <table id="dg'.ucfirst($table).'" style="height:100%"></table>    

        <!-- Form de Alteração -->
        <div id="dlg'.ucfirst($table).'" >
            <form id="fm'.ucfirst($table).'" method="post"novalidate> '.
            $fields
            .'            </form>
        </div>

        <script>

            var pathData = "../control/'.$table.'Controller.php?action=get_data";
            var dataGridID = "dg'.ucfirst($table).'";
            var dataDialogID = "dlg'.ucfirst($table).'";
            var dataFormID = "fm'.ucfirst($table).'";
            var path = "../control/'.$table.'Controller.php";

            $(function(){
                dataGridReacall(pathData,dataGridID);
                dialogRecall(dataDialogID,400,200);
            });

        </script>
        ';
        
        
        
        file_put_contents($this->path.'/'.ucfirst($table)."View.php",$content, FILE_APPEND);
        
        
        
        
        
        
    }

    
    
    
    
}
?>
