function formatnumber(numero,opt)
{
    b1=false;
    if (opt.substr(0,1)=="k") {
        if (numero.substr(0,1)=="-") {b1=true;numero=numero.substr(1);}
        numero=parseInt(numero/1000);
        numero+="k";
        if (b1) numero="-"+numero;
    }
    b1=false;
    b2=false;
    if (((opt.substr(0,1)==".")||(opt.substr(1,1)=="."))&&(numero.length>3)) {
        if (numero.substr(0,1)=="-") {b1=true;numero=numero.substr(1);}
        if (opt.substr(0,1)=="k") {b2=true;numero=numero.substr(0,numero.length-1);}
        num=numero.split("");//1,2,3,4,5
        num.reverse();//5,4,3,2,1
        str="";
        for(i=0;i<num.length;i++)
            if (!(i%3)&&(i!=0)) str+="."+num[i]; else str+=num[i];
        num=str.split("");
        num.reverse();
        numero=num.join("");
        if (b1) numero="-"+numero;
        if (b2) numero+="k";
    }
    return numero;
}
function controlnumber(oggetto)
{
    oggetto.value=oggetto.value.replace(/k/gi,"000");
    oggetto.value=oggetto.value.replace(/[^0-9]/g,"");
}