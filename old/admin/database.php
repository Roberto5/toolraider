<?php

include("../functions.php");
include("../my_config.php");

Pagina_protetta_admin();


// ricerca info db
$Db=new db();
$Db->connect();

$separatore="/";

$table=$Db->tocolarray("SHOW TABLES FROM `".DATABASE."`");
$backup="SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n\n";
for ($i=0;$table[$i];$i++)
{
    $create=$Db->toarray("SHOW CREATE TABLE `".$table[$i]."`");
    $create=$create['Create Table'];
    $backup.="\n".str_replace("CREATE TABLE","CREATE TABLE IF NOT EXISTS",$create).";\n\n";
}

$Db->close();

//echo '<textarea cols="100" rows="50">'.$backup.'</textarea>';
//exit;

// scrittura file

$file = fopen("backup.sql","w");
fwrite($file, $backup);
fclose($file);

$this_folder = getcwd();
$filename=$this_folder.$separatore."backup.sql";
//echo $filename;
//exit;
$file=substr($filename,strrpos($filename,$separatore));

function detect_browser($var) {
		if(@eregi("(msie) ([0-9]{1,2}.[0-9]{1,3})", $var)) {
			$str = "ie";
		} else {
			$str = "nn";
		}
	return $str;
}

if (detect_browser($_SERVER['HTTP_USER_AGENT']) == "ie") {
    Header("Content-type: application/force-download");
} else {
    Header("Content-Type: application/octet-stream");
}
Header("Content-Length: " . filesize($filename));
Header("Content-Disposition: attachment; filename=".$file);
readfile($filename);
?>


