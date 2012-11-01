// logger.js
function showopt(opt)
{
    document.getElementById('batle').style.display="none";
    document.getElementById('spy').style.display="none";
    document.getElementById('rici').style.display="none";
    switch (opt)
    {
        case "1" : document.getElementById('batle').style.display="block"; break;
        case "2" : document.getElementById('spy').style.display="block"; break;
        case "3" : document.getElementById('rici').style.display="block"; break;
    }
}

function loggerf(opt,salva)
{
    bool=true;
    switch (opt)
    {
        case "1" : batle(document.logger.testo,salva);bool=false; break;
        case "2" : spy(document.logger.testo,salva);bool=false; break;
        case "3" : rici(document.logger.testo,salva);bool=false; break;
        case "4" : calcola(document.logger.testo);bool=false; break;
        
    }
    testo=document.logger.testo.value;
    
    if (bool) {
        //cerco segni di un indebolimento del generatore
        i=testo.indexOf("Un generatore di scudo è stato indebolito del");
        j=testo.indexOf("il potere combattivo della flotta attaccante.");
        if ((i>=0)&&(j>=0)) {
            scudo=testo.substr(i,j);
            percentuali=scudo.match(/[0-9]{1,3}\.[0-9]{1}\%|[0-9]{1,3}\%/gi);
            if (percentuali) {
                if (percentuali[1]=="100%") {calcola(document.logger.testo);bool=false;}
            }
        }
        //cerco report di riciclaggio 
        i=testo.indexOf("Risorse raccolte dai detriti");
        if ((i>=0)&&(bool)) {
            rici(document.logger.testo,salva);bool=false;
        }
        //cerco se e un spyreport 
        i=testo.indexOf("Report di intelligence");
        if ((i>=0)&&(bool)) {
            spy(document.logger.testo,salva);bool=false;
        }
        //cerco batle report 
        i=testo.indexOf("attacca");
        if ((i>=0)&&(bool)) {
            batle(document.logger.testo,salva);bool=false;
        }
    }
    if (bool) document.logger.testo.value="report sconosciuto";
}
function rici(oggetto,salva)
{
    
}
function spy(oggetto,salva)
{
    testo=oggetto.value;
    // ricerca pianeta spiato
    i=testo.indexOf("Report di intelligence da");
    j=testo.indexOf("Ora");
    if ((i<0)||(j<0)) return 0;
    difensore=testo.substr(i+25,j-25-i);
    //ora
    F=testo.indexOf("Flotte");
    if (F<0) F=testo.indexOf("Truppe");
    D=testo.indexOf("Difesa");
    S=testo.indexOf("Scienza");
    E=testo.indexOf("Edifici");
    R=testo.indexOf("Risorse");
    next=testo.length;
    if (F>=0) next=F; else {
        if (D>=0) next=D; else {
            if (S>=0) next=S; else {
                if (E>=0) next=E; else {
                    if (R>=0) next=R;
                }
            }
        }
    }
    ora=testo.substr(j,next-j);
    sup=new Array();
    if (F>=1) {
        
        //flotta
        f=testo.indexOf("Flotta planetaria");
        if (f<0) return 0;
        next=testo.length;
        if (D>=0) next=D; else {
            if (S>=0) next=S; else {
                if (E>=0) next=E; else {
                    if (R>=0) next=R;
                }
            }
        }
        flotte=testo.substr(f,next-f);
        naviD=flotte.match(/([0-9]{1,3}\.[0-9]{3}\.[0-9]{3})|([0-9]{1,3}\.[0-9]{3})|([0-9]{1,3})/gi);
        razD=getrazza(flotte,"difensore");
        if (navi.length>12) {// presenza di supporti
            supp=navi.slice(12);
            navi=navi.slice(0,12);
            
            for (i=0;supp.length>12;i++)
            {
                sup[i]= new Array();
                sup[i]=supp.slice(0,12);
                supp=supp.slice(12);
                j=flotte.indexOf("Flotta planetaria",5);
                flotte=flotte.substr(j);
                sup[i]['razza']=getrazza(flotte,"supporto");
            }
        }
    }
    //difesa
    if (D>=0) {
        next=testo.length;
        if (S>=0) next=S; else {
            if (E>=0) next=E; else {
                if (R>=0) next=R;
            }
        }
        difese=testo.substr(D,next-D);	 	 	 	 	
        difese=difese.replace("Missile Intercettore 1","");
        difese=difese.replace("Missile Intercettore 2","");
        difese=difese.replace("Missile d'attacco 1","");
        difese=difese.replace("Missile d'attacco 2","");
        difese=difese.replace("Missile d'attacco 3","");
        difese=difese.replace("Missile d'attacco 4","");
        dif=difese.match(/([0-9]{1,3}\.[0-9]{3}\.[0-9]{3})|([0-9]{1,3}\.[0-9]{3})|([0-9]{1,3})/gi);
    }
    //scienza
    if (S>=0) {
        next=testo.length;
        if (E>=0) next=E; else {
            if (R>=0) next=R;
        }
        scienza=testo.substr(S,next-S);
        scienza=scienza.replace("Scienza","");
        scienza=scienza.split(/(\t)|(\n)/);
        vet=new Array();
        j=0;
        for (i=0;i<scienza.length;i++)
        {
            if ((scienza[i])&&(scienza[i]!="\n")&&(scienza[i]!="\t")) {vet[j]=scienza[i];j++;}
        }
        scienza=vet;
    }
    //edifici
    if (E>=0) {
        next=testo.length;
        if (R>=0) next=R;
        edifici=testo.substr(E,next-E);
        edifici=edifici.replace("Edifici","");
        edifici=edifici.split(/(\t)|(\n)/);
        vet=new Array();
        j=0;
        for (i=0;i<edifici.length;i++)
        {
            if ((edifici[i])&&(edifici[i]!="\n")&&(edifici[i]!="\t")) {vet[j]=edifici[i];j++;}
        }
        edifici=vet;
    }
    //risorse
    if (R>=0) {
        risorse=testo.substr(R);
        risorse=risorse.match(/[0-9]{1,15}/gi);
    }
    
    //*************************************visualizzazione**********************************
    //target
    opt="";
    if (document.logger.sop4.checked) opt="k";
    if (document.logger.sop3.checked) opt+=".";
    if (!document.logger.sop1.checked) {
        difensore="???";
    }
    if (document.logger.allyS.value) {
        difensore+=" ("+document.logger.allyS.value+")";
    }
    testo="<h3>Report di intelligence da <span style=\"color: grey;\">"+difensore+"</span></h3>";
    //ora
    testo+="<h4>"+ora+"</h4>";
    // visualizza flotte
    if (F>=0) {
        testo+="<h3>Flotte</h3><table style=\"background-color: black; width: 755px;\"><colgroup><col style=\"width: 124px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"></colgroup><tr><td style=\"text-align: left;\">Flotta planetaria</td>";
        switch(razD)
        {
            case "titani" :r=1;break;
            case "terrestri" :r=2;break;
            case "xen" :r=3;break;
        }
        for (i=1;i<=12;i++)
            testo+="<td class=\"report\"><img src=\"images/"+razD+"/"+i+".gif\" title=\""+navi[r][i]+"\"></td>";
        testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr><tr><td style=\"text-align: left;\">Truppe</td>";
        for (i=0;i<12;i++)
            testo+="<td class=\"report\">"+naviD[i]+"</td>";
        testo+="</table><div style=\"clear: both; height: 20px;\" ></div>";
        //supporti
        for (j=0;sup[j];j++)
        {
            testo+="<table style=\"background-color: black; width: 755px;\"><colgroup><col style=\"width: 124px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"></colgroup><tr><td style=\"text-align: left;\">Flotta planetaria</td>";
            switch(sup[j]['razza'])
            {
                case "titani" :r=1;break;
                case "terrestri" :r=2;break;
                case "xen" :r=3;break;
            }
            for (i=1;i<=12;i++)
                testo+="<td class=\"report\"><img src=\"images/"+sup[j]['razza']+"/"+i+".gif\" title=\""+navi[r][i]+"\"></td>";
            testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr><tr><td style=\"text-align: left;\">Truppe</td>";
            for (i=0;i<12;i++)
                testo+="<td class=\"report\">"+sup[j][i]+"</td>";
            testo+="</table><div style=\"clear: both; height: 20px;\" ></div>";
        }
    }
    // difesa
    if (D>=0) {
        testo+="<h3>Difesa</h3>";
        testo+="<table style=\"background-color: black; width: 755px;\"><tr><td></td>";
        switch(razD)
        {
            case "titani" :r=1;break;
            case "terrestri" :r=2;break;
            case "xen" :r=3;break;
        }
        for (i=1;i<=5;i++)
            testo+="<td class=\"report\"><img src=\"images/difesa"+i+"_"+razD.substr(0,3)+".gif\" title=\""+difese[r][i]+"\"></td>";
        testo+="<td class=\"report\"></td>";
        if (r!=1) {
            for (i=1;i<=2;i++)
                testo+="<td class=\"report\"><img src=\"images/intercettore"+i+"_"+razD.substr(0,3)+".gif\" title=\"Missile Intercettore "+i+"\"></td>";
            for (i=1;i<=4;i++)
                testo+="<td class=\"report\"><img src=\"images/attacco"+i+"_"+razD.substr(0,3)+".gif\" title=\"Missile d'attacco "+i+"\"></td>";
        
        testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr><tr><td style=\"text-align: left;\">Artiglieria</td>";
        for (i=0;i<5;i++)
            testo+="<td class=\"report\">"+dif[i]+"</td>";
        testo+="<td class=\"report\">Missili</td>";
        if (r!=1) {
            for (;i<11;i++)
                testo+="<td class=\"report\">"+dif[i]+"</td>";
        }
        testo+="</tr>";
        }
        testo+="</table><div style=\"clear: both; height: 20px;\" ></div>";
    }
    // scienza
    if (S>=0) {
        testo+="<h3>Scienza</h3>";
        testo+="<table style=\"background-color: black; width: 755px;\">";
        for (i=0;scienza[i];i+=2)
        {
            str="";
            if (!(i%4)) testo+="<tr>"; else str="class=\"report\"";
            testo+="<td "+str+" style=\"align: left;\">"+scienza[i]+"</td><td class=\"report\">"+scienza[i+1]+"</td>";
            if (i%4) testo+="</tr><tr><td class=\"spacer\"  colspan=\"4\"></td></tr>";
        }
        if (i%4) testo+="</tr><tr><td class=\"spacer\"  colspan=\"4\"></td></tr>";
        testo+="</table><div style=\"clear: both; height: 20px;\" ></div>";
    }
    // edifici
    if (E>=0) {
        testo+="<h3>Edifici</h3>";
        testo+="<table style=\"background-color: black; width: 755px;\">";
        for (i=0;edifici[i];i+=2)
        {
            str="";
            if (!(i%4)) testo+="<tr>"; else str="class=\"report\"";
            testo+="<td "+str+" style=\"align: left;\">"+edifici[i]+"</td><td class=\"report\">"+edifici[i+1]+"</td>";
            if (i%4) testo+="</tr><tr><td class=\"spacer\"  colspan=\"4\"></td></tr>";
        }
        if (i%4) testo+="</tr><tr><td class=\"spacer\"  colspan=\"4\"></td></tr>";
        testo+="</table><div style=\"clear: both; height: 20px;\" ></div>";
    }
    //risorse
    if (R>=0) {
        testo+="<table style=\"background-color: black;\"><colgroup><col style=\"width: 20%;\"><col style=\"width: 20%;\"><col style=\"width: 20%;\"><col style=\"width: 20%;\"><col style=\"width: 20%;\"></colgroup><tbody><tr><td>&nbsp;</td><td class=\"report\">Metallo</td><td class=\"report\">Cristallo</td><td class=\"report\">Deuterio</td><td class=\"report\">Unità Qi</td></tr><tr><td colspan=\"5\" class=\"spacer\"></td></tr><tr><td>Numero</td><td class=\"report\">"+formatnumber(risorse[0],opt)+"</td><td class=\"report\">"+formatnumber(risorse[1],opt)+"</td><td class=\"report\">"+formatnumber(risorse[2],opt)+"</td><td class=\"report\">"+risorse[3]+"</td></tr></tbody></table>";
    }
    //statistiche
    if (document.logger.sop2.checked) {
        
    }
    
    testo+="<div style=\"clear: both; height: 20px;\" ></div>";
    document.getElementById("visualizza").innerHTML=testo;
    document.getElementById("visualizza").style.display="block";
    if (salva) {
        oggetto.value=testo;   
    }
}
function batle(oggetto,salva)
{
    testo=oggetto.value;
    //nome difensore attaccante
    i=testo.indexOf("attacca");
    if (i<0) return 0;
    j=testo.indexOf("Ora");
    if (j<0) return 0;
    attaccante=testo.substr(0,i);
    difensore=testo.substr(i+7,j-i-7)
    // ora
    i=testo.indexOf("Attaccante");
    if (i<0) return 0;
    ora=testo.substr(j,i-j);
    i=testo.indexOf("\n");
    if (i>0) ora=ora.substr(0,i);
    //                                razza 
    D=testo.indexOf("Difensore");
    k=testo.indexOf("Artiglieria");
    difesa=true;
    if (D<0) {D=testo.length;difesa=false;}
    repA=testo.substr(0,D);
    if (k>0) repD=testo.substr(D,k-D); else repD=testo.substr(D);
    razA=getrazza(repA,"attaccante");
    if (repD!="") razD=getrazza(repD,"difensore");
    //navi attaccante
    i=repA.indexOf("Truppe");
    j=repA.indexOf("Perdite");
    if ((i<0)||(j<0)) return 0;
    naviA=repA.substr(i,j-i);
    perseA=repA.substr(j,D-j);
    naviA=naviA.match(/([0-9]{1,3}\.[0-9]{3}\.[0-9]{3})|([0-9]{1,3}\.[0-9]{3})|([0-9]{1,3})/gi);
    perseA=perseA.match(/([0-9]{1,3}\.[0-9]{3}\.[0-9]{3})|([0-9]{1,3}\.[0-9]{3})|([0-9]{1,3})/gi);
    bo=testo.indexOf("Il bottino consiste");
    det=testo.indexOf("Rimane un");
    gen=testo.indexOf("Un generatore di scudo è stato indebolito");
    qi=testo.indexOf("Sono state trovate");
    if (bo<0) b=testo.length; else b=bo;
    //navi difensore
    supn=0;
    difP=new Array();
    if (difesa) {
        i=repD.indexOf("Truppe");
        j=repD.indexOf("Perdite");
        naviD=repD.substr(i,j-i);
        perseD=repD.substr(j);
        naviD=naviD.match(/([0-9]{1,3}\.[0-9]{3}\.[0-9]{3})|([0-9]{1,3}\.[0-9]{3})|([0-9]{1,3})/gi);
        perseD=perseD.match(/([0-9]{1,3}\.[0-9]{3}\.[0-9]{3})|([0-9]{1,3}\.[0-9]{3})|([0-9]{1,3})/gi);
    // difese
        j2=testo.indexOf("Perdite",k);
        if (k>0) {
            if (det>0) {
                perart=testo.substr(k,det-k);
            }
            else {
                perart=testo.substr(k,b-k);
            }
             i=perart.indexOf("Numero");
            j=perart.indexOf("Perdite");
            if ((i<0)||(j<0)) return 0;
            dif=perart.substr(i,j-i);
            difP=perart.substr(j);
            dif=dif.match(/([0-9]{1,3}\.[0-9]{3}\.[0-9]{3})|([0-9]{1,3}\.[0-9]{3})|([0-9]{1,3})/gi);
            difP=difP.match(/([0-9]{1,3}\.[0-9]{3}\.[0-9]{3})|([0-9]{1,3}\.[0-9]{3})|([0-9]{1,3})/gi);
        }
        // rilevo flotta di supporto
        if (difP.length>=17) {
            s1=testo.indexOf("Difensore",k);
            supt=difP.slice(5);
            sup=new Array();
            supP=new Array();
            razS=new Array();
            if (supt.length>24) {
                sup[0]=supt.slice(0,12);
                supP[0]=supt.slice(12,24);
                sup[1]=supt.slice(24,36);
                supP[1]=supt.slice(36);
                supn=2;
                s2=testo.indexOf("Difensore",s1+1);
                razS[0]=getrazza(testo.substr(s1,s2-s1),"supporto 1");
                razS[1]=getrazza(testo.substr(s2),"supporto 2");
            }
            else {
                sup[0]=supt.slice(0,12);
                supP[0]=supt.slice(12);
                razS[0]=getrazza(testo.substr(s1),"supporto 1");
                supn=1;
            }
            difP=difP.slice(0,5);
        }
        else supn=0;
    }
    // detriti
    if (det>0) {
        detriti=testo.substr(det,b-det);
        detriti=detriti.match(/[0-9]{1,15}/gi);
    }
    //bombardamento
    bom=testo.indexOf("bombardata");
    if (bom>0) {
        if (bo>0) {
            if (det>0) danni=testo.substr(det,b-det); else danni=testo.substr(j2,b-j2);
        }
        else {
            if (qi>0) { if (det>0) danni=testo.substr(det,qi-det); else danni=testo.substr(j2,qi-j2);}
            else {
                if (gen>0) {if (det>0) danni=testo.substr(det,gen-det); else danni=testo.substr(j2,gen-j2);}
                else {
                    if (det>0) danni=testo.substr(det); else danni=testo.substr(j2);
                }
            }
        }   
        i=danni.indexOf("\n");
        if (i>0) danni=danni.substr(i);
        i=danni.indexOf(" livello");
        struttura=danni.substr(0,i);
        i=struttura.indexOf("\n");
        if (i>=0) struttura=struttura.substr(++i);
        i=struttura.indexOf("\n");
        if (i>=0) struttura=struttura.substr(++i);
        liv=danni.match(/[0-9]{1,15}/gi);
    }
    //bottino
    if (qi>0) {
        bottino=testo.substr(b,qi-b);
    }
    else {
        if (gen>0) bottino=testo.substr(b,gen-b); else bottino=testo.substr(b);
    }
    bottino=bottino.match(/[0-9]{1,15}/gi);
    
    //unità Qi
    if (qi>0) {
        if (gen>0) {
            Qi=testo.substr(qi,gen-qi);
        }
        else {
            Qi=testo.substr(qi);
        }
        Qi=Qi.match(/[0-9]{1,15}/gi);
    }
    //generatore scudo
    if (gen>0) {
        scudo=testo.substr(gen);
    }
    al=testo.indexOf("Il difensore");
    if (al>0) altro=testo.substr(al);
    //genero il report
    testo="<h3><span style=\"color: grey;\">";// inserisco attaccante
    if (document.logger.op1.checked) testo+=attaccante; else testo+="???";
    if (document.logger.allyA.value) testo+=" ("+document.logger.allyA.value+")";//alleanza che attacca
    testo+="</span> attacca <span style=\"color: grey;\">";
    if (document.logger.op2.checked) testo+=difensore; else testo+="???";
    if (document.logger.allyD.value) testo+=" ("+document.logger.allyD.value+")";//alleanza che difende
    testo+="</span></h3>";
    if (document.logger.op3.checked) testo+="<h4>"+ora+"</h4>";// ore
    // flotta attaccante 
    testo+="<table style=\"background-color: black; width: 755px;\"><colgroup><col style=\"width: 124px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"></colgroup><tr><td style=\"text-align: left;\">Attaccante</td>";
    switch(razA)
    {
        case "titani" :r=1;break;
        case "terrestri" :r=2;break;
        case "xen" :r=3;break;
    }
    for (i=1;i<=12;i++)
        testo+="<td class=\"report\"><img src=\"images/"+razA+"/"+i+".gif\" title=\""+navi[r][i]+"\"></td>";
    testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr><tr><td style=\"text-align: left;\">Truppe</td>";
    for (i=0;i<12;i++)
        testo+="<td class=\"report\">"+naviA[i]+"</td>";
    testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr><tr><td style=\"text-align: left;\">Perdite</td>";
    for (i=0;i<12;i++)
        testo+="<td class=\"report\">"+perseA[i]+"</td>";
    testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr>";
    testo+="</table><div style=\"clear: both; height: 10px;\" ></div>";
    //flotta difensiva
    if (difesa) {
    testo+="<table style=\"background-color: black; width: 755px;\"><colgroup><col style=\"width: 124px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"></colgroup><tr><td style=\"text-align: left;\">Difensore</td>";
    switch(razD)
    {
        case "titani" :r=1;break;
        case "terrestri" :r=2;break;
        case "xen" :r=3;break;
    }
    for (i=1;i<=12;i++)
        testo+="<td class=\"report\"><img src=\"images/"+razD+"/"+i+".gif\" title=\""+navi[r][i]+"\"></td>";
    testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr><tr><td style=\"text-align: left;\">Truppe</td>";
    for (i=0;i<12;i++)
        testo+="<td class=\"report\">"+naviD[i]+"</td>";
    testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr><tr><td style=\"text-align: left;\">Perdite</td>";
    for (i=0;i<12;i++)
        testo+="<td class=\"report\">"+perseD[i]+"</td>";
    testo+="</tr></table><div style=\"clear: both; height: 10px;\" ></div>";
    //difese
    if (k>0) {
    testo+="<table style=\"background-color: black; width: 755px;\"><colgroup><col style=\"width: 124px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"></colgroup>";
    testo+="<td style=\"text-align: left;\">Artiglieria</td>";
    for (i=1;i<=5;i++)
        testo+="<td class=\"report\"><img src=\"images/difesa"+i+"_"+razD.substr(0,3)+".gif\" title=\""+difese[r][i]+"\"></td>";
    testo+="</tr><tr><td class=\"spacer\"  colspan=\"6\"></td></tr><tr><td style=\"text-align: left;\">Numero</td>";
    for (i=0;i<5;i++)
        testo+="<td class=\"report\">"+dif[i]+"</td>";
    testo+="</tr><tr><td class=\"spacer\"  colspan=\"6\"></td></tr><tr><td style=\"text-align: left;\">Perdite</td>";
    for (i=0;i<5;i++)
        testo+="<td class=\"report\">"+difP[i]+"</td>";
    testo+="</tr></table><div style=\"clear: both; height: 10px;\" ></div>";
    }
    // supporti
    for (j=0;j<supn;j++)
    {
        testo+="<table style=\"background-color: black; width: 755px;\"><colgroup><col style=\"width: 124px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"><col style=\"width: 48px;\"></colgroup><tr><td style=\"text-align: left;\">Difensore</td>";
        switch(razS[j])
        {
            case "titani" :r=1;break;
            case "terrestri" :r=2;break;
            case "xen" :r=3;break;
        }
        for (i=1;i<=12;i++)
            testo+="<td class=\"report\"><img src=\"images/"+razS[j]+"/"+i+".gif\" title=\""+navi[r][i]+"\"></td>";
        testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr><tr><td style=\"text-align: left;\">Truppe</td>";
        for (i=0;i<12;i++)
            testo+="<td class=\"report\">"+sup[j][i]+"</td>";
        testo+="</tr><tr><td class=\"spacer\"  colspan=\"13\"></td></tr><tr><td style=\"text-align: left;\">Perdite</td>";
        for (i=0;i<12;i++)
            testo+="<td class=\"report\">"+supP[j][i]+"</td>";
        testo+="</tr></table><div style=\"clear: both; height: 10px;\" ></div>";
    }
    }
    opt="";
    if (document.logger.op5.checked) opt="k";
    if (document.logger.op6.checked) opt+=".";
    // detriti
    if (det>0) {
        testo+="<p>Rimane un'area di detriti contenente <span class=\"metallo\">"+formatnumber(detriti[0],opt)+"</span> metallo e <span class=\"cristallo\">"+formatnumber(detriti[1],opt)+"</span> cristallo.</p>";
    }
    //bombardamento
    if (bom>0) {
        testo+="<p>"+danni+"</p>";
        //testo+="<p>"+struttura+" demolita da "+liv[0]+" a "+liv[1]+"</p>";
    }
    //bottino
    if (bo>0) {
        testo+="<p>Il bottino consiste di <span class=\"metallo\">"+formatnumber(bottino[0],opt)+"</span> metallo, <span class=\"cristallo\">"+formatnumber(bottino[1],opt)+"</span> cristallo e <span class=\"deuterio\">"+formatnumber(bottino[2],opt)+"</span> Deuterio.</p>";
    }
    //qi
    if (qi>0) {
        testo+="<p>Sono state trovate "+Qi[0]+" Unità Qi.</p>";

    }
    //scudo
    if (gen>0) {
        testo+="<p>"+scudo+"</p>";
        vet=calcpntscu(scudo,razA,naviA);
        testo+="<p style=\"color: blue;\">la forza dello scudo si aggira ora tra : "+ vet[0] +" e "+ vet[1] +"\nforza della flotta "+vet[2]+"</p>";
    }
    if (al>0) {
        testo+="<p>"+altro+"</p>";
    }
    // *****************************************statistiche********************************
    if (document.logger.op4.checked) {
        //detriti
        guadagno=0;
        detrit=0;
        if (det>0) {
            guadagno+=detriti[0]*1+detriti[1]*1;
            detrit=detriti[0]*1+detriti[1]*1;
        } 
        /*else { //per i simulatori, calcola i detriti dello scontro
            
        }*/
        //danni demolizione
        danni_dem=0;
        if (bom>0) {
            vet=getedificio(struttura,razD);
            for (i=liv[1]*1+1;(i<=parseInt(liv[0]))&&(vet[i]!=null);i++)
                danni_dem+=vet[i]['metallo']+vet[i]['cristallo']+vet[i]['deuterio'];
            testo+="<p>danni demolizione "+struttura+", risorse : <span class=\"danni\">"+formatnumber(""+danni_dem,opt)+"</span></p>";
        }
        //bottino
        switch (razA)
        {
            case "titani": dati=navi_tit;break;
            case "terrestri": dati=navi_ter;break;
            case "xen": dati=navi_xen;break;
        }
        guadagnoR=0;
        bottinoT=0;
        caricoper=0;
        carico=0;
        ricerca=0;
        if (bo>0) {
            guadagno+=bottino[0]*1+bottino[1]*1+bottino[2]*1;
            guadagnoR=bottino[0]*1+bottino[1]*1+bottino[2]*1;
            bottinoT=guadagnoR;
            for(i=1;i<=12;i++)
            {
                cn=dati[i]['carico'];
                if (document.logger.opt7.checked) {
                    if (dati[i]['tipo']=="civ") cn=dati[i]['carico']*(1+document.logger.ricercac.value*0.05);
                    else {
                        if (dati[i]['tipo']=="mil") cn=dati[i]['carico']*(1+document.logger.ricercam.value*0.05);
                    }
                }
                carico+=cn*parseInt(naviA[i-1].replace(".",""));
            }
            caricoper=parseInt(bottinoT*100/carico);
            if (caricoper>100) caricoper=100;
        }
        //perdite att
        danniA=0;
        //dati=new Array();
        for (i=0;i<12;i++)
            danniA+=parseInt(perseA[i].replace(".",""))*dati[i+1]['costo'];
        //perdite difensore
        danniD=0;
        danniS=new Array();
        danniS[0]=0;
        danniS[1]=0;
        if (difesa) {
            switch (razD)
            {
                case "titani": dati=navi_tit;break;
                case "terrestri": dati=navi_ter;break;
                case "xen": dati=navi_xen;break;
            }
            for (i=0;i<12;i++)
                danniD+=parseInt(perseD[i].replace(".",""))*dati[i+1]['costo'];
            switch (razD)
            {
                case "titani": dati=difese_tit;break;
                case "terrestri": dati=difese_ter;break;
                case "xen": dati=difese_xen;break;
            }
            for (i=0;i<5;i++)
                danniD+=parseInt(difP[i]!=null ? difP[i].replace(".","") : 0)*dati[i+1]['costo'];
            danniD+=bottinoT+danni_dem;
            //danni supporti
            
            for (j=0;j<supn;j++)
            {
                switch (razS[j])
                {
                    case "titani": dati=navi_tit;break;
                    case "terrestri": dati=navi_ter;break;
                    case "xen": dati=navi_xen;break;
                }
                for (i=0;i<12;i++)
                    danniS[j]+=parseInt(supP[j][i].replace(".",""))*dati[i+1]['costo'];
            }
        }
        //guadagno
        guadagno-=danniA;
        guadagnoR-=danniA;
        guadagnoD=detrit*1-danniD;
        punti=danniD-danniA+danniS[0]+danniS[1];
        danniDT=danniD+danniS[0]+danniS[1];
        // punti 
        if (qi>0) QI=Qi[0]; else QI=0;
        punti=(punti/1000)+QI*4;
        punti=parseInt(punti);
        // visualizzazione
        if (guadagno>0) str1="guadagno"; else str1="danni";
        if (guadagnoR>0) str2="guadagno"; else str2="danni";
        if (guadagnoD>0) str3="guadagno"; else str3="danni";
        if (punti>0) {str4="guadagno";str5="danni"} else {str4="danni";str5="guadagno";}
        testo+="<div>Risorse caricate : <span class=\"stats\">"+caricoper+"%</span> (<span class=\"stats\">"+bottinoT+"</span>/<span class=\"stats\">"+carico+"</span>)</div>";
        testo+="<p><div>Danni subiti dall'attaccante : <span class=\"danni\">"+formatnumber(""+danniA,opt)+"</span></div><div>Danni subiti dal difensore : <span class=\"danni\">"+formatnumber(""+danniD,opt)+"</span></div>";
        for (j=0;j<supn;j++)
        {
            testo+="<div>Danni subiti dal supporto "+razS[j]+" : <span class=\"danni\">"+formatnumber(""+danniS[j],opt)+"</span></div>";
        }
        if (supn>0) testo+="<div>Danni totali subiti dai difensori :<span class=\"danni\">"+formatnumber(""+danniDT,opt)+"</span></div>";
        testo+="<div>Punti quadagnati dall'attacante : <span class=\""+str4+"\">"+punti+"</span></div>";punti=0-punti;
        testo+="<div>Punti quadagnati dal difensore : <span class=\""+str5+"\">"+punti+"</span></div>";
        testo+="<div>Guadagno attaccante con detriti : <span class=\""+str1+"\">"+formatnumber(""+guadagno,opt)+"</span></div><div>Guadagno attaccante senza detriti : <span class=\""+str2+"\">"+formatnumber(""+guadagnoR,opt)+"</span></div>";
        testo+="<div>Guadagno difensore dai detriti : <span class=\""+str3+"\">"+formatnumber(""+guadagnoD,opt)+"</span></div></p>";
    }
    
    testo+="<div style=\"clear: both; height: 10px;\" ></div>";
    document.getElementById("visualizza").innerHTML=testo;
    document.getElementById("visualizza").style.display="block";
    if (salva) {
        oggetto.value=testo;
        document.logger.punti.value=(0-punti)*1+document.logger.punti.value*1;
        document.logger.bottino.value=bottinoT*1+document.logger.bottino.value*1;
        document.logger.detriti.value=detrit*1+document.logger.detriti.value*1;
    }
}
function getrazza(testo,parte)
{
    x=0;t=0;T=0;
    x=testo.indexOf("Zek");
    t=testo.indexOf("Sonda");
    T=testo.indexOf("Osservatore");
    i=0;
    if (x>0) {raz="xen";i++}
    if (t>0) {raz="terrestri";i++}
    if (T>0) {raz="titani";i++}
    if (i>1) {
        vet=new Array();
        j=0;
        if (x>0) {j++;vet['x']=x;}
        if (t>0) {j++;vet['t']=t;}
        if (T>0) {j++;vet['T']=T;}
        m=testo.length;r="";
        for (nome in vet)
            if (vet[nome]<m) {m=vet[nome];r=nome}
        switch (r)
        {
            case "x" : raz="xen";break;
            case "t" : raz="terrestri";break;
            case "T" : raz="titani";break;
        }
    }
    if (!i) {
        do
        {
            raz=prompt("inserisci la razza del "+parte,"titani xen terrestri");
        }
        while((raz!="terrestri")&&(raz!="titani")&&(raz!="xen"))
    }
    return raz;
}
function impval(mil,civ,oggetto,disp)
{
    if (oggetto.checked) {
        if (disp) { 
            document.logger.ricercam.style.display="block";
            document.logger.ricercac.style.display="block";
        }
        document.logger.ricercam[mil].selected=true;
        document.logger.ricercac[civ].selected=true;
    }
    else {
        if (disp) {
            document.logger.ricercam.style.display="none";
            document.logger.ricercac.style.display="none";
        }
    }
}


function getedificio(edificio,r)
{
    if (r=="xen") {
        switch(edificio)
        {
            case "Miniera di metallo" : return met_xen;break;
            case "Miniera di cristallo" : return cri_xen; break;
            case "Giacimento di deuterio" : return deu_xen; break;
            case "Fornace" : return fornace_xen; break;
            case "Laboratorio di cristalli" : return labcri_xen; break;
            case "Depuratore di deuterio" : return depuratore_xen; break;
            case "Impianto energetico fotosintetico" : return solar_xen; break;
            case "Centrale eolica" : return eolica_xen; break;
            case "Centrale termoenergetica" : return termo_xen; break;
            case "Centrale nucleare" : return nuclear_xen; break;
            case "CEA" : return cea_xen; break;
            case "Magazzino generale" : return maga_gen; break;
            case "Serbatoio di deuterio" : return serb_deu_xen; break;
            case "Bioreattore" : return bio_xen; break;
            case "Piccolo cantiere" : return piccolo_cantiere_xen; break;
            case "Grande cantiere" : return grande_cantiere_xen; break;
            case "Fabbrica di armi" : return fabrica_armi_xen; break;
            case "Silo per razzi" : return silo_xen; break;
            case "Base navale" : return base_xen; break;
            case "Tana Zek" : return tana_xen; break;
            case "Cervello" : return cervello_xen; break;
            case "Centro sviluppo" : return sviluppo_xen; break;
            case "Ambasciata" : return amb_xen; break;
            case "Centro costruzioni" : return costruzioni_xen; break;
            case "Deposito segreto" : return deposito_segreto_xen; break;
            case "Centro commerciale" : return commerciale_xen; break;
        }
    }
    if (r=="terrestri") {
        switch (edificio)
        {
            case "Miniera di metallo" : return met_ter;break;
            case "Miniera di cristallo" : return cri_ter;break;
            case "Giacimento di deuterio" : return deu_ter;break;
            case "Fornace" : return fornace_ter;break;
            case "Laboratorio di cristalli" : return labcri_ter;break;
            case "Depuratore di deuterio" : return depuratore_ter;break;
            case "Impianto fotovoltaico" : return solar_ter;break;
            case "Centrale eolica" : return eolica_ter;break;
            case "Impianto idroenergetico" : return idrica_ter;break;
            case "Centrale nucleare" : return nuclear_ter;break;
            case "CEA" : return cea_ter;break;
            case "Magazzino di metallo" : return magamet_ter;break;
            case "Magazzino di cristallo" : return magacri_ter;break;
            case "Serbatoio di deuterio" : return magadeu_ter;break;
            case "Cantiere navale" : return cantiere_ter;break;
            case "Fabbrica di armi" : return fabrica_armi_ter;break;
            case "Silo per razzi" : return silo_ter;break;
            case "Base militare" : return base_ter;break;
            case "Scanner ad impulsi" : return scanner_ter;break;
            case "Hangar" : return hangar_ter;break;
            case "Laboratorio di ricerca" : return lab_ter;break;
            case "Ambasciata" : return amb_ter;break;
            case "Centro costruzioni" : return centro_ter;break;
            case "Ricicleria" : return ricicleria_ter;break;
            case "Deposito segreto" : return deposito_ter;break;
            case "Centro commerciale" : return mercato_ter;break;
            case "Centro colonizzazione" : return colo_ter;break;
            case "Cantiere navale civile" : return civile_ter;break;
            case "Fabbrica robotica" : return robotica_ter;break;
        }
    }
    if (r=="titani")
    {
        switch(edificio)
        {
            case "Miniera di metallo" : return met_tit;break;
            case "Miniera di cristallo" : return cri_tit;break;
            case "Giacimento di trizio" : return deu_tit;break;
            case "Fornace" : return fornace_tit;break;
            case "Laboratorio di cristalli" : return labcri_tit;break;
            case "Filtro di trizio" : return filtro_tit;break;
            case "Impianto fotovoltaico" : return solar_tit;break;
            case "Centrale eolica" : return eolica_tit;break;
            case "Impianto idroelettrico" : return idrica_tit;break;
            case "Centrale nucleare" : return nucleare_tit;break;
            case "CEA" : return cea_tit;cea_tit;cea_tit;break;
            case "Magazzino di metallo" : return magamet_tit;break;
            case "Magazzino di cristallo" : return magacri_tit;break;
            case "Serbatoio di trizio" : return magadeu_tit;break;
            case "Magazzino iperspaziale" : return nascondiglio_tit;break;
            case "Accumulatore di energia" : return accumulatore_tit;break;
            case "Cantiere navale" : return cantiere_tit;break;
            case "Fabbrica di armi" : return fabbrica_tit;break;
            case "Base navale" : return base_tit;break;
            case "Generatore di scudo" : return scudo_tit;break;
            case "Sistema di scansione" : return scansione_tit;break;
            case "Jammer" : return jammer_tit;break;
            case "Transportale" : return transportale_tit;break;
            case "Generatore stealth" : return stealth_tit;break;
            case "Modulatore dimensionale" : return modulatore_tit;break;
            case "Laboratorio di ricerca" : return lab_tit;break;
            case "Ambasciata" : return amb_tit;break;
            case "Centro costruzioni" : return centro_tit;break;
            case "Cantiere navale civile" : return civile_tit;break;
            case "Teletrasportatore" : return teletrasportatore_tit;break;
            case "Fabbrica robotica" : return robotica_tit;break;
            case "Deposito segreto" : return deposito_tit;break;
            case "Trasmettitore" : return trasmettitore_tit;break;          
        }
    }
    return "";
}