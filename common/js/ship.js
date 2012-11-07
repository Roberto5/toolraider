$(function() {
        var tabTitle = $( "#tab_title" ),
            tabContent = $( "#tab_content" ),
            tabTemplate = "<li><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close'>Remove Tab</span></li>",
            tabCounter = 2;
 
        var tabs = $( ".tabs" ).tabs({
        	//event: "mouseover",
        	collapsible: true,
        });
 
        // modal dialog init: custom buttons and a "close" callback reseting the form inside
        var dialog = $( "#dialog" ).dialog({
            autoOpen: false,
            modal: true,
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
            var label = tabTitle.val() || "Tab " + tabCounter,
                id = "tabs-" + tabCounter,
                li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) ),
                tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";
 
            tabs.find( ".ui-tabs-nav" ).append( li );
            tabs.append( "<div id='" + id + "'><p>" + tabContentHtml + "</p></div>" );
            tabs.tabs( "refresh" );
            tabCounter++;
            //ship.add();
        }
 
        // addTab button: just opens the dialog
        $( "#add_tab" )
            .button()
            .click(function() {
                dialog.dialog( "open" );
            });
 
        // close icon: removing the tab on click
        $( "#tabs span.ui-icon-close" ).live( "click", function() {
        	//ship.del(ID_PLANET)
            var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
            $( "#" + panelId ).remove();
            tabs.tabs( "refresh" );
        });
    });