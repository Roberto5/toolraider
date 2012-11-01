<?php

/**
 * @author roberto
 * @copyright 2010
 */

session_start();
include("functions.php");
include("my_config.php");
include("inc/foot.php");

online(4);
Pagina_protetta(1);
$_POST=inputctrl($_POST);
$Db=new db();
?>
<script language="javascript">

function link(oggetto)
{
    str=oggetto.value;
    link=str.match(/http:\/\/[a-zA-Z/\.]+(\.)[a-zA-Z]{2,3}|www.[a-zA-Z/\.]+(\.)[a-zA-Z]{2,3}/gi);
    i=0;
    while(link[i])
    {
        link2=link[i];
        if (link2.substr(0,7)!="http:\/\/") {link2="http:\/\/"+link[i];}
        str=str.replace(link[i],"<a href=\""+link2+"\">"+link[i]+"</a>");
        i++;
    }
    oggetto.value=str;
}
</script>
<link rel="stylesheet" href="scripts/thumbnailviewer.css" type="text/css" />


<script src="scripts/thumbnailviewer.js" type="text/javascript"></script>

<script type="text/javascript">
function askdel(sid) 
{
    Check = confirm("Sei sicuro di voler eliminare questo giocatore?");
    if (Check == true) window.location.href = 'ally.php?action=rifiuta&uid=' + sid;
}
</script>
<?php

menu();
// "m" modifica pofilo "g" gestisci "a" accetta entrate "p" permessi tutti i privilegi mgap
$action=$_GET['action'];

if ($action=="crea") {
    echo "<center><form name=\"ally\" action=\"ally.php?action=crea_do\" method=\"post\">
Nome alleanza <input name=\"nome\" size=\"10\" maxlength=\"20\" /><br />
<abbr title=\"per trovarlo vai su statistiche, nel link della tua alleanza troverai un indirizzo simile a questo http://u1.imperion.it/alliance/show/1234, il numero è l'id dell'alleanza.\">Id alleanza </abbr>
<input name=\"aid\" size=\"5\" /><br />
Descrizione<br />
<textarea onchange=\"link(this)\" name=\"descrizione\" cols=\"50\" rows=\"25\" >
</textarea><br />
<input type=\"submit\" value=\"crea\" />
</form>
</center>";
}
elseif ($action=="crea_do") {
    $Db->connect();
    $nome=$_POST['nome'];
    $aid=(int)$_POST['aid'];
    $descrizione=$_POST['descrizione'];
    $descrizione=str_replace("\n","<br />",$descrizione);
    $num=$Db->conta("SELECT * FROM `tt_ally` WHERE `link`='".$aid."' OR `nome`='".$nome."'");
    if ($num) Errore("javascript:history.back()","ERRORE","l'alleanza esiste già");
    $Db->query("INSERT INTO `tt_ally` SET `nome`='".$nome."' , `link`='".$aid."' , `descrizione`='".$descrizione."'");
    $Db->query("UPDATE `us_player` SET `alleanza`='".$nome."' WHERE `nome`='".$_SESSION['nome']."'");
    //, `privilegi`='mgap'
    $Db->query("INSERT INTO `us_privilegi` (`nome`,`privilegio`) VALUES ('".$_SESSION['nome']."','g') ,
    ('".$_SESSION['nome']."','a'),
    ('".$_SESSION['nome']."','p'),
    ('".$_SESSION['nome']."','m')");
    $Db->close();
    Ok("ally.php","Eseguito","creazione alleanza eseguita con successo");
}
elseif ($action=="cerca") {
    $Db->connect();
    echo "<center><form action=\"ally.php?action=cerca_do\" method=\"post\"><input name=\"nome\" size=\"10\" maxlength=\"20\" /><input type=\"submit\" value=\"cerca\" /></form>";
    $a=$Db->totable("SELECT * FROM `tt_ally`");
    for($i=0;$a[$i];$i++)
        echo "<div>".$a[$i]['nome']." <a href=\"ally.php?action=richiedi&aid=".$a[$i]['id']."\">Entra</a></div>";
    echo "</center>";
    $Db->close();
}
elseif ($action=="cerca_do") {
    $Db->connect();
    $nome=$_POST['nome'];
    $ally=$Db->totable("SELECT * FROM `tt_ally` WHERE `nome`LIKE'%".$nome."%'");
    echo "<center><table>";
    $i=0;
    while($ally[$i])
    {
        echo "<tr><td>".$ally[$i]['nome']."</td><td><a href=\"ally.php?action=richiedi&aid=".$ally[$i]['id']."\">Entra</a></td></tr>";
        $i++;
    }
    echo "</table></center>";
    if (!$i) echo "<center>nessuna alleanza trovata</center>";
    $Db->close();
}
elseif ($action=="richiedi") {
    $Db->connect();
    $aid=(int)$_GET['aid'];
    if ($Db->conta("SELECT * FROM `tt_richieste` WHERE `player`='".$_SESSION['nome']."'"))
    $Db->query("INSERT INTO `tt_richieste` SET `ally`='".$aid."' , `player`='".$_SESSION['nome']."'");
    $Db->close();
    Ok("ally.php","Eseguito","richiesta di entrata inviata con successo, attendi l'accettazione dell'alleanza");
}
elseif ($action=="permessi") {
    $Db->connect();
    $uid=$_POST['uid'];
    $permessi=$_POST['permessi'];
    $bool=$_POST['bool'];
    if ($bool) {// aggiungo
        $Db->query("INSERT INTO `us_privilegi` SET `nome`='".$uid."' , `privilegio`='".$permessi."'");
    } else {//tolgo
        $Db->query("DELETE FROM `us_privilegi` WHERE `nome`='".$uid."' , `privilegio`='".$permessi."'");
    }
    $Db->close();
    echo "<script language=\"javascript\">history.back();</script>";
}
elseif ($action=="gestisci") {
    $ally=isally();
    $priv=privilegi($ally,array('g'),"",1);
    echo "<center><h1>gestione alleanza</h1>";
    if (readpriv($priv['privilegi'],"a")) {
     // richieste di ingresso
        $Db->connect();
        $richieste=$Db->totable("SELECT * FROM `tt_richieste` WHERE `ally`='".$ally."'");
        $num=$Db->n;
        echo "<table class=\"itable\" cellpadding=\"2\" cellspacing=\"1\" width=\"400\">
        <tr>
	       <td class=\"header1\" colspan=\"6\">Richieste di ingresso ".$num."</td>
	   </tr>
	   <tr>
	   <td class=\"header2\">#</td>
	   <td class=\"header2\">UserName</td>
	   <td class=\"header2\">Razza</td>
	   <td class=\"header2\" colspan=\"2\"></td>
	   </tr>";
        if ($num) {
            for ($i=0;$i<$num;$i++)
            {
                $us=$Db->toarray("SELECT * FROM `us_player` WHERE `nome`='".$richieste[$i]['player']."'");
                switch($us['razza'])
                {
                    case "Titani" : $style="blue";break;
                    case "Terrestri" : $style="#FF8040";break;
                    case "Xen" : $style="green";break;
                    default :$style="black";
                }
                echo "
<tr><td class=\"zeile1\">".($i+1)."</td>
<td class=\"zeile1\" style=\"color: ".$style."\">".$richieste[$i]."<a href=\"mess.php?action=send&dest=".$richieste[$i]['player']."\"><img src=\"images/mess.gif\" /></a></td>
<td class=\"zeile1\" style=\"color: ".$style.";\">".$us['razza']."</td>
<td class=\"zeile1\"><a href=\"ally.php?action=accetta&uid=".$us['nome']."\" title=\"Accetta registrazioni\"><img src=\"images/ok.gif\" /></a></td>
<td class=\"zeile1\"><a href=\"ally.php?action=rifiuta&uid=".$us['nome']."\" title=\"Rifiuta registrazioni\"><img src=\"images/del.gif\" /></a></td>
</tr>";
            }
            echo "</table><br /><br /><br />";
        }
        else 
        {
            echo "<tr><td colspan=\"6\" class=\"footer\"><span class='null'>-</span></td></tr></table>";
        }
    }
    // gestione privilegi
    if (readpriv($priv['privilegi'],array('p'))) {
        echo "<table class=\"itable\" cellpadding=\"2\" cellspacing=\"1\" width=\"300\">
    <tr>

	  <td class=\"header1\" colspan=\"8\">Lista Membri</td>
	</tr>
	<tr>
	  <td class=\"header2\">#</td>
	  <td class=\"header2\">UserName</td>
<td class=\"header2 infbox\" title=\"Modifica profilo\">1</td>
<td class=\"header2 infbox\" title=\"Gestisci alleanza\">2</td>
<td class=\"header2 infbox\" title=\"Accetta entrate\">3</td>
<td class=\"header2 infbox\" title=\"Modifica Permessi\">4</td>
<td class=\"header2 infbox\" title=\"\" ></td> 
<td class=\"header2\"></td></tr>";
        $member=$Db->totable("SELECT * FROM `us_player` WHERE `alleanza`='".$ally."' ORDER BY `nome` ASC");
        $i=0;$j=0;
        while($member[$i])
        {
            $priv=privilegi($ally,array('m'),$member[$i]['nome'],1);
            $g=readpriv($priv['privilegi'],"g");
            $a=readpriv($priv['privilegi'],"a");
            $m=readpriv($priv['privilegi'],"m");
            $p=readpriv($priv['privilegi'],"p");
            switch($member[$i]['razza'])
                {
                    case "Titani" :$style="blue";break;
                    case "Terrestri" :$style="#FF8040";break;
                    case "Xen" :$style="green";break;
                    default :$style="black";
                }
            
            echo "<tr>
<td class=\"zeile1\">".($i+1)."</td>
<td class=\"zeile1\"><a style=\"color: ".$style.";\" href=\"ship.php?action=show&uid=".$member[$i]['nome']."\" onmouseover=\"return overlib('<img src=\'diagramm.php?uid=".$member[$i]['nome']."&ally=".$ally."\' alt=\'".$member[$i]['nome']."\' border=\'0\' style=\'margin:0px;\'>');\" onmouseout=\"return nd();\">".$member[$i]['nome']."</a> <a href=\"mess.php?action=send&dest=".$member[$i]['nome']."\"><img src=\"images/mess.gif\" /></a></td>
<td class=\"zeile1\"><a onmouseover=\"return overlib('";
if ($priv['bool']) { $title="Modifica i dati";} else { $title="Nessun privilegio";} 
echo $title."');\" onclick=\"return overlib('<form action=\'ally.php?action=permessi\' method=\'post\' ><input type=\'hidden\' name=\'uid\' value=\'".$member[$i]['nome']."\'><input type=\'hidden\' name=\'ally\' value=\'".$ally."\'><input type=\'hidden\' name=\'permessi\' value=\'m\'><button type=\'submit\' name=\'bool\' value=\'0\'><img src=\'images/readno.gif\'></button><button type=\'submit\' name=\'bool\' value=\'1\'><img src=\'images/readall.gif\'></button></form>', STICKY, CAPTION, '<font color=\'black\'>".$member[$i]['nome']."</font>');\" onmouseout=\"return nd();\">
<img src=\"images/";
if ($priv['bool']) { echo "readwrite.gif"; } else { echo "readno.gif"; }
echo "\" alt=\"".$title."\" /></a></td>
<td class=\"zeile1\"><a onmouseover=\"return overlib('";
if ($g) { $title="Gestisci alleanza";} else { $title="Nessun privilegio";} 
echo $title."');\" onclick=\"return overlib('<form action=\'ally.php?action=permessi\' method=\'post\' ><input type=\'hidden\' name=\'uid\' value=\'".$member[$i]['nome']."\'><input type=\'hidden\' name=\'ally\' value=\'".$ally."\'><input type=\'hidden\' name=\'permessi\' value=\'g\'><button type=\'submit\' name=\'bool\' value=\'0\'><img src=\'images/readno.gif\'></button><button type=\'submit\' name=\'bool\' value=\'1\'><img src=\'images/read.gif\'></button></form>', STICKY, CAPTION, '<font color=\'black\'>".$member[$i]['nome']."</font>');\" onmouseout=\"return nd();\">
<img src=\"images/";
if ($g) { echo "read.gif"; } else { echo "readno.gif"; } 
echo "\" alt=\"".$title."\" /></a></td>
<td class=\"zeile1\"><a onmouseover=\"return overlib('";
if ($a) { $title="Gestisci alleanza";echo $title;} else { $title="Nessun privilegio";echo $title;} 
echo "');\" onclick=\"return overlib('<form action=\'ally.php?action=permessi\' method=\'post\' ><input type=\'hidden\' name=\'uid\' value=\'".$member[$i]['nome']."\'><input type=\'hidden\' name=\'ally\' value=\'".$ally."\'><input type=\'hidden\' name=\'permessi\' value=\'a\'><button type=\'submit\' name=\'bool\' value=\'0\'><img src=\'images/readno.gif\'></button><button type=\'submit\' name=\'bool\' value=\'1\'><img src=\'images/read.gif\'></button></form>', STICKY, CAPTION, '<font color=\'black\'>".$member[$i]['nome']."</font>');\" onmouseout=\"return nd();\">
<img src=\"images/";
if ($a) { echo "read.gif"; } else { echo "readno.gif"; } 
echo "\" alt=\"".$title."\" /></a></td>
<td class=\"zeile1\"><a onmouseover=\"return overlib('";
if ($a) { $title="Gestisci alleanza";echo $title;} else { $title="Nessun privilegio";echo $title;} 
echo "');\" onclick=\"return overlib('<form action=\'ally.php?action=permessi\' method=\'post\' ><input type=\'hidden\' name=\'uid\' value=\'".$member[$i]['nome']."\'><input type=\'hidden\' name=\'ally\' value=\'".$ally."\'><input type=\'hidden\' name=\'permessi\' value=\'p\'><button type=\'submit\' name=\'bool\' value=\'0\'><img src=\'images/readno.gif\'></button><button type=\'submit\' name=\'bool\' value=\'1\'><img src=\'images/readwrite.gif\'></button></form>', STICKY, CAPTION, '<font color=\'black\'>".$member[$i]['nome']."</font>');\" onmouseout=\"return nd();\">
<img src=\"images/";
if ($p) { echo "readwrite.gif"; } else { echo "readno.gif"; } 
echo"\" alt=\"".$title."\" /></a></td>
<td></td>
<td class=\"zeile1\"><a href=\"javascript:askdel('".$member[$i]['nome']."');\"><img src=\"images/del.gif\" title=\"Elimina questo giocatore\" /></a></td>
</tr>";
            $i++;
        }
        echo "</table>";
    }
    // truppe ally
    $b=privilegi($ally,"g",$_SESSION['nome'],1);
    if ($b['bool']) {
        showally($ally);
    }
    
    //gruppi
    
    
    echo"</center>";
    $Db->close();
}
elseif ($action=="accetta") {
    $Db->connect();
    $uid=$_GET['uid'];
    $ally=isally();
    $Db->query("UPDATE `us_player` SET `alleanza`='".$ally."' WHERE `nome`='".$uid."'");
    $Db->query("UPDATE `tt_navi` SET `ally`='".$ally."' WHERE `uid`='".$uid."'");
    $Db->query("DELETE FROM `tt_richieste` WHERE `player`='".$uid."'");
    $Db->close();
    Ok("javascript:history.back()","ok","utente accettato");
}
elseif ($action=="rifiuta") {
    $Db->connect();
    $uid=$_GET['uid'];
    $ally=isally();
    $Db->query("DELETE FROM `us_privilegi` WHERE `nome`='".$uid."'");
    $Db->query("UPDATE `us_player` SET `alleanza`='' WHERE `nome`='".$uid."'");
    $Db->query("DELETE FROM `tt_richieste` WHERE `player`='".$uid."'");
    $num=$Db->conta("SELECT * FROM `us_player` WHERE `alleanza`='".$ally."'");
    if (!$num) $Db->query("DELETE FROM `tt_ally` WHERE `nome` = '".$ally."' LIMIT 1");
    $Db->close();
    Ok("javascript:history.back()","ok","utente rifiutato");
}
elseif ($action=="mod") {
    $Db->connect();
    $ally=isally();
    $priv=privilegi($ally,array('m'));
    $riga=$Db->toarray("SELECT * FROM `tt_ally` WHERE `nome`='".$ally."'");
    $descrizione=str_replace("<br />","\n",$riga['descrizione']);
    echo "<center><form name=\"ally\" action=\"ally.php?action=mod_do\" method=\"post\">
Nome alleanza <input name=\"nome\" size=\"10\" maxlength=\"20\" value=\"".$riga['nome']."\" /><br />
<abbr title=\"per trovarlo vai su statistiche, nel link della tua alleanza troverai un indirizzo simile a questo http://u1.imperion.it/alliance/show/1234, il numero è l'id dell'alleanza.\">Id alleanza </abbr>
<input size=\"5\" value=\"".$riga['link']."\" readonly=\"readonly\" /><br />
Descrizione<br />
<textarea  onchange=\"link(this)\" name=\"descrizione\" cols=\"50\" rows=\"25\" >".$descrizione."</textarea><br />
<input type=\"submit\" value=\"modifica\" />
<input name=\"nome2\" type=\"hidden\" value=\"".$riga['nome']."\" />
</form>
</center>";
    $Db->close();
}
elseif ($action=="mod_do") {
    $Db->connect();
    $nome=$_POST['nome'];
    $descrizione=$_POST['descrizione'];
    $nome2=$_POST['nome2'];
    $descrizione=str_replace("\n","<br />",$descrizione);
    $Db->query("update `tt_ally` SET `descrizione`='".$descrizione."' WHERE `nome`='".$nome2."'");
    if ($nome!=$nome2) {
        $num=$Db->conta("SELECT * FROM `tt_ally` WHERE `nome`='".$nome."'");
        if ($num) Errore("javascript:history.back()","ERRORE","l'alleanza ".$nome." esiste già");
        $Db->query("UPDATE `dl_user` SET `alleanza`='".$nome."' WHERE `alleanza`='".$nome2."'");
    }
    $Db->close();
    Ok("ally.php","Eseguito","modifica profilo eseguita con successo");
}
elseif ($action=="ritira") {
    $Db->connect();
    $Db->query("DELETE FROM `tt_richieste` WHERE `player`='".$_SESSION['nome']."'");
    $Db->close();
    Ok("ally.php","ok","richiesta ritirata");
}
else {
    
    $ally=isally();
    if ($ally) 
    {// descrizione ally visibile solo agli alleati
        $Db->connect();
        echo "<center>";
        $riga=$Db->toarray("SELECT * FROM `tt_ally` WHERE `nome`='".$ally."'");
        $num=$Db->conta("SELECT * FROM `us_player` WHERE `alleanza`='".$ally."'");
        //lista utenti user=$Db->totable("SELECT * FROM `us_player` WHERE `alleanza`='".$ally."'");
        //$num=$Db->n;
        $universe=$_SESSION['universo'];
        echo "<center>
    <table border=\"1\" bgcolor=\"black\">
    <tr><td><b>Nome</b></td><td>".$riga['nome']."</td></tr>
    <tr><td></td><td><a target=\"_blank\" href=\"".$universe."/alliance/show/".$riga['link']."\">link ad imperion</a></td></tr>
    <tr><td><b>descrizione ally</b></td><td>".$riga['descrizione']."</td></tr>
    <tr><td><b>Numero di membri</b></td><td>".$num."</td></tr>
    <!-- lista utenti -->
    </table>
    <a href=\"allY.php?action=mod\">Modifica profilo</a><br />
    
    <a href=\"map.php?ally=".$ally."&resize=1&g=1\" rel=\"thumbnail\" title=\"mappa\">
    <img class=\"image\" width=\"200\" height=\"200\" src=\"map.php?ally=".$ally."&resize=1&g=1\" title=\"G1\" /></a>
    
    <a href=\"map.php?ally=".$ally."&resize=1&g=2\" rel=\"thumbnail\" title=\"mappa\">
    <img class=\"image\" width=\"200\" height=\"200\" src=\"map.php?ally=".$ally."&resize=1&g=2\" title=\"G2\" /></a>
    
    <a href=\"map.php?ally=".$ally."&resize=1&g=3\" rel=\"thumbnail\" title=\"mappa\">
    <img class=\"image\" width=\"200\" height=\"200\" src=\"map.php?ally=".$ally."&resize=1&g=3\" title=\"G3\" /></a>

    </center>";
        $pianeti=$Db->totable("SELECT `us_pianeti`.* , `us_player`.`nome` FROM `us_pianeti` , `us_player` WHERE `alleanza`='".$ally."' AND `nome`=`us_pianeti`.`id`");
        $maxx=0;$maxy=0;$i=0;$minx=0;$miny=0;
        while($pianeti[$i])
        {
            $minx= $pianeti[$i]['x'] < $minx ? $pianeti[$i]['x'] : $minx ;
            $miny= $pianeti[$i]['y'] < $miny ? $pianeti[$i]['y'] : $miny ;
            $maxx= $pianeti[$i]['x'] > $maxx ? $pianeti[$i]['x'] : $maxx ;
            $maxy= $pianeti[$i]['y'] > $maxy ? $pianeti[$i]['y'] : $maxy ;
            $i++;
        }
        $max=$maxx > $maxy ? $maxx : $maxy;
        $min=$minx < $miny ? $minx : $miny;
        $min=0-$min;
        $max=$max > $min ? $max : $min ;
        $max++;
        $gr=intval(400/$max);
        echo "<map name=\"map\" id=\"map\">";
        for($i=0;$pianeti[$i];$i++)
        {
            $x=intval($pianeti[$i]['x']*400/$max);
            $off=intval(400);
            $y=intval($pianeti[$i]['y']*400/$max);
            $off2=$off+$gr;
            $r=intval($gr/2);
            echo "<area shape=\"circle\" coords=\"".($off+$x).",".($off-$y).",".$r."\" title=\"".$pianeti[$i]['nome']."`s ".$pianeti[$i]['nome_pianeta']."(".$pianeti[$i]['x'].",".$pianeti[$i]['y'].")"."\" href=\"#\" />";
        }
        echo "</map>";
    }
    else {
        $Db->connect();
        $ally=$Db->toarray("SELECT `nome` FROM `tt_ally`,`tt_richieste` WHERE `player`='".$_SESSION['nome']."' AND `ally`=`id`");
        if ($Db->n) {
            $ally=$ally['nome'];
            echo "<center>Sei in attesa che l'ally ".$ally." ti accetti<br />
            puoi ritirare la richiesta cliccando <a href=\"ally.php?action=ritira\" >qui</a>
            </center>";
            $Db->close();
            exit;
        }
        echo "<center><p>al momento sei senza alleanza, puoi <a href=\"ally.php?action=cerca\">cercarne</a> una o <a href=\"ally.php?action=crea\">crearla</a></p></center>";
    }
    $Db->close();
}

foot();

?>