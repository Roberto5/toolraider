<?php
session_start();
include ("functions.php");
include ("my_config.php");
include ("inc/foot.php");
online(6);
$type=Pagina_protetta(1);
$Db = new db();
$_GET = inputctrl($_GET);
$_POST = inputctrl($_POST);

echo '<script language="javascript">
function seltt(oggetto)
{
    if (oggetto.checked) {
        with (document.modulo) {
            for (var i=0; i < elements.length; i++) {
                if (elements[i].type == \'checkbox\') elements[i].checked = true;
            }
        }
    }
    else { with (document.modulo) 
        {
            for (var i=0; i < elements.length; i++) {
				if (elements[i].type == \'checkbox\' ) elements[i].checked = false;
            }
        }
    }

}	

</script>';

menu();

$action = $_GET['action'];

if ($action == "send") {
    $oggetto = $_GET['oggetto'];
    $testo = $_POST['testo'];
    $dest = $_GET['dest'];
    echo "<center><form action=\"mess.php?action=do_send\" method=\"post\" >
    <select name=\"destinatario\">
    <option value=\"".$_SESSION['nome']."\" >".$_SESSION['nome']."</option>";
    //alleanza
    if ($dest) echo "<option value=\"".$dest."\">".$dest."</option>";
    echo "</select><br />
    <input name=\"oggetto\" size=\"50\" maxlength=\"40\" value=\"".$oggetto."\" /><br />
<textarea name=\"testo\" cols=\"50\" rows=\"20\" >".$testo."</textarea><br />
<input type=\"submit\" value=\"invia\" /></form></center>";
}
if ($action == "do_send") {
    $Db->connect();
    $oggetto = $_POST['oggetto'];
    $testo = $_POST['testo'];
    $destinatario = $_POST['destinatario'];
    if ($oggetto == "") $oggetto = "nessun oggetto";
    $Db->query("INSERT INTO `us_mess` SET `oggetto`='".$oggetto."' , `messaggio`='".$testo."' , `destinatario`='".$destinatario."' , `mittente`='".$_SESSION['username']."/".$_SESSION['nome']."'");

}
if ($action == "read") {
    $Db->connect();
    $id = $_GET['id'];
    if ($id) {
        $mess = $Db->toarray("SELECT * FROM `us_mess` WHERE `id`='".$id."'");
        echo "<center>
        <form name=\"modulo\" action=\"mess.php?action=send&oggetto=re:".$mess['oggetto']."\" method=\"post\">
        ".$mess['oggetto']."<br />
        <textarea name=\"testo\" readonly=\"readonly\" cols=\"50\" rows=\"20\" >".$mess['messaggio']."</textarea>
        <br />
        <input type=\"button\" value=\"rispondi\" onclick=\"document.modulo.testo.value='\\n\\n".$mess['mittente']." ha scritto\\n_________\\n'+document.modulo.testo.value;document.modulo.submit()\" /></center>";
        if (!$Db->conta("SELECT * FROM `us_read` WHERE `id`='".$mess['id']."' AND `user`='".$_SESSION['username']."'")) $Db->query("INSERT INTO `us_read` SET `id`='".$mess['id']."' , `user`='".$_SESSION['username']."'");
    }
}
if ($action == "canc") {
    $tot = $_POST['tot'];
    $Db->connect();
    for ($i = 0; $i < $tot; $i++) {
        $variabile = 'check'.$i;
        $id = "";
        $id = (int)$_POST[$variabile];
        if ($id) {
            if ($type == "3") {
                $Db->query("DELETE FROM `us_mess` WHERE `id`='".$id."' LIMIT 1");
                $Db->query("DELETE FROM `us_read` WHERE `id`='".$id."'");
            } else  echo "<center>non puoi cancellare un quanto sei sharer.</center>";
        }
    }
}

echo "<center><a href=\"mess.php?action=send\" >Invia messaggio</a><br />";

$Db->connect();
$i = $Db->conta("SELECT `us_mess`.`id` FROM `us_mess`,`us_read` 
    WHERE `destinatario`='".$_SESSION['nome']."' 
    AND `us_read`.`user`!='".$_SESSION['username']."' 
    AND `mittente` NOT LIKE '%".$_SESSION['username']."%' 
    AND `us_mess`.`id`=`us_read`.`id`");
if ($i) {
    if ($i == 1) echo "hai un nuovo messaggio <br /> ";
    else  echo "hai ".$i." nuovi messaggi <br />";
} else  echo "nessun nuovo messaggio";
echo "</center>";
$riga = $Db->totable("SELECT * FROM `us_mess` WHERE `destinatario`='".$_SESSION['nome']."' ORDER BY `id` DESC");
$readt= $Db->totable("SELECT `id` FROM `us_read` WHERE `user`='".$_SESSION['username']."'");
for ($i=0;$readt[$i];$i++)
    $read[$i]=$readt[$i]['id'];
echo "<form name=\"modulo\" action=\"mess.php?action=canc\" method=\"post\">
<table align=\"center\">
<tr>
<td><input type=\"checkbox\" name=\"\" onclick=\"seltt(this)\" /></td>
<td>oggetto</td>
<td>mittente</td>
<td>ora</td>
<td>letto da:</td>
</tr>";
$i = 0;
while ($riga[$i]) {
    $variabile = 'check'.$i;
    //opt sara il grassetto per i mess non letti
    echo "<tr><td>";
    if (@in_array($riga[$i]['id'],$read)) echo "";
    else  echo "<img src=\"images/mail.gif\" border=\"0\" />";
    $sharereadt=$Db->totable("SELECT `user` FROM `us_read` WHERE `id`='".$riga[$i]['id']."'");
    $n=$Db->n;$shareread="";
    for ($j=0;$j<$n;$j++)
        $shareread[$j]=$sharereadt[$j]['user'];
    if ($shareread) $shareread=@implode(",",$shareread);
    echo "<input type=\"checkbox\" name=\"".$variabile."\" value=\"".$riga[$i]['id']."\" /></td><td><a href=\"mess.php?action=read&id=".$riga[$i]['id']."\"> ".$riga[$i]['oggetto']." </a></td><td>".$riga[$i]['mittente'].
        "</td><td>".$riga[$i]['ora']."</td><td>".$shareread."</td></tr>";
    $i++;
}
echo "</table><center><input type=hidden name=tot value=".$i.">
<input type=\"submit\" value=\"cancella\" /></center></form>";
$Db->close();
foot();
?>