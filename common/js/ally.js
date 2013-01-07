$(function(){
	//show button
	$('.enter').click(function() {
		id=this.id.replace('enter','');
		request('/alliance/application/id/'+id,null,function(){location.href=path+'/alliance';});
		
	});
	$('.ok').click(function() {
		id=this.id.replace('ok','');
		request('/alliance/add/id/'+id,null,function(){
			$('button#ok'+id).parent().toggleClass('WAIT','MEMBER');
			$('button#ok'+id).hide();	
		});
		
	});
	$('.delete').click(function() {
		id=this.id.replace('delete','');
		request('/alliance/delete/id/'+id,null,function(){
			$('button#delete'+id).parent().remove();
		});
		
	});
	
});