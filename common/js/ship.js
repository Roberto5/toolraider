$(function() {
	ship.init();
});

var ship = {
	inedit : new Array(),
	tabTitle : null,
	tabContent : null,
	tabTemplate : null,
	tabs : null,
	dialog : null,
	form : null,
	init : function() {
		this.tabTitle = $("#tab_title");
		this.tabContent = $("#tab_content");
		this.tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>";
		// create a tabs
		this.tabs = $(".tabs").tabs({
			collapsible : true,
		});
		// modal dialog init: custom buttons and a "close" callback reseting the
		// form inside
		this.dialog = $("#dialogship").dialog({
			autoOpen : false,
			modal : true,
			width : 1000,
			buttons : {
				Add : function() {
					ship.add();
					$(this).dialog("close");
				},
				Cancel : function() {
					$(this).dialog("close");
				}
			},
			close : function() {
				ship.form[0].reset();
			}
		});

		// addTab form: calls addTab function on submit and closes the dialog
		this.form = this.dialog.find("form").submit(function(event) {
			event.preventDefault();
			ship.add();
			ship.dialog.dialog("close");
		});
		// actual addTab function: adds new tab using the input from the form
		// above

		// addTab button: just opens the dialog
		$("#add_tab").button().click(function() {
			ship.dialog.dialog("open");
		});
		this.tabs.find('form').live(
				'submit',
				function(event) {
					event.preventDefault();
					id = $(this).children()
							.attr('id').match(/i\d+p(\d+)/);
					console.log('close edit id ', id[1]);
					ship.edit(id[1]);

				});
		// close icon: removing the tab on click
		$(".tabs span.ui-icon-close").live("click", function() {
			var panel=$(this).closest("li");
			var panelId = panel.attr("aria-controls");
			ship.del(panelId,panel);
		});
		$('button.ship.edit').live('click', function(event) {
			pid = this.id.replace('edit', '');
			console.log('id ', pid);
			ship.edit(pid);
		});
	},
	/**
	 * edit a table
	 * 
	 * @param pid
	 */
	edit : function(pid) {
		if (this.inedit[pid]) {
			this.inedit[pid] = false;
			$('#planet' + pid + ' button').button('option', 'icons', {
				primary : "ui-icon-wrench"
			});
			input = $('#planet' + pid + ' input').removeClass('ship').addClass(
					'readonly');
			$('#planet' + pid + ' span.ship').removeClass('ship').addClass(
					'readonly');
			diff = new Array();
			edit = false;
			s=new Array();
			ka=new Array();
			for (key in input) {
				if (key == 'length')
					break;
				v = $(input[key]);
				n = v.val();
				input.eq(key).parent()[0].reset();
				after = parseInt(v.attr('value'));
				before = parseInt(n);
				v.attr('value', n);
				diff[key] = before - after;
				id = v.attr('id').replace('i', 't');
				$('span#' + id).text(parseInt(n));
				if (diff[key] != 0) {
					edit = true;
					s.push(before);
					ka.push(key);
				}
			}
			// ajax call
			
			
			if (edit) {
				request('/shiptool/update/pid/'+pid,{'ship':s,'key':ka},function(data){
						ship.updateTot(diff);
				},true);
			}
			
		} else {
			this.inedit[pid] = true;
			$('#planet' + pid + ' input').removeClass('readonly').addClass(
					'ship');
			$('#planet'+pid+' span:not(.ui-icon,.ui-button-text)').removeClass('readonly')
					.addClass('ship');
			$('#planet' + pid + ' button').button('option', 'icons', {
				primary : 'ui-icon-check'
			});
		}
	},
	/**
	 * aggiorna il totale
	 * 
	 * @param Array
	 *            diff
	 */
	updateTot : function(diff) {
		span = $('div.tabs div:eq(0) span');
		for (i in diff) {
			if (i == 'length')
				break;
			t = parseInt(span.eq(i).text()) + parseInt(diff[i]);
			span.eq(i).text(t);
		}
	},
	add : function() {
		var label = this.tabTitle.find('option:selected').text(), id = "planet"
				+ this.tabTitle.val(), li = $(this.tabTemplate.replace(
				/#\{href\}/g, "#" + id).replace(/#\{label\}/g, label)), tabContentHtml = this.tabContent
				.html();
		tabContentHtml = tabContentHtml.replace(/"ship"/gi, '"readonly"');
		tabContentHtml = tabContentHtml.replace('table class="readonly"',
				'table class="ship"');
		input = this.tabContent.find('input');
		diff=[];
		for (key in input) {
			if (key == 'length')
				break;
			v = $(input[key]);
			diff.push(v.val());
			p = new RegExp('<span id="t' + key + 'p0" class="readonly">[0-9]+');
			pid = id.replace('planet', '');
			rep = '<span id="t' + key + 'p' + pid + '" class="readonly">'
					+ v.val();
			tabContentHtml = tabContentHtml.replace(p, rep);
			p = new RegExp('id="i' + key + 'p0" value="[0-9]+');
			rep = 'id="i' + key + 'p' + pid + '" value="' + v.val();
			tabContentHtml = tabContentHtml.replace(p, rep);
			//tabContentHtml = tabContentHtml.replace('edit0', 'edit' + pid);
			v.val(0);
		}
		title=tabContentHtml.match(/button[\w\s="-]+title="(\w+)"/);
		tabContentHtml = tabContentHtml.replace(/<button[\s\w="-><]+button>/,
				'<button id="edit'+pid+'" class="edit ship">'+title[1]+'</button>');
		si=this.tabTitle[0].selectedIndex;
		request('/shiptool/add/pid/'+pid,{'ship':diff},function(data){
			ship.tabs.find(".ui-tabs-nav").append(li);
			ship.tabs.append("<div id='" + id + "'>" + tabContentHtml + "</div>");
			ship.tabs.tabs("refresh");
			//update totale
			ship.updateTot(diff);
			//remove planet from list
			ship.tabTitle[0].remove(si);
			$("#"+id+" button").button({icons: {
		        primary: "ui-icon-wrench"
		    },
		    text: false
		    });
		});
	},
	del:function(panelId,panel) {
		pid=panelId.replace('planet','');
		input=$('#planet'+pid+' input');
		diff=[];
		for (key in input) {
			if (key == 'length')
				break;
			v = $(input[key]);
			diff.push(-parseInt(v.val()));
		}
		this.updateTot(diff);
		//add planet to list
		request('/shiptool/del/pid/'+pid,null,function(data){
			ship.tabTitle[0].options[ship.tabTitle[0].options.length]=new Option($('a[href=#planet'+pid+']').text(),pid);
			panel.remove();
			$("#" + panelId).remove();
			ship.tabs.tabs("refresh");
		});
		
		//ajax call
	}
};