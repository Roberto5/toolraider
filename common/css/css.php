<?php
if (! empty($_SERVER["HTTP_ACCEPT_ENCODING"]) &&
 strpos("gzip", $_SERVER["HTTP_ACCEPT_ENCODING"]) === NULL) {} else {
    ob_start("ob_gzhandler");
}
header('Content-Type: text/css; charset: UTF-8');
header('Cache-Control: must-revalidate');
$expire_offset = 1814400; // set to a reaonable interval, say 3600 (1 hr)
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expire_offset) . ' GMT');
function css_compress ($buffer)
{
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer); // remove comments
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', 
    $buffer); // remove tabs, spaces, newlines, etc.
    $buffer = str_replace('{ ', '{', $buffer); // remove unnecessary spaces.
    $buffer = str_replace(' }', '}', $buffer);
    $buffer = str_replace('; ', ';', $buffer);
    $buffer = str_replace(', ', ',', $buffer);
    $buffer = str_replace(' {', '{', $buffer);
    $buffer = str_replace('} ', '}', $buffer);
    $buffer = str_replace(': ', ':', $buffer);
    $buffer = str_replace(' ,', ',', $buffer);
    $buffer = str_replace(' ;', ';', $buffer);
    return $buffer;
}
function dump_css_cache ($filename)
{
    $cwd = getcwd() . DIRECTORY_SEPARATOR;
    $stat = stat($filename);
    $current_cache = $cwd . '.' . $filename . '.' . $stat['size'] . '-' .
     $stat['mtime'] . '.cache';
    // the cache exists - just dump it
    if (is_file($current_cache)) {
        $cache_contents = file_get_contents($current_cache);
    } else {
        // remove any old, lingering caches for this file
        $dead_files = glob($cwd . '.' . $filename . '.*.cache', 
        GLOB_NOESCAPE);
        if ($dead_files)
            foreach ($dead_files as $dead_file)
                @unlink($dead_file);
        if (! function_exists('file_put_contents')) {
            function file_put_contents ($filename, $contents)
            {
                $handle = fopen($filename, 'w');
                fwrite($handle, $contents);
                fclose($handle);
            }
        }
        $cache_contents = css_compress(file_get_contents($filename));
        @file_put_contents($current_cache, $cache_contents);
    }
    return array('text' => $cache_contents, 'mtime' => $stat['mtime']);
}
if (isset($_GET['l']) && ($_GET['l'] == 'm')) {
    $layout = 'mobile.css';
  
} else {
    $layout = 'style.css';
}
$css=array(
		'jquery.contextmenu.css',
		'jquery.lightbox-0.5.css',
		'jquery-ui.css',
		'scroll.css');

$r = dump_css_cache($layout);
$display = $r['text'];
$mtime = $r['mtime'];
foreach ($css as $value) {
	$r = dump_css_cache($value);
	$display .= $r['text'];
	if ($mtime < $r['mtime']) $mtime = $r['mtime'];
}    
//$display.=file_get_contents('jquery-ui-1.8.11.custom.css');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
echo $display;
?>