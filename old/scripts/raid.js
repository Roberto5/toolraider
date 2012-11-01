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
        if (document.link.opt.value!="") document.modulo.action="open.php?opt="+document.link.opt.value;
		else document.modulo.action="open.php";
        document.modulo.target="_blanc";
		document.modulo.submit();
        nextl();	
	}
	function nextl()
	{
		document.link.index.value=document.link.index.value*1+document.link.step.value*1;
        setind(document.link.index.value,document.link.listname.value,document.link.listtype.value);
		richiesta(document.link.listname,document.link.listtype.value,document.link.index.value,'',document.link.step.value,document.link.opt.value);
    }
	function backl()
	{
		document.link.index.value=document.link.index.value*1-document.link.step.value*1;
        setind(document.link.index.value,document.link.listname.value,document.link.listtype.value);
		richiesta(document.link.listname,document.link.listtype.value,document.link.index.value,'',document.link.step.value,document.link.opt.value);
    }
    function setind(i,n_list,list)
    {
        switch(list)
        {
            case "activ": l="0";break;
            case "inactiv": l="1";break;
            default : table="us_list2";break;
        }
        $.ajax({
            url : "query.php",
            data : "action=setind&ind="+i+'&n_list='+n_list+'&l='+ l ,
            async : false ,
            timeout: 1000 ,
            type : "POST" ,
            success : function (data,stato) {
                document.link.index.value=data;
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
    }
    function aggiornadata(id,list)
    {
        $.ajax({
            url : "query.php",
            type : "POST" ,
            data : "action=agg&id="+id+"&table="+list ,
            success : function (data,stato) {
                $("#data"+id).text(data);
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
        document.link.step.selectedIndex=0;
        nextl();
    }
    function changenave()
    {
        if (document.link.tipo_nave.selectedIndex!=0) document.link.opt.value="raid"+razza+document.link.tipo_nave.value+document.link.num_nave.value;
        else {document.link.opt.value="";document.link.num_nave.value=0;}
        aggiorna();
    }
    function aggiorna()
    {
        richiesta(document.link.listname,document.link.listtype.value,document.link.index.value,'',document.link.step.value,document.link.opt.value)
    }
    function inizializza(tipo)
    {
        document.link.tipo_nave.selectedIndex=parseInt(tipo);
    }
    function stepl(n_list,list)
    {
        switch(list)
        {
            case "activ": table="us_list";break;
            case "inactiv": table="us_list2";break;
            default : table="us_list2";break;
        }
        $.ajax({
            url : "query.php" ,
            async : false ,
            timeout: 1000 ,
            type : "POST" ,
            data : 'action=step&n_list='+n_list+'&table='+ table ,
            success : function (data,stato) {
                $("#step").html(data);
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
        $.ajax({
            url : "query.php" ,
            async : false ,
            timeout: 1000 ,
            type : "POST" ,
            data : 'action=index&n_list='+n_list+'&table='+ table ,
            success : function (data,stato) {
                document.link.index.value=data;
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
        $.ajax({
            url : "query.php" ,
            type : "POST" ,
            data : "action=up&list="+document.link.listtype.selectedIndex+"&n_list="+document.link.listname.selectedIndex ,
            success : function (data,stato) {
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
    }
    function richiesta(oggetto,list,start,sort,step,opt)
    {
        switch(list)
        {
            case "activ": table="us_list";break;
            case "inactiv": table="us_list2";break;
            default : table="us_list2";break;
        }
        n_list=oggetto.value;
        $("#visualizza").html("<img src=\"images/loading.gif\">");
        $.ajax({
            url : "query.php",
            type : "POST" ,
            data : 'action=list&step='+step+'&list='+list+'&n_list='+n_list+'&start='+start+'&sort='+sort+'&raid=raid&opt='+opt ,
            success : function (data,stato) {
                testo=data.split("@");
                $("#visualizza").html(testo[0]);
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
    }
    function changemenuplanet(table)
    {
        $.ajax({
            url : "query.php" ,
            data : "action=n_list&table="+table ,
            async : false ,
            timeout: 2000 ,
            type : "POST" ,
            success : function (data,stato) {
                $("#listname").html(data);
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
        $.ajax({
            url : "query.php" ,
            type : "POST" ,
            data : "action=up&list="+document.link.listtype.selectedIndex+"&n_list="+document.link.listname.selectedIndex ,
            success : function (data,stato) {
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
    }