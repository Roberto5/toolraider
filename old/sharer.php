<?php

/**
 * @author roberto
 * @copyright 2010
 */

session_start();
include("functions.php");
include("my_config.php");
include("inc/foot.php");
online(6);
$type=Pagina_protetta(2);
$Db=new db();
$_POST=inputctrl($_POST);
menu();
$Db->connect();
$action=$_GET['action'];
$id=(int)$_GET['id'];
$type=(int)$_POST['type'];
if (($action=="activ")&&($id!="")) {
    $query="UPDATE `dl_user` SET `type`=4 WHERE `id`='".$id."'";
    $Db->query($query);
    //ok("javascript:history.back()","ok","attivato");
}
if (($action=="canc")&&($id!="")) {
    $query="UPDATE `dl_user` SET `type`=2 AND `nome`='' WHERE `id`='".$id."'";
    $Db->query($query);
    //ok("javascript:history.back()","ok","cancellato");
}
if (($action=="cange")&&($id!="")) {
    $query="UPDATE `dl_user` SET `type`='".$type."' WHERE `id`='".$id."'";
    $Db->query($query);
    //ok("javascript:history.back()","ok","modificato");
}


$query="SELECT * FROM `dl_user` WHERE `nome`='".$_SESSION['nome']."' AND `username`!='".$_SESSION['username']."' AND `type`!='rifiutato'";
$riga=$Db->totable($query);

$enum=getenum("dl_user","type");
echo "
<center>
<table>
<tr>
<td>user</td><td>stato</td><td></td>
</tr>";
for($i=0;$riga[$i];$i++)
{
    echo "<tr>
        <td>".$riga[$i]['username']."</td><td>";
    if (($riga[$i]['type']==$enum[2])||($riga[$i]['type']===$enum[3])) { $sel="";$sel2="";
        if ($riga[$i]['type']===$enum[2]) $sel="selected=selected"; else $sel2="selected=selected";
        echo "<form action=\"sharer.php?action=cange&id=".$riga[$i]['id']."\" method=\"post\"><select name=\"type\" onchange=\"submit()\"><option value=\"3\" ".$sel.">master</option><option value=\"4\" ".$sel2.">sharer</option><option value=\"1\" >attesa</option></select></form>";
    }
    else echo $riga[$i]['type'];
    echo "</td><td>";
    if ($riga[$i]['type']=="attesa") echo "<a href=sharer.php?action=activ&id=".$riga[$i]['id']."><img src=images/button_ok.gif border=0></a>";
    echo "<a href=sharer.php?action=canc&id=".$riga[$i]['id']."><img border=0 src=images/button_cancel.png></a></td>
    </tr>";
}
echo "</table>
</center>";

foot();
?>