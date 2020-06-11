/*
var Transporter = {
		var controller,
		initialize: function(controller){
			this.controller = controller;
		}
		getCreateForm: function (){
			$.post(this.controller,
				{action:'get_properties'},
				function(data,textStatus){
				var form = '';
					$.each(data, function(index, value) {
						if(value != 'id'){
							form +=	'<div class="input-prepend">';
							form +=	'<span class="add-on">'+value+'</span>';
							form +=	'<input type="text" id="'+index+'" name="'+index+'" value="" class="input-xlarge" />';
							form +=	'</div><br/><br/>';
						}	
					});
				form +=	'<div class="control-group">';	
				form +=	'<button type="button" id="insert" class="btn btn-primary"><i class="icon-ok icon-white"></i> Adicionar </button>';
				form +=	'</div>';
				
				$('div#content').html(form);

				},
				"json"
			)
		},
		renderLista: function(jsonData){			
			$.post(this.controller,
				{action:'get_properties'},
				function(data,textStatus){
				var table  = '<table width="600" cellpadding="5" class="table table-hover table-bordered"><thead><tr>';
					$.each(data, function(index, value) {
							table += '<th scope="col">'+value+'</th>';
					});
				table += '<th scope="col"></th></tr></thead><tbody>';	
				$.each(jsonData, function(index,data){
					table += '<tr>';
					$.each(this, function(index, value) {
						if(index != 'id'){
							table += '<td class="edit" field="'+index+'" data_id="'+data.id+'">'+value+'</td>';
						}
					});
					table += '<td><a href="javascript:void(0);" data_id="'+data.id+'" class="btn-danger"><i class="icon-remove icon-white"></i></a></td>';
					table += '</tr>';
				});
				table += '</tbody></table>';	
				$('div#content').html(table);
				},
				"json"
			)
		},
		deixarEditavel:function(element){
			$(element).html('<input id="editbox" size="'+ $(element).text().length +' type="text" value="'+ $(element).text() +'">');
			$('#editbox').focus();
			$(element).addClass('current');
		},
		removeEditavel:function(element){
			$('#indicator').show();
			var Data = new Object();
				Data.id = $('.current').attr('data_id');
				Data.field = $('.current').attr('field');
				Data.newvalue = $(element).val();
			var dataJson = JSON.stringify(Data);	
			$.post(this.controller,
				{action: 'update',post_data:dataJson},
				function(data,textStatus){
					$('td.current').html($(element).val());
					$('.current').removeClass('current');
					$('#indicator').hide();
				},
				"json"
			);	
		},
		insereDados:function(element){
			$('#indicator').show();
			var dataForm = $( "form" ).serialize();
			$.post(this.controller,
				{action: 'insert',post_data:dataForm},
				function(data,textStatus){
					this.getLista(element);
					$('#indicator').hide();
				},
				"json"
			);
		},
		deleteData:function(element){
			$('#indicator').show();
			var Data = new Object();
				Data.id = $(element).attr('data_id');
			var dataJson = JSON.stringify(Data);
			
			$.post(this.controller,
				{action: 'delete',post_data:dataJson},
				function(data,textStatus){
					this.getLista(element);
					$('#indicator').hide();
				},
				"json"
			);
		},
		getLista:function(element){
			$('#indicator').show();
			$.post(this.controller,
				{action:'get_lista'},
				function(data,textStatus){
					this.renderLista(data);
					$('#indicator').hide();
				},
				"json"
			)
			
		}

}
*/




















