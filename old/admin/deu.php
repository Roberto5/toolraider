<?php
session_start();
include ("../functions.php");
include ("../my_config.php");
include ("inc/foot.php");
online(1);
Pagina_protetta_admin();

$separatore="/"; // \\ per windos / per linux


menu();$Db= new db();
$Db->connect();
$folder = $_GET['folder'];
if (($_GET['source'] == "del")&&($folder))  {
    chdir("../");
    $root = getcwd(); // cartella principale progetto
    if (is_dir($folder)) rmdir($folder)or print("<div style=\"color: red;\">impossibile cancellare ".$folder."</div>"); else
    unlink($folder) or print("<div style=\"color: red;\">impossibile cancellare ".$folder."</div>");
    $i = strrpos($folder,$separatore);
    if ($i) $folder = substr($folder,0,$i);
    else  $folder = "";
    
    $_GET['source'] ="down";

}
if ($_GET['source'] == "down") {
    $projectsListIgnore = array('.','..');
    chdir("../");
    $root = getcwd(); // cartella principale progetto
    if ($folder) chdir($folder); //cambio cartella
    $this_folder = getcwd();
    $handle = opendir(".");
    //cartella precedente
    $prev_folder = str_replace($root,"",$this_folder);
    $prev_folder = substr($prev_folder,1);
    $i = strrpos($prev_folder,$separatore);
    if ($i) $prev_folder = substr($prev_folder,0,$i);
    else  $prev_folder = "";
    
    if ($folder) $folders = '<li style="list-style-image: url(images/cartella.png);"><a href="deu.php?source=down&folder='.$prev_folder.'">..</a></li>';
    $files = "";
    while ($file = readdir($handle)) {
        $del="";
        if (@eregi("upload",$this_folder.$file)) $del=' <a href="deu.php?source=del&folder='.$this_folder.$separatore.$file.'"><img src="../images/del.gif" /></a>';
        if (is_dir($file) && !in_array($file,$projectsListIgnore)) {
            $folders .= '<li style="list-style-image: url(images/cartella.png);"><a href="deu.php?source=down&folder='.$this_folder.$separatore.$file.'">'.$file.'</a>'.$del.'</li>';
        } 
        elseif (!in_array($file,$projectsListIgnore)) {
            $files .= '<li style="list-style-image: url(images/file.gif);"><a href="down.php?file='.$this_folder.$separatore.$file.'">'.$file.'</a>'.$del.'</li>';
        }
    }
    closedir($handle);
    echo "<ul>".$folders.$files."<ul>";
} 
elseif ($_GET['source'] == "up") {
    echo "<center>
    <form name=\"bug\" action=\"up.php\" method=\"post\" enctype=\"multipart/form-data\">
    <input type=\"file\" name=\"upfile\" /><br />
    <input name=\"action\" type=\"submit\" value=\"upload\" />
    </form>
    </center>";
} 
else {
    echo "<center><a href=\"deu.php?source=down\">download sorgenti</a> | <a href=\"deu.php?source=up\">upload file</a></center>";
}

//echo $_SERVER['HTTP_HOST']."<br />";
//echo $_SERVER['DOCUMENT_ROOT']."<br />";
$Db->close();
foot();
?>
