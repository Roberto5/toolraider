<?php
$title = ": registrazione";
include ("functions.php");
include ("inc/foot.php");
include ("my_config.php");
online(6);
$Db = new db();
?>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script language="Javascript" src="scripts/registrazione.js"></script>
<?php
// Controllo di installazione non effettuata
if (!file_exists('inc/config.php')) Errore("install.php","Errore","Attenzione! Non hai ancora installato lo script!");

$_POST = inputctrl($_POST);
$_GET = inputctrl($_GET);

// Azione: Registrazione utenti

@$action = $_GET['action'];
if ($action == "add_user_do") {

    $user = trim($_POST['user']);
    $pass = trim($_POST['pass']);
    $pass2 = trim($_POST['pass2']);
    $name = trim($_POST['name']);
    $mail = trim($_POST['mail']);
    $type = (int)trim($_POST['type']);
    $razza = (int)trim($_POST['razza']);
    $madre = trim($_POST['madre']);
    $server = trim($_POST['server']);

    // Check 1: Se uno dei campi del form rimane vuoto
    if ($user == "" or $pass == "" or $mail == "") // La funziona trim esclude gli spazi e li considera anch'essi come spazio vuoto
             Errore("javascript:history.go(-1)","Errore","I campi username, password ed email devono essere riempiti!");
    if ($type!=1) {
        if (($type!=3)||(!in_array($razza,array(1,2,3)))||($madre=="")||($server=="")) Errore("javascript:history.go(-1)","ERRORE","I campi universo, pianeta madre ed razza devono essere riempiti se sei master!");
    }
    
    $user1 = str_replace(" ","",$user); //Tolgo provvisoriamente lo spazio per permettere controllo caratteri non consentiti
    $name1 = str_replace(" ","",$name);
    if (!eregi("^[_.0-9a-z-]+$",$user1.$name1)) Errore("javascript:history.go(-1)","Errore","L'username, il nome o il cognome hanno caratteri non consentiti.");
    
    // Check 2: Controllo password di conferma
    if ($pass == $pass2) {
        /* Inizio Check */

        //controllo server

        if ((!eregi("u[0-9]{1,2}\.imperion\.[a-z]{2,3}",$server))||(substr($server,0,5)=="speed")) Errore("javascript:history.go(-1)","Errore","Sintassi link errata");
        $server = "http://".$server;
        // Check 3: Controllo validit� E-Mail
        if (eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$",$mail)) {

            // Check 4(1): Controllo numero caratteri minimi
            $exlen = array('user' => 4,'pass' => 4,);

            foreach ($exlen as $key => $val) {
                if (strlen($$key) < $val) Errore("javascript:history.go(-1)","Errore","I campi username e password devono contenere almeno 4 caratteri!");
            }

            // Check 4(2): Controllo numero caratteri massimi
            $exlen1 = array('user' => 15,'pass' => 15,'name' => 15,'mail' => 30,);

            foreach ($exlen1 as $key1 => $val1) {
                if (strlen($$key1) > $val1) {
                    Errore("javascript:history.go(-1)","Errore","Alcuni campi contengono troppi caratteri!");
                }
            }

            /* Check eseguiti correttamente */

            // Inserimento dati nel database

            // Connessione mysql
            $Db->connect();

            // Sub-Check 1: Controllo user gi� esistente
            $query = "SELECT * FROM dl_user WHERE username = '$user'";
            $num_righe = $Db->conta($query);
            // Sub-Check 1 = TRUE: L'utente risulta gi� nel database quindi � gi� registrato!
            if ($num_righe) Errore("javascript:history.go(-1)","Errore","L'username inserito � gi� utilizzato da un altro utente");

            // Sub-Check 2: Controllo mail gi� esistente
            $query = "SELECT * FROM dl_user WHERE mail = '$mail'";
            $num_righe = $Db->conta($query);

            // Sub-Check 2 = TRUE: La mail � gi� stata registrata
            if ($num_righe) Errore("javascript:history.go(-1)","Errore","L'email inserita � gi� stata utilizzata da un altro utente");

            //controllo se il nick � gia resistrato
            $query = "SELECT * FROM dl_user WHERE nome = '$name' AND `type`=3";
            $num_righe = $Db->conta($query);

            if (($num_righe) && ($type == "master")) {
                $type = "attesa";
                echo "esiste gi� un account master per il nick ".$name.
                    " vuoi cambiare il tuo account in sharer? <form action=add_user.php?action=add_user_do method=post><input type=submit value=si><input type=hidden name=type value=".$type.
                    "><input type=hidden name=user value=".$user."><input type=hidden name=pass value=".$pass."><input type=hidden name=pass2 value=".$pass2."><input type=hidden name=name value=".$name.
                    "><input type=hidden name=mail value=".$mail."></form><form action=add_user.php><input type=submit value=no></form>";
                exit;
            }

            //Query configurazione
            $query2 = "SELECT * FROM dl_config";
            $num_righe2 = $Db->toarray($query2);

            $auth = Auth();
            // Cripta la password nel database in md5
            $crypt_auth = md5($auth);

            // Cripta la password nel database in md5
            $crypt_pass = md5($pass);
            $query = "INSERT INTO `dl_user` (username , password , nome , mail , auth , type ) VALUES ('$user', '$crypt_pass' , '$name' , '$mail' , '$crypt_auth', '$type')";
            if ($type != "1") {
                $Db->query("INSERT INTO `us_player` SET `nome`='$name',`razza`=$razza,`universo`='$server'");
                $Db->query("INSERT INTO `us_pianeti` SET `id`='".$name."' , `nome_pianeta`='".$madre."'");
            }
            if ($Db->query($query)) {

                // Invio email per la conferma della registrazione (Se richiesto dall'admin)
                if ($num_righe2['conferma'] == 1) {
                    $oggetto = "$sito - Conferma registrazione account $user";

                    $messaggio = "
									<html>
									<head>
									<title>$sito - Conferma registrazione account $user</title>
									</head>
									<body>
									<p><b>$sito</b></p>
									<br />Conferma account $user</b>
									<br /><br />Per completare la registrazione <a href=\"$urlsito/add_user.php?action=confirm&auth=$crypt_auth\">clicca qui</a>
									<br />Grazie per esserti registrato!
									</p>
									</body>
									</html>";
                    $intestazioni = "MIME-Version: 1.0\r\n";
                    $intestazioni .= "Content-type: text/html; charset=iso-8859-1\r\n";
                    $intestazioni .= "From: $sito <$web_mail>\r\n";

                    mail($mail,$oggetto,$messaggio,$intestazioni);

                    Ok("javascript:history.go(-1)","Registrazione completata","E' stata inviata una email all'indirizzo $mail con tutte le istruzioni per attivare l'account.");

                }
                // Registrazione avvenuta con successo (Se l'admin non attiva l'invio email di conferma)
                else {

                    $query3 = "UPDATE `dl_user` SET `actived` = '1' WHERE `username` = '$user'";
                    $Db->query($query3);
                    Ok("javascript:history.go(-1)","Registrazione completata","Registrazione effettuata correttamente");

                }
            }
            $Db->close();
        }
        // Check 3 = FALSE: La mail inserita non � scritta correttamente
        else  Errore("javascript:history.go(-1)","Errore","L'indirizzo email non ha una sintassi corretta (es. nome@dominio.com)");
    }
    // Check 2 = FALSE: La password di controllo non � uguale alla password inserita
    else  Errore("javascript:history.go(-1)","Errore","La password di controllo inserita non � corretta");

}

// Conferma indirizzo email
if ($action == "confirm") {
    $Db->connect();
    $auth = $_GET["auth"];
    if (trim($auth) == "") Errore("javascript:history.go(-1)","Errore","Codice di autorizzazione non corretto");
    $query = "UPDATE `dl_user` SET `actived` = '1' WHERE `auth` = '$auth'";
    $risultato = $Db->query($query);
    Ok("login.php","Conferma effettuata","Conferma indirizzo email avvenuta con successo");
    $Db->close();
}

// Non si � verificata l'azione di registrazione utenti
menu();
Registrazione();
foot();
?>
