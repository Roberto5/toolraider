<?php
if(!empty($_SERVER["HTTP_ACCEPT_ENCODING"]) && strpos("gzip",$_SERVER["HTTP_ACCEPT_ENCODING"]) === NULL){}else{ob_start("ob_gzhandler");}

header('Content-Type: text/javascript; charset: UTF-8');
header('Cache-Control: must-revalidate');

$expire_offset = 1814400; // set to a reaonable interval, say 3600 (1 hr)
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expire_offset) . ' GMT');



$file=array(		
		"*framework*",
		"jquery.js",
		"jquery-ui.js",
		//menu col tasto destro
		"jquery.contextmenu.js",
		//uso cokie
		"jquery.cookie.js",
		'*validate form*',
		'jquery.validate.min.js',
		//presentazione immagini
		//"jquery.lightbox-0.5.min.js",
		// scroller
		"jquery.li-scroller.1.0.js",
		//toltip
		//"jquery.tools.min.js",
		//upload con ajax
		//"jquery.ajaxupload.3.5.js,"
		'jquery.details.min.js',
		'ship.js',
		'planet.js',
		'*main script*',
		"main.js"
		,'reg.js'
		,'profile.js');

$text="";$mtime=0;
foreach ($file as $value) {
	if (preg_match("/\*(.+)\*/", $value)) {
		$text.="\n/************$value************/\n";
	}
	else {
		$text.=file_get_contents($value);
		$stat=stat($value);
	if ($mtime<$stat['mtime']) $mtime=$stat['mtime'];
	}
	
}
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
echo $text;

?>