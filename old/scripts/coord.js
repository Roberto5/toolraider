naveT="v";
navet="v";
navex="v";
function selnave()
{
    if (document.coord.nave.value.substr(3)=="v") {testo="la più veloce";tipo="v";}
    if (document.coord.nave.value.substr(3)=="l") {testo="la più lenta";tipo="l";}
    raz=document.coord.nave.value.substr(0,3);
    if ((document.coord.nave.value.substr(3)!="l")&&(document.coord.nave.value.substr(3)!="v")) {
        tipo=document.coord.nave.value.match(/[0-9]{1,2}/gi);
        tipo=tipo[0];
    }
    switch (raz)
    {
        case "Tit": raz=1;naveT=tipo;break;
        case "Ter": raz=2;navet=tipo;break;
        case "Xen": raz=3;navex=tipo;break;
        case "all": naveT=tipo;navet=tipo;navex=tipo;
    }
    
    id= new Array('#tit','#ter','#xen');
    if (raz=="all") {$("#tit").text(testo);$("#ter").text(testo);$("#xen").text(testo);} 
    else $(id[raz-1]).text(navi[raz][tipo]);
}
function dispayopt()
{
    document.getElementById('opt').style.display="block";
    $(".tit").css("display","none");
    $(".ter").css("display","none");
    $(".xen").css("display","none");
    if (document.coord.coord.value=="ally") {a=true;
    $(".tit").css("display","block");
    $(".ter").css("display","block");
    $(".xen").css("display","block");} else a=false;
    changetype(document.coord.type.value);
    //aggoungo navi titane terrestri e xen
    
    oGroup=new Array();
    for (j=1;j<=3;j++)
    {
        if ((a)||(razza==j)) {
            oGroup[j] = document.createElement('optgroup');
            switch (j)
            {
                case 1: label="Titani";break;
                case 2: label="Terrestri";break;
                case 3: label="Xen";break;
            }
            oGroup[j].label = label;
            oOption = new Array();  
            for(i=1;i<=12;i++)
            {
                oOption[i] = document.createElement('OPTION');
                oOption[i].value = label.substr(0,3)+i;
                oOption[i].text = navi[j][i];
                oGroup[j].appendChild(oOption[i]);
                //el.push({text: navi[j][i], value: i});
            }
        }
    }
    var oSelect = document.coord.nave;
    oSelect.innerHTML="";
    opt1=document.createElement('OPTION');
    opt1.value="allv";
    opt1.text="la più veloce";
    opt2=document.createElement('OPTION');
    opt2.value="alll";
    opt2.text="la più lenta";
    oSelect.appendChild(opt1);
    oSelect.appendChild(opt2);
    for (j=1;j<=3;j++)
        if (oGroup[j]!=null) oSelect.appendChild(oGroup[j]);
}
done=true;
pianeti=new Array();
function getplanet(bool)
{
    if (document.coord.coord.value=="ally") find=1; else find=0;
    if (done) {
        $.ajax({
            url : "query.php",
            data : "action=planet&find="+find ,
            type : "POST" ,
            success : function (data,stato) {
                dati=data.split(",");
                $("#target").html(dati[0]);
                pianeti[0]=new  Array();
                pianeti[0]['x']=0;
                pianeti[0]['y']=0;
                pianeti[0]['g']=0;
                pianeti[0]['player']=" ";
                for (i=1;i<dati.length;i++)
                {
                    el=dati[i].split(" ");
                    pianeti[i]=new  Array();
                    pianeti[i]['x']=el[0];
                    pianeti[i]['y']=el[1];
                    pianeti[i]['g']=el[2];
                    pianeti[i]['player']=el[3];
                    pianeti[i]['nome']=el[4];
                    pianeti[i]['razza']=el[5];
                }
                if (bool) showp();
            },
            error : function (richiesta,stato,errori) {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
            }
        });
        done=false;
    }
    
}
function changetype(tipo)
{
    if (tipo=="true") {
        document.coord.target.disabled=false;
        getplanet(false);
        
    }
    else {
        document.coord.target.disabled=true;
    }
}
function addcoord(oggetto)
{
    document.coord.x.value=pianeti[oggetto.selectedIndex]['x'];
    document.coord.y.value=pianeti[oggetto.selectedIndex]['y'];
    document.coord.g.selectedIndex=pianeti[oggetto.selectedIndex]['g']-1;
}
function instable(riga)
{
    testo="<tr>\n";
    for(j=0;j<riga.length;j++)
        testo+="<td>"+riga[j]+"</td>\n";
    testo+="</tr>\n"
    return testo;
}
function showp()
{
    if (done) {getplanet(true);return 0;}
    testo="";
    riga=new Array('Distanza','Pianeta','giocatore','coordinate','durata viaggio','consumo','nave');
    tabella=new Array();
    testo="<center><table>\n"+instable(riga);
    tabella[0]=new Array(0,0,0);
    for(i=1;(i<pianeti.length);i++)
    {
        x=(pianeti[i]['x']-parseInt(document.coord.x.value))*(pianeti[i]['x']-parseInt(document.coord.x.value));
        y=(pianeti[i]['y']-parseInt(document.coord.y.value))*(pianeti[i]['y']-parseInt(document.coord.y.value));
        pianeti[i]['distanza']=Math.round(Math.sqrt(x+y))+5;
        ricerche(pianeti[i]['player']);
        switch(pianeti[i]['razza'])
        {
            case "1": nave=naveT;break;
            case "2": nave=navet;break;
            case "3": nave=navex;break;
        }
        dur=0;
        if (nave=="v") {
            dur=durata(1,pianeti[i]['razza'],pianeti[i]['distanza']);
            serb=serbatoio(1,pianeti[i]['razza'],pianeti[i]['distanza'])
            cons=consumo(1,pianeti[i]['razza'],pianeti[i]['distanza']);
            if (serb<cons) dur=0;
            for (j=2;j<=12;j++)
            {
                dur2=durata(j,pianeti[i]['razza'],pianeti[i]['distanza']);
                serb2=serbatoio(j,pianeti[i]['razza'])
                cons2=consumo(j,pianeti[i]['razza'],pianeti[i]['distanza']);
                if (serb2<cons2) dur2=0;
                if (((dur==0)&&(dur2!=0))||(dur2<dur)) {dur=dur2;serb=serb2;cons=cons2;}
            }
        }
        if (nave=="l") {
            for (j=2;j<=12;j++)
            {
                dur2=durata(j,pianeti[i]['razza'],pianeti[i]['distanza']);
                serb2=serbatoio(j,pianeti[i]['razza'])
                cons2=consumo(j,pianeti[i]['razza'],pianeti[i]['distanza']);
                if (serb2<cons2) dur2=0;
                if (dur2>dur) {dur=dur2;serb=serb2;cons=cons2;}
            }
        }
        if ((nave!="v")&&(nave!="l")) {
            cons=consumo(nave,pianeti[i]['razza'],pianeti[i]['distanza']);
            serb=serbatoio(nave,pianeti[i]['razza'],pianeti[i]['distanza']);
            dur=durata(nave,pianeti[i]['razza'],pianeti[i]['distanza']);
            if (serb<cons) dur=0;
        }
        if (dur!=0) tabella[i]=new Array(pianeti[i]['distanza'],pianeti[i]['nome'],pianeti[i]['player'],pianeti[i]['g']+"|"+pianeti[i]['x']+"|"+pianeti[i]['y'],dur,cons,nave);
    }
    tabella.sort(function (a,b){
        return a[0]-b[0];
    })
    for(i=1;(i<tabella.length)&&(i<document.coord.step.value);i++)
        testo+=instable(tabella[i]);
    testo+="</table></center>";
    $("#visualizza").html(testo);
}
function consumo(nave,raz,dist)
{
    if (dist==5) return 0.5;
    if (raz==3) {
        switch (nave)
        {
            case 1:
            case 2:
            case 8:
            case 11:
            case 12:tipo="civ";break;
        
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 9:
            case 10:tipo="mil";break;
        }
        consumo=navi_xen[nave]['consumo']*((100-5*ricerca['consumo_'+tipo])/100);
    }
    if (raz==2) {
        switch (nave)
        {
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:tipo="mil";break;
            
            case 1:
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:tipo="civ";break;
            
        }
        consumo=navi_ter[nave]['consumo']*((100-5*ricerca['consumo_'+tipo])/100);
    }
    if (raz==1) {
        switch (nave)
        {
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:tipo="mil";break;
            
            case 1:
            case 9:
            case 10:
            case 11:
            case 12:tipo="civ";break;
            
        }
        consumo=navi_tit[nave]['consumo']*((100-5*ricerca['consumo_'+tipo])/100);
    }
    return (dist-5)*consumo;
}
function serbatoio(nave,raz)
{
    if (raz==3) {
        switch (nave)
        {
            case 1:
            case 2:
            case 8:
            case 11:
            case 12:tipo="civ";break;
        
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 9:
            case 10:tipo="mil";break;
        }
        return navi_xen[nave]['serbatoio']*((1+10*ricerca['serbatoio_'+tipo])/100);
    }
    if (raz==2) {
        switch (nave)
        {
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:tipo="mil";break;
            
            case 1:
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:tipo="civ";break;
            
        }
        return navi_ter[nave]['serbatoio']*((1+10*ricerca['serbatoio_'+tipo])/100);
    }
    if (raz==1) {
        switch (nave)
        {
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:tipo="mil";break;
            
            case 1:
            case 9:
            case 10:
            case 11:
            case 12:tipo="civ";break;
            
        }
        return navi_tit[nave]['serbatoio']*((1+10*ricerca['serbatoio_'+tipo])/100);
    }
}
function durata(nave,raz,dist)
{
    if (raz==3) {
        switch (nave)
        {
            case 1:
            case 2:
            case 8:
            case 11:
            case 12:tipo="civ";break;
        
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 9:
            case 10:tipo="mil";break;
        }
        velocita=navi_xen[nave]['velocita']*((1+10*ricerca['propulsione_'+tipo])/100);
    }
    if (raz==2) {
        switch (nave)
        {
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:tipo="mil";break;
            
            case 1:
            case 8:
            case 9:
            case 10:
            case 11:
            case 12:tipo="civ";break;
            
        }
        velocita=navi_ter[nave]['velocita']*((1+10*ricerca['propulsione_'+tipo])/100);
    }
    if (raz==1) {
        switch (nave)
        {
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:tipo="mil";break;
            
            case 1:
            case 9:
            case 10:
            case 11:
            case 12:tipo="civ";break;
            
        }
        velocita=navi_tit[nave]['velocita']*((1+10*ricerca['propulsione_'+tipo])/100);
    }
    return dist/velocita;
}
ricerca=new Array();
function ricerche(nome)
{
    $.ajax({
        url : "query.php",
        data : "action=ricerca&nome="+nome+"&fields=propulsione_mil,consumo_mil,serbatoio_mil,propulsione_civ,consumo_civ,serbatoio_civ,fisica",
        async : false ,
        timeout: 1000 ,
        type : "POST" ,
        success : function (data,stato) {
                data=data.substr(0,data.length-1);
                vet=data.split(",");
                for(j=0;j<vet.length;j++)
                {
                    el=vet[j].split(".");
                    ricerca[el[0]]=el[1];
                }
        },
        error : function (richiesta,stato,errori) {
            alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
        }
    })
    
}