<?php
$title=": pannello utente";
session_start();
include ("functions.php");
include ("inc/foot.php");
include ("my_config.php");
online(6);
$type=Pagina_protetta();
$Db=new db();

echo '
<style>
.black_overlay{
  display: none;
  position: absolute;
  top: 0%;
  left: 0%;
  width: 2000px;
  height: 1500px;
  background-color: black;
  z-index:1001;
  -moz-opacity: 0.8;
  opacity:.60;
  filter: alpha(opacity=60);
}

.white_content {
  color: black;
  display: none;
  position: absolute;
  top: 200px;
  left: 150px;
  width: 400px;
  height: 100px;
  padding: 16px;
  border: 16px solid #900;
  background-color: white;
  z-index:1002;
  overflow: auto;
}
</style>
<script type="text/javascript">
function repeat()// controllo ripetizione nuova password
{
if (document.confirm.password.value!=document.edit_profile.password.value) {document.edit_profile.password.value="";alert("le password non conbaciano!");}
}
</script>';

menu();
$_POST=inputctrl($_POST);
$_GET=inputctrl($_GET);
echo "<br></br>
<div id=\"light\" class=\"white_content\">
<form name=\"confirm\">
<div style=\"color: black;\">Ripeti password</div>
<input type=\"password\" name=\"password\" />
</form>
<a style=\"color: black;\" href=\"javascript:void(0)\" onclick=\"document.getElementById('light').style.display='none'; document.getElementById('fade').style.display='none';repeat()\">Conferma</a>
</div>
<div id=\"fade\" class=\"black_overlay\"></div>";
// Connessione al database
$Db->connect();


$razza=getenum("us_player","razza");	
$row=$Db->toarray("SELECT * FROM `dl_user` WHERE `username`='".$_SESSION['username']."' AND `auth`='".$_SESSION['auth']."'");
if (in_array($type,array(3,4))) {
    $player=$Db->toarray("SELECT `us_player`.`razza`,`us_player`.`universo`,`tt_ally`.`nome` FROM `us_player`,`tt_ally` WHERE `us_player`.`nome`='".$row['nome']."' AND `alleanza`=`id`");
}
$action = $_GET['action'];
if ($action == "edit_profile")
{
    $mail0 = $row['mail'];
    //if ($type!=3) $dis1="disabled=\"disabled\"";
    if (($type!=3)&&($type!=4)) $dis2="disabled=\"disabled\"";
    echo "<br /><br />
 <table border=\"0\" align=\"center\" >
 <tr>
	<td>
		
  		<div align=\"left\" style=\"margin-top:80px; margin-left:170px;\">MODIFICA PROFILO <br />".$row['username']."</div>
  		<form name=\"edit_profile\" action=\"protetta.php?action=edit_profile_do\" method=\"post\" >
   	</td>
 </tr>
 <tr>
   	<td>
    		<strong>Nick in gioco</strong>:<br /><input type=\"text\" name=\"nome\" value=\"".$row['nome']."\" /><br /><br />
		<input type=\"submit\" name=\"submit\" value=\"Modifica &raquo;\" />
   	</td>
    <td>
    <strong><abbr title=\"Se si sostituisce l\'email ne verrà inviata un'altra per confermare l'indirizzo\">Email</abbr></strong>:<br /><input type=\"text\" name=\"mail\" value=\"".$row['mail']."\"/><br /><br />
    <abbr title=\"Lasciare vuoto per non modificare la password\"><strong>Vecchia Password</strong></abbr>:<br /><input type=\"password\" name=\"old_pass\"/><br /><br />
    		<abbr title=\"Lasciare vuoto per non modificare la password\"><strong>Nuova Password</strong></abbr>:<br /><input onchange=\"document.getElementById('light').style.display='block'; document.getElementById('fade').style.display='block'\" type=\"password\" name=\"password\"  /><br /><br />
   	</td>
   	<td>
   		<strong>seleziona razza</strong><br /><br />";
        $s=0;
        for ($i=0;$razza[$i];$i++)
        {
            $vet[$i]['nome']=$razza[$i];
            $vet[$i]['valore']=$i+1;
            if ($razza[$i]==$player['razza']) $s=$i;
        }
        echo "<select name=\"razza\" ".$dis2.">";
        select($i-1,0,$vet,"",$s);
		echo"</select>
   		<br /><br />
   	</td>
   <input type=\"hidden\" name=\"mail0\" value=\"".$mail0."\" />
  </form>
 </tr></table>";

    echo "<br /><br />";
    foot(); 
	exit;
}
if ($action == "edit_profile_do")
{
    $nome_us= trim($_POST['nome']);
	$mail= trim($_POST['mail']);
	$mail0= trim($_POST['mail0']);
    $password= trim($_POST['password']);
    $old_pass= trim($_POST['old_pass']);
    $razza=trim($_POST['razza']);
	  // Check 1: Se uno dei campi del form rimane vuoto 
    if ($mail == "")
        Errore("protetta.php", "Errore", "Il campo Email non deve rimanere vuoto!" );

	 // Check: Controllo livello ricerca
    if (($livm=="")||($livm>10)||($livm<0)) $livm=0;
    if (($livc=="")||($livc>10)||($livc<0)) $livc=0;
   	$nome_us1 = str_replace(" ", "", $nome_us); //Tolgo provvisoriamente lo spazio per permettere controllo caratteri non consentiti
      
    if (!eregi("^[_.0-9a-z-]+$", $nome_us1))
        Errore("protetta.php", "Errore", "Il nick in gioco hanno caratteri non consentiti." );

	    /* Inizio Check */
    $nome_us = addslashes(stripslashes($nome_us));
    $mail = addslashes(stripslashes($mail));
    $nome_us = htmlspecialchars($nome_us);
    $mail = htmlspecialchars($mail);

		
		// Check 3: Controllo validità E-Mail
	if (eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$", $mail)) 
	{
	   // Check 4: Controllo numero caratteri massimi
	   $exlen1 = array (
           'nome'=>15,
           'mail'=>30,
        );
 
        foreach ($exlen1 as $key1=>$val1) 
        {
            if (strlen($$key1) > $val1) 
            {      
                Errore("protetta.php", "Errore", "Alcuni campi contengono troppi caratteri!" );
            }
        }


	     /* Check eseguiti correttamente */

		// Sub-Check 2: Controllo mail già esistente
		$query = "SELECT * from dl_user WHERE mail = '$mail'"; 
		$num_righe= $Db->conta($query);
		$actived = 1;
        $auth = $_SESSION['auth'];		
        if ($mail != $mail0)
        {
            // Sub-Check 2 = TRUE: La mail è già stata registrata
            if($num_righe)
                Errore("protetta.php", "Errore", "L'email inserita è già stata utilizzata da un altro utente" );
            $actived = 0;
            $auth = Auth();
            $auth = md5($auth);
	    }
  
        if ($nome_us!=$_SESSION['nome']) 
        {//cambio nick in gioco
            $query="SELECT * FROM `dl_user` WHERE `nome`='".$nome_us."'";
            $num=$Db->conta($query);
            if ($num) $type="attesa"; else $type="3";
            $num=$Db->conta("SELECT * FROM `dl_user` WHERE `nome`='".$_SESSION['nome']."'");
            if ($num<2) {
            // cancello tutti i riferimenti al player
            //comete registrate
                $Db->query("DELETE FROM `ct_comet` WHERE `user_comet`='".$_SESSION['nome']."'");
            //cronologia FROM `ct_cronologia`
                $Db->query("DELETE FROM `ct_cronologia` WHERE `uid`='".$_SESSION['nome']."'");
            //ship tool
                $Db->query("DELETE FROM `tt_navi` WHERE `uid`='".$_SESSION['nome']."'");
            //toolraider
                $Db->query("DELETE FROM `us_index` WHERE `uid`='".$_SESSION['nome']."'");
                $Db->query("DELETE FROM `us_list` WHERE `uid`='".$_SESSION['nome']."'");
                $Db->query("DELETE FROM `us_list2` WHERE `uid`='".$_SESSION['nome']."'");
            //messaggi
                $Db->query("DELETE FROM `us_mess` WHERE `destinatario`='".$_SESSION['nome']."'");
            //pianeti
                $Db->query("DELETE FROM `us_pianeti` WHERE `id`='".$_SESSION['nome']."'");
            //ricerche
                $Db->query("DELETE FROM `us_ricerche` WHERE `id`='".$_SESSION['nome']."'");
            }
        }
        $_SESSION['nome']=$nome_us;
        $query = "UPDATE dl_user SET `nome` = '$nome_us' , `mail` = '$mail' , `auth` = '$auth' , `type`='".$type."' WHERE username='".$_SESSION['username']."'";
	   $Db->query($query);
    //upgrade del profilo anche ad altri sharer
        if (($type=="4")||($type=="3")) {
            $query = "UPDATE `us_player` SET razza=$razza WHERE nome = '$_SESSION[nome]'"; 
	       $Db->query($query);
        }
        if ($password != "")
        {
            $query="SELECT * FROM dl_user WHERE username = '$_SESSION[username]'";
            $row = $Db->toarray($query);
            $crypt_old_pass = md5($old_pass);
            if ($crypt_old_pass != $row['password']) 
                Errore("protetta.php", "Errore", "La vecchia password digitata non è corretta" );
            $crypt_pass = md5($password);
            $query = "UPDATE dl_user SET password = '$crypt_pass' WHERE username = '$_SESSION[username]'";
            $Db->query($query);
        }
        Ok("protetta.php", "Inserimento dati corretto", "Il profilo è stato modificato correttamente" );

    }
    // Check 3 = FALSE: La mail inserita non è scritta correttamente
    else 
        Errore("protetta.php", "Errore", "L'indirizzo email non ha una sintassi corretta (es. nome@dominio.com)");

    exit;
}



	/*******************************************************************/
	/*    Qui andrà il contenuto della pagina protetta                 */
	/*******************************************************************/
echo "<center>";
$enum=getenum("dl_user","type");
if ($row['type']==$enum[2]) {echo "<div><a href=\"sharer.php\"><b>lista sharer</b></a>";
numrichieste();echo "</div>";}

echo "<a href=\"ricerche.php\"><b>lista ricerche</b></a><br />
<a href=\"mess.php\"><b>messaggi</b></a><br />
<a href=\"pianeti.php\"><b>lista pianeti</b></a></center>
<br /><br />
<table align=\"center\" background=\"images/profilo.jpg\" style=\"background-repeat:no-repeat;\" width=\"700\" height=\"300\"><tr><td>
 <img src=\"images/profilo_right.jpg\" alt=\"Profilo utente\" border=\"0\" align=\"right\" usemap=\"#Map2\" />
 <div align=\"left\" style=\"margin-top:80px; margin-left:170px;\">".$row['username']." stato ".$row['type']."</div>
 <table align=\"left\"><tr><td>
  <strong>Sei l utente registrato N°</strong>: ".$row['id']."<br /><br />
  <strong>Nick in gioco</strong>:<br />".$row['nome']."<br /><br />
  <strong>alleanza</strong>:<br />".$player['nome']."<br /><br />
 </td></tr></table>
  <table align=\"right\" style=\"margin-right:20px;\"><tr><td>
  <strong>Email</strong>:<br />".$row['mail']."<br /><br />
  <strong>razza</strong>:<br />".$player['razza']."<br /><br />
  <strong>universo</strong>:<br />".$player['universo']."
 <br /><br />
 	</td></tr>
 </table>
</td></tr></table>



<map name=\"Map2\" id=\"Map2\">
 <area shape=\"rect\" coords=\"36,117,164,168\" href=\"protetta.php?action=edit_profile\" />
 <area shape=\"rect\" coords=\"29,198,162,258\" href=\"logout.php\" />
 <area shape=\"rect\" coords=\"82,18,162,44\" href=\"delete_user.php?action=delete\" style=\"cursor:pointer;\"  />
</map>
<br /><br />";
$_SESSION['razza']=$player['razza'];
    foot();
?>