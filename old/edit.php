<?php
$title = ": modifica";
session_start();
include ("my_config.php");
include ("inc/foot.php");
include ("functions.php");
$Db = new db();
online(5);
Pagina_protetta(1);
?>
<script language="javascript">
function control()
{
    document.insert.link.value=document.insert.link.value.match(/[0-9]{8}/gi);
    if (document.insert.link.value=null) alert("l'id deve essere numerico");
}
</script>
<?php
menu();

$_POST = inputctrl($_POST);
$_GET = inputctrl($_GET);
$action = $_GET['action'];
$lid = (int)$_GET['lid'];
$table = (int)$_GET['table'];
if ($table != "1") $table = "2";
if (($action == "edit") && ($lid != "")) {
    // form per editare ****************************************
    $Db->connect();
    $query = "SELECT * FROM `us_list` WHERE `lid`='".$lid."' AND `type`=".$table;
    $riga = $Db->toarray($query);
    if (($riga['uid'] != $_SESSION['nome']) && (islog() != "admin")) Errore("javascript:history.back()","permesso negato","bisogna essere admin o proprietari del link");
    echo "<form name=insert action=\"edit.php?action=do_edit&lid=".$lid."\" method=post>
    <center><table>
    <tr>
    <td>lista</td>
    <td>pianeta di invio</td>
    <td>nome farm</td><td>id pianeta</td>
    <td>commento</td>
    <td>tipo nave</td>
    <td>numero</td></tr>
    <tr>
    <td><select name=\"table2\">
    <option value=\"1\">farm attive</option>
    <option value=\"2\">farm inattive</option>
    </select><input type=\"hidden\" name=\"table1\" value=\"".$table."\" /></td>
    <td><input type=\"text\" name=\"nome_lista\" value=\"".$riga['nome_lista']."\" /></td>
    <td><input type=\"text\" name=\"nome_farm\" value=\"".$riga['nome_farm']."\" /></td>
    <td><input type=\"text\" name=\"link\" value=\"".$riga['link']."\" size=\"8\" maxlength=\"8\" onChange='control()' /></td>
    <td><input type=\"text\" name=\"comment\" value=\"".$riga['comment']."\" /></td><td>
    <select nome=\"tipo_nave\">";
    switch ($riga['tipo_nave']) {
        case 0:
            $sel0 = "selected=selected";
            break;
        case 1:
            $sel1 = "selected=selected";
            break;
        case 2:
            $sel2 = "selected=selected";
            break;
        case 3:
            $sel3 = "selected=selected";
            break;
        case 4:
            $sel4 = "selected=selected";
            break;
        case 5:
            $sel5 = "selected=selected";
            break;
    }
    switch ($_SESSION['razza']) {
        case 0:
            echo "non hai selezionato la razza nel pannello di controllo";
            break;
        case "Titani":
            echo "<option value=\"0\" ".$sel0." ></option><option value=\"1\" ".$sel1." >piccolo trasportatore</option><option value=\"2\" ".$sel2." >grande trasportatore</option><option value=\"3\" ".$sel3.
                " >corsair</option>";
            break;
        case "Terrestri":
            echo "<option value=\"0\" ".$sel0." ></option><option value=\"1\" ".$sel1." >piccolo trasportatore</option><option value=\"2\" ".$sel2." >caccia</option><option value=\"3\" ".$sel3.
                " >cacciatorpediniere</option><option value=\"4\" ".$sel4." >corazzata</option><option value=\"5\" ".$sel5." >incrociatore</option>";
            break;
        case "Xen":
            echo "<option value=\"0\" ".$sel0." ></option><option value=\"1\" ".$sel1." >mylon</option><option value=\"2\" ".$sel2." >xnair</option><option value=\"3\" ".$sel3." >maxtron</option><option value=\"4\" ".
                $sel4." >nave madre</option>";
            break;
    }
    echo "</select></td><td><input size=5 name=\"num_nave\" value=\"".$riga['num_nave']."\" ></td><td>
    <input type=\"submit\" value=\"modifica\" ></td></tr></table></center></form>";
    $Db->close();
} elseif (($action == "do_edit") && ($lid!="")) {
    $table2 = (int) $_POST['table2'];
    $table1 = (int)$_POST['table1'];
    $nome_lista = $_POST['nome_lista'];
    $nome_farm = $_POST['nome_farm'];
    $link = $_POST['link'];
    $comment = $_POST['comment'];
    $tipo_nave = $_POST['tipo_nave'];
    $num_nave = $_POST['num_nave'];
    if ($table1 != "1") $table1 = "2";
    if ($table2 != "1") $table2 = "2";
    if (trim($nome_lista) == "" or trim($nome_farm) == "" or trim($link) == "") {
        Errore("javascript:history.back();","Errore","I campi devono essere riempiti");
    } 
    else {
        $Db->connect();
        $n="";
        if ($table1 != $table2) {
            $query = "SELECT count(*) AS tot FROM `us_list` WHERE uid='".$_SESSION['nome']."' AND nome_lista='".$nome_lista."' AND `type`=".$table2;
            $row = $Db->toarray($query);
            if ($row['tot'] == "") $row['tot'] = 1;
            else  $row['tot']++;
            $n="`n`='".$row['tot']."' ,";
        }
        
        $query = "UPDATE `us_list` SET `type`=".$table2." , ".$n."
             `uid`='".$_SESSION['nome']."' , `nome_lista`='".$nome_lista."' , `nome_farm`='".$nome_farm."' ,
             `link`='".$link."' , `comment`='".$comment."' , `tipo_nave` = '".$tipo_nave."' , 
             `num_nave`='".$num_nave."' WHERE `lid`='".$lid."'";
        $Db->query($query);
        $Db->close();
        riordina();
        ok("list.php","modifiche apportate con successo");
    }
}

foot();
?>