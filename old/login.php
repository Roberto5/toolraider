<?php
$title=": login";
session_start();
include ("functions.php");
include ("inc/foot.php");
include ("my_config.php");
online(6);
$Db=new db();

// Controllo di installazione non effettuata
if ( !file_exists( 'inc/config.php' ) )
 Errore("install.php", "Errore", "Attenzione! Non hai ancora installato lo script!" );
     
include ("inc/config.php");



// Controllo file di installazione ancora esistente
if ( file_exists( 'install.php' ) OR file_exists( 'tabelle.sql' ) )
 Warning ("Attenzione", "Cancellare il file di installazione \"install.php\" e il file \"tabelle.sql\" prima di utilizzare lo script" );


/********************************************************************/
/* Azione di Login                                                  */
/********************************************************************/

$action= $_GET['action'];

if($action == "login_do")
{   
   
    $user= $_POST['user'];
    $pass= $_POST['pass'];
    $rem= $_POST['remember'];
    
	// Controllo inserimento dati
	if (trim($user) == "" OR trim($pass) == "")
     Errore("javascript:history.go(-1)", "Errore", "I campi devono essere riempiti" );

	else 
 	{
  		$user = addslashes(stripslashes($user));
	 	$pass = addslashes(stripslashes($pass));
	 	$user = str_replace("<", "&lt;", $user);
		$pass = str_replace(">", "&gt;", $pass);
		
		// Connessione al database
        $Db->connect();
         		
 			// Recupero dati dal database
 			if (!get_magic_quotes_gpc())
 			{
 				$user= addslashes($_POST['user']);
 				$pass= addslashes($_POST['pass']);
 			}
 			
 			else
 			{
 				$user= $_POST['user'];
 				$pass= $_POST['pass'];
 			}
 			$crypt_pass = md5($pass);
 			$query = "SELECT * from `dl_user` WHERE `username` = '$user' AND `password` = '$crypt_pass'";
 			$row = $Db->toarray($query);
            $num_righe= $Db->n;
 			if($num_righe)
			{
				//  L'utente è stato riconosciuto
			  if ($row['actived'] != "1")
         Errore("javascript:history.go(-1)", "Errore", "L'account non risulta ancora attivato!" );
             
				session_cache_limiter("private_no_expire");
                $enum=getenum("dl_user","admin");
				if ($row['admin']==$enum[1]) $_SESSION['login'] = "admin"; 
                else $_SESSION['login'] = "yes";
                $_SESSION['username'] = $row['username'];
                $_SESSION['nome'] = $row['nome'];
                $player=$Db->toarray("SELECT * FROM `us_player` WHERE `nome`='".$row['nome']."'");
                $_SESSION['razza'] = $player['razza'];
                $_SESSION['mail'] = $row['mail'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['universo'] = $player['universo'];
				$auth=md5(auth());
				if ($rem=="true") {
					setcookie("remember",$auth,time()+86400,"/");	
				}
				$query="UPDATE `dl_user` SET `auth`='".$auth."' , `ip`='".$_SERVER['REMOTE_ADDR']."' , `attivita`=CURRENT_TIMESTAMP WHERE `id`='".$_SESSION['id']."'";
				$Db->query($query);
				$_SESSION['auth'] = $auth;
				$Db->close();
				Ok("index.php", "Inserimento dati corretto", "Login effettutato con successo" );				
			}
			
		    // Nome utente o password errati
 			else
             Errore("javascript:history.go(-1)", "Errore", "Username o password errati!" );
	}
	menu();
	foot(); 
}
menu();

echo "<br /><br />";

Login();
foot();
?>