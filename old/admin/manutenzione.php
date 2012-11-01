<?php
session_start(); 
include("../functions.php");
include("../my_config.php");
include("inc/foot.php");
online();
Pagina_protetta_admin();

echo '<script type="text/javascript" src="editor/ckeditor.js"></script>
	<script src="editor/sample.js" type="text/javascript"></script>
	<link href="editor/sample.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="manutenzione.js"></script>
';

//  <-------------qui ci vanno i javascript, tra l'include e la funzione menu ------------------->

menu();
$Db= new db();
$Db->connect();

//$_POST=inputctrl($_POST);


if ($_POST['action']=="invia") {
    $testo=$_POST['editor'];
    echo $testo;
}

echo '<dir id="editor"></dir>';


// gestione avvisi 
$avvisi=$Db->totable("SELECT * FROM `avvisi` ORDER BY `id`");
echo "<form action=\"manutenzione.php\" method=\"post\"><ul>";
echo '<center><h3><a href="#" onclick="spoiler(document.getElementById(\'avvisi\'))">avvisi presenti'.($Db? "(".$Db->n.")" : "").'</a></h3><a href="#" onclick=editA(\'0\')> aggiungi nuovo </a><br />
<textarea id="avviso0" name="avviso0" style="display: none;"></textarea></center><div id="avvisi" style="display:none;">';
for($i=0;$avvisi[$i];$i++)
{
    echo '<li id="conenent'.$avvisi[$i]['id'].'"><img src="images/edit.gif" title="modifica" onclick="editA(\''.$avvisi[$i]['id'].'\')" /><img src="../images/del.gif" title="cancella" onclick="del(\''.$avvisi[$i]['id'].'\')" />
    letto da ';
    $user="";
    $user=$Db->tocolarray("SELECT `user` FROM `read` WHERE `id`='".$avvisi[$i]['id']."'");
    if ($Db->n)
    $user=implode(",",$user);
    
    echo $Db->n.' <abbr title="'.$user.'">utenti</abbr>
    <div id="testo'.$avvisi[$i]['id'].'">'.$avvisi[$i]['testo'].'</div><textarea id="avviso'.$avvisi[$i]['id'].'" name="avviso'.$avvisi[$i]['id'].'" style="display: none;"></textarea></li>';
}
echo '</ul></form></div>';
// offline
echo '<center><h3><a href="#" onclick="spoiler(document.getElementById(\'offline\'))">metti offline</a></h3></center>
<div id="avvisi" style="display:none;">';

echo '</div>';
// avvio script


$Db->close();

foot();
?>
    
    
    
    
    
