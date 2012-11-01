<?php
Header("Content-Type: image/png");

function inputctrl($vettore,$vectargint=0)
{
    if (!get_magic_quotes_gpc()) {
        $i=0;
        foreach($vettore as $key=>$val )
        {
            $bool=0;
            for($i=0;($vectargint[$i])&&(!$bool);$i++)
                if ($key==$vectargint[$i]) $bool=1; 
            if ($bool) $vettore[$key]=(int)$vettore[$key]; else $vettore[$key]=addslashes($vettore[$key]);
            //$vettore[$key]=str_replace("%","\%",$vettore[$key]);
            //$vettore[$i]=str_replace("_","\_",$vettore[$i]);
            $i++;
        }
    }
    return $vettore;
}

$_GET=inputctrl($_GET,array('g','resize'));

$nome=$_GET['nome'];
$resize=$_GET['resize'];
$ally=$_GET['ally'];
$g=$_GET['g'];

include('sql.php');

$Db=new db();
$Db->connect();

if ($nome) $pianeti=$Db->totable("SELECT `nome_pianeta`,`x`,`y` FROM `us_pianeti` WHERE `id`='".$nome."' AND `g`='".$g."'");
if ($ally) $pianeti=$Db->totable("SELECT `us_pianeti`.* , `us_player`.`nome` FROM `us_pianeti` , `us_player` WHERE `alleanza`='".$ally."' AND `nome`=`us_pianeti`.`id` AND `us_pianeti`.`g`='".$g."'");
$maxx=0;$maxy=0;$i=0;$minx=0;$miny=0;
while($pianeti[$i])
{
    $minx= $pianeti[$i]['x'] < $minx ? $pianeti[$i]['x'] : $minx ;
    $miny= $pianeti[$i]['y'] < $miny ? $pianeti[$i]['y'] : $miny ;
    $maxx= $pianeti[$i]['x'] > $maxx ? $pianeti[$i]['x'] : $maxx ;
    $maxy= $pianeti[$i]['y'] > $maxy ? $pianeti[$i]['y'] : $maxy ;
    $i++;
}
$Db->close();

$im = @imagecreate(800,800) or die("Cannot Initialize new GD image stream");
$black = imagecolorallocate($im, 0, 0, 0);
$white = imagecolorallocate($im, 255, 255, 255);
$red = imagecolorallocate($im, 200, 0, 0);
imageline($im,400,0,400,800,$red);
imageline($im,0,400,800,400,$red);
imageline($im,0,0,800,0,$red);
imageline($im,0,0,0,800,$red);
imageline($im,799,799,0,799,$red);
imageline($im,799,799,799,0,$red);
if (!$i) {
    imagestring($im,5,10,10,'i "'.$i.'" ',$red);
    imagepng($im);
    imagedestroy($im);
    exit;
}
$max=$maxx > $maxy ? $maxx : $maxy;
$min=$minx < $miny ? $minx : $miny;
$min=0-$min;
$max=$max > $min ? $max : $min ;
$max++;
$gr=2;
if ($resize) $gr=intval(400/$max); else $max=400;

$c=0;$color="";
$s=intval(255*6/$i);
$r=255;$v=0;$b=0;
for ($k=0;$c<7;$k++)
{
    switch($c)
    {
        case "1" : $v+=$s;break;
        case "2" : $r-=$s;break;
        case "3" : $b+=$s;break;
        case "4" : $v-=$s;break;
        case "5" : $r+=$s;break;
        case "6" : $b-=$s;break;
    }
    if ($v>255) {$v=255;$c++;}
    if ($v<0) {$v=0;$c++;}
    if ($r>255) {$r=255;$c++;}
    if ($r<0) {$r=0;$c++;}
    if ($b>255) {$b=255;$c++;}
    if ($b<0) {$b=0;$c++;}
    if (!$c) $c++;
    $color[]= imagecolorallocate($im, $r, $v, $b);      
}


/*for($inc=2;((($inc*400/$max)-(400/$max))<29)&&($inc<40);$inc++);

imagestring($im,1,200,($inc*10),"inc ".$inc,$red);
for($i=1;$i<$max*2;$i+=$inc)
{
    $l=405;
    if (!(($i)%5)) {imagestring($im,1,410,($i*400/$max),$max-$i,$red);$l=410;}
    imageline($im,395,$i*400/$max,$l,$i*400/$max,$red);
}
for($i=1;$i<$max*2;$i+=$inc)
{
    $l=405;
    if (!(($i)%5)) {imagestring($im,1,($i*400/$max),410,0-($max-$i),$red);$l=410;}
    imageline($im,$i*400/$max,395,$i*400/$max,$l,$red);
}*/
for($i=0;$pianeti[$i];$i++)
{
    $x=intval($pianeti[$i]['x']*400/$max);
    $off=400;
    $y=intval($pianeti[$i]['y']*400/$max);
    $colore=$color[$i];
    imagestring($im,1,10,$i*10,$pianeti[$i]['nome_pianeta']."(".$pianeti[$i]['x'].",".$pianeti[$i]['y'].")",$colore);
    imagefilledarc($im,$off+$x,$off-$y,$gr,$gr,0,360,$colore,0);
}

imagepng($im);
imagedestroy($im);
?>