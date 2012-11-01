    function showforms(formname, formtext, refresh_site) {
    	if (document.getElementById(formname).style.display == "none") {
    		document.getElementById(formname).style.display = "";
    		document.getElementById(formtext).disabled = 1;
    	}
    	else {
			document.getElementById(formname).style.display = "none";
    		document.getElementById(formtext).disabled = 0;
    	}
    }

    function switchtroops() {
    	if (document.getElementById("unitManual").style.display == "none") {
    		document.getElementById("unitManual").style.display = "";
    		document.getElementById("unitAuto").style.display = "none";
    	} else {
    		document.getElementById("unitManual").style.display = "none";
    		document.getElementById("unitAuto").style.display = "";
    	}
    }

    function askdel(tid, site) {
        Check = confirm("Sei sicuro di voler cancellare?");
        if (Check == true) window.location.href = site + '?del=' + tid;
    }
	
function editf(i,pid)
{
    document.edit.t1.value=pianeti[i][1];
    document.edit.t2.value=pianeti[i][2];
    document.edit.t3.value=pianeti[i][3];
    document.edit.t4.value=pianeti[i][4];
    document.edit.t5.value=pianeti[i][5];
    document.edit.t6.value=pianeti[i][6];
    document.edit.t7.value=pianeti[i][7];
    document.edit.t8.value=pianeti[i][8];
    document.edit.t9.value=pianeti[i][9];
    document.edit.t10.value=pianeti[i][10];
    document.edit.t11.value=pianeti[i][11];
    document.edit.t12.value=pianeti[i][12];
    document.getElementById("unitManual").style.display = "";
    document.getElementById("unitAuto").style.display = "none";
    ind=0;
    for (i=0;document.edit.pianeta[i];i++)
    {
        if (pid==document.edit.pianeta[i].value) ind=i;
    }
    document.edit.pianeta.selectedIndex=ind;
    
}	
    
function control(oggetto)
{
    oggetto.value=oggetto.value.replace(/k/g,"000");
    oggetto.value=oggetto.value.replace(/[^0-9]/g,"");
}

function autoins(oggetto)
{
    testo=oggetto.value;
    riga=testo.split("\t");
    navi=new Array();
    nav=new Array();g=0
    for(i=0;riga[i];i++)
    {
        if (riga[i].match(/Navi/)) {i++;j=1;
            bool=true;
            while(bool)
            {
                riga[i]=riga[i].replace(/\./gi,"");
                if (!navi[j]) navi[j]=0;
                if (riga[i]!=parseInt(riga[i])) bool=false; else {navi[j]+=riga[i]*1;j++;if (!g) g=i;}
                i++;
            }
        }
    }
    i=testo.indexOf("Plus");
    j=testo.indexOf("Base militare");
    pianeta=testo.substr(i,j);
    bool=false;
    for (i=0;(pianeti[i])&&(!bool);i++)
    {
        ind=pianeta.indexOf(pianeti[i]['nome']);
		if (ind>=0) {bool=true;i--;}
    }
    if (bool) {
        l=i;
        pid=pianeti[i]['pid'];
    }
    else {
        l=pianeti.length;
        pid=0;
    }
    pianeti[l]=new Array();
    for (i=1;i<=12;i++)
        pianeti[l][i]=navi[i];
    
    editf(l,pid);
}
