var editor;

function spoiler(oggetto)
{
    if (oggetto.style.display=="none") oggetto.style.display="block";
    else oggetto.style.display="none";
}

function editA(id)
{
	if ( editor )
		return;
    element=document.getElementById( "avviso"+id );
    if (id!="0")  {
        element.value=$( "#testo"+id ).html();
        $( "#testo"+id ).html("");
    }
	var config = {
            skin : 'office2003'
        };
	editor = CKEDITOR.replace( element, config );
}

function removeEditor(editor1)
{
	if ( !editor )
		return;
	// Retrieve the editor contents. In an Ajax application, this data would be
	// sent to the server or used in any other way.
    id2=editor1.name.substr(6,1);
    testo2=editor.getData();
    if (id2!='0') $( "#"+'testo'+id2 ).html(testo2);
	// Destroy the editor.
	editor.destroy();
	editor = null;
    $( "#"+editor1.name ).css("display","none");
    if (id2!='0') action2="modavviso"; else action2="addavviso";
    $.ajax({
        url : "query.php" ,
        data : {action: action2,id :id2, testo: testo2} ,
        type : "post" ,
        success : function (data,stato) {
            if (data) alert(data);
            if (action2=="addavviso") location.reload();
        } ,
        error : function (stato,errore) {
            alert("errore nella chiamata :"+errore);
        }
    });
}
function del(id2)
{
    $("#avviso"+id2).html("");
    $("#testo"+id2).html("");
    $("#conenent"+id2).html("");
    $.ajax({
        url : "query.php" ,
        data : {action: "delavviso",id :id2} ,
        type : "post" ,
        success : function (data,stato) {
            if (data) alert(data);
        } ,
        error : function (stato,errore) {
            alert("errore nella chiamata :"+errore);
        }
    });
}