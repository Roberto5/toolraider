<?php
Header("Content-Type: image/png");
include("sql.php");
function sum_query($ally)
{
    $sql="SELECT sum( n1 ) +  sum( n2 ) + sum( n3 ) +  sum( n4 ) + sum( n5 ) +  sum( n6 ) + sum( n7 ) +  sum( n8 ) + sum( n9 ) +  sum( n10 ) AS navi
FROM `tt_navi`
WHERE `ally` = '".$ally."'";
    $risultato=mysql_query($sql);
    $row=mysql_fetch_array($risultato);
    return $row['navi'];
}
function sum_query2($uid)
{
    $sql="SELECT sum( n1 ) +  sum( n2 ) + sum( n3 ) +  sum( n4 ) + sum( n5 ) +  sum( n6 ) + sum( n7 ) +  sum( n8 ) + sum( n9 ) +  sum( n10 ) AS navi
FROM `tt_navi` 
WHERE `uid` = '".$uid."'";
    $risultato=mysql_query($sql);
    $row=mysql_fetch_array($risultato);
    return $row['navi'];
}


//security();
$ally=$_GET['ally'];
$uid=$_GET['uid'];


$Db=new db();
$Db->connect();

$tot=(int)sum_query($ally);//ges=tot
$utot=(int)sum_query2($uid);
$Db->close();
//$tot=1000;
//$utot=105;
$u_ges=($utot/$tot)*360;
  //Prozente
$u_per='('.round($utot/$tot*100).'%)';
$t_per='('.(100-round($utot/$tot*100)).'%)';
//$u_ges  $u_ges
  
    $im = @imagecreate(160,110) or die("Cannot Initialize new GD image stream");
    $white = imagecolorallocate($im, 255, 255, 255);
    $black = imagecolorallocate($im, 0, 0, 0);
    $red = imagecolorallocate($im, 255, 0, 0);
    $green = imagecolorallocate($im, 0, 255, 0);
    $blue = imagecolorallocate($im, 0, 0, 255);
    
    imagefilledarc($im,50,55,100,100,0,$u_ges,$red,0);
    imagefilledarc($im,50,55,100,100,$u_ges,360,$blue,0);
    imagestring($im,1,120,10,"truppe",$red);
    imagestring($im,1,120,20,"proprie",$red);
    imagestring($im,1,120,30,$u_per,$red);
    imagestring($im,1,120,50,"truppe",$blue);
    imagestring($im,1,120,60,"alleate",$blue);
    imagestring($im,1,120,70,$t_per,$blue);
  
  imagepng($im);
  imagedestroy($im);
  
?>