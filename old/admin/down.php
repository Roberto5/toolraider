<?php
include ("../functions.php");
include ("../my_config.php");
Pagina_protetta_admin();

$separatore="/"; // \\ per windos / per linux

$filename=$_GET['file'];
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
Header("Content-Disposition: attachment; filename=" . $file);
readfile($filename);


?>