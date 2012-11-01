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


<link rel="stylesheet" type="text/css" href="inc/ddlevelsfiles/ddlevelsmenu-base.css" />
<link rel="stylesheet" type="text/css" href="inc/ddlevelsfiles/ddlevelsmenu-topbar.css" />
<link rel="stylesheet" type="text/css" href="inc/ddlevelsfiles/ddlevelsmenu-sidebar.css" />
<link href="../css.css" rel="stylesheet" type="text/css" />

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

</head>

<?php 

function menu() { // menu pagina
// pubblicit� da togliere in locale
echo "<center>
<script type=\"text/javascript\">
//<![CDATA[
	document.write('<s'+'cript type=\"text/javascript\" src=\"http://ad.altervista.org/js.ad/size=728X90/r='+new Date().getTime()+'\"><\/s'+'cript>');


//]]>



</script>
</center>";
// */

if ((islog()!= "yes")&&(islog()!="admin")){
    // *********************visitatore**************************

?> 

<div id="ddtopmenubar" class="indentmenu">
<ul>
<li><a href="../index.php"><img alt="Home" src="../images/index.gif" border="0" /> Home</a></li>
<li><a href="login.php"><img alt="Login" src="../images/login.gif" border="0" /> Login</a></li>
</ul>
</div>



<!--
<link href="../css.css" rel="stylesheet" type="text/css" />
-->

<?php
 } else { 
// ********************************utente loggato    ***********************************
?>

<div id="ddtopmenubar" class="indentmenu">
<ul>
<li><a href="../index.php"><img alt="Home" src="images/index.gif" border="0" /> Home</a></li>
<li><a href="index.php"><img alt="Home" src="images/index.gif" border="0" /> Admin Home</a></li>
<li><a href="logout.php"><img alt="Logout" src="../images/logout.png" border="0" /> Logout</a></li>
<li><a href="deu.php" rel="source"> Download/Upload Source</a></li>
<li><a href="manutenzione.php">Manutenzione</a></li>
<li><a href="database.php">Struttura DB</a></li>
</ul>
</div>

<ul id="source" class="ddsubmenustyle">
<li><a href="deu.php?source=up">upload</a></li>
<li><a href="deu.php?source=down">download</a></li>
</ul>

<?php } echo "<br />";

} 

function foot() { ?>

<p><center>RINGRAZIAMENTI: a <b><u><a href="http://matt93.altervista.org" target="_blanc">matt93</a></b></u>, all'alleanza J.D. di imperion server 1 primo round e a <b><u><a href="http://www.traviantrucchi.org" target="_blanc">TravianTrucchi</a></b></u>.</center></p>
<br />
</body>
</html>
<?php } ?>