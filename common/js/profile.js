/**
 * 
 */
$(function(){
	$('button.edit').unbind('click').click(function() {
		profile.edit($(this).parent().parent());
		return false;
	}
	);
	//email_validator={optional:function(e){return false;},email:jQuery.validator.methods.email};
	$('.profile').validate({
		submitHandler:function() {
			
			
		},
		errorPlacement: function(error, element) {},
		rules:{
			username:{
				minlength:4,
				maxlength:30,
				regExpr:/^[a-zA-Z\d]+$/,
				remote:{
					url : path+"/profile/ctrl",
					type : "post" 
					//dataType : "json"
				}
			},
			password:{
				minlength:8,
				maxlength:16,
				regExpr:/^[a-zA-Z\d]+$/
			},
			'new':{
				minlength:8,
				maxlength:16,
				regExpr:/^[a-zA-Z\d]+$/
			},
			new2:{
				equalTo:'#password'
			},
			email: {
				remote: {
					url : path+"/profile/ctrl",
					type : "post" 
				}
			}
		},
	});
	$('.profile').submit(function(e){
	});
	$('.button button:eq(0)').click(function(){
		profile.password(this);
	});
	$('.button button:eq(1)').click(function(){
		$("#dialog").dialog('open');
	});
	$("#dialog").dialog({
		autoOpen : false,
		modal : true,
		//width : 1000,
		buttons : {
			Ok : function() {
				request('/profile/delete',{password:$('#delete').val()},function(data){
					if (data.success) location.reload();
				});
				$(this).dialog("close");
			},
			Cancel : function() {
				$(this).dialog("close");
			}
		},
	});
	
});
var profile={
	edit:function(row) {
		row.find('input,select,textarea').show();
		row.find('span:eq(0)').hide();
		button=row.find('button');
		button.unbind('click').click(function(){
			profile.send($(this).parent().parent());
		});
		button.button('option','icons',{
			primary:'ui-icon-check'
		});
	},
	send:function(row) {
		i=row.find('input,select,textarea').hide();
		if (i.is('select')) v=i.find('option:selected').text();
		else v=i.val();
		
		row.find('span:eq(0)').show().text(v);
		button=row.find('button');
		button.unbind('click').click(function(){
			profile.edit($(this).parent().parent());
			return false;
		});
		$('.button button:eq(0)').button('option','icons',{
			primary:'ui-icon-wrench'
		});
		request('/profile/edit',{key:row.attr('id'),value:v},false,true);
		return false;
	},
	password:function(button) {
		$('.password').show();
		$(button).unbind('click').click(function(){
			pass=$('.password:eq(1) input');
			if (pass.eq(0).val()==pass.eq(1).val()) {
				request('/profile/password',{key:'password',value:pass.eq(0).val()});
			}
			$('.profile')[0].reset();
			$('.password').hide();
			$(button).unbind('click').click(function(){
				profile.password(this);
			}).button('option','icons',{
				primary:''
			});
		}).button('option','icons',{
			primary:'ui-icon-check'
		});
	}
};