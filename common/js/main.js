$(function(){
	//support of details tag
	$('html').addClass($.fn.details.support ? 'details' : 'no-details');
	$('.no-details details').details();
	//**************
	$("ul#news").liScroll();
	$("button:not(.close):not(.edit):not(.add):not(.delete),input[type=submit]:not(.edit):not(.add):not(.delete)").button();
	$("button.edit").button({icons: {
        primary: "ui-icon-wrench"
    },
    text: false
    });
	$("button.close").button({icons: {
        primary: "ui-icon-circle-close"
    },
    text: false
    });
	$("button.add").button({icons: {
        primary: "ui-icon-plus"
    },
    text: false
    });
	$("button.delete").button({icons: {
        	primary: "ui-icon-close"
    	},
    		text: false
    });;
    $('option').addClass('ui-widget-content');
    $('select').addClass('ui-state-default ui-corner-all')
    	.bind('mouseover',function(){
    		$(this).addClass('ui-state-hover');
    	}).bind('mouseout',function(){
    		$(this).removeClass('ui-state-hover');
    	});
});
loader={
	n:0,
	show:function(){
		this.n++;
		$('#loader').show();
	},
	hide:function(){
		this.n--;
		if (!this.n) $('#loader').hide();
	}
};
/**
 * 
 * @param url
 * @param data
 * @param callback
 * @param reload
 */
function request(url,data,callback,reload) {
	loader.show();
	$.ajax({
		url:path+url,
		type:'post',
		dataType : "json" ,
		success :function(data){
			loader.hide();
			if (!data.success) {
				alert(data.message);
				if (reload) location.reload();
			}
			if (callback) callback(data);
		},
		'data':data,
		error:function(r,s,e ) {
			loader.hide();
			alert(e+' on call:'+this.url+' whit data '+this.data);
		}
	});
}
jQuery.validator.addMethod('regExpr',function(value,element,param){
	return this.optional(element) || param.test(value);
},jQuery.validator.format("don't match the expression {0}"));