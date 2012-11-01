<?php
session_start();
include("functions.php");


$destinatario=$_POST['destinatario'];
$oggetto=$_POST['oggetto'];
$messaggio=$_POST['messaggio'];
$mittente=$_POST['mittente'];
if (!in_array($sito,array($destinatario,$mittente))) {
    $destinatario=$sito;
}
if ($_POST['action']) {
    if (($mittente==$sito)&&(islog()!="admin")) Errore("mail.php","ERRORE","Non puoi mandare email a nome di ".$sito);
    sendmail($destinatario,$oggetto,$messaggio,$mittente);
}
else echo '<center><a href="index.php">menu</a></center>'.foglio($destinatario,$oggetto,$messaggio,$mittente);

function sendmail($destinatario,$oggetto,$messaggio,$mittente="")
{
    if ($destinatario==$sito) $destinatario=$web_mail;
    if (($mittente=="")||($mittente=$sito)) $intestazioni .= "From: $sito <$web_mail>\r\n";
    else $intestazioni.="From: $mittente \r\n";
    $intestazioni  = "MIME-Version: 1.0\r\n";
    $intestazioni .= "Content-type: text/html; charset=iso-8859-1\r\n";
	if (eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$", $destinatario)) {
	mail($destinatario, $oggetto, $messaggio, $intestazioni);}
}
function foglio($destinatario="",$oggetto="",$messaggio="",$mittente="")
{
    return '<html>
<head>
<title>invia email</title>
<style type="text/css">

#foglio{
    background-image: url(\'images/foglio.jpg\'); 
    height: 500px; 
    width: 400px;
    color: black;
}
#contenitore{
    width: 310px;
    color: black;
}

input.testo{
    background-color: transparent; 
    border-color: black; 
    width: 250px; 
    border-width: 0px 0px 2px;
}
textarea{
    background-color: transparent; 
    height: 250px; 
    width: 300px; 
    border-width: 0px; 
    margin-top: 10px; 
    margin-left: 0px;
    color: black;
}
</style>
</head>
<body>
<form action="mail.php" method="post">
<center><div id="foglio">
<div id="contenitore">
<br /><br />
<table>
<tr>
<td>a:</td><td style="text-align: right;"><input class="testo" name="destinatario" value="'.$destinatario.'" /></td>
</tr>
<tr>
<td>da:</td><td style="text-align: right;"><input class="testo" name="mittente" value="'.$mittente.'" /></td>
</tr>
<tr>
<td>oggetto:</td><td style="text-align: right;"><input class="testo" name="oggetto" value="'.$oggetto.'" /> </td>
</tr>
</table>
 <br />
testo:<br />
<textarea id="area" name="messaggio">'.$messaggio.'</textarea><br />
<center>
<input type="submit" name="action" value="invia" /></center>
</div></div></center></form>

</body>
</html>';
}

?>