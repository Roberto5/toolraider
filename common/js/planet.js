$(document).tooltip({
	items:"td.planet_name",
	position:{
		my:"right-50 center",
		at:"rigth center"
	},
	content: function() {
		var e=$(this);
		id=e.attr('id').replace('p','');v=planet.data[id];
		text='<div>'+v.type+'</div>Bonus';
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
			text+='<div>'+key+':<span style="color:'+color+';">'+v.bonus[key]+'</span></div>';
		}
		return text;
	}
});

var planet={
	data:new Array(),
	add:function() {
			
	},
	edit:function(pid){
		
	},
	del:function(pid){
		
	}
};