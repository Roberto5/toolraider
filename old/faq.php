<?php
$title=": f.a.q.";
session_start();
include("inc/foot.php");
include ("functions.php");
include ("my_config.php");
include ("inc/config.php");
online();

menu();
$Db=new db();
$Db->connect();

$faq=$Db->totable("SELECT * FROM `faq`");

$submenu="<ul>\n";
$body="";
for ($i=0;$faq[$i];$i++)
{
    $submenu.='<li><a href="#'.($i+1).'">'.$faq[$i]['domanda'].'</a></li>';
    $body.='<a name="'.($i+1).'"></a><h3>'.$faq[$i]['domanda'].'</h3><p>'.$faq[$i]['risposta'].'</p>';
}
$submenu.='</ul>';
echo $submenu.$body.'
<form name="mail" action="mail.php" method="post">
<p><center><h3><a href="#mail" onclick="document.mail.submit()">Per altre domande contattami</a></h3></center></p>
<input type="hidden" name="oggetto" value="F.A.Q" />
</form>';

foot();
?>