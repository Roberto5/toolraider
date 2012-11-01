function planet()
{
    var i=document.push.pianeta.value*1;
    document.push.met_dep.value=pianeti[i]['dep_met'];
    document.push.cri_dep.value=pianeti[i]['dep_cri'];
    document.push.deu_dep.value=pianeti[i]['dep_deu'];
    document.push.prod_met.value=pianeti[i]['prod_met'];
    document.push.prod_cri.value=pianeti[i]['prod_cri'];
    document.push.prod_deu.value=pianeti[i]['prod_deu'];
    document.push.mercato.value=pianeti[i]['mercato'];
}
camb=0;
camb2=0;
function gestisciliv()
{
    edificio=document.push.edificio.value;
    switch(edificio)
    {
        case "fornace_ter" : camb=1;cambia(5);break;
        case "fornace_tit" : camb=1;cambia(5);break;
        case "labcri_ter" : camb=1;cambia(5);break;
        case "labcri_tit" : camb=1;cambia(5);break;
        case "filtro_tit" : camb=1;cambia(5);break;
        case "fornace_xen" : camb=1;cambia(5);break;
        case "labcri_xen" : camb=1;cambia(5);break;
        case "depuratore_xen" : camb=1;cambia(5);break;
        case "cea_ter" : camb=1;cambia(10);break;
        case "cea_tit" : camb=1;cambia(10);break;
        case "cea_xen" : camb=1;cambia(10);break;
        case "deposito_ter" : camb=1;cambia(10);break;
        case "deposito_tit" : camb=1;cambia(10);break;
        case "deposito_xen" : camb=1;cambia(10);break;
        default : camb=0;cambia(20);break;
    }
}

function cambia(tot)
{
    ini="<select onchange=\"calc()\" name=\"liv_edificio\">"
    fin="</select>";
    if ((camb!=camb2)||(tot!=20)) {
        str="";
        for (i=0;i<=tot;i++)
        {
            str+="<option value="+i+">"+i+"</option>";
        }
        document.getElementById("liv_edificio").innerHTML=ini+str+fin;
    }
    camb2=camb;
}
function magazzini()
{
    //calcolo magazzini
    met_liv_dep=document.push.met_liv_dep.value*1;
    num_dep_met=document.push.num_dep_met.value*1;
    if (razza!=3) {cri_liv_dep=document.push.cri_liv_dep.value*1;num_dep_cri=document.push.num_dep_cri.value*1;} else {cri_liv_dep=met_liv_dep;num_dep_cri=num_dep_met}
    deu_liv_dep=document.push.deu_liv_dep.value*1;
    num_dep_deu=document.push.num_dep_deu.value*1;
    if (razza==3) {
        cap_met=maga_gen[met_liv_dep]['capacita']+(num_dep_met-1)*maga_gen[20]['capacita'];
        cap_cri=cap_met;
        cap_deu=serb_deu_xen[deu_liv_dep]['capacita']+(num_dep_deu-1)*serb_deu_xen[20]['capacita'];
    }
    if (razza==2) {
        cap_met=magamet_ter[met_liv_dep]['capacita']+(num_dep_met-1)*magamet_ter[20]['capacita'];
        cap_cri=magacri_ter[cri_liv_dep]['capacita']+(num_dep_cri-1)*magacri_ter[20]['capacita'];
        cap_deu=magadeu_ter[deu_liv_dep]['capacita']+(num_dep_deu-1)*magadeu_ter[20]['capacita'];
    }
    if (razza==1) {
        cap_met=magamet_tit[met_liv_dep]['capacita']+(num_dep_met-1)*magamet_tit[20]['capacita'];
        cap_cri=magacri_tit[cri_liv_dep]['capacita']+(num_dep_cri-1)*magacri_tit[20]['capacita'];
        cap_deu=magadeu_tit[deu_liv_dep]['capacita']+(num_dep_deu-1)*magadeu_tit[20]['capacita'];
    }
    capacita= ricerca['capacita']!=null ? ricerca['capacita'] : 0 ;
    document.push.met_dep.value=cap_met*(1+0.1*capacita);
    document.push.cri_dep.value=cap_cri*(1+0.1*capacita);
    document.push.deu_dep.value=cap_deu*(1+0.1*capacita);
    calc();
}
oldtmet=0;
oldtcri=0;
oldtdeu=0;
function calc()
{
    //alert("1");
    //calcolo costi obbiettivo
    edificio=document.push.edificio.value;
    liv=document.push.liv_edificio.value;
    target=true;
    switch(edificio)
    {
        case "met_xen" : met_target=met_xen[liv]['metallo'];cri_target=met_xen[liv]['cristallo'];deu_target=met_xen[liv]['deuterio'];break;
        case "cri_xen" : met_target=cri_xen[liv]['metallo'];cri_target=cri_xen[liv]['cristallo'];deu_target=cri_xen[liv]['deuterio']; break;
        case "deu_xen" : met_target=deu_xen[liv]['metallo'];cri_target=deu_xen[liv]['cristallo'];deu_target=deu_xen[liv]['deuterio']; break;
        case "fornace_xen" : met_target=fornace_xen[liv]['metallo'];cri_target=fornace_xen[liv]['cristallo'];deu_target=fornace_xen[liv]['deuterio']; break;
        case "labcri_xen" : met_target=labcri_xen[liv]['metallo'];cri_target=labcri_xen[liv]['cristallo'];deu_target=labcri_xen[liv]['deuterio']; break;
        case "depuratore_xen" : met_target=depuratore_xen[liv]['metallo'];cri_target=depuratore_xen[liv]['cristallo'];deu_target=depuratore_xen[liv]['deuterio']; break;
        case "solar_xen" : met_target=solar_xen[liv]['metallo'];cri_target=solar_xen[liv]['cristallo'];deu_target=solar_xen[liv]['deuterio']; break;
        case "eolica_xen" : met_target=eolica_xen[liv]['metallo'];cri_target=eolica_xen[liv]['cristallo'];deu_target=eolica_xen[liv]['deuterio']; break;
        case "termo_xen" : met_target=termo_xen[liv]['metallo'];cri_target=termo_xen[liv]['cristallo'];deu_target=termo_xen[liv]['deuterio']; break;
        case "nuclear_xen" : met_target=nuclear_xen[liv]['metallo'];cri_target=nuclear_xen[liv]['cristallo'];deu_target=nuclear_xen[liv]['deuterio']; break;
        case "cea_xen" : met_target=cea_xen[liv]['metallo'];cri_target=cea_xen[liv]['cristallo'];deu_target=cea_xen[liv]['deuterio']; break;
        case "maga_gen" : met_target=maga_gen[liv]['metallo'];cri_target=maga_gen[liv]['cristallo'];deu_target=maga_gen[liv]['deuterio']; break;
        case "serb_deu_xen" : met_target=serb_deu_xen[liv]['metallo'];cri_target=serb_deu_xen[liv]['cristallo'];deu_target=serb_deu_xen[liv]['deuterio']; break;
        case "bio_xen" : met_target=bio_xen[liv]['metallo'];cri_target=bio_xen[liv]['cristallo'];deu_target=bio_xen[liv]['deuterio']; break;
        case "piccolo_cantiere_xen" : met_target=piccolo_cantiere_xen[liv]['metallo'];cri_target=piccolo_cantiere_xen[liv]['cristallo'];deu_target=piccolo_cantiere_xen[liv]['deuterio']; break;
        case "grande_cantiere_xen" : met_target=grande_cantiere_xen[liv]['metallo'];cri_target=grande_cantiere_xen[liv]['cristallo'];deu_target=grande_cantiere_xen[liv]['deuterio']; break;
        case "fabrica_armi_xen" : met_target=fabrica_armi_xen[liv]['metallo'];cri_target=fabrica_armi_xen[liv]['cristallo'];deu_target=fabrica_armi_xen[liv]['deuterio']; break;
        case "silo_xen" : met_target=silo_xen[liv]['metallo'];cri_target=silo_xen[liv]['cristallo'];deu_target=silo_xen[liv]['deuterio']; break;
        case "base_xen" : met_target=base_xen[liv]['metallo'];cri_target=base_xen[liv]['cristallo'];deu_target=base_xen[liv]['deuterio']; break;
        case "tana_xen" : met_target=tana_xen[liv]['metallo'];cri_target=tana_xen[liv]['cristallo'];deu_target=tana_xen[liv]['deuterio']; break;
        case "cervello_xen" : met_target=cervello_xen[liv]['metallo'];cri_target=cervello_xen[liv]['cristallo'];deu_target=cervello_xen[liv]['deuterio']; break;
        case "sviluppo_xen" : met_target=sviluppo_xen[liv]['metallo'];cri_target=sviluppo_xen[liv]['cristallo'];deu_target=sviluppo_xen[liv]['deuterio']; break;
        case "amb_xen" : met_target=amb_xen[liv]['metallo'];cri_target=amb_xen[liv]['cristallo'];deu_target=amb_xen[liv]['deuterio']; break;
        case "costruzioni_xen" : met_target=costruzioni_xen[liv]['metallo'];cri_target=costruzioni_xen[liv]['cristallo'];deu_target=costruzioni_xen[liv]['deuterio']; break;
        case "deposito_segreto_xen" : met_target=deposito_segreto_xen[liv]['metallo'];cri_target=deposito_segreto_xen[liv]['cristallo'];deu_target=deposito_segreto_xen[liv]['deuterio']; break;
        case "commerciale_xen" : met_target=commerciale_xen[liv]['metallo'];cri_target=commerciale_xen[liv]['cristallo'];deu_target=commerciale_xen[liv]['deuterio']; break;
        
        case "met_ter" : met_target=met_ter[liv]['metallo'];cri_target=met_ter[liv]['cristallo'];deu_target=met_ter[liv]['deuterio'];break;
        case "cri_ter" : met_target=cri_ter[liv]['metallo'];cri_target=cri_ter[liv]['cristallo'];deu_target=cri_ter[liv]['deuterio'];break;
        case "deu_ter" : met_target=deu_ter[liv]['metallo'];cri_target=deu_ter[liv]['cristallo'];deu_target=deu_ter[liv]['deuterio'];break;
        case "fornace_ter" : met_target=fornace_ter[liv]['metallo'];cri_target=fornace_ter[liv]['cristallo'];deu_target=fornace_ter[liv]['deuterio'];break;
        case "labcri_ter" : met_target=labcri_ter[liv]['metallo'];cri_target=labcri_ter[liv]['cristallo'];deu_target=labcri_ter[liv]['deuterio'];break;
        case "depuratore_ter" : met_target=depuratore_ter[liv]['metallo'];cri_target=depuratore_ter[liv]['cristallo'];deu_target=depuratore_ter[liv]['deuterio'];break;
        case "solar_ter" : met_target=solar_ter[liv]['metallo'];cri_target=solar_ter[liv]['cristallo'];deu_target=solar_ter[liv]['deuterio'];break;
        case "eolica_ter" : met_target=eolica_ter[liv]['metallo'];cri_target=eolica_ter[liv]['cristallo'];deu_target=eolica_ter[liv]['deuterio'];break;
        case "idrica_ter" : met_target=idrica_ter[liv]['metallo'];cri_target=idrica_ter[liv]['cristallo'];deu_target=idrica_ter[liv]['deuterio'];break;
        case "nuclear_ter" : met_target=nuclear_ter[liv]['metallo'];cri_target=nuclear_ter[liv]['cristallo'];deu_target=nuclear_ter[liv]['deuterio'];break;
        case "cea_ter" : met_target=cea_ter[liv]['metallo'];cri_target=cea_ter[liv]['cristallo'];deu_target=cea_ter[liv]['deuterio'];break;
        case "magamet_ter" : met_target=magamet_ter[liv]['metallo'];cri_target=magamet_ter[liv]['cristallo'];deu_target=magamet_ter[liv]['deuterio'];break;
        case "magacri_ter" : met_target=magacri_ter[liv]['metallo'];cri_target=magacri_ter[liv]['cristallo'];deu_target=magacri_ter[liv]['deuterio'];break;
        case "magadeu_ter" : met_target=magadeu_ter[liv]['metallo'];cri_target=magadeu_ter[liv]['cristallo'];deu_target=magadeu_ter[liv]['deuterio'];break;
        case "cantiere_ter" : met_target=cantiere_ter[liv]['metallo'];cri_target=cantiere_ter[liv]['cristallo'];deu_target=cantiere_ter[liv]['deuterio'];break;
        case "fabrica_armi_ter" : met_target=fabrica_armi_ter[liv]['metallo'];cri_target=fabrica_armi_ter[liv]['cristallo'];deu_target=fabrica_armi_ter[liv]['deuterio'];break;
        case "silo_ter" : met_target=silo_ter[liv]['metallo'];cri_target=silo_ter[liv]['cristallo'];deu_target=silo_ter[liv]['deuterio'];break;
        case "base_ter" : met_target=base_ter[liv]['metallo'];cri_target=base_ter[liv]['cristallo'];deu_target=base_ter[liv]['deuterio'];break;
        case "scanner_ter" : met_target=scanner_ter[liv]['metallo'];cri_target=scanner_ter[liv]['cristallo'];deu_target=scanner_ter[liv]['deuterio'];break;
        case "hangar_ter" : met_target=hangar_ter[liv]['metallo'];cri_target=hangar_ter[liv]['cristallo'];deu_target=hangar_ter[liv]['deuterio'];break;
        case "lab_ter" : met_target=lab_ter[liv]['metallo'];cri_target=lab_ter[liv]['cristallo'];deu_target=lab_ter[liv]['deuterio'];break;
        case "amb_ter" : met_target=amb_ter[liv]['metallo'];cri_target=amb_ter[liv]['cristallo'];deu_target=amb_ter[liv]['deuterio'];break;
        case "centro_ter" : met_target=centro_ter[liv]['metallo'];cri_target=centro_ter[liv]['cristallo'];deu_target=centro_ter[liv]['deuterio'];break;
        case "ricicleria_ter" : met_target=ricicleria_ter[liv]['metallo'];cri_target=ricicleria_ter[liv]['cristallo'];deu_target=ricicleria_ter[liv]['deuterio'];break;
        case "deposito_ter" : met_target=deposito_ter[liv]['metallo'];cri_target=deposito_ter[liv]['cristallo'];deu_target=deposito_ter[liv]['deuterio'];break;
        case "mercato_ter" : met_target=mercato_ter[liv]['metallo'];cri_target=mercato_ter[liv]['cristallo'];deu_target=mercato_ter[liv]['deuterio'];break;
        case "colo_ter" : met_target=colo_ter[liv]['metallo'];cri_target=colo_ter[liv]['cristallo'];deu_target=colo_ter[liv]['deuterio'];break;
        case "civile_ter" : met_target=civile_ter[liv]['metallo'];cri_target=civile_ter[liv]['cristallo'];deu_target=civile_ter[liv]['deuterio'];break;
        case "robotica_ter" : met_target=robotica_ter[liv]['metallo'];cri_target=robotica_ter[liv]['cristallo'];deu_target=robotica_ter[liv]['deuterio'];break;
        
        case "met_tit" : met_target=met_tit[liv]['metallo'];cri_target=met_tit[liv]['cristallo'];deu_target=met_tit[liv]['deuterio'];break;
        case "cri_tit" : met_target=cri_tit[liv]['metallo'];cri_target=cri_tit[liv]['cristallo'];deu_target=cri_tit[liv]['deuterio'];break;
        case "deu_tit" : met_target=deu_tit[liv]['metallo'];cri_target=deu_tit[liv]['cristallo'];deu_target=deu_tit[liv]['deuterio'];break;
        case "fornace_tit" : met_target=fornace_tit[liv]['metallo'];cri_target=fornace_tit[liv]['cristallo'];deu_target=fornace_tit[liv]['deuterio'];break;
        case "labcri_tit" : met_target=labcri_tit[liv]['metallo'];cri_target=labcri_tit[liv]['cristallo'];deu_target=labcri_tit[liv]['deuterio'];break;
        case "filtro_tit" : met_target=filtro_tit[liv]['metallo'];cri_target=filtro_tit[liv]['cristallo'];deu_target=filtro_tit[liv]['deuterio'];break;
        case "solar_tit" : met_target=solar_tit[liv]['metallo'];cri_target=solar_tit[liv]['cristallo'];deu_target=solar_tit[liv]['deuterio'];break;
        case "eolica_tit" : met_target=eolica_tit[liv]['metallo'];cri_target=eolica_tit[liv]['cristallo'];deu_target=eolica_tit[liv]['deuterio'];break;
        case "idrica_tit" : met_target=idrica_tit[liv]['metallo'];cri_target=idrica_tit[liv]['cristallo'];deu_target=idrica_tit[liv]['deuterio'];break;
        case "nucleare_tit" : met_target=nucleare_tit[liv]['metallo'];cri_target=nucleare_tit[liv]['cristallo'];deu_target=nucleare_tit[liv]['deuterio'];break;
        case "cea_tit" : met_target=cea_tit[liv]['metallo'];cri_target=cea_tit[liv]['cristallo'];deu_target=cea_tit[liv]['deuterio'];break;
        case "magamet_tit" : met_target=magamet_tit[liv]['metallo'];cri_target=magamet_tit[liv]['cristallo'];deu_target=magamet_tit[liv]['deuterio'];break;
        case "magacri_tit" : met_target=magacri_tit[liv]['metallo'];cri_target=magacri_tit[liv]['cristallo'];deu_target=magacri_tit[liv]['deuterio'];break;
        case "magadeu_tit" : met_target=magadeu_tit[liv]['metallo'];cri_target=magadeu_tit[liv]['cristallo'];deu_target=magadeu_tit[liv]['deuterio'];break;
        case "nascondiglio_tit" : met_target=nascondiglio_tit[liv]['metallo'];cri_target=nascondiglio_tit[liv]['cristallo'];deu_target=nascondiglio_tit[liv]['deuterio'];break;
        case "accumulatore_tit" : met_target=accumulatore_tit[liv]['metallo'];cri_target=accumulatore_tit[liv]['cristallo'];deu_target=accumulatore_tit[liv]['deuterio'];break;
        case "cantiere_tit" : met_target=cantiere_tit[liv]['metallo'];cri_target=cantiere_tit[liv]['cristallo'];deu_target=cantiere_tit[liv]['deuterio'];break;
        case "fabbrica_tit" : met_target=fabbrica_tit[liv]['metallo'];cri_target=fabbrica_tit[liv]['cristallo'];deu_target=fabbrica_tit[liv]['deuterio'];break;
        case "base_tit" : met_target=base_tit[liv]['metallo'];cri_target=base_tit[liv]['cristallo'];deu_target=base_tit[liv]['deuterio'];break;
        case "scudo_tit" : met_target=scudo_tit[liv]['metallo'];cri_target=scudo_tit[liv]['cristallo'];deu_target=scudo_tit[liv]['deuterio'];break;
        case "scansione_tit" : met_target=scansione_tit[liv]['metallo'];cri_target=scansione_tit[liv]['cristallo'];deu_target=scansione_tit[liv]['deuterio'];break;
        case "jammer_tit" : met_target=jammer_tit[liv]['metallo'];cri_target=jammer_tit[liv]['cristallo'];deu_target=jammer_tit[liv]['deuterio'];break;
        case "transportale_tit" : met_target=transportale_tit[liv]['metallo'];cri_target=transportale_tit[liv]['cristallo'];deu_target=transportale_tit[liv]['deuterio'];break;
        case "stealth_tit" : met_target=stealth_tit[liv]['metallo'];cri_target=stealth_tit[liv]['cristallo'];deu_target=stealth_tit[liv]['deuterio'];break;
        case "modulatore_tit" : met_target=modulatore_tit[liv]['metallo'];cri_target=modulatore_tit[liv]['cristallo'];deu_target=modulatore_tit[liv]['deuterio'];break;
        case "lab_tit" : met_target=lab_tit[liv]['metallo'];cri_target=lab_tit[liv]['cristallo'];deu_target=lab_tit[liv]['deuterio'];break;
        case "amb_tit" : met_target=amb_tit[liv]['metallo'];cri_target=amb_tit[liv]['cristallo'];deu_target=amb_tit[liv]['deuterio'];break;
        case "centro_tit" : met_target=centro_tit[liv]['metallo'];cri_target=centro_tit[liv]['cristallo'];deu_target=centro_tit[liv]['deuterio'];break;
        case "civile_tit" : met_target=civile_tit[liv]['metallo'];cri_target=civile_tit[liv]['cristallo'];deu_target=civile_tit[liv]['deuterio'];break;
        case "teletrasportatore_tit" : met_target=teletrasportatore_tit[liv]['metallo'];cri_target=teletrasportatore_tit[liv]['cristallo'];deu_target=teletrasportatore_tit[liv]['deuterio'];break;
        case "robotica_tit" : met_target=robotica_tit[liv]['metallo'];cri_target=robotica_tit[liv]['cristallo'];deu_target=robotica_tit[liv]['deuterio'];break;
        case "deposito_tit" : met_target=deposito_tit[liv]['metallo'];cri_target=deposito_tit[liv]['cristallo'];deu_target=deposito_tit[liv]['deuterio'];break;
        case "trasmettitore" : met_target=trasmettitore_tit[liv]['metallo'];cri_target=trasmettitore_tit[liv]['cristallo'];deu_target=trasmettitore_tit[liv]['deuterio'];break;          
        default : target=false;
    }
    if (target) {
        if (adstr) 
        {
            document.push.met_target.value=met_target*1+oldtmet*1;
            document.push.cri_target.value=cri_target*1+oldtcri*1;
            document.push.deu_target.value=deu_target*1+oldtdeu*1;
        } 
        else 
        {
            document.push.met_target.value=met_target;oldtmet=met_target;
            document.push.cri_target.value=cri_target;oldtcri=cri_target;
            document.push.deu_target.value=deu_target;oldtdeu=deu_target;
        }
    }
    
    //calcolo differenza 
    cap_met=document.push.met_dep.value;
    cap_cri=document.push.cri_dep.value;
    cap_deu=document.push.deu_dep.value;
    met_target=document.push.met_target.value;
    cri_target=document.push.cri_target.value;
    deu_target=document.push.deu_target.value;
    sto_met=document.push.sto_met.value;
    sto_cri=document.push.sto_cri.value;
    sto_deu=document.push.sto_deu.value;
    mer_met=document.push.mer_met.value;
    mer_cri=document.push.mer_cri.value;
    mer_deu=document.push.mer_deu.value;
    document.push.dif_met.value=met_target-sto_met-mer_met;
    document.push.dif_cri.value=cri_target-sto_cri-mer_cri;
    document.push.dif_deu.value=deu_target-sto_deu-mer_deu;
    sov_met=sto_met*1+mer_met*1-cap_met*1;
    sov_cri=sto_cri*1+mer_cri*1-cap_cri*1;
    sov_deu=sto_deu*1+mer_deu*1-cap_deu*1;
    if (sov_met>0) document.push.sov_met.value=sov_met; else document.push.sov_met.value=0;
    if (sov_cri>0) document.push.sov_cri.value=sov_cri; else document.push.sov_cri.value=0;
    if (sov_deu>0) document.push.sov_deu.value=sov_deu; else document.push.sov_deu.value=0;
    t1=document.push.dif_met.value/document.push.prod_met.value;
    t2=document.push.dif_cri.value/document.push.prod_cri.value;
    t3=document.push.dif_deu.value/document.push.prod_deu.value;
    te1=parseInt(t1);
    te2=parseInt(t2);
    te3=parseInt(t3);
    min1=t1-te1;
    min2=t2-te2;
    min3=t3-te3;
    min1=parseInt(min1*60);
    min2=parseInt(min2*60);
    min3=parseInt(min3*60);
    b=true;
    if ((t1>t2)&&(t1>t3)) {document.getElementById("tempo").innerHTML=te1+":"+min1;b=false;}
    if ((t2>t1)&&(t2>t3)) {document.getElementById("tempo").innerHTML=te2+":"+min2;b=false;}
    if ((t3>t2)&&(t3>t1)) {document.getElementById("tempo").innerHTML=te3+":"+min3;b=false;}
    if (b) document.getElementById("tempo").innerHTML="";
}
function add(nome)
{
    nome.value++;
    magazzini()
}
function remove(nome)
{
    nome.value--;
    if (nome.value<1) nome.value=1;
    magazzini()
}
num_campi=0;
function addprod()
{
    liv_met=document.push.met_liv_prod.value;
    liv_cri=document.push.cri_liv_prod.value;
    liv_deu=document.push.deu_liv_prod.value;
    metallo=met_xen[liv_met]['produzione']*(1+5*document.push.liv_fornace.value/100);
    cristallo=met_xen[liv_cri]['produzione']*(1+5*document.push.liv_labcri.value/100);
    deuterio=met_xen[liv_deu]['produzione']*(1+5*document.push.liv_depuratore.value/100);
    if (num_campi<10) document.push.prod_met.value=document.push.prod_met.value*1+metallo*1;
    if (num_campi<10) document.push.prod_cri.value=document.push.prod_cri.value*1+cristallo*1;
    if (num_campi<12) document.push.prod_deu.value=document.push.prod_deu.value*1+deuterio*1;
    if ((liv_met!=0)&&(num_campi<10)) num_campi++;
    if ((liv_cri!=0)&&(num_campi<10)) num_campi++;
    if ((liv_deu!=0)&&(num_campi<12)) num_campi++;
    document.getElementById("num_campi").innerHTML=num_campi;
}
function resetprod()
{
    document.push.prod_met.value=0;
    document.push.prod_cri.value=0;
    document.push.prod_deu.value=0;
    num_campi=0;
    document.getElementById("num_campi").innerHTML=num_campi;
}
function addmer()
{
    document.push.mer_met.value=document.confirm.invio_met.value*1+document.push.mer_met.value*1;
    document.push.mer_cri.value=document.confirm.invio_cri.value*1+document.push.mer_cri.value*1;
    document.push.mer_deu.value=document.confirm.invio_deu.value*1+document.push.mer_deu.value*1;
    resetform();
}
function resetform()
{
    liv=document.confirm.mercato.value;
    document.confirm.reset();
    document.confirm.mercato.value=liv;
}
function resetmer()
{
    document.push.mer_met.value=0;
    document.push.mer_cri.value=0;
    document.push.mer_deu.value=0;
}
function nmercanti()
{
    risorse=document.confirm.invio_met.value*1+document.confirm.invio_cri.value*1+document.confirm.invio_deu.value*1;
    commercio= ricerca['commercio']!=null ? ricerca['commercio'] : 0 ;
    switch(razza)
    {
        case 1 : mercante=3000*(1+0.2*commercio);break;
        case 2 : mercante=4000*(1+0.2*commercio);break;
        case 3 : mercante=5000*(1+0.2*commercio);break;
        default : mercante=0;
    }
    n=risorse/mercante;
    n2=parseInt(n);
    if (n>n2) n2++;
    if (mercante!=0) document.getElementById("mer_us").innerHTML=n2;
    if (n2>document.confirm.mercato.value) document.getElementById("mer_us").style.color="red";
    if (n2<document.confirm.mercato.value) document.getElementById("mer_us").style.color="white";
    if (razza==1) {
        teletrasporto= ricerca['teletrasporto']!=null ? ricerca['teletrasporto'] : 0 ;
        document.getElementById("necessarie").innerHTML="risorse necessarie per l'invio; "+document.confirm.invio_met.value*(1+(20-teletrasporto*2)/100)+" metallo "+document.confirm.invio_cri.value*(1+(20-teletrasporto*2)/100)+" cristallo " +document.confirm.invio_deu.value*(1+(20-teletrasporto*2)/100);
        document.getElementById("perdita").innerHTML="perdita dovuta al teletrasportatore "+(20-teletrasporto*2)+"%";
    }
}
adstr=false;
function addstr(oggetto)
{
    adstr=!adstr;
    if (adstr) oggetto.value="togli"; else oggetto.value="aggiungi";
}
function merplanet(oggetto)
{
    document.confirm.mercato.value=pianeti[oggetto.value]['mercato'];
}