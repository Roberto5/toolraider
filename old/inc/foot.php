<?php // VERSIONE SCRIPT
$version = "1.0";
?>

<!--  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php  echo "toolraider".@$title?></title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<!--[if IE]>
 <link rel="stylesheet" type="text/css" href="iestyle.css" />
<![endif]-->
<script type="text/javascript" src="scripts/overlib.js"></script>

<link rel="stylesheet" type="text/css" href="inc/ddlevelsfiles/ddlevelsmenu-base.css" />
<link rel="stylesheet" type="text/css" href="inc/ddlevelsfiles/ddlevelsmenu-topbar.css" />
<link rel="stylesheet" type="text/css" href="inc/ddlevelsfiles/ddlevelsmenu-sidebar.css" />
<link href="css.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="inc/ddlevelsfiles/ddlevelsmenu.js">

/***********************************************
* All Levels Navigational Menu- (c) Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>


<script type="text/javascript">
ddlevelsmenu.setup("ddtopmenubar", "topbar") //ddlevelsmenu.setup("mainmenuid", "topbar")
</script>



<?php 

function menu() { // menu pagina
echo "</head>
<body>";
// pubblicit� da togliere in locale

echo "<center>
<script type=\"text/javascript\">
//<![CDATA[
	document.write('<s'+'cript type=\"text/javascript\" src=\"http://ad.altervista.org/js.ad/size=728X90/r='+new Date().getTime()+'\"><\/s'+'cript>');
//]]>
</script></center>";
// */



if (($log!= "yes")&&($log!="admin")){
    // *********************visitatore**************************
echo '
<div id="ddtopmenubar" class="indentmenu">
<ul>
<li><a href="index.php"><img alt="Home" src="images/index.gif" /> Home</a></li>
<li><a href="login.php" onclick=\'return overlib("<form name=\"login\" method=\"post\" action=\"login.php?action=login_do\">Username <input name=\"user\" type=\"text\" size=\"20\" /><br />Password <input name=\"pass\" type=\"password\" size=\"20\" /><br /><input type=\"checkbox\" name=\"remember\" value=\"true\" /> ricordami su questo pc<br /><input name=\"submit\" type=\"submit\" value=\"Login\" /><br /></form>", STICKY, CAPTION, "<font color=\"black\">login</font>");\' onmouseout="return nd();"><img alt="Login" src="images/login.gif" /> Login</a></li>
<li><a href="tool.php" rel="tool"><img src="images/tool.gif" /> Tool vari</a></li>
<li><a href="add_user.php"><img alt="Registrati" src="images/registrati.gif" /> Registrati</a></li>
<li><a href="lost_password.php"><img alt="Recupero dati" src="images/recupero_dati.gif" /> Recupero dati</a></li>
<li><a href="#" rel="help"><img src="images/LibroAperto.gif" /> Help</a></li>
</ul>
</div>

<ul id="help" class="ddsubmenustyle">
<li><a href="faq.php">F.a.q</a></li>
<li><a href="http://toolraider.altervista.org/blog/">Blog</a></li>
<li><a target="_blank" href="http://toolraider.altervista.org/blog/toolraider/" >funzionamento del tool </a></li>
<li><a target="_blank" href="http://toolraider.altervista.org/blog/toolraider-in-costruzione/">funzioni da implementare/implementate</a></li>
</ul>

<ul id="tool" class="ddsubmenustyle">
<li><a href="tool.php?action=scudo">Calcolo scudo</a></li>
<li><a href="tool.php?action=push">Calcolo push</a></li>
<li><a href="tool.php?action=missili">Simulatore missilistico</a></li>
<li><a href="tool.php?action=log">Loger</a></li>
</ul>

<link href="css.css" rel="stylesheet" type="text/css" />';

 } else { 
// ********************************utente loggato    ***********************************
$ally=isally();
$g=privilegi($ally,array("a"),"",1);
avvisi();
cancinativ();
include("admin/funzioni_admin.php");
echo '
<div id="ddtopmenubar" class="indentmenu">
<ul>
<li><a href="index.php" '.($log=="admin" ? 'rel="admin"' : "").'><img alt="Home" src="images/index.gif" /> Home'.(newreport() && $log=="admin" ? '[<span style="color:red;">!</span>]' : "").'</a></li>
<li><a href="raid.php" rel="raid"><img alt="Raid" src="images/raid.JPG" /> Raid</a></li>
<li><a href="list.php" rel="list"><img alt="liste farm" src="images/List.png" /> Liste farm</a></li>
<li><a href="tool.php" rel="tool"><img src="images/tool.gif" /> Tool vari</a></li>
<li><a href="logout.php"><img alt="Logout" src="images/logout.png" /> Logout</a></li>
<li><a href="lost_password.php"><img alt="Recupero dati" src="images/recupero_dati.gif" /> Recupero dati</a></li>
<li><a href="protetta.php" rel="user"><img alt="Pannello utente" src="images/pannello_utente.png" /> Pannello utente'.numrichieste().'</a></li>
<li><a href="ally.php" rel="ally"> Alleanza'.allyrichieste($ally,$g['privilegi']).'</a></li>
<li><a href="#" rel="help"><img src="images/LibroAperto.gif" /> Help</a></li>
</ul>
</div>

<ul id="admin" class="ddsubmenustyle">
<li><a href="admin/index.php">Pannello Admin</a></li>
</ul>

<ul id="ally" class="ddsubmenustyle">';
if ($ally) {
    
	if (readpriv($g['privilegi'],array('g','a','p'))) {// privilegi gestisci ally 'gap'
        echo "<li><a href=\"ally.php?action=gestisci\">Amministra alleanza</a></li>";
    }
    if (readpriv($g['privilegi'],array('m'))) { // privilegi modifica profilo
        echo "<li><a href=\"ally.php?action=mod\">Modifica profilo</a></li>";
    }
    if ($g) {// � in un ally
        echo"<li><a href=\"ship.php\">ShipTool</a></li>
             <li><a href=\"add.php\">CometeTool</a></li>";
    }
} 
else {// non � in un ally
    echo "<li><a href=\"ally.php?action=crea\">Crea alleanza</a></li>
          <li><a href=\"ally.php?action=cerca\">Cerca alleanza</a></li>";
}
?>
</ul>

<ul id="help" class="ddsubmenustyle">
<li><a href="faq.php">F.a.q</a></li>
<li><a href="http://blogpagliaccio.wordpress.org">Blog</a></li>
</ul>

<ul id="raid" class="ddsubmenustyle">
<li><a href="raid.php?raid=raid">raid veloce</a></li>
</ul>

<ul id="list" class="ddsubmenustyle">
<li><a href="list.php?list=activ">Farm attive</a></li>
<li><a href="list.php?list=inactiv">Farm inattive</a></li>
<li><a href="list.php?list=add">Aggiungi farm</a></li>
<li><a href="list.php?list=cerca">Cerca</a></li>
</ul>

<ul id="tool" class="ddsubmenustyle">
<li><a href="tool.php?action=scudo">Calcolo scudo</a></li>
<li><a href="tool.php?action=push">Calcolo push</a></li>
<li><a href="tool.php?action=missili">simulatore missilistico</a></li>
<li><a href="tool.php?action=log">Loger</a></li>
<li><a href="coordina.php">Coordinatore attacchi</a></li>
</ul>

<ul id="user" class="ddsubmenustyle">
<li><a href="mess.php">Messaggi degli sharer</a></li>
<li><a href="sharer.php">Gestione sharer</a></li>
<li><a href="pianeti.php">Lista pianeti</a></li>
<li><a href="ricerche.php">Lista ricerche</a></li>
</ul>

<link href="css.css" rel="stylesheet" type="text/css" />

<?php } echo "<br />";

} 

function foot() { ?>

<p><center>RINGRAZIAMENTI: a <b><u><a href="http://matt93.altervista.org" target="_blanc">matt93</a></b></u>, all'alleanza J.D. di imperion server 1 primo round e a DarckSimon per l'aiuto fornito <!--
<b><u><a href="http://www.traviantrucchi.org" target="_blanc">TravianTrucchi</a></b></u>
-->.<br />Le immagini sono concesse dalla TravianGames, proprietaria di tutti i diritti relativi ai contenuti. Questo sito non � gestito dalla TravianGames, e non � realizzato per scopi di lucro.</center></p>
<br />
</body>
</html>
<?php } ?>