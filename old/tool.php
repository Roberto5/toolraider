<?php
$title = ": tools vari";
session_start();
include ("functions.php");
include ("my_config.php");
include ("inc/foot.php");
include ("inc/data.php");
online(7);

$_POST=inputctrl($_POST);

$enum = getenum("us_player","razza");
echo '

<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

<style type="text/css">
.saturazione {
    color: red;
}

.black_overlay{
  display: none;
  position: absolute;
  top: 0%;
  left: 0%;
  width: 2000px;
  height: 1500px;
  background-color: black;
  z-index:1001;
  -moz-opacity: 0.8;
  opacity:.60;
  filter: alpha(opacity=60);
}

.white_content {
  color: white;
  display: none;
  position: absolute;
  top: 200px;
  left: 550px;
  width: 400px;
  height: 170px;
  padding: 16px;
  border: 10px solid #3B3B3B;
  background: url(images/center.jpg);
  background-color: black;
  z-index:1002;
  overflow: auto;
}
</style>

<script src="inc/data.js" language="javascript"></script>
<script src="inc/data2.js" language="javascript"></script>
<script src="scripts/scudo.js" language="javascript"></script>
<script src="scripts/push.js" language="javascript"></script>
<script src="scripts/missili.js" language="javascript"></script>
<script src="scripts/logger.js" language="javascript"></script>
<script src="inc/navi.js" language="javascript"></script>
<script src="scripts/number.js" language="javascript"></script>
<script type="text/javascript">
var ricerca=new Array();

    $.ajax({
        url : "query.php",
        data : "action=ricerca&nome='.$_SESSION['nome'].'&fields=teletrasporto,commercio",
        
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
            alert("E\' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
        }
    })
    //async : false ,
    //timeout: 1000 ,

    

var razza="';
$bool = true;
for ($i = 0; $enum[$i] && $bool; $i++)
    if ($_SESSION['razza'] == $enum[$i]) {
        $razza = $i + 1;
        $bool = false;
    }

echo $razza.'";


var pianeti=new Array();
pianeti[0]=new Array();
pianeti[0][\'prod_met\']=\'0\';
pianeti[0][\'prod_cri\']=\'0\';
pianeti[0][\'prod_deu\']=\'0\';
pianeti[0][\'dep_met\']=\'0\';
pianeti[0][\'dep_cri\']=\'0\';
pianeti[0][\'dep_deu\']=\'0\';
pianeti[0][\'mercato\']=\'0\';';

if ($_SESSION['nome'] != "") {
    $pianeti = estraipianeti();
}
for ($i = 0; $i < $pianeti['tot']; $i++) {
    $nome = "p".$pianeti[$i];
    echo '
pianeti['.($i + 1).']=new Array();
pianeti['.($i + 1).'][\'prod_met\']="'.$pianeti[$nome]['prod_met'].'";
pianeti['.($i + 1).'][\'prod_cri\']="'.$pianeti[$nome]['prod_cri'].'";
pianeti['.($i + 1).'][\'prod_deu\']="'.$pianeti[$nome]['prod_deu'].'";
pianeti['.($i + 1).'][\'dep_met\']="'.$pianeti[$nome]['dep_met'].'";
pianeti['.($i + 1).'][\'dep_cri\']="'.$pianeti[$nome]['dep_cri'].'";
pianeti['.($i + 1).'][\'dep_deu\']="'.$pianeti[$nome]['dep_deu'].'";
pianeti['.($i + 1).'][\'mercato\']="'.$pianeti[$nome]['mercato'].'";';
}
echo 'function control(oggetto)
{
    oggetto.value=oggetto.value.replace(/k/g,"000");
    oggetto.value=oggetto.value.replace(/[^0-9]/g,"");
}
</script>';

$Db = new db();

echo "<br /><div id=\"light\" class=\"white_content\">";
if (islog() != "no") {
    echo "seleziona pianeta da cui spedire le risorse<br />
       <select name=\"pianeta_push\" onchange=\"merplanet(this)\">
       <option value=\"\">sel. un pianeta</option>";
    $var = "seleziona";
    for ($i = 0; $i < $pianeti['tot']; $i++) {
        $sel = $var.$i;
        if ($pianeta == ($i + 1)) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=\"".($i + 1)."\">".$pianeti[$i]."</option>";
    }
    echo "</select>";
}
echo "<form name=\"confirm\"><table style=\"color: black;\">
<tr>
<td>metallo</td><td>cristallo</td><td>deuterio</td>
</tr>
<tr>
<td><input name=\"invio_met\" size=\"9\" onkeyup=\"control(this);nmercanti()\" /></td><td><input onkeyup=\"control(this);nmercanti()\" name=\"invio_cri\" size=\"9\" /></td><td><input onkeyup=\"control(this);nmercanti()\" name=\"invio_deu\" size=\"9\" /></td></tr>
<td>mercanti rimanenti</td><td><input onchange=\"nmercanti();document.push.mercato.value=document.confirm.mercato.value;\" name=\"mercato\" maxlength=\"2\" size=\"2\" value=\"".$mercato."\" /></td><td>mercanti usati <div id=\"mer_us\"></div></td>
</table></form>
<span id=\"necessarie\"></span>
<span id=\"perdita\"></span>
<input type=\"button\" onclick=\"document.getElementById('light').style.display='none'; document.getElementById('fade').style.display='none';addmer();calc()\" value=\"invia\" />
<input type=\"button\" onclick=\"resetform();document.getElementById('light').style.display='none'; document.getElementById('fade').style.display='none';\" value=\"chiudi\" />
</div>
<div id=\"fade\" class=\"black_overlay\"></div>";

menu();

echo "<center><a href=\"tool.php?action=push\">calcolo push</a> | <a href=\"tool.php?action=scudo\">calcolo scudo</a> | <a href=\"tool.php?action=missili\">simulatore missilistico</a> | <a href=\"tool.php?action=log\">Archivia report</a></center>";

$action = $_GET['action'];
$id = $_GET['id'];
if (is_numeric($id)) {
    $Db->connect();
    $riga = toarray("SELECT * FROM `log` WHERE `id`='".$id."'");
    echo "<p>".$riga['text']."</p>";
    $Db->query("UPDATE `log` SET `visita`='".date("Y-m-d H:i:s")."' WHERE `id`='".$id."'");
} elseif ($action == "push") {

    echo '<script language="javascript">

while((razza!=1)&&(razza!=2)&&(razza!=3))
    razza=parseInt(prompt("inserisci la tua razza (1 titani, 2 terrestri e 3 xen)",0));

</script>';

    $pianeta = $_POST['pianeta'];
    $met_dep = $_POST['met_dep'];
    $cri_dep = $_POST['cri_dep'];
    $deu_dep = $_POST['deu_dep'];
    $met_liv_dep = $_POST['met_liv_dep'];
    $cri_liv_dep = $_POST['cri_liv_dep'];
    $deu_liv_dep = $_POST['deu_liv_dep'];
    $num_dep_met = $_POST['num_dep_met'];
    $num_dep_cri = $_POST['num_dep_cri'];
    $num_dep_deu = $_POST['num_dep_deu'];
    $met_target = $_POST['met_target'];
    $cri_target = $_POST['cri_target'];
    $deu_target = $_POST['deu_target'];
    $edificio = $_POST['edificio'];
    $liv_edificio = $_POST['liv_edificio'];
    $prod_met = $_POST['prod_met'];
    $prod_cri = $_POST['prod_cri'];
    $prod_deu = $_POST['prod_deu'];
    $met_liv_prod = $_POST['met_liv_prod'];
    $cri_liv_prod = $_POST['cri_liv_prod'];
    $deu_liv_prod = $_POST['deu_liv_prod'];
    $dif_met = $_POST['dif_met'];
    $dif_cri = $_POST['dif_cri'];
    $dif_deu = $_POST['dif_deu'];
    $sov_met = $_POST['sov_met'];
    $sov_cri = $_POST['sov_cri'];
    $sov_deu = $_POST['sov_deu'];
    $mer_met = $_POST['mer_met'];
    $mer_cri = $_POST['mer_cri'];
    $mer_deu = $_POST['mer_deu'];
    $sto_met = $_POST['sto_met'];
    $sto_cri = $_POST['sto_cri'];
    $sto_deu = $_POST['sto_deu'];
    $mercato = $_POST['mercato'];
    $liv_fornace = $_POST['liv_fornace'];
    $liv_labcry = $_POST['liv_labcry'];
    $liv_depuratore = $_POST['liv_depuratore'];
    if ($num_dep_met == "") $num_dep_met = "1";
    if ($num_dep_cri == "") $num_dep_cri = "1";
    if ($num_dep_deu == "") $num_dep_deu = "1";
    $save = $_GET['save'];
    if (($save) && ($_SESSION['nome'])) {
        $query = "UPDATE `us_pianeti` SET `prod_met`='".$prod_met."' , 
        `prod_cri`='".$prod_cri."' , `prod_deu`='".$prod_deu."' , 
        `dep_met`='".$met_dep."' , `dep_cri`='".$cri_dep."' , 
        `dep_deu`='".$deu_dep."' , `mercato`='".$mercato."' 
        WHERE `nome_pianeta`='".$pianeti[$pianeta - 1]."' AND `id`='".$_SESSION['nome']."'";
        $Db->connect();
        $Db->query($query);

        $oggetto = "invio di risorse su ".$pianeti[$pianeta - 1]." per la costruzione";
        $testo = $_SESSION['username']." sta calcolando quante risorse mandare per costruire ".$edificio." al livello ".$liv_edificio." su ".$pianeti[$pianeta - 1].".\nle risorse presenti sul pianeta sono :\n".
            $sto_met.", ".$sto_cri.", ".$sto_deu.".\nrisorse in arrivo :\n".$mer_met.", ".$mer_cri.", ".$mer_deu."\nmancano le seguenti risorse : ".($dif_met < 0?0:$dif_met).", ".($dif_cri < 0?0:$dif_cri).", ".($dif_deu <
            0?0:$dif_deu);
        $destinatario = $_SESSION['nome'];
        $Db->query("INSERT INTO `us_mess` SET `oggetto`='".$oggetto."' , `messaggio`='".$testo."' , `destinatario`='".$destinatario."' , `mittente`='".$_SESSION['username']."/".$_SESSION['nome']."'");
    }
    echo "<center> 
    <form name=\"push\" action=\"tool.php?action=push&save=push\" method=\"post\" >
        <table style=\"background-image: url(images/center.jpg); border: 4px solid silver;\">
            <tr>
                <td>";
    if ($_SESSION['nome'] != "") {
        echo "
        <select  onchange=\"planet()\" name=\"pianeta\">
        <option value=\"\">sel. un pianeta</option>";
        $var = "seleziona";
        for ($i = 0; $i < $pianeti['tot']; $i++) {
            $sel = $var.$i;
            if ($pianeta == ($i + 1)) $$sel = "selected=\"selected\"";
            else  $$sel = "";
            echo "<option ".$$sel." value=\"".($i + 1)."\">".$pianeti[$i]."</option>";
        }
        echo "</select>
        ";
    }
    echo "</td><td>metallo</td><td>cristallo</td><td>deuterio</td><td>liv deposito</td><td>n. maga</td><td>liv</td><td>n. maga</td><td>liv</td><td>n. maga</td>
            </tr>
            <tr>
                <td>magazzini</td><td><input onkeyup=\"control(this);calc()\" name=\"met_dep\" size=\"9\" value=\"".$met_dep."\" />
                </td><td><input onkeyup=\"control(this);calc()\" name=\"cri_dep\" size=\"9\" value=\"".$cri_dep."\" /></td>
                <td><input onkeyup=\"control(this);calc()\" name=\"deu_dep\" size=\"9\" value=\"".$deu_dep."\" /></td>
                <td><select onchange=\"magazzini()\" name=\"met_liv_dep\">";
    for ($i = 0; $i <= 20; $i++) {
        $sel = $var.$i;
        if ($met_liv_dep == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "</select></td>
                <td><input onclick=\"add(num_dep_met)\" type=\"button\" value=\"+\" /><input onchange=\"magazzini()\" readonly=\"readonly\" value=\"".$num_dep_met."\" maxlength=\"1\" size=\"1\" name=\"num_dep_met\" />
                <input onclick=\"remove(num_dep_met)\" type=\"button\" value=\"-\" /></td>";
    if ($_SESSION['razza'] == "Xen") $dis = "disabled=\"disabled\"";
    echo "<td><select onchange=\"magazzini()\" ".$dis." name=\"cri_liv_dep\">";
    for ($i = 0; $i <= 20; $i++) {
        $sel = $var.$i;
        if ($cri_liv_dep == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "
                </select></td>
                <td><input ".$dis." onclick=\"add(num_dep_cri)\" type=\"button\" value=\"+\" /><input ".$dis." onchange=\"magazzini()\" readonly=\"readonly\" value=\"".$num_dep_cri."\" maxlength=\"1\" size=\"1\" name=\"num_dep_cri\" /><input ".
        $dis." onclick=\"remove(num_dep_cri)\" type=\"button\" value=\"-\" /></td>
                <td><select onchange=\"magazzini()\" name=\"deu_liv_dep\">";
    for ($i = 0; $i <= 20; $i++) {
        $sel = $var.$i;
        if ($deu_liv_dep == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "</select></td>
                <td><input onclick=\"add(num_dep_deu)\" type=\"button\" value=\"+\" />
                <input onchange=\"magazzini()\" readonly=\"readonly\" value=\"".$num_dep_deu."\" maxlength=\"1\" size=\"1\" name=\"num_dep_deu\" />
                <input onclick=\"remove(num_dep_deu)\" type=\"button\" value=\"-\" /></td>
            </tr>
            <tr>
            <td>obbiettivo</td><td><input onkeyup=\"control(this);calc()\" name=\"met_target\" value=\"".$met_target."\" size=\"9\" /></td>
            <td><input onkeyup=\"control(this);calc()\" name=\"cri_target\" value=\"".$cri_target."\" size=\"9\" /></td>
            <td><input onkeyup=\"control(this);calc()\" name=\"deu_target\" value=\"".$deu_target."\" size=\"9\" /></td>
            <td></td><td>sel. struttura</td><td></td><td>
            <select onchange=\"calc();gestisciliv()\" name=\"edificio\">
            <option>sel. struttura</option>";
    if (($_SESSION['razza'] == $enum[2]) || ($_SESSION['razza'] == 0)) {
        echo "<optgroup label=\"Xen\">
                <option value=\"met_xen\">Miniera di metallo</option>
                <option value=\"cri_xen\">Miniera di cristallo</option>
                <option value=\"deu_xen\">Miniera di deuterio</option>
                <option value=\"fornace_xen\">Fornace</option>
                <option value=\"labcri_xen\">Lab. di cristalli</option>
                <option value=\"depuratore_xen\">Dep. di deuterio</option>
                <option value=\"solar_xen\">Centrale solare</option>
                <option value=\"eolica_xen\">Centrale eolica</option>
                <option value=\"termo_xen\">Centrale termica</option>
                <option value=\"nuclear_xen\">Centrale nucleare</option>
                <option value=\"cea_xen\">CEA</option>
                <option value=\"maga_gen\">Magazzino gen.</option>
                <option value=\"serb_deu_xen\">Serb. di deuterio</option>
                <option value=\"bio_xen\">Bioreattore</option>
                <option value=\"piccolo_cantiere_xen\">Piccolo cantiere</option>
                <option value=\"grande_cantiere_xen\">Grande cantiere</option>
                <option value=\"fabrica_armi_xen\">Fabbrica d'armi</option>
                <option value=\"silo_xen\">Silo per razzi</option>
                <option value=\"base_xen\">Base navale</option>
                <option value=\"tana_xen\">Tana Zek</option>
                <option value=\"cervello_xen\">Cervello</option>
                <option value=\"sviluppo_xen\">Centro sviluppo</option>
                <option value=\"amb_xen\">Ambasciata</option>
                <option value=\"costruzioni_xen\">Centro costruzioni</option> 
                <option value=\"deposito_segreto_xen\">Deposito segreto</option> 
                <option value=\"commerciale_xen\">Ctr commerciale</option>
                </optgroup>";
    }
    if (($_SESSION['razza'] == $enum[1]) || ($_SESSION['razza'] == 0)) {
        echo "<optgroup label=\"Terrestri\">
                <option value=\"met_ter\">Miniera di metallo</option>
                <option value=\"cri_ter\">Miniera di cristallo</option>
                <option value=\"deu_ter\">Miniera di deuterio</option>
                <option value=\"fornace_ter\">Fornace</option>
                <option value=\"labcri_ter\">Lab. di cristalli</option>
                <option value=\"depuratore_ter\">Dep. di deuterio</option>
                <option value=\"solar_ter\">Centrale solare</option>
                <option value=\"eolica_ter\">Centrale eolica</option>
                <option value=\"idrica_ter\">Impianto idrico</option>
                <option value=\"nuclear_ter\">Centrale nucleare</option>
                <option value=\"cea_ter\">CEA</option>
                <option value=\"magamet_ter\">Magazzino di met.</option>
                <option value=\"magacri_ter\">Magazzino di crist.</option>
                <option value=\"magadeu_ter\">Ser. di deuterio</option>
                <option value=\"cantiere_ter\">Cantiere navale</option>
                <option value=\"fabrica_armi_ter\">Fabbrica di armi</option>
                <option value=\"silo_ter\">Silo per razzi</option>
                <option value=\"base_ter\">Base militare</option>
                <option value=\"scanner_ter\">Scanner ad imp.</option>
                <option value=\"hangar_ter\">Hangar</option>
                <option value=\"lab_ter\">Lab di ricerca</option>
                <option value=\"amb_ter\">Ambasciata</option>
                <option value=\"centro_ter\">Centro costruzioni</option>
                <option value=\"ricicleria_ter\">Ricicleria</option>
                <option value=\"deposito_ter\">Deposito segreto</option>
                <option value=\"mercato_ter\">Ctr commerciale</option>
                <option value=\"colo_ter\">Centro di coloniz.</option>
                <option value=\"civile_ter\">Cantiere civile</option>
                <option value=\"robotica_ter\">Fabbrica robotica</option>
                </optgroup>";
    }
    if (($_SESSION['razza'] == $enum[0]) || ($_SESSION['razza'] == 0)) {
        echo "<optgroup label=\"Titani\">
                <option value=\"met_tit\">Miniera di metallo</option>
                <option value=\"cri_tit\">Miniera di cristallo</option>
                <option value=\"deu_tit\">Miniera di trizio</option>
                <option value=\"fornace_tit\">Fornace</option>
                <option value=\"labcri_tit\">Lab. di cristalli</option>
                <option value=\"filtro_tit\">Filtro di trizio</option>
                <option value=\"solar_tit\">centrale solare</option>
                <option value=\"eolica_tit\">Centrale eolica</option>
                <option value=\"idrica_tit\">Impianto idrico</option>
                <option value=\"nucleare_tit\">Centrale nucleare</option>
                <option value=\"cea_tit\">CEA</option>
                <option value=\"magamet_tit\">Magazzino di met.</option>
                <option value=\"magacri_tit\">Magazzino di crist.</option>
                <option value=\"magadeu_tit\">Serb. di trizio</option>
                <option value=\"nascondiglio_tit\">Nascond. iper.</option>
                <option value=\"accumulatore_tit\">Accumulatore</option>
                <option value=\"cantiere_tit\">Cantiere navale</option>
                <option value=\"fabbrica_tit\">Fabbrica di armi</option>
                <option value=\"base_tit\">Base navale</option>
                <option value=\"scudo_tit\">Gen. di scudo</option>
                <option value=\"scansione_tit\">Sist. di scansione</option>
                <option value=\"jammer_tit\">Jammer</option>
                <option value=\"transportale_tit\">Transportale</option>
                <option value=\"stealth_tit\">Gen. stealth</option>
                <option value=\"modulatore_tit\">Mod. dimensionale</option>
                <option value=\"lab_tit\">Lab di ricerca</option>
                <option value=\"amb_tit\">Ambasciata</option> 
                <option value=\"centro_tit\">Centro costruzioni</option>
                <option value=\"civile_tit\">Cantiere civile</option>
                <option value=\"teletrasportatore_tit\">Teletrasportatore</option>
                <option value=\"robotica_tit\">Fabbrica robotica</option>
                <option value=\"deposito_tit\">Deposito segreto</option>
                <option value=\"trasmettitore\">Trasmettitore</option>
                </optgroup>";
    }
    echo "</select>
            </td><td>
            <div id=\"liv_edificio\">
            <select onchange=\"calc()\" name=\"liv_edificio\">";
    for ($i = 0; $i <= 20; $i++) {
        $sel = $var.$i;
        if ($liv_edificio == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "
            </select></div>
            </td><td><input onclick=\"addstr(this)\" type=\"button\" value=\"aggiungi\" /></td>
            </tr>
            <tr>
            <td>produzione</td><td><input onkeyup=\"control(this);calc()\" size=\"6\" name=\"prod_met\" value=\"".$prod_met."\" /></td>
            <td><input onkeyup=\"control(this);calc()\" size=\"6\" name=\"prod_cri\" value=\"".$prod_cri."\" /></td>
            <td><input onkeyup=\"control(this);calc()\" size=\"6\" name=\"prod_deu\" value=\"".$prod_deu."\" /></td>
            <td><select name=\"met_liv_prod\">";
    for ($i = 0; $i <= 20; $i++) {
        $sel = $var.$i;
        if ($met_liv_prod == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "</select></td>
                <td><select name=\"cri_liv_prod\">";
    for ($i = 0; $i <= 20; $i++) {
        $sel = $var.$i;
        if ($cri_liv_prod == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "</select></td>
                <td><select name=\"deu_liv_prod\">";
    for ($i = 0; $i <= 20; $i++) {
        $sel = $var.$i;
        if ($deu_liv_prod == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "</select></td>
                <td><input onclick=\"addprod()\" type=\"button\" value=\"aggiungi\" /></td><td><div id=\"num_campi\"></div></td>
                <td><input onclick=\"resetprod()\" value=\"reset\" type=\"button\" /></td>
            </tr>
            <tr>
            <td>differenza</td><td><input onkeyup=\"control(this);calc()\" name=\"dif_met\" size=\"9\" value=\"".$dif_met."\" /></td>
            <td><input onkeyup=\"control(this);calc()\" name=\"dif_cri\" value=\"".$dif_cri."\" size=\"9\" /></td>
            <td><input onkeyup=\"control(this);calc()\" name=\"dif_deu\" value=\"".$dif_deu."\" size=\"9\" /></td>
            <td></td><td><select name=\"liv_fornace\"><option value=\"0\">liv. fornace</option>";
    for ($i = 1; $i <= 5; $i++) {
        $sel = $var.$i;
        if ($liv_fornace == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "</select></td>
                <td>
                <select name=\"liv_labcri\"><option value=\"0\">liv. lab.</option>";
    for ($i = 1; $i <= 5; $i++) {
        $sel = $var.$i;
        if ($liv_labcry == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "</select>
                </td>
                <td>
                <select name=\"liv_depuratore\"><option value=\"0\">liv. depur.</option>";
    for ($i = 1; $i <= 5; $i++) {
        $sel = $var.$i;
        if ($liv_depuratore == $i) $$sel = "selected=\"selected\"";
        else  $$sel = "";
        echo "<option ".$$sel." value=".$i.">".$i."</option>";
    }
    echo "</select>
                </td>
            </tr>
            <tr>
            <td>sovrapush</td><td><input class=\"saturazione\" readonly=\"readonly\" onkeyup=\"control(this);calc()\" name=\"sov_met\" value=\"".$sov_met."\" size=\"9\" /></td>
            <td><input class=\"saturazione\" readonly=\"readonly\" onkeyup=\"control(this);calc()\" name=\"sov_cri\" value=\"".$sov_cri."\" size=\"9\" /></td>
            <td><input class=\"saturazione\" readonly=\"readonly\" onkeyup=\"control(this);calc()\" name=\"sov_deu\" value=\"".$sov_deu."\" size=\"9\" /></td>
            <td>tempo</td><td><div id=\"tempo\"></div></td>
            </tr>
            <tr>
            <td>risorse</td><td><input onkeyup=\"control(this);calc()\" name=\"sto_met\" value=\"".$sto_met."\" size=\"9\" /></td>
            <td><input onkeyup=\"control(this);calc()\" name=\"sto_cri\" value=\"".$sto_cri."\" size=\"9\" /></td>
            <td><input onkeyup=\"control(this);calc()\" name=\"sto_deu\" value=\"".$sto_deu."\" size=\"9\" /></td>
            </tr>
            <tr>
            <td>invii del mercato</td><td><input readonly=\"readonly\" name=\"mer_met\" value=\"".$mer_met."\" size=\"9\" /></td>
            <td><input readonly=\"readonly\" name=\"mer_cri\" value=\"".$mer_cri."\" size=\"9\" /></td>
            <td><input readonly=\"readonly\" name=\"mer_deu\" value=\"".$mer_deu."\" size=\"9\" /></td>
            <td></td><td>aggiungi spedizioni</td><td><input onclick=\"document.getElementById('light').style.display='block'; document.getElementById('fade').style.display='block';\" type=\"button\" value=\"aggiungi\" /></td>
            <td><input onclick=\"resetmer()\" type=\"button\" value=\"reset\" /></td>
            <td><input name=\"mercato\" value=\"".$mercato."\" size=\"2\" maxlength=\"2\" /></td>
            <td>liv.mercato</td>
            </tr>
        </table>";
    if (islog() != "no") {
        echo "<input type=\"submit\" value=\"salva\" />";
    }
    echo "<input onclick=\"calc()\" type=\"button\" value=\"ricalcola\" />
    <input type=\"reset\" value=\"reset\" /></form>
    </center>";
} elseif ($action == "scudo") {
    echo "<form name=\"scudo\"><p><center><textarea name=\"testo\" cols=\"50\" rows=\"10\"></textarea><br />
    <input type=\"button\" value=\"calcola\" onclick='calcola(document.scudo.testo)' />
    <input type=\"reset\" value=\"reset\" /></center></p></form>";
} elseif ($action == "log") {
    if ($_GET['do'] == "do") {
        $testo = $_POST['testo'];
        $allyA = $_POST['allyA'];
        $allyD = $_POST['allyD'];
        $allyS = $_POST['allyS'];
        $link = $_POST['link'];
        $punti = (int)$_POST['punti'];
        $bottino = (int)$_POST['bottino'];
        $detriti = (int)$_POST['detriti'];
        $riciclato = (int)$_POST['riciclato'];
        $testo = str_replace("'","`",$testo);
        $Db->connect();
        $testo = "<center><div align=\"left\" class=\"reportcontainer\"> ".$testo." </div></center>";
        $Db->query("INSERT INTO `log` SET `text`='".$testo."' , `visita`='".date("Y-m-d H:i:s")."'");
        $id = $Db->toarray("SELECT `id` from `log` ORDER BY `id` DESC LIMIT 1");
        $id = trim($id['id']);
        echo $testo."<br />";
        $link .= "http://toolraider.altervista.org/tool.php?id=".$id."\n";
    }
    echo "<form name=\"logger\" action=\"tool.php?action=log&do=do\" method=\"post\"><p><center>";
    if ($_GET['do'] == "do") {
        echo "<div align=\"center\" class=\"reportcontainer\">
    <div>link salvati</div>
    <textarea cols=\"50\" rows=\"5\" name=\"link\" readonly=\"\">".$link."</textarea>
    <div>Punti totali attaccante : ";
        if ($punti > 0) $str = "guadagno";
        else  $str = "danni";
        echo "<span class=\"".$str."\">".$punti."</span></div>
    <div>Detriti totali : <span style=\"color: yellow;\">".$detriti."</span></div>
    <div>Bottino totale : <span style=\"color: yellow;\">".$bottino."</span></div>
    <div>Guadagno teorico : <span style=\"color: yellow;\">".($bottino + $detriti)."</span></div>";
        //<!-- <div>Guadagno reale : <span style="color: yellow;">//echo ($bottino+$riciclato) </span></div> -->
        echo "</div>
    <center>
    <h3><a href=\"tool.php?action=log\">Salva nuovi report</a></h3>
    <h3>Aggiungi report</h3></center>";
    }
    echo "<br /><br />
    <select name=\"option\" onchange=\"showopt(this.value)\">
    <option value=\"0\"> </option>
    <option value=\"1\">Report di combattimento</option>
    <option value=\"2\">Spy report</option>
    <option value=\"3\">Riciclata</option>
    <option value=\"4\">Abbattimento scudo</option>
    </select><br />
    <textarea name=\"testo\" cols=\"50\" rows=\"10\"></textarea><br />
    <div id=\"batle\" style=\"display: none;\">
    <table>
    <tr>
    <td>Visualizza attaccante <input name=\"op1\" type=\"checkbox\" checked=\"checked\" value='1' /></td>
    <td>Visualizza difensore <input name=\"op2\" type=\"checkbox\" checked=\"checked\" value='1' /></td>
    </tr>
    <tr>
    <td>Visualizza ora <input name=\"op3\" type=\"checkbox\" checked=\"checked\" value='1' /></td>
    <td>Visualizza statistiche <input name=\"op4\" type=\"checkbox\" checked=\"checked\" value='1' /></td>
    </tr>
    <tr>
    <td>Visualizza il punto per separare le migliaia <input name=\"op6\" type=\"checkbox\" checked=\"checked\" value='1' /></td>
    <td>Visualizza in unità di misura k <input name=\"op5\" type=\"checkbox\" value='1' /></td>
    </tr>
    <tr>
    <td>Alleanza attaccante <input name=\"allyA\" type=\"text\" value=\"".$allyA."\" /></td>
    <td>Alleanza difensore <input name=\"allyD\" type=\"text\" value=\"".$allyD."\" /></td>
    </tr>
    <tr>
    <td>imposta ricerca di carico attaccante <input name=\"opt7\" type=\"checkbox\" onclick='impval(0,0,this,true)'/></td>
    <td>";
    if (islog()) {
        $Db->connect();
        $ricerche = $Db->toarray("SELECT * FROM `us_ricerche` WHERE `id`='".$_SESSION['nome']."'");
        echo "imposta i propri valori di ricerca <input type=\"checkbox\" onclick=\"impval(".$ricerche['carico_mil'].",".$ricerche['carico_civ'].",this,false)\" />";
    }
    echo "</td>
    </tr>
    <tr>
    <td><select name=\"ricercac\" style=\"display: none;\">
    <option value=\"0\">civile</option>";
    select(10,1);
    echo "</select></td>
    <td><select name=\"ricercam\" style=\"display: none;\">
    <option value=\"0\">militare</option>";
    select(10,1);
    echo "</select></td>
    </tr>
    </table>
    </div>
    <div id=\"spy\" style=\"display: none;\">
    <table>
    <tr>
    <td>Visualizza difensore <input type=\"checkbox\" checked=\"checked\" name=\"sop1\" /></td>
    <td>Alleanza spiata <input name=\"allyS\" value=\"".$allyS."\" /></td>
    </tr>
    <tr>
    <td>Statistiche <input type=\"checkbox\" checked=\"checked\" name=\"sop2\" /></td>
    </tr>
    <tr>
    <td>Visualizza il punto per separare le migliaia <input name=\"sop3\" type=\"checkbox\" checked=\"checked\" value='1' /></td>
    <td>Visualizza in unità di misura k <input name=\"sop4\" type=\"checkbox\" value='1' /></td>
    </tr>
    </table>
    </div>
    <div id=\"rici\" style=\"display: none;\">3</div>
    <input type=\"button\" value=\"preview\" onclick='loggerf(document.logger.option.value,false)' />
    <input type=\"reset\" value=\"reset\" />
    <input type=\"button\" value=\"salva\" onclick=\"loggerf(document.logger.option.value,true);submit()\" />
    <input type=\"hidden\" name=\"punti\" value=\"".$punti."\" />
    <input type=\"hidden\" name=\"bottino\" value=\"".$bottino."\" />
    <input type=\"hidden\" name=\"detriti\" value=\"".$detriti."\" />
    <input type=\"hidden\" name=\"riciclato\" value=\"".$riciclato."\" />
    </center></p></form>
    <center><div id=\"visualizza\" align=\"left\" class=\"reportcontainer\" style=\"display: none;\"></div></center>";
} elseif ($action == "missili") { //simulatore missili
    echo "<center><form name=\"simulatore\">
    <select name=\"attaccante\" onchange=\"selrazza(1)\">
<option value=\"0\">Attaccante</option>
<option value=\"2\">Terrestre</option>
<option value=\"3\">Xen</option>
</select><br />
<table>
<tr id=\"att\" >

</tr>

<tr>
<td><input name=\"missile1\" size=\"4\" onkeyup=\"control(this)\" /></td>
<td><input name=\"missile2\" size=\"4\" onkeyup=\"control(this)\" /></td>
<td><input name=\"missile3\" size=\"4\" onkeyup=\"control(this)\" /></td>
<td><input name=\"missile4\" size=\"4\" onkeyup=\"control(this)\" /></td>
</tr>
</table>
<br /><select name=\"difensore\" onchange=\"selrazza(2)\">
<option value=\"0\">Difensore</option>
<option value=\"1\">Titano</option>
<option value=\"2\">Terrestre</option>
<option value=\"3\">Xen</option>
</select><br />
<table>
<tr id=\"dif\" >

</tr>
<tr>
<td><input name=\"difesa1\" size=\"4\" onkeyup=\"control(this)\" /></td>
<td><input name=\"difesa2\" size=\"4\" onkeyup=\"control(this)\" /></td>
<td><input name=\"difesa3\" size=\"4\" onkeyup=\"control(this)\" /></td>
<td><input name=\"difesa4\" size=\"4\" onkeyup=\"control(this)\" /></td>
<td><input name=\"difesa5\" size=\"4\" onkeyup=\"control(this)\" /></td>
<td><input name=\"intercettore1\" size=\"4\" onkeyup=\"control(this)\" /></td>
<td><input name=\"intercettore2\" size=\"4\" onkeyup=\"control(this)\" /></td>
</tr>


</table>
<span id=\"scudo\">Punti scudo </span>
<input name=\"scudo\" size=\"9\" style=\"display: none;\" />
<input type=\"button\" value=\"simula\" onclick=\"simula(difensore,attaccante)\" />
</form>
<p id=\"risultato\"></p>
</center>";
}

foot();
?>