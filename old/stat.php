<?php
Header("Content-Type: image/png");

function intgr($num)
{
    if ($num==intval($num)) return $num; else return intval($num)+1;
}

$im = @imagecreate(800,300) or die("Cannot Initialize new GD image stream");
$white = imagecolorallocate($im, 255, 255, 255);
$black = imagecolorallocate($im, 0, 0, 0);
imagestring($im,10,5,10,$_GET['nome'],$black);
$dati="";
$row="";$max=0;
for($i=1;($i<=7)&&($_GET['d'.$i]);$i++)
{
    $dati[$i-1]=$_GET['d'.$i];
    $row[$i-1]=$_GET['r'.$i];
    if ($_GET['d'.$i]>$max) $max=$_GET['d'.$i];  
}
$l=$i;
$m=10;
$s=intval(200/$max);
$sr=$s*intgr($max/$m);
if ($max<$m) {$sr=$s;$m=$max;}
if (200<$m*$sr) {$m=intgr($max*$s/$sr);}
$dati=array_reverse($dati);
$row=array_reverse($row);
$j=0;
for($i=0,$k=6;$i<=6;$i++,$k--)
{
    $data=date("Y-m-d",strtotime("-".$k." days"));
    for ($j=$l-1;$j>=0;$j--)
    {
        if ($data==$row[$j]) {
            imagefilledrectangle($im,30+$i*100,250,50+$i*100,250-$dati[$j]*$s,$black);
        }
    }
    imagefilledrectangle($im,30+$i*100,250,50+$i*100,250,$black);
    imagestring($im,2,30+$i*100,250,$data,$black);
}
for ($i=1;$i<=$m;$i++)
{
    imageline($im,30,250-$i*$sr,800,250-$i*$sr,$black);
    imagestring($im,2,5,250-$i*$sr,intval($i*$sr/$s),$black);
}

imagepng($im);
imagedestroy($im);
?>