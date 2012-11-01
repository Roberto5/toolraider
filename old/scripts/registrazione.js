function control()
{
    sub=true;
    if (document.reg.mail.value.match(/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/gi)==null) {alert("email non valida");sub=false;}
    if (document.reg.user.value.length<4) {alert("username troppo corto");sub=false;}
    if (document.reg.pass.value.length<4) {alert("password troppo corta");sub=false;}
    if (document.reg.user.value.length>15) {alert("username troppo lungo");sub=false;}
    if (document.reg.pass.value.length>15) {alert("password troppo lunga");sub=false;}
    if (!document.reg.name.value) {alert("inserisci il nome");sub=false;}
    if (document.reg.pass.value!=document.reg.pass2.value) {alert("le due password inserite non coincidono");sub=false;}
    if (document.reg.type.value==3) {
        if (document.reg.razza.value==0) {alert("seleziona la razza");sub=false;}
        if (!document.reg.madre.value) {alert("inserisci il nome del pianeta madre");sub=false;}
        if ((document.reg.server.value==document.reg.server.value.match(/u[0-9]{1,2}\.imperion\.[a-z]{2,3}/))==null) {alert("Url server errato: es: s1.imperion.it");sub=false;}
    }
    return sub;
}
function trim(stringa){
    while (stringa.substring(0,1) == ' '){
        stringa = stringa.substring(1, stringa.length);
    }
    while (stringa.substring(stringa.length-1, stringa.length) == ' '){
        stringa = stringa.substring(0,stringa.length-1);
    }
    return stringa;
}

function jcontrol(oggetto,opt)
{
   
    switch (opt)
    {
        case "username": 
            if (document.reg.user.value.length<4) {alert("username troppo corto");$("#user").html("<img src=\"images/del.gif\">");return 0;}
            if (document.reg.user.value.length>15) {alert("username troppo lungo");return 0;}
            $("#user").html("<img src=\"images/loading.gif\">");
            $.ajax({
                url : "query.php",
                type : "post" ,
                data : 'action=reg&cerca=user&valore='+oggetto.value,
                success : function (data,stato) {
                $("#user").html(data);
                if (data.trim()=='<img src="images/del.gif">') oggetto.value="";
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
            });
            break;
        case "mail":
            if (document.reg.mail.value.match(/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$/gi)==null) {alert("email non valida");$("#mail").html("<img src=\"images/del.gif\">");return 0;}
            $("#mail").html("<img src=\"images/loading.gif\">");
            $.ajax({
                url : "query.php",
                type : "post" ,
                data : 'action=reg&cerca=mail&valore='+oggetto.value,
                success : function (data,stato) {
                $("#mail").html(data);
                if (data.trim()=='<img src="images/del.gif">') oggetto.value="";
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
            });
            break;
        case "type":
            $("#nome").html("<img src=\"images/loading.gif\">");
            $.ajax({
                url : "query.php",
                type : "post" ,
                data : 'action=reg&cerca=nome&valore='+oggetto.value,
                success : function (data,stato) {
                if (data=='<select name=\"type\"><option value=\"1\">Sharer</option></select>') disableinput();
                $("#type").html(data);
                $("#nome").html("");
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
            });
            break;
    }
}
function sel_type(type)
{
    if (type==1) disableinput();
    else {
        $("#razza").css("display","block");//attr("disabled","false");
        $("#madre").css("display","block");//attr("disabled","false");
        $("#server").css("display","block");//attr("disabled","false");
    }
}
function disableinput()
{
    $("#razza").css("display","none");//attr("disabled","true");
    $("#madre").css("display","none");//attr("disabled","true");
    $("#server").css("display","none");//attr("disabled","true");
}