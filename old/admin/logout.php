<?php
session_start();
include ("../functions.php");

if ($_SESSION['login'] != "admin") Errore("login.php", "Errore", "Per accedere a questa pagina � prima necessario effettuare il login!" ,".");

else
{
	unset ($_COOKIE['login']);
	setcookie ("remember","",time()-86400,"/");
	session_destroy();	
	Ok("login.php", "Logout eseguito correttamente", "Il logout � stato eseguito con successo!");
}
?>