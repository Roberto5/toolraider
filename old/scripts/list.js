var b=false;
        function aggiornadata(id,list)
        {
            $.ajax({
                url : "query.php",
                data : "action=agg&id="+id+"&table="+list ,
                type : "POST" ,
                success : function (data,stato) {
                    $("#target").text(data);
                },
                error : function (richiesta,stato,errori) {
                    alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
                }
            });
            
        }
		function seltt(oggetto)
		{
			if (oggetto.checked) {
				with (document.modulo) {
					for (var i=0; i < elements.length; i++) {
						if (elements[i].type == 'checkbox') elements[i].checked = true;
					}
				}
			}
			else { with (document.modulo) 
				{
				for (var i=0; i < elements.length; i++) {
					if (elements[i].type == 'checkbox' ) elements[i].checked = false;
				}
				}
			}

		}	
		function openl(bool)
		{
            if (bool) {document.modulo.all.checked=true;seltt(document.modulo.all);}
			document.modulo.action="open.php";
            document.modulo.target="_blanc";
            document.modulo.submit();	
		}
		function control()
		{
			ok=true;
			var testo=document.addlink.testo.value;
			if (testo.length<8) {alert("lunghezza minima 8 caratteri!!");ok=false;}
			if (testo.match(/[0-9]{8}/gi)==null) {alert("id errato!");ok=false;}
			if (ok) document.addlink.submit();
		}
        function control2(oggetto)
        {
            oggetto.value=oggetto.value.replace(/[^0-9]{8}/g,"");
        }
        function estrai()
        {
            
            var opt=document.addlink.text.value;
            var txt=document.addlink.testo.value;
            txt=txt.replace(/P[0-9]{8,9}/gi,"");
            txt=txt.match(/[0-9]{8,9}/gi);
            for (i=0;i<txt.length;i++)
            {
                txt[i]+=opt;
            }
            document.addlink.testo.value=txt.join("\n");
        }
        function richiesta(oggetto,list,start,sort,step,oggi)
        {
            n_list=oggetto.value;
            $("#visualizza").html("<img src=\"images/loading.gif\">");
            $.ajax({
                url : "query.php?",
                type : "POST" ,
                data : 'action=list&step='+step+'&list='+list+'&n_list='+n_list+'&start='+start+'&sort='+sort+'&oggi='+oggi,
                success : function (data,stato) {
                    testo=data.split("@");
                    $("#visualizza").html(testo[0]);
                    $("#ris").text(testo[1]);
                },
                error : function (richiesta,stato,errori) {
                    alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
                }
            });
            
        }
    function changemenuplanet(menu)
    {
        switch(menu)
        {
            case "activ": table="us_list";break;
            case "inactiv": table="us_list2";break;
            default : table="us_list2";break;
        }
        $.ajax({
            url : "query.php" ,
            type : "POST" ,
            data : "action=n_list&table="+table ,
            async : false ,
            timeout: 2000 ,
            success : function (data,stato) {
                $("#listname").html(data);
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
    }