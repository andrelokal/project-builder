<!-- Grid de Modulo -->
<table id="dgModulo" style="height:100%"></table>	

<!-- Form de Alteração -->
<div id="dlgModulo">
    <form id="fmModulo" method="post"novalidate>
    <div class="fitem">
        <label>Modulo:</label>
        <input name="text" class="easyui-textbox" required="true">
    </div>
    <div class="fitem">
        <label>Superior:</label>
        <input name="pai" class="easyui-textbox" required="true">
    </div>
    <div class="fitem">
        <label>Status:</label>
        <input name="statusID" class="easyui-textbox" required="true" validType="email">
    </div>
	<div class="fitem">
        <label>Link:</label>
        <input name="link" class="easyui-textbox" required="true">
    </div>
    </form>
</div>

<script>

    var pathData = '../control/moduloController.php?action=get_modulo';
    var dataGridID = 'dgModulo';
    var dataDialogID = 'dlgModulo';
    var dataFormID = 'fmModulo';
    var path = '../control/moduloController.php';

    $(function(){
        dataGridReacall(pathData,dataGridID);
        dialogRecall(dataDialogID,400,200);
    });

</script>