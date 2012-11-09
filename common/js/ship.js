$(function() {
        var tabTitle = $( "#tab_title" ),
            tabContent = $( "#tab_content" ),
            tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>";
        //crea la tabs
        var tabs = $( ".tabs" ).tabs({
        	collapsible: true,
        });
 
        // modal dialog init: custom buttons and a "close" callback reseting the form inside
        var dialog = $( "#dialog" ).dialog({
            autoOpen: false,
            modal: true,
            width:1000,
            buttons: {
                Add: function() {
                    addTab();
                    $( this ).dialog( "close" );
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            },
            close: function() {
                form[ 0 ].reset();
            }
        });
 
        // addTab form: calls addTab function on submit and closes the dialog
        var form = dialog.find( "form" ).submit(function( event ) {
            addTab();
            dialog.dialog( "close" );
            event.preventDefault();
        });
        // actual addTab function: adds new tab using the input from the form above
        function addTab() {
            var label = tabTitle.find('option:selected').text(),
                id = "planet" + tabTitle.val(),
                li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
                tabContentHtml = tabContent.html();
            tabContentHtml=tabContentHtml.replace(/"ship"/gi,'"readonly"');
            tabContentHtml=tabContentHtml.replace('table class="readonly"','table class="ship"');
            input=tabContent.find('input');
        	for (key in input) {
        		if (key=='length') break;
        		v=$(input[key]);
        		p=new RegExp('<span id="t'+key+'p0" class="readonly">[0-9]+');
        		pid=id.replace('planet','');
        		rep='<span id="t'+key+'p'+pid+'" class="readonly">'+v.val();
        		tabContentHtml=tabContentHtml.replace(p,rep);
        		p=new RegExp('id="i'+key+'p0" value="[0-9]+');
        		rep='id="i'+key+'p'+pid+'" value="'+v.val();
        		tabContentHtml=tabContentHtml.replace(p,rep);
        		/*v.attr('value',v.val());
        		v.removeClass('ship');
        		v.addClass('readonly');
        		spanid=v.attr('id').replace('i','t');
        		$('#'+spanid).text(v.val()).removeClass('ship').addClass('readonly');*/
        	}
            tabs.find( ".ui-tabs-nav" ).append( li );
            tabs.append( "<div id='" + id + "'><p>" + tabContentHtml + "</p></div>" );
            tabs.tabs( "refresh" );
            //ship.add();
        }
 
        // addTab button: just opens the dialog
        $( "#add_tab" )
            .button()
            .click(function() {
                dialog.dialog( "open" );
            });
        tabs.find('form').live('submit',function(event){
        	console.log('close edit');
        	event.preventDefault();
        });
        // close icon: removing the tab on click
        $( ".tabs span.ui-icon-close" ).live( "click", function() {
        	//ship.del(ID_PLANET)
            var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
            $( "#" + panelId ).remove();
            tabs.tabs( "refresh" );
        });
    });