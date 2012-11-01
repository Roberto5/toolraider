<?php
session_start();
include ("functions.php");
include ("my_config.php");
include ("inc/config.php");
$Db = new db();
online(2);
pagina_protetta();
$_POST = inputctrl($_POST);
$_GET = inputctrl($_GET);
$action = $_GET['action'];
if ($action == "multi_clear") {
    $tot = (int)$_POST['tot'];
    $Db->connect();
    for ($i = 0; $i < $tot; $i++) {
        $variabile = '$check'.$i;
        $idk = "";
        $idk = $_POST[$variabile];
        $query = "SELECT * FROM `ct_comet` WHERE `id_comet`='".$idk."'";
        $riga = $Db->toarray($query);
        if ($idk != "") {
            if (($riga['user_comet'] == $_SESSION['nome']) || (islog() == "admin")) {
                $query = "DELETE FROM `ct_comet` WHERE `id_comet` = '".$idk."' LIMIT 1";
                $Db->query($query);
            }
        }
    }
    $Db->close();
    ok("list.php","fatto","cancellazione multipla avvenuta non successo");
    exit;
} 
elseif ($action == "canc") {
    $cid = $_GET['cid'];
    if ($cid != "") {
        $Db->connect();
        $query = "SELECT * FROM `ct_comet` WHERE `id_comet`='".$cid."'";
        $riga = $Db->toarray($query);
        if (($riga['user_comet'] == $_SESSION['nome']) || (islog() == "admin")) {
            $query = "DELETE FROM `ct_comet` WHERE `id_comet` = '".$cid."' LIMIT 1";
            $risultato = $Db->query($query);
        }
        $Db->close();
        ok("list.php","cancellazione singola avvenuta con successo");
    }
} 
else  Errore("index.php","ERRORE","questa pagina non è visualizzabile");
?>