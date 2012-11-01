<?php
session_start();
include ("inc/foot.php");
include ("my_config.php");
include ("functions.php");
online();
echo '
<script type="text/javascript" src="http://www.google.com/jsapi?key=ABQIAAAAfKC-nO0HuM5wGd3DeApcrBS1oovni2DagzTGMoy7G1tWHw6UHhQBMXFHln11xRF2iHq14q6FO3VX0g"></script>

<script type="text/javascript" src="rss/gfeedfetcher.js"></script>

<script type="text/javascript" src="rss/gajaxscroller.js">

/***********************************************
* gAjax RSS Pausing Scroller- (c) Dynamic Drive (www.dynamicdrive.com)
* Requires "gfeedfetcher.js" class
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript"> 
function hide()
{
    if ($("#feed").css("display")!="none") {$("#feed").css("display","none");$("#selettore").text("mostra");}
    else {
        $("#feed").css("display","block");
        $("#selettore").text("nascondi");
    }
}
</script>';
$_POST = inputctrl($_POST);
$Db=new db();

@$action = $_GET['action'];
if (($action == "bugreport")||($action == "consigli")) {
    @$oggetto = $_POST['oggetto'];
    @$testo = $_POST['testo'];
    @$mail = $_POST['mail'];
    @$nick=$_POST['nick'];
    if ((!$oggetto)||(!$testo)||(!$mail)||(!$nick)) Errore("index.php","ERRORE","Riempire almeno uni deri tre campi, oggetto, email e testo ".$oggetto.$testo.$mail.$nick);
    // upload file
    $upload_dir = "upload";
    $new_name = $action=="bugreport" ?  "bugreport_" : "tip_";
    $file_name = $new_name . $_FILES["upfile"]["name"];
    if (trim($_FILES["upfile"]["name"]) != "") {
        if (@is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
            @move_uploaded_file($_FILES["upfile"]["tmp_name"], "$upload_dir/$file_name") or
                Errore("index.php", "ERRORE", "Impossibile spostare il file $file_name in $upload_dir, contatta l'amministratore del sito.");
        } else {
            die("Problemi nell'upload del file " . $_FILES["upfile"]["name"]);
        }
        $link= $upload_dir . "/" . $file_name;
    }
    else $link="";
    //****************
    $Db->connect();
    $Db->query("INSERT INTO `report` SET `mail`='" . $mail . "' , `oggetto`='" . $oggetto .
        "' , `testo`='" . $testo . "' , `tipo`=".($action=="bugreport" ? "1" : "0").", `link`='" .$link.
        "' , `mittente`='".$nick."'");
    $Db->close();
    Ok("index.php", "invio effetuato con successo", "attendere");
}


menu(); 
echo '
<center>
<h1 align="center" style="background-image: url(images/titolo.jpg); background-repeat: no-repeat; background-position: center; height: 183px; width: 615px;">
Toolraider '.$version.'</h1></center>

<p>';
if (($type=islog())=="no") {
    login();
    // ********************pagina pubblica********************
} else {
    welcome();
    //********************pagina privata*******************
}
echo '
</p>


<center><h4><a href="mailto:toolraider@altervista.org">Contattami via email</a></h4>
<h3><a href="javascript:;" onclick="if (document.getElementById(\'bugreport\').style.display==\'none\') document.getElementById(\'bugreport\').style.display=\'block\'; else document.getElementById(\'bugreport\').style.display=\'none\';">segnala bug</a></h3>
<div id="bugreport" style="display: none;"><form name="bug" action="index.php?action=bugreport" method="post" enctype="multipart/form-data">';
if (@$_SESSION['login'] == "no")
    echo "lasciaci una mail per essere ricontattato oppure fai <a href=\"login.php\">l'accesso</a><br />
    <input name=\"mail\" size=\"10\" /><br />
    <input type=\"hidden\" name=\"nick\" value=\"ospite\" /><br />";
else
    echo "<input type=\"hidden\" name=\"mail\" value=\"" . $_SESSION['mail'] . "\" />
    <input type=\"hidden\" name=\"nick\" value=\"" . $_SESSION['username'] . "\" /><b>".$_SESSION['username']."</b>";

echo '
<b>Oggetto</b><br /><input name="oggetto" size="40" maxlength="40" /><br />
<b>invia immagini delle schermate</b><br />
<input type="file" name="upfile" /><br />
<b>testo</b><br /><textarea name="testo" cols="30" rows="10"></textarea><br />
<input type="submit" value="invia" /></form></div>
<h3><a href="javascript:;" onclick="if (document.getElementById(\'consigli\').style.display==\'none\') document.getElementById(\'consigli\').style.display=\'block\'; else document.getElementById(\'consigli\').style.display=\'none\';">Inviami suggerimenti</a></h3>
<div id="consigli" style="display: none;">
<form name="consigli" action="index.php?action=consigli" method="post">';
    if (@$_SESSION['login'] == "no")
    echo "lasciaci una mail per essere ricontattato oppure fai <a href=\"login.php\">l'accesso</a><br />
    <input name=\"mail\" size=\"10\" /><br />
    <input type=\"hidden\" name=\"nick\" value=\"ospite\" /><br />";
else
    echo "<input type=\"hidden\" name=\"mail\" value=\"" . $_SESSION['mail'] . "\" />
    <input type=\"hidden\" name=\"nick\" value=\"" . $_SESSION['username'] . "\" /><b>".$_SESSION['username']."</b>";

echo '
<b>Oggetto</b><br /><input name="oggetto" size="40" maxlength="40" /><br />
<strong>testo</strong><br /><textarea name="testo" cols="30" rows="10"></textarea><br />
<input type="submit" value="invia" /></form>


</div>

</center>


<div style="position:absolute; top: 280px; right: 10px;"><b>NESW DEL SITO</b> <a href="http://toolraider.altervista.org/blog/feed/rss2/">(abbonati)</a><a href="#" id="selettore" onclick="hide()">(Nascondi)</a></div>
<script type="text/javascript">

var newsfeed=new gfeedpausescroller("feed", "feedclass", 5000, "_new")
newsfeed.addFeed("toolraider", "http://blogpagliaccio.wordpress.com/feed/") //Specify "label" plus URL to RSS feed
newsfeed.displayoptions("datetime snippet") //show the specified additional fields
newsfeed.setentrycontainer("p") //Display each entry as a paragraph
newsfeed.filterfeed(8, "date") //Show 8 entries, sort by date
newsfeed.entries_per_page(2)
newsfeed.init() //Always call this last

</script>
<br />
<br />
<br />
';

foot();
?>