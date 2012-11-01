<?php
session_start();
include("../functions.php");
include("../my_config.php");
include("inc/foot.php");
online(1);
$Db=new db();

$_POST=inputctrl($_POST);
$error="";
if ($_POST['action']) {
    $Db->connect();
    $user= $_POST['user'];
    $pass= $_POST['pass'];
    $crypt_pass = md5($pass);
    $query = "SELECT * from `dl_user` WHERE `username` = '$user' AND `password` = '$crypt_pass' AND `admin`=2";
    $row = $Db->toarray($query);
    $num_righe= $Db->n;
    if($num_righe) {
        //  L'utente è stato riconosciuto
        session_regenerate_id();
        session_cache_limiter("private_no_expire");
        $_SESSION['login'] = "admin";
        $_SESSION['username'] = $row['username'];
        $_SESSION['mail'] = $row['mail '];
        $_SESSION['id'] = $row['id'];
        $_SESSION['ip'] =$_SERVER['REMOTE_ADDR'];
        $auth=md5(auth());
        $query="UPDATE `dl_user` SET `auth`='".$auth."' , `ip`='".$_SESSION['ip']."' WHERE `id`='".$_SESSION['id']."'";
        $Db->query($query);
        $_SESSION['auth'] = $auth;
        $Db->close();
        Ok("index.php", "Inserimento dati corretto", "Login effettutato con successo" );
    }
    else $error="<div style=\"color: red;\">utente o password errara!</div>";
}
menu();

echo"<form action=\"login.php\" method=\"post\">
<center>".$error."
<table>
<tr>
<td>Username: </td>
<td style=\"width: 150px; text-align: right;\" ><input name=\"user\" size=\"15\" /></td>
</tr>
<tr>
<td>Password :</td>
<td style=\"width: 150px; text-align: right;\" ><input name=\"pass\" type=\"password\" size=\"15\" /></td>
</tr>
<tr>
<td colspan=\"2\" style=\"text-align: right;\" ><input name=\"action\" type=\"submit\" value=\"login\" /></td>
</tr>
</table>
</center></form>";

foot();

?>