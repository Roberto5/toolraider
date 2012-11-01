<?php
include("../functions.php");
include("../my_config.php");
Pagina_protetta_admin();
$_POST=inputctrl($_POST);
$Db=new db();
if ($_POST['action']=="modavviso") {
    $id=(int)$_POST['id'];
    $testo=$_POST['testo'];
    $Db->connect();
    $Db->query("UPDATE `avvisi` SET `testo`='$testo' WHERE `id`='$id'");
    $Db->query("DELETE FROM `read` WHERE `id`='$id'");
}
elseif ($_POST['action']=="addavviso") {
    $testo=$_POST['testo'];
    $Db->connect();
    $Db->query("INSERT INTO `avvisi` SET `testo`='$testo'");
}
elseif ($_POST['action']=="delavviso") {
    $id=$_POST['id'];
    $Db->connect();
    $Db->query("DELETE FROM `avvisi` WHERE `id`='$id'");
    $Db->query("DELETE FROM `read` WHERE `id`='$id'");
}
else {
    echo "pagina non visualizzabile";
}
?>