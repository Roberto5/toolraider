function selrazza(selettore)
{
    if (selettore==1) {
        valore=document.simulatore.attaccante.value;
        switch (valore)
        {
            case "2" : document.getElementById("att").innerHTML="<td><img title=\"Missile d'attacco 1\" src=\"images/attacco1.gif\" /></td><td><img title=\"Missile d'attacco 2\" src=\"images/attacco2.gif\" /></td><td><img title=\"Missile d'attacco 3\" src=\"images/attacco3.gif\" /></td><td><img title=\"Missile d'attacco 4\" src=\"images/attacco4.gif\" /></td>";break;//document.getElementById("terA").style.display="block";break;
            case "3" : document.getElementById("att").innerHTML="<td><img title=\"Missile d'attacco 1\" src=\"images/attacco1_xen.gif\" /></td><td><img title=\"Missile d'attacco 2\" src=\"images/attacco2_xen.gif\" /></td><td><img title=\"Missile d'attacco 3\" src=\"images/attacco3_xen.gif\" /></td><td><img title=\"Missile d'attacco 4\" src=\"images/attacco4_xen.gif\" /></td>";break;//document.getElementById("xenA").style.display="block";break;
            default : document.getElementById("att").innerHTML="";// Missile d'attacco 1
        }
    }
    else{
        document.simulatore.intercettore1.disabled=false;document.simulatore.intercettore2.disabled=false;document.simulatore.scudo.style.display="none";
        document.getElementById('scudo').style.display="none";
        valore=document.simulatore.difensore.value;
        switch (valore)
        {
            case "1" : document.getElementById("dif").innerHTML="<td><img title=\"Iniettore\" src=\"images/difesa1_tit.gif\" border=\"0\" /></td><td><img title=\"Nanoblade\" src=\"images/difesa2_tit.gif\" border=\"0\" /></td><td><img title=\"Acceleratore di bosoni\" src=\"images/difesa3_tit.gif\" border=\"0\" /></td><td><img title=\"Masterblaster\" src=\"images/difesa4_tit.gif\" border=\"0\" /></td><td><img title=\"Disturbatore\" src=\"images/difesa5_tit.gif\" border=\"0\" /></td>";document.simulatore.intercettore1.disabled=true;document.simulatore.intercettore2.disabled=true;document.simulatore.intercettore1.value=0;document.simulatore.intercettore2.value=0;document.simulatore.scudo.style.display="block";document.getElementById('scudo').style.display="block"; break;
            case "2" : document.getElementById("dif").innerHTML="<td><img title=\"Batteria antiaerea\" src=\"images/difesa1_ter.gif\" border=\"0\" /></td><td><img title=\"Laser ad impulsi\" src=\"images/difesa2_ter.gif\" border=\"0\" /></td><td><img title=\"Cannone gaussiano\" src=\"images/difesa3_ter.gif\" border=\"0\" /></td><td><img title=\"Artiglieria automatica\" src=\"images/difesa4_ter.gif\" border=\"0\" /></td><td><img title=\"Emettitore di tachioni\" src=\"images/difesa5_ter.gif\" border=\"0\" /></td><td><img title=\"Missile intercettore 1\" src=\"images/intercettore1_ter.gif\" border=\"0\" /></td><td><img title=\"Missile intercettore 2\" src=\"images/intercettore2_ter.gif\" border=\"0\" /></td>";break;
            case "3" : document.getElementById("dif").innerHTML="<td><img title=\"Sporok\" src=\"images/difesa1_xen.gif\" border=\"0\" /></td><td><img title=\"Acidor\" src=\"images/difesa2_xen.gif\" border=\"0\" /></td><td><img title=\"Kalamanar\" src=\"images/difesa3_xen.gif\" border=\"0\" /></td><td><img title=\"Zuikon\" src=\"images/difesa4_xen.gif\" border=\"0\" /></td><td><img title=\"Paratec\" src=\"images/difesa5_xen.gif\" border=\"0\" /></td><td><img title=\"Missile intercettore 1\" src=\"images/intercettore1_xen.gif\" border=\"0\" /></td><td><img title=\"Missile intercettore 2\" src=\"images/intercettore2_xen.gif\" border=\"0\" /></td>";break;
            default : document.getElementById("dif").innerHTML="";
        }
    }
}
function simula(dife,atta)
{
    att=new Array();
    dif=new Array();
    att[1]=new Array();
    att[2]=new Array();
    att[3]=new Array();
    att[4]=new Array();
    dif[1]=new Array();
    dif[2]=new Array();
    dif[3]=new Array();
    dif[4]=new Array();
    dif[5]=new Array();
    numa=new Array();
    numa[1]=document.simulatore.missile1.value;
    numa[2]=document.simulatore.missile2.value;
    numa[3]=document.simulatore.missile3.value;
    numa[4]=document.simulatore.missile4.value;
    numd=new Array();
    numd[1]=document.simulatore.difesa1.value;
    numd[2]=document.simulatore.difesa2.value;
    numd[3]=document.simulatore.difesa3.value;
    numd[4]=document.simulatore.difesa4.value;
    numd[5]=document.simulatore.difesa5.value;
    int1=document.simulatore.intercettore1.value;
    int2=document.simulatore.intercettore2.value;
    if (!int1) int1="0";
    if (!int2) int2="0";
    scud=document.simulatore.scudo.value; 
    switch(atta.value)
    {
        case "2" : att[1]['forza']=600;att[1]['numero']=1;att[2]['forza']=1600;att[2]['numero']=1;att[3]['forza']=100;att[3]['numero']=15;att[4]['forza']=200;att[4]['numero']=4;break;
        case "3" : att[1]['forza']=600;att[1]['numero']=1;att[2]['forza']=1600;att[2]['numero']=1;att[3]['forza']=100;att[3]['numero']=15;att[4]['forza']=200;att[4]['numero']=4;break;
        default :alert("seleziona la razza attaccante '"+atta.value+"'");return 0;
    }
    switch(dife.value)
    {
        case "1" : dif[1]['scafo']=375;dif[1]['scudo']=0;dif[2]['scafo']=555;dif[2]['scudo']=5;dif[3]['scafo']=850;dif[3]['scudo']=2;dif[4]['scafo']=900;dif[4]['scudo']=0;dif[5]['scafo']=480;dif[5]['scudo']=25;break;
        case "2" : dif[1]['scafo']=300;dif[1]['scudo']=0;dif[2]['scafo']=690;dif[2]['scudo']=0;dif[3]['scafo']=900;dif[3]['scudo']=0;dif[4]['scafo']=790;dif[4]['scudo']=5;dif[5]['scafo']=950;dif[5]['scudo']=4;break;
        case "3" : dif[1]['scafo']=365;dif[1]['scudo']=0;dif[2]['scafo']=685;dif[2]['scudo']=0;dif[3]['scafo']=850;dif[3]['scudo']=0;dif[4]['scafo']=735;dif[4]['scudo']=0;dif[5]['scafo']=900;dif[5]['scudo']=8;break;
        default : alert("seleziona la razza difensiva '"+dife.value+"'");return 0;
    }
    testo="";
    for(i=1;i<=4;i++)// ciclo missili
    {
        scudo=parseInt(document.simulatore.scudo.value);
        for(j=1;j<=5;j++) //ciclo difese
        {
            //repscud="";
            scud=true;
            abb=numa[i];
            mis=numa[i];
            if ((scudo>0)&&(numa[i])) {
                scudo-=(att[i]['forza']*att[i]['numero']*numa[i]);
                if (scudo>=0) {scud=false;testo+="i missili d'attacco "+i+" non hanno abbattuto lo scudo<br />L'hanno indebolito di "+(att[i]['forza']*att[i]['numero']*numa[i]);j=6} else {
                    forza=att[i]['forza']*att[i]['numero'];
                    abb=(parseInt(scudo/forza)+ (scudo%forza>0 ? 1 : 0 ))*(-1);
                    testo+=abb+" missili hanno passato lo scudo<br />";
                }
            }
            numa[i]=abb;
            if ((numa[i])&&(numd[j])&&(scud)) {
                interc=parseInt(int1)+parseInt(int2);
                testo+="<br /> " +numa[i]+ " missili d'attacco "+i+" contro "+numd[j]+" difese di tipo "+j+" e "+interc+" missili intercettori<br />";
                sup=numd[j];
                ris=dif[j]['scafo'];
                if (interc>numa[i]) interc=numa[i];
                numa[i]-=interc;
                //testo+=repscud;
                if (interc>0) testo+=interc+" missili sono stati abbattuti <br />";
                for(k=0;k<(numa[i]*att[i]['numero']);k++) // ciclo numero di impatti testate + numero missili
                {
                    ris-=((att[i]['forza']-dif[j]['scudo']));//alert(ris+" "+att[i]['forza']+" "+dif[j]['scudo']+" "+numa[i]);
                    if (ris<=0) 
                    {// difesa abbattuta
                        sup--;//alert(sup);// diminuisco le difese superstiti
                        if (sup>0) ris=dif[j]['scafo']; // se ci son ancora difese ne punto a un altra
                        else {
                            inutilizzati=((numa[i]*att[i]['numero'])-k)%att[i]['numero'];
                            tesrim=(numa[i]*att[i]['numero'])-k;
                            testo+="tutte le difese abbattute! "+inutilizzati+" missili erano in eccesso<br /><br /> testate rimaneti "+tesrim+"<br />";
                            k=(numa[i]*att[i]['numero']);
                        }
                    } 
                }
                if (sup>0) testo+="rimangono "+sup+" difese di tipo "+j+"<br /><br />";
            }
            numa[i]=mis;
        }
    }
    document.getElementById('risultato').innerHTML=testo;
}