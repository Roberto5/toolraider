function query()
{
    $("#vis").html("<img src=\"images/loading.gif\">");
    $.ajax({
        url: "query.php" ,
        type: "POST" ,
        data: "action=cometa&id="+document.insert.name.value ,
        success : function (data,stato) {
            $("#vis").html(data);
        },
        error : function (richiesta,stato,errori) {
            alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
        }
    });
}

function misto()
{
	if ((document.stima.capacity.value==500)||(document.stima.capacity.value==20000)) {
		if (document.stima.mist.checked) {document.getElementById('misto').innerHTML="<input name=n2 value=0 size=7 onChange='calc()'>";}
		else document.getElementById('misto').innerHTML="uso misto di grandi/piccoli riciclatori";
	}
	else document.getElementById('misto').innerHTML="";
}
function convert(temp)
{
	n=parseInt(temp);
	if(temp>n) n++;
	return n;
}
function calc(oggetto)
{
	if (document.stima.txt.value!="") estrai2(false);
	metallo=0;cristallo=0;deuterio=0;
	metallo=parseInt(document.stima.metallo.value);
	cristallo=parseInt(document.stima.cristallo.value);
	deuterio=parseInt(document.stima.deuterio.value);
	if (!metallo) metallo=0;
	if (!cristallo) cristallo=0;
	if (!deuterio) deuterio=0;
	document.stima.captot.value=(document.stima.capacity.value*(1+5/100*livc))*document.stima.number.value;
	alter=0;
	if (document.stima.capacity.value==500) alter=20000; else {if (document.stima.capacity.value==20000) alter=500;}
	if (document.stima.mist.checked) document.stima.captot.value=(document.stima.captot.value*1)+(alter*(1+5/100*livc))*document.stima.n2.value;
	document.stima.risorse.value = metallo + cristallo + deuterio;
	ric1=document.stima.risorse.value*1;
	ric2=document.stima.captot.value;
	ric=0;
	if (ric1<ric2) ric=ric1; else ric=ric2;
	temp=1/6+ ((ric/1000) / Math.pow(2,livr));
	document.stima.tempo.value = temp;
	//document.insert.time_r.value = convert((document.stima.tempo.value * 1) + (document.stima.durata.value * 1));
}

function estrai()
{
	estrai2(true)
}
function estrai2(done)
{
	testo=document.stima.txt.value;
	if (testo=="") return;
	i=testo.indexOf("Plus");
	if (i<0) {alert("errore, il copia incolla è errato 1");document.stima.txt.value="";return;}
	testo=testo.substring(i);
	j=testo.indexOf("K-");
	if (j<0) {alert("errore, il copia incolla è errato 2");document.stima.txt.value="";return;}
	pianeta=testo.substring(0,j);
	//**************************estrazione pianeta
	distance=false;
	for(d=0;(d<dati.length)&&(!distance);d++)
	{
		i=pianeta.indexOf(dati[d]['nome_pianeta']);
		if (i>=0) {distance=true;d--;} 
	}
	//********************* estrazione cometa
	testo=testo.substring(j);
	str=testo.substring(2,4);
	num=testo.substring(5,8);
	document.insert.string.value=str;
	document.insert.number.value=num;
	i=testo.indexOf("Depositi di risorse\nMetallo:");
	if (i<0) {i=testo.indexOf("Depositi di risorse");if (i<0) {alert("errore, il copia incolla è errato 3 ");document.stima.txt.value="";return;}}
	//controllare se fare questo
	i=testo.indexOf("Metallo:");
	if (i<0) {alert("errore, il copia incolla è errato 3.1");document.stima.txt.value="";return;}
	j=testo.indexOf("Cristallo:");
	if (j<0) {alert("errore, il copia incolla è errato 3.2");document.stima.txt.value="";return;}
	met=testo.substring(i,j);
	met=met.replace("Metallo:","");
	n=met.search(/[a-zA-Z]/g);
	if (n>=0) met=met.substring(0,n);
	met=met.replace(/[ \t\n\r\f\v]/g,"");
	b=false;
	if (met=="") {
		b=true;
		testo=testo.substr(i+1);
		i=testo.indexOf("Depositi di risorse");
		if (i<0) {alert("errore, il copia incolla è errato 4");document.stima.txt.value="";return;}
		testo=testo.substr(i);
		i=testo.indexOf("Metallo:");
		if (i<0) {alert("errore, il copia incolla è errato 5");document.stima.txt.value="";return;}
		j=testo.indexOf("Cristallo:");
		if (j<0) {alert("errore, il copia incolla è errato 6");document.stima.txt.value="";return;}
		met=testo.substring(i,j);
		met=met.replace("Metallo:","");
		n=met.search(/[a-zA-Z]/g);
		if (n>=0) met=met.substring(0,n);
		met=met.replace(/[ \t\n\r\f\v]/g,"");
	}
    met=met.replace(",","");
	document.stima.metallo.value=met
	i=testo.indexOf("Deuterio:");t=false;
	if (i<0) {i=testo.indexOf("Trizio:");t=true;
		if (i<0) {alert("errore, il copia incolla è errato 7");document.stima.txt.value="";return;}
	}
	cri=testo.substring(j,i);
	cri=cri.replace("Cristallo:","");
	n=cri.search(/[a-zA-Z]/g);
	if (n>=0) cri=cri.substring(0,n);
	cri=cri.replace(/[ \t\n\r\f\v]/g,"");
    cri=cri.replace(",","");
	document.stima.cristallo.value=cri;
	if (b) {
		testo=testo.substr(1);
		j=testo.indexOf("Depositi");
		if (j<0) {alert("errore, il copia incollla è errato 8");document.stima.txt.value="";return;}
		deu=testo.substring(i-1,j);}
	else {j=testo.indexOf("Sistema");
		if (j<0) {alert("errore, il copia incollla è errato 8.5");document.stima.txt.value="";return;}
		deu=testo.substring(i,j);
	}
	if (t) deu=deu.replace("Trizio:",""); else deu=deu.replace("Deuterio:","");
	n=deu.search(/[a-zA-Z]/g);
	if (n>=0) deu=deu.substring(0,n);
	deu=deu.replace(/[\t\n\r\f\v]/g,"");
	deu=deu.replace(",","");
	document.stima.deuterio.value=deu;
	cap=(document.stima.capacity.value*(1+5/100*livc));
	tot=met*1+cri*1+deu*1;
	nr=tot/cap;
	if (done) document.stima.number.value=convert(nr);
	//************Estrazione coordinate
	if (distance) {
		i=testo.indexOf("Sistema");
		if (i<0) {alert("errore, il copia incollla è errato 9");}
		sist=testo.substring(i,i+20);
		sist=sist.replace("Sistema (","");
		i=testo.indexOf("|");
		if (i<0) {alert("errore, il copia incollla è errato 10");}
		g=sist.substring(0,1);
		sist=sist.substring(2);
		i=sist.indexOf("|");
		if (i<0) {alert("errore, il copia incollla è errato 11");}
		x=sist.substring(0,i);
		sist=sist.substring(i+1);
		y=sist.substring(0,4);
		y=y.replace(")","");
		dist=Math.pow(x-dati[d]['x'],2)+Math.pow(y-dati[d]['y'],2);
		dist=Math.pow(dist,0.5)+5;
		vel=10;
		if (document.stima.capacity.value==20000) vel=6;
		if (document.stima.capacity.value==500) vel=12;
		if (document.stima.capacity.value==1000) vel=10;
		if (document.stima.capacity.value==800) vel=10;
		if (dist>5) vel=vel*(1+0.1*livv);
		if ((document.stima.mist.checked)&&(document.stima.capacity.value==500)) vel=6;
		document.stima.durata.value=dist/vel;
	}
	if (done) {calc();query();}
}