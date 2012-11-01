<?php
$title = ": raid";
session_start();
include ("functions.php");
include ("my_config.php");
include ("inc/foot.php");
online(5);
pagina_protetta(1);
$Db = new db();
echo '
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<!-- libreria locale
<script language="javascript" src="scripts/jquerymin.js"></script> -->
<script language="javascript" src="scripts/raid.js"></script>
<script language="Javascript">
    razza="'.$_SESSION['razza'].'";
    razza=razza.toLowerCase();
    razza=razza.substr(0,3);
	var b=false;
</script>
';

menu();
$_GET = inputctrl($_GET);
$raid = $_GET['raid'];

if ($raid == "raid") $n_list = $_SESSION['listname'];
$_SESSION['listname'] = $n_list;
//controllo $table
if (($table == "") && ($raid == "raid")) $table = $_SESSION['listtype'];
$_SESSION['listtype'] = $table;
if (!in_array($table,array(1,2))) $table = 2;

$Db->connect();

if (($raid == "agg") && ($id != "")) {
    $date = date("Y-m-d H:i:s");
    $Db->query("UPDATE `us_list` SET `date`='".$date."' WHERE `lid`='".$id."' ");
}

// *****************************************comendi di visualizzazione**************************
echo "<center>Seleziona pianeta<br />
	<form name=\"link\" action=\"raid.php\" method=\"post\">";
$query = "SELECT * FROM `us_list` WHERE `uid` = '".$_SESSION['nome']."' AND `type`=".$table." GROUP BY `nome_lista` ORDER BY `nome_lista` ASC";
$en1 = "";
$en2 = "";
if ($table == 1) $en1 = "selected=selected";
else  $en2 = "selected=selected";
echo "<select name=listtype onchange='changemenuplanet(this.value)'>
<option value=\"1\" ".$en1.">farm attive</option>
<option value=\"2\" ".$en2.">farm inattive</option>
</select>
<select name=\"listname\" id=\"listname\" onchange=\"stepl(this.value,document.link.listtype.value);richiesta(this,document.link.listtype.value,document.link.index.value,'',document.link.step.value,document.link.opt.value)\"><option value= selected=selected>      </option>";
if (!$raid) {
    $riga = $Db->totable($query);
    for ($i = 0; $riga[$i]; $i++) {
        if ($riga[$i]['nome_lista'] != $n_list) $sel = "";
        else  $sel = "selected=\"selected\"";
        echo "<option ".$sel." value=\"".$riga[$i]['nome_lista']."\">".$riga[$i]['nome_lista']."</option>";
    }
}
echo "</select> ";
if ($raid) echo "<script language=\"javascript\">
$.ajax({
    url : \"query.php\" ,
    type : \"POST\" ,
    data : \"action=down\" ,
    success : function (data,stato) {
        vet=data.split(\",\");
        document.link.listtype.selectedIndex=vet[0];
        changemenuplanet(document.link.listtype.value)
        document.link.listname.selectedIndex=vet[1];
        n_list=document.link.listname.value;
        list=document.link.listtype.value;
        stepl(n_list,list);     
        richiesta(document.link.listname,document.link.listtype.value,document.link.index.value,'',document.link.step.value,document.link.opt.value)
    },
    error : function (richiesta,stato,errori) {
        alert(\"E' evvenuto un errore. Il stato della chiamata: \"+stato+\" errore \"+errori);
    }
});
</script>";

echo "numero link <select name=\"step\" id=\"step\" onchange=\"richiesta(document.link.listname,document.link.listtype.value,document.link.index.value,'',document.link.step.value,document.link.opt.value)\">
</select>
	<input type=\"button\" value=\"back\" onClick='backl()'>
	<input type=\"button\" value=\"next\" onClick='nextl()'><br />";
//vai al numero
echo "<input type=\"hidden\" name=\"azione\" value=\"".$action."\">
    <input  name=\"index\" id=\"index\" size=\"2\"><input type=\"button\" value=\"vai\" onclick='richiesta(document.link.listname,document.link.listtype.value,document.link.index.value,\"\",document.link.step.value,document.link.opt.value)' /><br />";

// ****************************************selezione nave***************************

echo "cambia tipo di navi da inviare numero <input size=\"4\" name=\"num_nave\" value=\"".$num_navi."\" >
    <select name=\"tipo_nave\"><option value=0>reset</option>";
switch ($_SESSION['razza']) {
    case "Titani":
        echo "<option value=1>piccolo trasportatore</option><option value=2>grande trasportatore</option><option value=3>corsair</option>";
        break;
    case "Terrestri":
        echo "<option value=1>piccolo trasportatore</option><option value=2>caccia</option><option value=3>cacciatorpediniere</option><option value=4>corazzata</option><option value=5>incrociatore</option>";
        break;
    case "Xen":
        echo "<option value=1>mylon</option><option value=2>xnair</option><option value=3>maxtron</option><option value=4>nave madre</option>";
        break;
}
echo "</select><input type=\"button\" value=\"cambia\" onClick='changenave()'>
<input type=\"hidden\" name=\"opt\" value=\"".$opt."\">
	</form><div id=\"visualizza\"></div></center>";

foot();
?>