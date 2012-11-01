// ==UserScript==
// @name           Cometetool
// @namespace      http://toolraider.altervista.org
// @description    questo script permette di utilizzare il comete tool direttamente in imperion
// @include        http://u*.imperion.*/*
// @include        http://beta.imperion.*/*
// @require        http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js
// @author         Pagliaccio
// @version        1.0
// ==/UserScript==

var user="";
var pass="";
var recycleUrl="";

GM_registerMenuCommand("imposta nome utente e password", initscript);
GM_registerMenuCommand("registrati al cometetool", registra);

user=GM_getValue("user");
pass=GM_getValue("pass");
if ((user==null)||(pass==null)) initscript();

InitListener("mapDialog_cometInfo","style",cometView);

function registra()
{
    GM_openInTab( "http://toolraider.altervista.org" );
}

function initscript()
{
    user=prompt("inserire l'username del cometetool (registrati qui se non sei iscritto http://toolraider.altervista.org)",GM_getValue("user"));
    pass=prompt("inserire la password",GM_getValue("pass"));
    GM_setValue("user", user);
    GM_setValue("pass", pass);
    
}

function query()
{
    comet=$("#headlinePlanetName").text();
    id_comet=comet.replace("-","");
    id_comet=id_comet.replace("K","");
    id_comet=id_comet.replace("-","");
    $("#load").attr("src","http://toolraider.altervista.org/images/loading.gif");
    GM_xmlhttpRequest ({
			method: "GET",
			url: "http://toolraider.altervista.org/cometa.php?id="+id_comet+"&nome="+user+"&pass="+pass,
			headers: {"User-agent": "Mozilla/5.0", "Accept": "text/html"},
			onload: function (response) {
                risposta=response.responseText;
                type=risposta.substr(0,1);
                msg=risposta.substr(1);
                //alert('type='+type+"; msg="+msg+";risposta="+risposta+".") debug risposta server
                switch (type)
                {
                    case "1" : $("#load").attr("src","http://toolraider.altervista.org/images/del.gif");$("#planetDescription").text(msg);break;
                    case "2" : location.href=recycleUrl;break;
                    default : alert(msg);
                }                
			}	
		});
}

function InitListener (div,attrName,onFire) {
				var ListenerForElemet = document.getElementById(div);
				function ChargeFireEvent(eventElement,attribute) {			
					var attr = eventElement.getAttributeNode(attribute);
					if(attr == null) {
						eventElement.setAttribute('style', '');
						attr = eventElement.getAttributeNode(attribute);
					}
					var eventObject = document.createEvent('MutationEvent');
					eventObject.initMutationEvent("DOMAttrModified", true, true, attr, null, attr.value, attribute, MutationEvent.MODIFICATION);
					eventElement.dispatchEvent(eventObject);
				}
				ChargeFireEvent(ListenerForElemet, attrName);
				ListenerForElemet.addEventListener ('DOMAttrModified', onFire, false);  // Firefox, Opera
			}



function cometView()
{
    $('<img id="load" src="" >').appendTo("#headlinePlanetName");
    recycleUrl = $('#recycle').attr('href');
    $('#recycle').attr('href','javascript:;');
    $('#recycle').click(query);
}
