function calcola(oggetto)
{
    testo=oggetto.value
    x=testo.indexOf("Zek");
    t=testo.indexOf("Sonda");
    T=testo.indexOf("Osservatore");
    if (x>0) razza="xen";
    if (t>0) razza="terrestri";
    if ((T>0)&&((t<0)||(x<0))) razza="titani";
    if ((x<0)&&(t<0)&&(T<0)) {
        do
        {
            razza=prompt("inserisci la razza dell'attaccante","titani xen terrestri");
        }
        while((razza!="terrestri")&&(razza!="titani")&&(razza!="xen"))
    }
    i=testo.indexOf("Un generatore di scudo è stato indebolito del");
    if (i<0) {alert("errore, manca il resoconto dell'indebolimento dello scudo");exit;}
    j=testo.indexOf("il potere combattivo della flotta attaccante.");
    scudo=testo.substr(i,j);
    i=testo.indexOf("Truppe");
    j=testo.indexOf("Perdite");
    truppe=testo.substr(i,j);
    arr_truppe=truppe.match(/([0-9]{1,3}\.[0-9]{3}\.[0-9]{3})|([0-9]{1,3}\.[0-9]{3})|([0-9]{1,3})/gi);
    vet=calcpntscu(scudo,razza,arr_truppe)
    oggetto.value="la forza dello scudo si aggira ora tra : "+ vet[0] +" e "+ vet[1] +"\nforza della flotta "+vet[2]+"\n\n"+testo;
}
function calcpntscu(scudo,razza,arr_truppe)
{
    percentuali=scudo.match(/[0-9]{1,3}\.[0-9]{1}\%|[0-9]{1,3}\%/gi);
    if (razza=="xen") nav= new Array(1,1,276,528,270,7680,300,1,1,1,1,1);
    if (razza=="titani") nav= new Array(1,136,1368,1080,800,30000,1,1,1,1,1,1);
    if (razza=="terrestri") nav= new Array(1,90,880,525,8000,80,1,1,1,1680,1,1);
    forza=0;
    for (i=0;i<12;i++)
    {
        forza+=(arr_truppe[i].replace(".","")*nav[i]);
    }
    if (percentuali[0]!="100%") {
        percentuali[0]=percentuali[0].replace("%","");
        fs1=parseInt(forza*100/percentuali[0]-forza);
        fs2=parseInt(forza*100/(percentuali[0]*1+0.1*1)-forza);
    }
    else {
        percentuali[1]=percentuali[1].replace("%","");
        fs1=parseInt((forza*percentuali[1])/100);
        fs2=parseInt((forza*(percentuali[1]*1+0.1*1))/100);
    }
    
    var pnt=new Array()
    pnt[0]=formatnumber(""+fs1,".");
    pnt[1]=formatnumber(""+fs2,".");
    pnt[2]=formatnumber(""+forza,".");
    return pnt;
}