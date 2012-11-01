<?php
$title=": recupero password";
session_start();
include ("functions.php");
include ("inc/foot.php");
include ("my_config.php");
$Db=new db();
online(1);
$_GET=inputctrl($_GET);

$urlsito="http://localhost/toolraider";

// Controllo di installazione non effettuata
if ( !file_exists( 'inc/config.php' ) )
 Errore("install.php", "Errore", "Attenzione! Non hai ancora installato lo script!" );
     
// Connessione al database
include ('inc/config.php');
$Db->connect();

// Azione: Recupera password
$action= $_GET['action'];
if($action == "lost_password_do")
{
    $email=$_POST["email"];
    // Check 1: Controllo validità E-Mail:
    if (eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$", $email)) 
    {
        // Connessione al database
        $Db->connect();
        // Check 2: Controllo utente registrato
        $query="SELECT `username`,`id` FROM `dl_user` WHERE `mail` = '$email'";
        $riga = $Db->toarray($query);
        if($riga)
        {
            $username=$riga['username'];
            $id=$riga['id'];
            $key = Auth();
            $crypt_key = md5($key);
            $query="UPDATE `dl_config` SET `lost_key` = '$crypt_key' , `lost_id` = '$id'";
            $Db->query($query);
            
            $oggetto = "toolraider - Recupero dati di login";
            $messaggio = "
			            <html>
			            <head>
			            <title>toolraider - Recupero dati di login</title>
			            </head>
			            <body>
			            <p><b>toolraider</b></p>
			            <br />E' stato richiesto il cambio della password per l'account $username<br />
			            <br /><br />Per procedere al cambio della password <a href=\"$urlsito/lost_password.php?action=edit_password&id=$id&key=$crypt_key\">clicca qui</a>
			            <br />Se non sei stato tu a richiedere la sostituzione della password basta ignorare questo messaggio.
			            </p>
			            </body>
			            </html>";
            $intestazioni  = "MIME-Version: 1.0\r\n";
            $intestazioni .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $intestazioni .= "From: $sito <$web_mail>\r\n";
            mail($email, $oggetto, $messaggio, $intestazioni);
            Ok("javascript:history.go(-1)", "Inserimento dati corretto", "E' stata inviata la procedura da seguire via email" );				
       }
       // Check 2 = FALSE: L'utente non risulta registrato.
       else
        Errore("javascript:history.go(-1)", "Errore", "Non esiste nessun utente registrato con questa email" );
    }
    // Check 1 = FALSE: L'email inserita non ha una sintassi corretta.
    else 
        Errore("javascript:history.go(-1)", "Errore", "Attenzione! L'email non ha una sintassi corretta (es. nome@dominio.com)" );
}
if($action == "edit_password")
{
    $id=(int)$_GET["id"];
    $key=$_GET["key"];
    $Db->connect();
    $query="SELECT lost_key,lost_id FROM dl_config";
    $riga = $Db->toarray($query);
    $key0=$riga['lost_key'];
    $id0=$riga['lost_id'];
    if ($key0 != $key)
        Errore("javascript:history.go(-1)", "Errore", "La chiave di controllo non è corretta. Provare a ripetere la procedura per il recupero password." );
    if ($id0 != $id)
        Errore("javascript:history.go(-1)", "Errore", "L'utente selezionato non risulta aver richiesto il recupero della password. Provare a ripetere la procedura." );
    $query="SELECT username,mail FROM dl_user WHERE id = '$id'";
    $riga=$Db->toarray($query);
    $username=$riga['username'];
    $email=$riga['mail'];
    $s_password = Auth();
    // Cambio password con quella generata
                            
    $crypt_pass = md5($s_password);
    $query="UPDATE `dl_user` SET `password` = '$crypt_pass' WHERE `username` = '$username'";
    $Db->query($query);
    
    $oggetto = "toolraider - Recupero dati di login $username";
    $messaggio = "
 <html>
 <head>
 <title>toolraider - Recupero dati di login $username</title>
 </head>
 <body>
 <p>Dev.Login - <b>toolraider</b></p>
 <br />La password dell'account $username è stata modificata correttamente.<br />Ti consigliamo di fare il login e di modificarla nuovamente appena possibile mediante la pagina del tuo profilo utente.<br />
 <br /><br />I nuovi dati sono:<br /><b>Username</b>: $username<br /><b>Password</b>: $s_password<br /><br />
 </p>
 </body>
 </html>";
    $intestazioni  = "MIME-Version: 1.0\r\n";
    $intestazioni .= "Content-type: text/html; charset=iso-8859-1\r\n";

    //$intestazioni .= "To: $username <$email>\r\n";
    $intestazioni .= "From: toolraider <$web_mail>\r\n";

    if (mail($email, $oggetto, $messaggio, $intestazioni)) 
    Ok("login.php", "Inserimento dati corretto", "La nuova password è stata inviata alla casella email" );
    else Errore("index.php","ERRORE INVIO EMAIL","non è stato possibile inviare l'email, contattare l'admin");
}


// Azione recupero password = FALSE: mostro il form per il recupero password.

menu();

Recupero_dati();                      

foot(); 

?>