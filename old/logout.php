<?php
session_start();
include ("functions.php");

unset ($_COOKIE['login']);
setcookie ("remember","",time()-86400,"/");
session_destroy();	
Ok("login.php", "Logout eseguito correttamente", "Il logout è stato eseguito con successo!");
?>
