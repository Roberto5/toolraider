$(document).tooltip({
	items:"td.planet_name",
	position:{
		my:"right-50 center",
		at:"rigth center"
	},
	content: function() {
		var e=$(this);
		id=e.attr('id').replace('p','');v=planet.data[id];
		text='<div><img src="'+path+'/common/img/planets/'+planet.type[v.type]+'.png" width="100" height="100" /></div>Bonus';
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
			text+='<div>'+planet.bonus[key]+':<span style="color:'+color+';">'+v.bonus[key]+'%</span></div>';
		}
		return text;
	}
});
$(function(){
	planet.dialog=$('#dialogplanet').dialog({
		autoOpen : false,
		modal : true,
		width : 400,
		buttons : {
			Add : function() {
				planet.add();
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
	dialog:null,
	data:new Array(),
	bonus:null,
	type:null,
	add:function() {
			console.log('this',this);
			s=planet.dialog.find('select');
			i=planet.dialog.find('input');
			galaxy=s[0].value;
			type=s[1].value;
			name=i[0].value;
			x=i[1].value;
			y=i[2].value;
			bonus_name=[s[2].value,s[3].value,s[4].value,s[5].value,s[6].value];
			bonus_value=[i[3].value,i[4].value,i[5].value,i[6].value,i[7].value];
			request('/planet/add',{
				'galaxy':galaxy,
				'type':type,
				'name':name,
				'x':x,
				'y':y,
				'bonus_name':bonus_name,
				'bonus_value':bonus_value},function(data) {
					if (data.success) location.reload();
				}
			);
			planet.dialog.find('form')[0].reset();
	},
	edit:function(pid,go){
		if (go) {
			
		}
		else {
			this.dialog.dialog('open');
			i=this.dialog.find('input');
			s=this.dialog.find('select');
			s[0].selectedIndex=this.data[pid].galaxy;
			s[1].selectedIndex=this.data[pid].type;
			i[0].value=this.data[pid].name;
			i[1].value=this.data[pid].x;
			i[2].value=this.data[pid].y;
			j=2;
			for(k in this.data[pid].bonus) {
				s[j].selectedIndex=k;
				i[j+1].value=this.data[pid].bonus[k];
			}
		}
	},
	del:function(pid){
		request('/planet/delete/id/'+pid,null,function(data){
			$('#p'+pid).parent().remove();
		});
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