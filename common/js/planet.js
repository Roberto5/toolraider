$(document).tooltip({
	items:"td.planet_name",
	position:{
		my:"right-50 center",
		at:"rigth center"
	},
	content: function() {
		var e=$(this);
		id=e.attr('id').replace('p','');v=planet.data[id];
		text='<div><img src="'+path+'/common/img/planets/'+v.type+'.png" width="100" height="100" /></div>Bonus';
		// @todo add planet immage
		for(key in v.bonus) {
			if (key.match(/cost/)) {
				if (v.bonus[key]<0) color='green';
				else color='red';
			}
			else {
				if (v.bonus[key]>=0) color='green';
				else color='red';
			}
			text+='<div>'+key+':<span style="color:'+color+';">'+v.bonus[key]+'%</span></div>';
		}
		return text;
	}
});
$(function(){
	planet.dialog=$('#dialog').dialog({
		autoOpen : false,
		modal : true,
		width : 300,
		buttons : {
			Add : function() {
				//ship.add();
				$(this).dialog("close");
			},
			Cancel : function() {
				$(this).dialog("close");
			}
		},
		close : function() {
			//ship.form[0].reset();
		}
	});
	$('#target input').bind('input',function(){
		//console.log('valore ',$(this).val());
		input=$('#target input,#target select');
		planet.dist({g:input[0].value,x:input[1].value,y:input[2].value});
	});
	$('#target select').bind('change',function(){console.log('not implemented ',$(this).val());});
});

var planet={
	data:new Array(),
	add:function() {
			
	},
	edit:function(pid){
		
	},
	del:function(pid){
		
	},
	dist:function(coord) {
		for(i in this.data) {
			v=this.data[i];
			dx=v.x-coord.x;
			dy=v.y-coord.y;
			pit=Math.sqrt(dx*dx+dy*dy);
			//todo implement distance of galaxy
			$('#d'+i).text(Math.round(pit*100)/100);
		}
	}
};