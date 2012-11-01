<?php
include ("sql.php");
/**
 * messaggio di ok e ridirezione
 */
function Ok($link,$titolo,$testo,$sec = "10")
{
    $sec = intval($sec);
    echo '
    <script type="text/javascript">
    <!--
    function doRedirect() { 
    location.href = "'.$link.'";
    }
    window.setTimeout("doRedirect()", '.$sec.'000);
    //-->
    </script>';
    print "
	<div id=\"table_center\"> 
	<table width=\"476\" height=\"124\" background=\"images/done.gif\">
	<tr>
	<th height=\"15\" colspan=\"2\" scope=\"row\">
	<div align=\"center\" <strong> &iexcl; $titolo !</strong></div>
	</th>
	</tr>
		<tr>
			<th width=\"64\" scope=\"row\"><img src=\"images/apply_big.gif\" align=\"center\"></th>
			<th width=\"920\" scope=\"row\"><div align=\"center\">
			<p>$testo
			<br /><br />
			Attendi il caricamento oppure clicca <a href=\"$link\">qui</a></p>
			</div></th>
		</tr>
	</table></div>";
    exit;

}

/**
 * @name redirect
 * @param String $url
 * 
 * redirezione alla pagina riportata per parametro.
 */

function redirect($url = "index.php")
{
    echo '<script type="text/javascript">
    <!--
    location.href = "'.$url.'";
    //-->
    </script>';
    exit;
}
/**
 * messaggio di errore e ridirezione	
 */
function Errore($link,$titolo,$testo,$sec = "10")
{
    $sec = intval($sec);
    echo '
    <script type="text/javascript">
    <!--
    function doRedirect() { 
    location.href = "'.$link.'";
    }
    window.setTimeout("doRedirect()", '.$sec.'000);
    //-->
    </script>';
    print "
	<div id=\"table_center\"> 
	<table width=\"476\" height=\"124\" background=\"images/error.gif\">
			 <tr>
	<th height=\"15\" colspan=\"2\" scope=\"row\">
 	<div align=\"center\"><b> &iexcl; $titolo !</b></div>
	</th>
	</tr>
    	<tr>
      		<th width=\"64\" scope=\"row\"><img src=\"images/cancel_big.gif\" align=\"center\"></th>
      		<th width=\"920\" scope=\"row\"><div align=\"center\">
        	<p>$testo
            <br /><br />
      		Attendi il caricamento o clicca <a href=\"$link\">qui</a></p>
      		</div></th>
    	</tr>
	</table>
	</div>";
    exit;

}

/**
 * messaggio di warning
 */
function Warning($titolo,$testo)
{

    print "
	<div id=\"table_center\"> 
	<table width=\"500\" height=\"100\">
	<tr>
	<th height=\"15\" colspan=\"2\" scope=\"row\"  background=\"images/sfondo_rosso.jpg\" ><div align=\"justify\">
	<div align=\"center\"><strong> &iexcl; $titolo !</strong></div>
	</div></th>
	</tr>
		<tr>
			<th width=\"64\" scope=\"row\" ><img src=\"images/important.gif\" align=\"center\"></th>
			<th width=\"920\" scope=\"row\" ><div align=\"center\">
			<p>$testo<br /><br /></p>
			</div></th>
		</tr>
	</table></div>";
    exit;
}

/***********************************/
/*    Generazione casuale ID       */
/***********************************/
//Funzione di generazione seme per il numero random
//utilizzando i microsecondi
function crea_seme()
{
    list($usec,$sec) = explode(' ',microtime());
    return (float)$sec + ((float)$usec * 100000);
}

//Funzione principale
function Auth()
{

    //Thanks to Lemoeb
    //Defininione e valorizzazione array delle frasi
    $frasi = array('itorinonhannogenitori','provadipassword','testnumero2','nelmezzodelcammindinostravita','unonessunocentomila','loscheletronellarmadio','Nosferatueilmaestrodellanotte',
        'sediconutellatucosapensi','mapoiperchehoscrittoquestoscript','odioilmonopoliodimicosoft','adoroimangagiapponesi','stephenkingeungrandescrittore','lamiaragazzamenadamorire',
        'selamuccafamupercheiltopononfato','maisettenanieranoveramentesette','ilsignoredeglianellinonsivedemai','costantinounuomosenzasenso','sperochequestefrasipossanobastare',
        'altrimentisenepossonoaggiungerealtre');

    //Definizione e inizializzazione delle variabili di password
    //e carattere casuale
    $s_password = "";
    $carattere = "";
    $totchar = "10";

    //Inizializzazione generatore numeri random
    mt_srand(crea_seme());

    //Generazione numero casuale per recupero frase dall'array
    $valore_chiave = mt_rand(0,count($frasi) - 1);

    //Ciclo per la generazione della password
    //Il numero di caratteri conponenti la password
    //vanno specificati nella variabile $totchar
    for ($i = 0; $i <= $totchar; $i++) {
        //Genere un valore casuale sulla base della
        //lunghezza della frase selezionata
        $valorecasuale = mt_rand(0,strlen($frasi[$valore_chiave]));

        //Se viene incontrato uno spazio, questo viene sostituito con il carattere "_"
        if (substr($frasi[$valore_chiave],$valorecasuale,1) == " ") {
            $carattere = "_";
        } else {
            //Generazione di numero casuale per decidere se il carattere
            //deve essere convertito in maiuscolo
            if (mt_rand(0,10) > 5) {
                //Conversione del carattere prelevato dalla frase in maiuscolo
                $carattere = strtoupper(substr($frasi[$valore_chiave],$valorecasuale,1));
            }
            //Sostituzione di alcune lettere con numeri
            elseif (substr($frasi[$valore_chiave],$valorecasuale,1) == "a") {
                $carattere = "4";
            } elseif (substr($frasi[$valore_chiave],$valorecasuale,1) == "i") {
                $carattere = "1";
            } elseif (substr($frasi[$valore_chiave],$valorecasuale,1) == "e") {
                $carattere = "3";
            } elseif (substr($frasi[$valore_chiave],$valorecasuale,1) == "o") {
                $carattere = "0";
            } elseif (substr($frasi[$valore_chiave],$valorecasuale,1) == "g") {
                $carattere = "6";
            } elseif (substr($frasi[$valore_chiave],$valorecasuale,1) == "s") {
                $carattere = "2";
            } elseif (substr($frasi[$valore_chiave],$valorecasuale,1) == "z") {
                $carattere = "5";
            } else {
                $carattere = substr($frasi[$valore_chiave],$valorecasuale,1);
            }

        }

        $auth .= $carattere;
    }

    //composizione della password
    return $auth;

}

/**
 * genera link
 * @param int $idlink
 * @param String $option
 * @return String
 * 
 */

function linkmaker($l,$opt)
{
    //esempio mappa galattica:
    //http://u1.imperion.it/map/index/system/967184/#/center/967184/system/967184/planet/96718404
    //$opt=map
    //esempio invio navi
    //http://u1.imperion.it/fleetBase/mission/1/planetId/951191/m/302/ships/,,2
    //$opt=raidter120  raid->opzione ter->razza 1->tipo di nave 20 ->numero navi
    $t = array('0','2','3','6');
    $ter = array('0','2','5','6','10','12');
    $x = array('0','2','5','10','11');
    $universe = $_SESSION['universo'];
    $o = substr($opt,0,4);
    if ($o == "raid") {
        $o = substr($opt,4,3);
        if ($o == "ter") $vet = $ter;
        elseif ($o == "xen") $vet = $x;
        elseif ($o == "tit") $vet = $t;
        else  Errore("list.php","errore","impossibile creare il link. stringa opt \'".$opt."\' stringa 0 \'".$o."\'  contattare il webmaster",".");
        $t = substr($opt,7,1);
        $n = substr($opt,8);
        $init = $universe."/fleetBase/mission/1/planetId/";
        $f1 = "/m/302/ships/";
        $f2 = ",";
        $linkt = $init.$l.$f1;
        for ($i = 0; $i < $vet[$t]; $i++) $linkt .= $f2;
        $linkt .= $n;
        return $linkt;
    } elseif ($o == "map") {
        $init = "/map/index/system/";
        $sid = substr($l,0,6);
        $mid = "/#/center/";
        $mid2 = "/planet/";
        $mid3 = "/system/";
        $linkt = $universe.$init.$sid.$mid.$sid.$mid3.$sid.$mid2.$l;
        return $linkt;
    } else  Errore("list.php","errore","opt errato. stringa opt \'".$opt."\' stringa 0 \'".$o."\'  contattare il webmaster");
}

/***********************************/
/*             riordina            */
/***********************************/

function riordina()
{
    $Db = new db();
    $Db->connect();
    for ($t = 1; $t < 3; $t++) {
        $n_lista = $Db->totable("SELECT `nome_lista` FROM `us_list` WHERE `type`=".$t." GROUP BY `nome_lista`");
        $num = $Db->n;
        for ($i = 0; $i < $num; $i++) {
            $query = "SELECT `n`,`lid` FROM `us_list` WHERE type=".$t." AND `nome_lista`='".$n_lista[$i]['nome_lista']."' ORDER BY `n`";
            $link = $Db->totable($query);
            $n = $Db->n;
            if ($n != $link[$n - 1]['n']) { // lista in disordine, ci son dei buchi
                for ($j = 0; $j < $n; $j++) $Db->query("UPDATE `us_list` SET `n`='".($j + 1)."' WHERE `lid`='".$link[$j]['lid']."'");
            }
        }
    }
    $Db->close();
}

/***********************************/
/*        numero richieste         */
/***********************************/

function numrichieste()
{
    $Db = new db();
    $Db->connect();
    $txt = "";
    $query = "SELECT * FROM `dl_user` WHERE `nome`='".$_SESSION['nome']."' AND `type`=1";
    $num = $Db->conta($query);
    $query = "SELECT * FROM `dl_user` WHERE `username`='".$_SESSION['username']."' AND `type`=3";
    $master = $Db->conta($query);
    if (($master) && ($num)) $txt .= " (".$num.")";
    $num = $Db->conta("SELECT `us_mess`.`id` FROM `us_mess` WHERE `destinatario`='".$_SESSION['nome']."' 
    AND `mittente`!='".$_SESSION['nome']."/".$_SESSION['username']."' AND id!= ALL (
        SELECT `id` FROM us_read WHERE user='".$_SESSION['username']."' 
    )");
    if ($num) $txt .= "<img src=\"images/mail.gif\" border=\"0\" />(".$num.")";
    $Db->close();
    return $txt;
}

/***********************************/
/*        numero richieste         */
/***********************************/

function estraipianeti()
{
    $Db = new db();
    $Db->connect();
    $query = "SELECT * FROM `us_pianeti` WHERE `id`='".$_SESSION['nome']."'";
    $riga = $Db->totable($query);
    $i = 0;
    while ($riga[$i]) {
        $planet[$i] = $riga[$i]['nome_pianeta'];
        $nome = "p".$riga[$i]['nome_pianeta'];
        $planet[$nome]['prod_met'] = $riga[$i]['prod_met'];
        $planet[$nome]['prod_cri'] = $riga[$i]['prod_cri'];
        $planet[$nome]['prod_deu'] = $riga[$i]['prod_deu'];
        $planet[$nome]['dep_met'] = $riga[$i]['dep_met'];
        $planet[$nome]['dep_cri'] = $riga[$i]['dep_cri'];
        $planet[$nome]['dep_deu'] = $riga[$i]['dep_deu'];
        $planet[$nome]['mercato'] = $riga[$i]['mercato'];
        //altre cose da inserire
        $i++;
    }
    $planet['tot'] = $i;
    $Db->close();
    return $planet;
}

/***********************************/
/*           � alleato?            */
/***********************************/

/**
 * user is into ally?
 * @return name of ally
 */

function isally()
{
    $Db = new db();
    $Db->connect();
    $ally = $Db->toarray("SELECT `alleanza` FROM `us_player` WHERE `nome`='".$_SESSION['nome']."'");
    $Db->close();
    return $ally['alleanza'];
}

/***********************************/
/*            controllo            */
/***********************************/

/**
 * @param array $_GET or $_post
 * @param [optional] vector string of index integer
 * @return array $vettore
 */

function inputctrl($vettore,$vectargint = 0)
{
    if (!get_magic_quotes_gpc()) {
        $i = 0;
        foreach ($vettore as $key => $val) {
            $bool = 0;
            for ($i = 0; ($vectargint[$i]) && (!$bool); $i++)
                if ($key == $vectargint[$i]) $bool = 1;
            if ($bool) $vettore[$key] = (int)$vettore[$key];
            else  $vettore[$key] = addslashes($vettore[$key]);
            //$vettore[$key]=str_replace("%","\%",$vettore[$key]);
            //$vettore[$i]=str_replace("_","\_",$vettore[$i]);
            $i++;
        }
    }
    return $vettore;
}
/***********************************/
/*            privilegi            */
/***********************************/
/**
 * @param string ally, string option to search, name user , bool is on table
 * @return array $vet['privilegi'],$vet['bool'] if he as the option search
 * 
 */

function privilegi($ally,$opt,$nome = "",$elenco = 0)
{ // "m" modifica pofilo "g" gestisci "a" accetta entrate "p" permessi , tutti i privilegi mgap
    $Db = new db();
    if (!$ally) {
        if ($elenco) return 0;
        else  Errore("index.php","ERRORE","permesso negato");
    }
    if ($nome == "") $nome = $_SESSION['nome'];
    $Db->connect();
    $privi = $Db->totable("SELECT * FROM `us_privilegi` WHERE `nome`='".$nome."'");
    for ($i = 0; $privi[$i]; $i++) $priv[$i] = $privi[$i]['privilegio'];
    $b = !readpriv($priv,$opt);
    if (($b) && (!$elenco)) Errore("index.php","ERRORE","permesso negato");
    $vet['privilegi'] = $priv;
    $vet['bool'] = !$b;
    $Db->close();
    return $vet;
}
/***********************************/
/*         ally richieste          */
/***********************************/

function allyrichieste($ally,$priv)
{
    $Db = new db();
    $Db->connect();
    $i = "";
    if (readpriv($priv,array("a"))) $i = $Db->conta("SELECT * FROM `tt_richieste` WHERE `ally`='".$ally."'");
    $Db->close();
    return $i == "0"?"":$i;
}
/***********************************/
/*        leggi privilegi          */
/***********************************/

/**
 *@param string $pivilegi, array $opt
 *@return bool 
 */
function readpriv($priv,$opt)
{
    $bool = $priv != ""?1:0;
    for ($i = 0; $opt[$i] && $bool; $i++) $bool = in_array($opt[$i],$priv);
    return $bool;
}

/**
 * @param int $fine , int start, vector $vectel, string $first, selected index $sel
 * @tutorial create a option's list
 * @example select(10,3,$vectel,"seleziona",2); //create 7 option whit $vectel[$i]['nome'] and $vectel[$i]['valore']. second element selected. first is a element whith value null
 */

function select($end,$start = 0,$vectel = "",$first = "",$sel = 0)
{
    if (!$sel) $s = "selected=\"true\"";
    else  $s = "";
    if ($first) echo "<option value=\"\" ".$s.">".$first."</option>";
    $sel--;
    for ($i = $start; $i <= $end; $i++) {
        if (($i == $sel) && ($sel >= 0)) $s = "selected=\"true\"";
        else  $s = "";
        if ($vectel[$i]) {
            $val = $vectel[$i]['valore'] != ""?$vectel[$i]['valore']:$i;
            $nome = $vectel[$i]['nome'] != ""?$vectel[$i]['nome']:$i;
        } else {
            $val = $i;
            $nome = $i;
        }
        echo "<option ".$s." value=\"".$val."\">".$nome."</option>";
    }
}

/**
 * restituisce i valori enum di una colonna
 * @param String $table
 * @param String $col
 * @return Array enum
 */

function getenum($table,$col)
{
    $Db = new db();
    $Db->connect();
    $enum = $Db->toarray("SHOW COLUMNS FROM `".$table."` LIKE '".$col."'"); // scarico dal DB i valori di enum
    $enum = $enum['Type']; // $enum="enum('valore1','valore2'....)"
    $enum = str_replace("enum(","",$enum);
    $enum = str_replace("'","",$enum);
    $enum = str_replace(")","",$enum);
    $enum = explode(",",$enum); //tolgo i caratteri innutili ed esplodo il tutto in un vettore
    return $enum;
    $Db->close();
}

/**********************************/
/*    l'utente � loggato?         */
/**********************************/

function islog()
{
    $Db = new db();
    if (@$_SESSION['login'] == "admin") {
        $Db->connect();
        $query = "SELECT * FROM `dl_user` WHERE `auth` = '".$_SESSION['auth']."' AND `username`='".$_SESSION['username']."' AND `ip`='".$_SERVER['REMOTE_ADDR']."' AND `admin`=2";
        $num = $Db->conta($query);
        $Db->close();
        if ($num) $return = "admin";
    } elseif (@$_SESSION['login'] == "yes") {
        $Db->connect();
        $query = "SELECT * FROM `dl_user` WHERE `auth` = '".$_SESSION['auth']."' AND `username`='".$_SESSION['username']."' AND `ip`='".$_SERVER['REMOTE_ADDR']."'";
        $num = $Db->conta($query);
        if ($num) {
            $Db->close();
            $return = "yes";
        } else {
            $Db->close();
            $return = "no";
        }
    } else {
        if (@$_COOKIE["remember"] != "") {
            $Db->connect();
            $query = "SELECT * FROM `dl_user` WHERE `auth`='".$_COOKIE["remember"]."'";
            $row = $Db->toarray($query);
            $num = $Db->n;
            if ($num) {
                $enum = getenum("dl_user","admin");
                if ($row['admin'] == $enum[1]) $_SESSION['login'] = "admin";
                else  $_SESSION['login'] = "yes";
                $_SESSION['username'] = $row['username'];
                $_SESSION['nome'] = $row['nome'];
                $player = $Db->toarray("SELECT * FROM `us_player` WHERE `nome`='".$row['nome']."'");
                $_SESSION['razza'] = $player['razza'];
                $_SESSION['mail'] = $row['mail'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['universo'] = $player['universo'];
                $auth = md5(auth());
                $_SESSION['auth'] = $auth;
                $query = "UPDATE `dl_user` SET `auth`='".$auth."' , `ip`='".$_SESSION['ip']."' , `attivita`=CURRENT_TIMESTAMP WHERE `id`='".$_SESSION['id']."'";
                $result = $Db->query($query);
                setcookie("remember",$auth,time() + 86400,"/");
                redirect("javascript:window.location.reload(true)");
            }
        }
        $return = "no";
    }
    return $return;
}

/**
 * cancella utenti inattivi
 */

function cancinativ()
{
    if (!$_SESSION['inactiv']) {
        $_SESSION['inactiv'] = true;
        $intervallo = "30";
        $inativ = TIMECANC;
        $Db = new db();
        $Db->connect();
        $query = "SELECT * FROM `db_clean` WHERE `id`='2' AND `date`<'".date("Y-m-d H:i:s",strtotime("-".$intervallo." days"))."'";
        $num = $Db->conta($query);
        if ($num) {
            $Db->query("UPDATE `db_clean` SET `date`=CURRENT_TIMESTAMP WHERE `id`='2'");
            $user = $Db->tocolarray("SELECT `nome` FROM `dl_user` WHERE `attivita`<'".date("Y-m-d H:i:s",strtotime("-".$inativ." days"))."'");
            for ($i = 0; $user[$i]; $i++) cancuser($user[$i]);
        }
    }
}

/**
 * @param String user
 * cancella gli utenti
 */

function cancuser($user)
{
    $Db = new db();
    $Db->connect();
    //cancello tabella utente
    $nome = $Db->tovariable("SELECT `nome` FROM `dl_user` WHERE `username`='".$user."'");
    $ally = $Db->tovariable("SELECT `alleanza` FROM `us_player` WHERE `nome`='".$nome."'");
    $Db->query("DELETE FROM `dl_user` WHERE `username` = '".$user."'");
    //read
    $Db->query("DELETE FROM `us_read` WHERE `user`='".$user."'");
    //e riferimenti al user
    $num = $Db->conta("SELECT * FROM `dl_user` WHERE `nome`='".$nome."'");
    if ($num == 0) { // cancello tutti i riferimenti al player se non ci sono user che puntano al player
        // controllo che la cancellazione del utente non comporti un ally con 0 membri
        $Db->connect();
        if ($Db->conta("SELECT `nome` FROM `us_player` WHERE `alleanza`='".$ally."'") < 2) {
            //ally
            $Db->query("DELETE FROM `tt_ally` WHERE `nome`='".$ally."'");
        }
        //player
        $Db->query("DELETE FROM `us_player` WHERE `nome`='".$nome."'");
        //comete registrate
        $Db->query("DELETE FROM `ct_comet` WHERE `user_comet`='".$nome."'");
        //cronologia FROM `ct_cronologia`
        $Db->query("DELETE FROM `ct_cronologia` WHERE `uid`='".$nome."'");
        //ship tool
        $Db->query("DELETE FROM `tt_navi` WHERE `uid`='".$nome."'");
        //toolraider
        $Db->query("DELETE FROM `us_index` WHERE `uid`='".$nome."'");
        $Db->query("DELETE FROM `us_list` WHERE `uid`='".$nome."'");
        //messaggi
        $Db->query("DELETE FROM `us_mess` WHERE `destinatario`='".$nome."'");
        //pianeti
        $Db->query("DELETE FROM `us_pianeti` WHERE `id`='".$nome."'");
        //ricerche
        $Db->query("DELETE FROM `us_ricerche` WHERE `id`='".$nome."'");
        //privilegi
        $Db->query("DELETE FROM `us_privilegi` WHERE `nome`='".$nome."'");
        //richieste
        $Db->query("DELETE FROM `tt_richieste` WHERE `player`='".$nome."'");
    }
    $Db->close();
}
?>