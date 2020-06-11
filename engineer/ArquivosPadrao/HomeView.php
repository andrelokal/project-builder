<?php
require_once("../util/config.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= TITLE ?></title>
        <link rel="stylesheet" type="text/css" href="<?= PATH."view/easyui/themes/bootstrap/easyui.css"?>">
        <link rel="stylesheet" type="text/css" href="<?= PATH."view/easyui/themes/icon.css"?>">
        <link rel="stylesheet" type="text/css" href="<?= PATH."view/easyui/themes/color.css"?>">
        <link rel="stylesheet" type="text/css" href="<?= PATH."view/easyui/demo/demo.css"?>">
    </head>
    <body class="context-menu-one">

        <div style="margin:20px 0;" ></div>

        <div class="easyui-layout" style="width:1200px;height:600px;">

            <!-- MENU LATERAL -->
            <div data-options="region:'west',split:true" title="Menu" style="width:200px;">

                <ul id="itmn"  ondblclick="getSelected()"></ul>

            </div>

            <!-- JANELA CENTRAL -->		
            <div id="content" data-options="region:'center',title:'',iconCls:'icon-ok'"></div>

            <!-- INICIO MENU CONTEXTO -->
            <div id="mm" class="easyui-menu" style="width:120px;">
                <div data-options="iconCls:'icon-add'" onclick="newData()">Novo</div>
                <div data-options="iconCls:'icon-edit'" onclick="editData()">Editar</div>
                <div data-options="iconCls:'icon-cancel'" onclick="destroyData()">Excluir</div>
                <div class="menu-sep"></div>
                <div data-options="iconCls:'icon-back'">Exit</div>
            </div>

        </div>
    </body>

    <footer>

        <script type="text/javascript" src="<?= PATH."view/easyui/jquery.min.js"?>"></script>
        <script type="text/javascript" src="<?= PATH."view/easyui/jquery.easyui.min.js"?>"></script>
        <script type="text/javascript" src="<?= PATH."view/javaScript/index.js"?>"></script>
		<script type="text/javascript" src="<?= PATH."view/easyui/locale/easyui-lang-pt_BR.js"?>"></script>

        <script>
            $(function(){
                //Inicializa Pastas em Arvore
                $('#itmn').tree({
                    url:'<?= PATH ?>control/moduloController.php?action=getRecursiveList'
                });

                //Inicializa Menu de Contexto do Mouse
                $(document).bind('contextmenu',function(e){
                    e.preventDefault();
                    $('#mm').menu('show', {
                        left: e.pageX,
                        top: e.pageY
                    });
                });
            });
			
        </script>
    </footer>
</html>
