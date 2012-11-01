<?php
$title = ": aggiungi comete";
session_start();
include ("my_config.php");
include ("inc/foot.php");
include ("functions.php");
include ("form_comete.php");
online(2);
Pagina_protetta(1);
$ally = isally();
if (!$ally) Errore("ally.php","","","0");
$_GET = inputctrl($_GET);
$_POST = inputctrl($_POST);
clean(); // pulizzia db comete

?>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script language="javascript" src="scripts/number.js"></script>
<script language="javascript" src="scripts/cometetool.js"></script>
<script language="Javascript">
<?php
$Db = new db();
$Db->connect();

//****************************cerco i pianeti del giocatore
$query = "SELECT * FROM `us_pianeti` WHERE `id`='".$_SESSION['id']."'";
$row = $Db->totable($query);
?>
var dati= new Array();//e li metto nell'array dati
<?php
$i = 0;
while ($row[$i]) {
?>
         dati[<?php
    echo $i;
?>]=new Array();
         dati[<?php
    echo $i;
?>]['id']='<?php
    echo addslashes($row[$i]['id']);
?>';
         dati[<?php
    echo $i;
?>]['g']='<?php
    echo addslashes($row[$i]['g']);
?>';
         dati[<?php
    echo $i;
?>]['x']='<?php
    echo addslashes($row[$i]['x']);
?>';
         dati[<?php
    echo $i;
?>]['y']='<?php
    echo addslashes($row[$i]['y']);
?>';
         dati[<?php
    echo $i;
?>]['nome_pianeta']='<?php
    echo addslashes($row[$i]['nome_pianeta']);
?>';
<?php
    $i++;
}
$ric = $Db->toarray("SELECT * FROM `us_ricerche` WHERE `id`='".$_SESSION['nome']."'");
?>
var livc=<?php
echo $ric['carico_civ'];
?>;
var livr=<?php
echo $ric['riciclaggio'];
?>;
var livv=<?php
echo $ric['propulsione_civ'];
?>;
<?php
$Db->close();
?>
</script> 
 
<?php
menu();

$action = $_GET['action'];

if ($action == "insert_do") {
    $name = $_POST['name'];
$time_r=24;
    // Controllo inserimento dati
    if (trim($name) == "") {
        Errore("add.php","Errore","I campi devono essere riempiti");
    } else {
        $name = addslashes(stripslashes($name));
        
        $idk = str_replace("<","&lt;",$name);
        $data = date("Y-m-d H:i:s");
        $user = $_SESSION['nome'];
        $Db->connect();
        // Sub-Check 1: Controllo cometa già esistente
        $query = "SELECT * from `ct_comet` WHERE id_comet = '".$idk."' AND `ally`='".$ally."'";
        $riga = $Db->toarray($query);
        $num_righe = $Db->n;
        if ($num_righe) {
            
                $avviso = "La cometa inserita è già utilizzata da ".$riga['user_comet'];
                Errore("add.php","Errore",$avviso);
            
        }
        $scadenza = date("Y-m-d H:i:s",strtotime("+".$time_r." hours"));
        $query = "INSERT INTO `ct_comet` SET `id_comet`='".$idk."' , `inserita`='".$data."' ,
             `scadenza`='".$scadenza."' , `user_comet`='".$user."' , `ally`='".$ally."'";
        $Db->query($query);
        echo "<br></br> ";
        echo "cometa ".$name." aggiunta con successo";
        $num = $Db->conta("SELECT * FROM `ct_cronologia` WHERE `data`='".date("Y-m-d")."' AND `uid`='".$_SESSION['nome']."'");
        if (!$num) $Db->query("INSERT INTO `ct_cronologia` SET `uid`='".$_SESSION['nome']."' , `numero`='1' , `data`='".date("Y-m-d")."'");
        else {
            $num = $Db->toarray("SELECT `numero` FROM `ct_cronologia` WHERE `data`='".date("Y-m-d")."' AND `uid`='".$_SESSION['nome']."'");
            $Db->query("UPDATE `ct_cronologia` SET `numero`='".($num['numero'] + 1)."' WHERE `data`='".date("Y-m-d")."' AND `uid`='".$_SESSION['nome']."'");
        }
        $Db->close();
    }
}
echo "<p><center><a href=\"add.php\">aggiungi</a> | <a href=\"listk.php?list=mycomet&start=0&sort=idasc\">visualizza le mie comete</a> | <a href=\"listk.php?list=comet&start=0&sort=idasc\">visualizza tutte le comete</a> | <a href=\"listk.php?list=filter\">filtri comete</a>";
$priv = privilegi($ally,array('g'),"",1);
if ($priv['bool']) echo " | <a href=\"listk.php?list=cronologia\">cronologia comete</a> ";
echo "</center></p>
<br />
<p><center>";
insertK("","",$o1,$o1,$o2);
echo "
</center></p>
<p><b>Stima calcolo di riciclamento</b></p>
<form name=stima>
<table border=0>
<tr><td>selezione nave</td><td>numero navi</td><td>metallo</td><td>cristallo</td><td>idrogeno</td><td>totale</td><td>capacità totale</td><td>tempo stimato in ore</td><td>durata  viaggio</td></tr>";
switch ($_SESSION['razza']) {
    case "Titani":
        $s1 = "selected=selected";
        break;
    case "Terrestri":
        $s2 = "selected=selected";
        break;
    case "Xen":
        $s3 = "selected=selected";
        break;
}
echo "<tr><td><select name=capacity onChange='calc();estrai();misto()'>
	<option value=20000>grande riciclatore</option>
	<option value=500 ".$s2.">riciclatore terrestre</option>
	<option value=1000 ".$s1.">riciclatore titano</option>
	<option value=800 ".$s3.">octopon</option>
</select></td><td>
<input size=\"3\" name=\"number\" onchange='controlnumber(this);calc()' value=\"0\" /></td><td>
<input size=\"7\" name=\"metallo\" onchange='controlnumber(this);calc()' value=\"0\" /></td><td>
<input size=\"7\" name=\"cristallo\" onchange='controlnumber(this);calc()' value=\"0\" /></td><td>
<input size=\"7\" name=\"deuterio\" onchange='controlnumber(this);calc()' value=\"0\" /></td><td>
<input readonly=\"readonly\" size=\"7\" name=\"risorse\" value=\"0\" /></td><td>
<input readonly=\"readonly\" size=\"7\" name=\"captot\" value=\"0\" /></td><td>
<input readonly=\"readonly\" size=\"7\" name=\"tempo\" value=\"0\" /></td><td>
<input size=\"3\" name=\"durata\" readonly=\"readonly\" onchange='calc()' value=\"2\" /></td></tr>
</table>
<input type=\"checkbox\" name=\"mist\" onchange='misto()' /><span id=\"misto\"></span>
<br />
<input name=\"reset\" type=\"reset\" value=\"reset\" />
<input name=\"ricalcola\" type=\"button\" value=\"ricalcola\" onclick='estrai()' />
<br />
<abbr title='copia il tuo contenuto della pagina della cometa, per farlo digita ctrl+a oppure clikka col tasto destro sulla pagina della cometa, e seleziona \"seleziona tutto\" poi copia il contenuto (ctrl+c) ed incollalo qui (ctrl+v)'><b>area copia incolla</b></abbr>
<br /><br />
<textarea onchange='estrai()' name=\"txt\" rows=\"10\" cols=\"100\"></textarea>
</form>
";
foot();
?>