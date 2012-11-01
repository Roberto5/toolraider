<?php
$title = ": lista farm";
session_start();
include ("my_config.php");
include ("inc/foot.php");
include ("inc/config.php");
include ("functions.php");
$Db = new db();
online(5);
Pagina_protetta(1);

echo '
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<!-- libreria locale 
<script language="javascript" src="scripts/jquerymin.js"></script>-->
<script language="Javascript" src="scripts/list.js"></script> ';

menu();
// bivio lista farm attive e inattive
echo "<p><center><a href=\"list.php?list=activ\">lista farm attive</a> | <a href=\"list.php?list=inactiv\">lista farm inattive</a> | <a href=\"list.php?list=add\">aggiungi link</a> | <a href=\"list.php?list=cerca\">Cerca</a></center></p>";
$_GET = inputctrl($_GET);
$_POST = inputctrl($_POST);

$list = $_GET['list'];
$sort = "idasc";
$start = 0;



//passo di default
$step = 15;

if ($list == "activ") { // stampa lista farm attive*************************************
switch ($list) {
    case "activ":
        $list = 1;
        break;
    case "inactiv":
        $list = 2;
        break;
}
    $Db->connect();
    echo "
	<center>Seleziona pianeta<br />
	<form name=\"list\">";
    $query = "SELECT `nome_lista` FROM `us_list` WHERE `uid` = '".$_SESSION['nome']."' AND `type`=1 GROUP BY `nome_lista` ORDER BY `nome_lista` ASC ";
    echo "<select name=listname onchange=\"richiesta(this,'".$list."','".$start."','".$sort."','".$step."',0)\"><option value=\"\" selected=\"selected\">      </option>";
    $riga = $Db->totable($query);
    for ($i = 0; $riga[$i]; $i++) {
        echo "<option value=\"".$riga[$i]['nome_lista']."\">".$riga[$i]['nome_lista']."</option>";
    }
    echo "</select></form><div id=\"visualizza\"></div></center>";
    $Db->close();
} elseif ($list == "inactiv") {
    switch ($list) {
    case "activ":
        $list = 1;
        break;
    case "inactiv":
        $list = 2;
        break;
}
    $Db->connect();
    echo "
	<center>Seleziona pianeta<br />
	<form name=\"list\">";
    $query = "SELECT `nome_lista` FROM `us_list` WHERE `uid` = '".$_SESSION['nome']."'  AND `type`=2 GROUP BY `nome_lista` ORDER BY `nome_lista` ASC ";
    $riga = $Db->totable($query);
    echo "<select name=listname onchange=\"richiesta(this,'".$list."','".$start."','".$sort."','".$step."',0)\"><option value=\"\" selected=\"selected\">      </option>";
    for ($i = 0; $riga[$i]; $i++) {
        echo "<option value=\"".$riga[$i]['nome_lista']."\">".$riga[$i]['nome_lista']."</option>";
    }
    echo "</select></form><div id=\"visualizza\"></div></center>";
    $Db->close();
} elseif ($list == "add") {
    if ($_SESSION['listtype'] == "") $listtype = "1";
    else  $listtype = $_SESSION['listtype'];
    if ($_SESSION['listname'] == "") $listname = "";
    else  $listname = $_SESSION['listname'];
    if ($listtype == "1") $en1 = "selected=selected";
    else  $en2 = "selected=selected";
    echo "<center><form name=\"addlink\" action=\"list.php?list=add_do\" method=\"POST\">Seleziona lista
	<select name=\"sel\">
	<option value=\"1\" ".$en1.">farm attive</option>
	<option value=\"2\" ".$en2.">farm inattive</option>
	</select> pianeta di partenza
	<select name=\"n_list\">";
    $Db->connect();
    $query = "SELECT * FROM `us_pianeti` WHERE id='".$_SESSION['nome']."'";
    $riga = $Db->totable($query);
    $en1 = "";
    for ($i = 0; $riga[$i]; $i++) {
        if ($listname == $riga[$i]['nome_pianeta']) $en1 = "selected=selected";
        else  $en1 = "";
        echo "<option value=".$riga[$i]['nome_pianeta']." ".$en1.">".$riga[$i]['nome_pianeta']."</option>";
    }
    switch ($_SESSION['razza']) {
        case 0:
            $testo = "non hai selezionato la razza! ";
            break;
        case "Titani":
            $testo = "tipi di nave: [piccolo trasportatore] oppure [1], [grande trasportatore] oppure [2], [corsair] oppure [3] ";
            break;
        case "Terrestri":
            $testo = "tipi di nave: [piccolo trasportatore] oppure [1], [caccia] o [2], [cacciatorpediniere] o [3], [corazzata] o [4], [incrociatore] o [5]";
            break;
        case "Xen":
            $testo = "tipi di nave: [mylon] o [1], [xnair] o [2], [maxtron] o [3], [nave madre] o [4]";
            break;
    }
    echo "</select><br />
	<input type=\"button\" value=\"aggiungi\" onClick='control()'><input type=\"reset\" value=\"reset\"><br />
	<div><abbr title=\"l'id è il numero a 8 cifre del pianeta &#10;
    sintassi per inserire gli id: &#10;
    12345678 &quot;nome&quot; [tiponave] {numero} (commento) &#10;".$testo."
    \">istruzioni</abbr></div>
	<textarea name=testo rows=15 cols=50></textarea><br />
    opzioni da inserire nei link<input name=text value=\"".$_SESSION['opt']."\"/><br /><select name=\"optnav\" onchange='document.addlink.text.value=document.addlink.text.value.replace(/\[[0-9]{1}\]/gi,\"\")+\"[\"+this.value+\"]\"'><option value=\"0\"></option>";
    switch ($_SESSION['razza']) {
        case "Titani":
            echo "<option value=\"1\">piccolo trasportatore</option><option value=\"2\">grande trasportatore</option><option value=\"3\">corsair</option>";
            break;
        case "Terrestri":
            echo "<option value=\"1\">piccolo trasportatore</option><option value=\"2\">caccia</option><option value=\"3\">cacciatorpediniere</option><option value=\"4\">corazzata</option><option value=\"5\">incrociatore</option>";
            break;
        case "Xen":
            echo "<option value=\"1\">mylon</option><option value=\"2\">xnair</option><option value=\"3\">maxtron</option><option value=\"4\">nave madre</option>";
            break;
    }
    echo "</select> n° <input size=\"3\" maxlength=\"9\" onchange='document.addlink.text.value=document.addlink.text.value.replace(/\{[0-9]{1,9}\}/gi,\"\")+\"{\"+this.value+\"}\"' /><br />
    <input type=button onClick='estrai()' value=elabora />
	</center><br /><br />";
} elseif ($list == "add_do") {
    $tabel = (int)$_POST['sel'];
    $n_list = $_POST['n_list'];
    $testo = $_POST['testo'];
    $opt = $_POST['text'];

    if (!$opt) $_SESSION['opt'] = $opt;
    if (($n_list == "") || ($testo == "")) Errore("list.php","errore","compilazione dati");
    if (!in_array($tabel,array(1,2))) Errore("list.php","errore","seleziona una lista");
    $_SESSION['listtype'] = $tabel;
    $_SESSION['listname'] = $n_list;
    //***************************estrai id
    //per imperion **************
    //sintassi  12345678 "nome" [tiponave] {numero} (commento)
    $comment = "";
    $link = "";
    $numn = "";
    $tipon = "";
    $nomel = "";
    //echo "<textarea>" . $testo . "</textarea>";
    $t = explode("\n",$testo);
    $i = 0;
    while ($t[$i]) {
        //cerca l'id
        if (eregi("^[0-9]{8,9}",$t[$i],$link2)) {
            $link[] = $link2[0];
        }
        //cerca il nome link
        if (eregi("[\"]+[a-zA-Z 0-9]+[\"]",$t[$i],$nomel2)) {
            $nomel[] = $nomel2[0];
        } else  $nomel[] = $link2[0];
        //cerca il commento
        if (eregi("[(]+[a-zA-Z 0-9]+[)]",$t[$i],$comment2)) {
            $comment[] = $comment2[0];
        } else  $comment[] = " ";
        //cerca il tipo di nave
        if (eregi("\[+[a-zA-Z0-9]+\]",$t[$i],$tipon2)) {
            $tipon[] = $tipon2[0];
        } else  $tipon[] = "0";
        //cerca il numero
        if (eregi("\{+[0-9]+\}",$t[$i],$numn2)) {
            $numn[] = $numn2[0];
        } else  $numn[] = "0";
        $i++;
    }
    
    $n = 0;
    while ($link[$n]) {
        $n++;
    }
    $Db->connect();
    $query = "SELECT count(*) AS tot FROM `us_list` WHERE uid='".$_SESSION['nome']."' AND nome_lista='".$n_list."' AND type=".$tabel."";
    $row = $Db->toarray($query);
    if ($row['tot'] == "") $row['tot'] = 1;
    else  $row['tot']++;
    for ($i = 0; $i < $n; $i++) {
        $tipon[$i] = strtolower($tipon[$i]);
        $tipon[$i] = str_replace("[","",$tipon[$i]);
        $tipon[$i] = str_replace("]","",$tipon[$i]);
        if ($_SESSION['razza'] == "Xen") {
            switch ($tipon[$i]) {
                case "1":
                case "mylon":
                    $type = 1;
                    break;
                case "2":
                case "xnair":
                    $type = 2;
                    break;
                case "3":
                case "maxtron":
                    $type = 3;
                    break;
                case "4":
                case "nave madre":
                    $type = 4;
                    break;
                default:
                    $type = 0;
                    break;
            }
        } elseif ($_SESSION['razza'] == "Terrestri") {
            switch ($tipon[$i]) {
                case "1":
                case "piccolo trasportatore":
                    $type = 1;
                    break;
                case "2":
                case "caccia":
                    $type = 2;
                    break;
                case "3":
                case "cacciatorpediniere":
                    $type = 3;
                    break;
                case "4":
                case "corrazzata":
                    $type = 4;
                    break;
                case "5":
                case "incrociatore":
                    $type = 5;
                    break;
                default:
                    $type = 0;
                    break;
            }
        } elseif ($_SESSION['razza'] == "Titani") {
            switch ($tipon[$i]) {
                case "1":
                case "piccolo trasportatore":
                    $type = 1;
                    break;
                case "2":
                case "grande trasportatore":
                    $type = 2;
                    break;
                case "3":
                case "corsair":
                    $type = 3;
                    break;
                default:
                    $type = 0;
                    break;
            }
        }
        $comment[$i] = str_replace("(","",$comment[$i]);
        $comment[$i] = str_replace(")","",$comment[$i]);
        $nomel[$i] = str_replace("\"","",$nomel[$i]);
        $nomel[$i] = str_replace("\"","",$nomel[$i]);
        $num = str_replace("{","",$numn[$i]);
        $num = str_replace("}","",$num);
        if (!$Db->conta("SELECT * FROM `us_list` WHERE `link`='".$link[$i]."'")) {
            $query = "INSERT INTO `us_list` SET `type`=".$tabel." , `n`='".$row['tot']."' ,
             `uid`='".$_SESSION['nome']."' , `nome_lista`='".$n_list."' , `nome_farm`='".$nomel[$i]."' ,
              `link`='".$link[$i]."' , `comment`='".$comment[$i]."' , `tipo_nave` = '".$type."' ,
               `num_nave`='".$num."'";
            $Db->query($query);
            $row['tot']++;
        } else {
            echo "link ".$link[$i]." è già esistente <br/>";
        }
    }
    $Db->close();
    ok("javascript:history.back()","ok","inserimento eseguito con successo");
} elseif ($list == "cerca") {
    echo "
    <center><form action=\"list.php?list=do\" method=\"post\">
    <select name=\"table\">
    <option value=\"1\">farm attive</option>
    <option value=\"2\" selected=\"selected\">farm passive</option>
    </select>
    inserisci l'id da cercare <input name=\"id\" size=\"8\" maxlength=\"9\" onchange=\"control(this)\" /> 
    <input type=\"submit\" value=\"cerca\" /></form>
    </center>";
} elseif ($list == "do") {
    $Db->connect();
    $id = $_POST['id'];
    $table = (int)$_POST['table'];
    if (!in_array($table,array(1,2))) Errore("index.php","ERRORE","lista errata");
    $query = "SELECT * FROM `us_list` WHERE `link`='".$id."' AND `type`=".$table;
    $num = $Db->conta($query);
    echo "<center><p>numero risultati ".$num."</p></center>";
    visualizza("",$query,"`n` ASC",0,30,$table,"",1);
} else {
    echo "<center><h2>Farm non ancora raidate oggi</h2><br /><form name=\"list\"><select name=\"table\" onchange=\"changemenuplanet(this.value);richiesta(document.list.listname,document.list.table.value,'".
        $start."','".$sort."','".$step."',1)\">
    <option value=\"1\" >farm attive</option>
    <option value=\"2\" selected=\"selected\">farm passive</option>
    </select> </center>";
    $step = 15;
    $Db->connect();
    echo "<center>Filtra pianeta<br />";
    $query = "SELECT `nome_lista` FROM `us_list` WHERE `uid` = '".$_SESSION['nome']."' AND `type`=2 GROUP BY `nome_lista` ORDER BY `nome_lista` ASC ";
    echo "<select id=\"listname\" name=\"listname\" onchange=\"richiesta(this,document.list.table.value,'".$start."','".$sort."','".$step."',1)\"><option value=\"\" selected=\"selected\">      </option>";
    $riga = $Db->totable($query);
    for ($i = 0; $riga[$i]; $i++) {
        echo "<option value=\"".$riga[$i]['nome_lista']."\">".$riga[$i]['nome_lista']."</option>";
    }
    echo "</select></form><br />numero risultati : <span id=\"ris\"></span><div id=\"visualizza\"></div></center>";
    $Db->close();
    echo "<script language=\"javascript\">
richiesta(document.list.listname,document.list.table.value,'".$start."','".$sort."','".$step."',1)
</script>";
}
foot();
?>