<?php
session_start();
include("../functions.php");
include("../my_config.php");
include("inc/foot.php");
include_once("funzioni_admin.php");
online(1);
$type=Pagina_protetta_admin();
echo '<script language="javascript">
    function spoiler(oggetto)
{
    if (oggetto.style.display=="none") oggetto.style.display="block";
    else oggetto.style.display="none";
}</script>';
menu();
$Db= new db();
if ($_GET['action'] == "canc") { //*cancella segnalazione
    $id = (int)$_GET['id'];
    $Db->connect();
    $Db->query("DELETE FROM `report` WHERE `id`='" . $id . "'");
    $Db->close();
}

$Db->connect();

if(newreport()) {
    $rep=newreportcon();
    $sug=newsuggcon();
    $bug=newbugcon();
    echo "<center><h1>".($rep>1 ? "ci sono ".$rep : "c'e un solo")." report</h1>";
    if ($bug) {
        echo '<a href="javascript:;" onclick="spoiler(document.getElementById(\'bug\'))">'.$bug.' segnalazion'.($bub>1 ? "i" : "e").
        ' bug</a>
        <div id="bug" style="display:none;"><table>
        <tr>
        <td>mittente</td>
        <td>oggetto</td>
        <td>testo</td>
        <td>file upload</td>
        <td></td>
        </tr>';
        $bug=getbug();
        for ($i = 0; $i < $bug[n]; $i++)
            echo "<tr><td>" . $bug[$i]['mittente'] . "</td><td>" . $bug[$i]['oggetto'] .
                    "</td><td>" . $bug[$i]['testo'] . "</td><td><a href=\"" . $bug[$i]['link'] . "\">" . $bug[$i]['link'] .
                    "</a></td><td><a href=\"index.php?action=canc&id=" . $bug[$i]['id'] . "\"><img src=\"../images/del.gif\" /></a></td></tr>";
        echo '</table></div></center>';
    }
    if ($sug) {
        echo '<center><a href="javascript:;" onclick="spoiler(document.getElementById(\'sugg\'))">'.$sug.' suggeriment'.(sug>1 ? "i" : "o").
        '</a>
            <div id="sugg" style="display:none;"><table>
            <tr>
            <td>mittente</td>
            <td>oggetto</td>
            <td>testo</td>
            </tr>';
        $sug=getsugg();
        for ($i = 0; $i < $sug['n']; $i++)
            echo "<tr><td>" . $sug[$i]['mittente'] . "</td><td>" . $sug[$i]['oggetto'] .
                    "</td><td>" . $sug[$i]['testo'] . "</td><td><a href=\"index.php?action=canc&id=" . $sug[$i]['id'] .
                    "\"><img src=\"../images/del.gif\" /></a></td></tr>";
        echo "</table></div></center>";
    }
}else {
    
    echo "<center><h1>Non ci sono nuovi report</h1></center>";
}

$Db->close();
foot();
?>