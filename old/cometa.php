<?php
include ("functions.php");
$Db = new db();
$_GET = inputctrl($_GET); // prevengo mysql injqction
$id = $_GET['id'];
$time_r = 2;
if (!@eregi("[a-zA-Z]{2}[0-9]{3}",$id)) {
    echo "errore, id cometa non valido! ".$id;
    exit;
}
$Db->connect();
$nome=$Db->tovariable("SELECT `nome` FROM `dl_user` WHERE `username`='".$_GET['nome']."' AND `password`='".md5($_GET['pass'])."'");
if (!$Db->n) {
    echo "password errata!";
    exit;
}
$ally = $Db->tovariable("SELECT `alleanza` FROM `us_player` WHERE `nome`='".$nome."'");

$user = $Db->toarray("SELECT `user_comet` FROM `ct_comet` WHERE `id_comet`='".$id."' AND `ally`='".$ally['alleanza']."'");
if ($Db->n) {
    if ($user['user_comet']==$nome) echo "2la cometa è tua!";
    else echo "1la cometa è utilizzata da ".$user['user_comet'];
}
else {
    echo "2la cometa ".$id." è libera";
    // inserimento cometa e statistiche
    $data = date("Y-m-d H:i:s");
    $scadenza = date("Y-m-d H:i:s",strtotime("+".$time_r." hours"));
    $Db->query("INSERT INTO `ct_comet` SET `id_comet`='".$id."' , `inserita`='".$data."' ,
             `scadenza`='".$scadenza."' , `user_comet`='".$nome."' , `ally`='".$ally."'");
    $num = $Db->conta("SELECT * FROM `ct_cronologia` WHERE `data`='".date("Y-m-d")."' AND `uid`='".$nome."'");
    if (!$num) $Db->query("INSERT INTO `ct_cronologia` SET `uid`='".$nome."' , `numero`='1' , `data`='".date("Y-m-d")."'");
    else {
        $num = $Db->toarray("SELECT `numero` FROM `ct_cronologia` WHERE `data`='".date("Y-m-d")."' AND `uid`='".$_GET['nome']."'");
        $Db->query("UPDATE `ct_cronologia` SET `numero`='".($num['numero'] + 1)."' WHERE `data`='".date("Y-m-d")."' AND `uid`='".$_GET['nome']."'");
    }
}
?>