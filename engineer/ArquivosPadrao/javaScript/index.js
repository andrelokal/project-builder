//Inicializa DataGrid
function dataGridReacall(path,divID){
	$.ajax({
		url:path,
		dataType: "JSON",
		success : function( ret ){
			$('#'+divID).datagrid({
				url:path,
				data: ret ,
				pagination:'true', 
				singleSelect:'true',
				pageSize: 20,
				pageList: [20,30,50,100],
				striped:'true', 
				fitColumns:'true',
				columns:[ret.columns]
			});
		}
	})	
}


//Inicializa Dialog
function dialogRecall(divID,W,H){	
	$('#'+divID).dialog({
		title: 'Editar Dados',
		width: W,
		height: H,
		closed: true,
		cache: false,
		modal: true,
		buttons:[{
			iconCls:"icon-ok",
			text:'Salvar',
			handler:function(){
				saveData();
			}
			},{
				text:'Cancelar',
				iconCls:"icon-cancel",
				handler:function(){
					$('#'+divID).dialog('close');
				}
		}]
	});
}

//Pegar Itens das pastas
function getSelected(){
	var node = $('#itmn').tree('getSelected');
	if (node){
		var url = node.link;
		$.get(url, function( data ) {
			$( "#content" ).empty();
			$( "#content" ).html( data );

		});

	}
}

//METODOS DO MODULO

function newData(){
	if(typeof dataDialogID == "undefined"){
		return false;
	} 
	
	$('#'+dataDialogID).dialog('open').dialog('center').dialog('setTitle','Novo');
	$('#'+dataFormID).form('clear');
	url = path + '?action=save';
}

function editData(){
	if(typeof dataDialogID == "undefined"){
		return false;
	} 
	
	var row = $('#'+dataGridID).datagrid('getSelected');
	if (row){
		$('#'+dataDialogID).dialog('open').dialog('center').dialog('setTitle','Editar');
		$('#'+dataFormID).form('load',row);
		url = path+'?action=update&id='+row.id;
	}
}

function saveData(){
	$('#'+dataFormID).form('submit',{
		url: url,
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			var result = eval('('+result+')');
			if (result.errorMsg){
				$.messager.show({
					title: 'Erro',
					msg: result.errorMsg
				});
			} else {
				$('#'+dataDialogID).dialog('close');        // fecha a caixa de dialogo
				$('#'+dataGridID).datagrid('reload');    // atualiza o datagrid
				//dataGridReacall(pathData,dataGridID); // recria o datagrid
				
			}
		}
	});
}

function destroyData(){
	if(typeof dataDialogID == "undefined"){
		return false;
	}
	
	var row = $('#'+dataGridID).datagrid('getSelected');
	if (row){
		$.messager.confirm('Confirme','Deseja mesmo excluir este registro?',function(r){
			if (r){
				$.post(path+'?action=delete',{id:row.id},function(result){
					if (result.success){
						$('#'+dataGridID).datagrid('reload');    // atualiza o datagrid
						//dataGridReacall(pathData,dataGridID); // recria o datagrid
					} else {
						$.messager.show({    // mostra mensagem de erro
							title: 'Erro',
							msg: result.errorMsg
						});
					}
					},'json');
			}
		});
	}
}