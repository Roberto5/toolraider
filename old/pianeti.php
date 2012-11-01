<?php
session_start();
$title=": pianeti";
include("inc/foot.php");
include("my_config.php");
include("functions.php");
online(6);
pagina_protetta(1);

$Db=new db();

echo '
<link rel="stylesheet" href="scripts/thumbnailviewer.css" type="text/css" />
<script src="scripts/thumbnailviewer.js" type="text/javascript"></script>
<script language="Javascript"><!--
function control()
{
	if ((document.mod.x.value>400)||(document.mod.x.value<-400)) {document.mod.x.value=0;}
	if ((document.mod.y.value>400)||(document.mod.y.value<-400)) {document.mod.y.value=0;}
	if ((document.mod.g.value>3)||(document.mod.g.value<1)) {document.mod.g.value=0;}
	document.mod.x.value=document.mod.x.value.replace(/[a-zA-Z]/g,"");
	document.mod.y.value=document.mod.y.value.replace(/[a-zA-Z]/g,"");
	document.mod.g.value=document.mod.g.value.replace(/[a-zA-Z]/g,"");
}
//--></script>';

menu();

$_POST=inputctrl($_POST,array('g','x','y'));
$pid=(int)$_GET['pid'];
$action=$_GET['action'];

if ($action=="add") {
	echo "<form name=\"mod\" action=\"pianeti.php?action=do_add\" method=\"post\"> <center><table>
    <tr><td>nome</td><td>coordinate</td></tr>
    <tr><td><input size=\"20\" maxlength=\"20\" name=\"pianeta\" type=\"text\" /> </td><td> <select name=\"g\" ><option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option></select> | <input onchange='control()' type=\"int\" size=\"4\" maxlength=\"4\" name=\"x\" /> | <input onchange='control()' type=\"int\" size=\"4\" maxlength=\"4\" name=\"y\" /><br /></td></tr>
    </table><input type=\"submit\" value=\"insert\" /></center></form>";
}
elseif ($action=="do_add") {
	$nome1=$_POST['pianeta'];
	$g=$_POST['g'];
	$x=$_POST['x'];
	$y=$_POST['y'];
	$Db->connect();
	$query="SELECT * FROM `us_pianeti` WHERE `nome_pianeta`='".$nome1."' AND `id`='".$_SESSION['nome']."'";
	$row=$Db->totable($query);
	for ($i=0;$row[$i];$i++)
		if ($row[$i]['nome_pianeta']==$nome1) Errore("pianeti.php","ERRORE","il nome è già usato",".");
	$query="INSERT INTO `us_pianeti` SET `id`='".$_SESSION['nome']."' , `nome_pianeta`='".$nome1."' , `g`='".$g."' , `x`='".$x."' , `y`='".$y."'";
	$Db->query($query);
	$Db->close();
	ok("pianeti.php","ok","aggiunta avvenuta con successo");
}
elseif ($action=="canc"){
	if ($pid!="") {
		$Db->connect();
		$query="DELETE FROM `us_pianeti` WHERE `pid` = '".$pid."'";
		$Db->query($query);
		$Db->close();
		ok("pianeti.php","ok","cancellazione avvenuta con successo");
	}
}
elseif ($action=="do") {
	$nome1=$_POST['pianeta'];
	$g=$_POST['g'];
	$x=$_POST['x'];
	$y=$_POST['y'];
	$Db->connect();
	$query="SELECT * FROM `us_pianeti` WHERE `nome_pianeta`='".$nome1."' AND `id`='".$_SESSION['nome']."'";
	$row=$Db->totable($query);
	$i=0;
	while ($row[$i])
		if ($row[$i]['nome_pianeta']==$nome1) $i++;
	if (i>1) ERRORE("pianeti.php","ERRORE","il nome è già usato");
	$query="UPDATE `us_pianeti` SET `nome_pianeta`='".$nome1."' , `g`='".$g."' , `x`='".$x."' , `y`='".$y."' WHERE `pid`='".$pid."'";
	$Db->query($query);
	$Db->close();
	ok("pianeti.php","ok","modifica avvenuta con successo");
}
elseif ($pid!="") {
	$Db->connect();
	$query="SELECT * FROM `us_pianeti` WHERE `pid`='".$pid."'";
	$row=$Db->toarray($query);
	echo "<center><form name=mod action=pianeti.php?action=do&pid=".$pid." method=post><input type=text name=pianeta size=20 maxlength=20 value=".$row['nome_pianeta']."> <input onChange='control()' type=int maxlength=1 size=1 name=g value=".$row['g']."> | <input onChange='control()' type=int size=4 maxlength=4 name=x value= ".$row['x']."> | <input onChange='control()' type=int size=4 maxlength=4 name=y value=".$row['y']."><br /><input type=submit value=modifica></form></center>";
	$Db->close();
}
else {

	echo "<center><table>
	<tr>
	<td>nome</td><td>coordinate</td><td></td>
	</tr>";
	$Db->connect();
	$query="SELECT * FROM `us_pianeti` WHERE `id`='".$_SESSION['nome']."'";
	$pianeti=$Db->totable($query);
	for ($i=0;$pianeti[$i];$i++)
	{
		echo "<tr><td>".$pianeti[$i]['nome_pianeta']."</td><td>".$pianeti[$i]['g']." | ".$pianeti[$i]['x']." | ".$pianeti[$i]['y']."</td><td><a href=pianeti.php?pid=".$pianeti[$i]['pid']."><img src=admin/images/edit.gif border=0></a><a href=pianeti.php?action=canc&pid=".$pianeti[$i]['pid']."><img src=images/button_cancel.gif border=0></a></td></tr>";
	}
	$Db->close();
	echo "</table><br /><a href=pianeti.php?action=add><b><u>Aggiungi</u></b></a><br /><br /><br />
    <div><span style=\"color: red;\">Più vicino</span> <span style=\"color: blue;\">più distante</span></div>
    <table border=\"1\"><tr><td></td>";
    $distance="";$max=0;
    for ($i=0;$pianeti[$i];$i++)
    {
        for ($j=0;$pianeti[$j];$j++)
        {
            $distance[$i][$j]=intval(sqrt((($pianeti[$j]['x']-$pianeti[$i]['x'])*($pianeti[$j]['x']-$pianeti[$i]['x']))+(($pianeti[$j]['y']-$pianeti[$i]['y'])*($pianeti[$j]['y']-$pianeti[$i]['y']))));
            if ($distance[$i][$j]>$max) $max=$distance[$i][$j];
        }
    }
    
    for ($i=0;$pianeti[$i];$i++)
        echo "<td>".$pianeti[$i]['nome_pianeta']."</td>";
    echo "</tr>";
    $s=intval(255*4/$max);
    for ($i=0;$pianeti[$i];$i++)
    {
        echo "<tr><td>".$pianeti[$i]['nome_pianeta']."</td>";
        for ($j=0;$pianeti[$j];$j++)
        {
            $c=1;
            $r=255;
            $v=0;
            $b=0;
            for ($k=0;$k<($distance[$i][$j]);$k++)
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
                if ($c>6) $c=1;
            }
            $re=dechex($r);
            $be=dechex($b);
            $ve=dechex($v);
            $ri=dechex(255-$r);
            $bi=dechex(255-$b);
            $vi=dechex(255-$v);
            if (strlen($re)<2) $re="0".$re;
            if (strlen($ve)<2) $ve="0".$ve;
            if (strlen($be)<2) $be="0".$be;
            if (strlen($ri)<2) $ri="0".$ri;
            if (strlen($vi)<2) $vi="0".$vi;
            if (strlen($bi)<2) $bi="0".$bi;
            $colore="#".$re.$ve.$be;
            $sfondo="";
            if (!$distance[$i][$j]) $sfondo="background: #".$ri.$vi.$bi.";";
            //$distance.="(".$pianeti[$j]['x'].",".$pianeti[$j]['y'].")(".$pianeti[$i]['x'].",".$pianeti[$i]['y'].")";
            if ($i!=$j) echo "<td style=\"color: ".$colore."; ".$sfondo."\">".$distance[$i][$j]."</td>"; else echo "<td>-</td>";
        }
        echo "</tr>";
    }
    echo "</table>
    mappa stellare edi pianeti<br />
    
    <a href=\"allY.php?action=mod\">Modifica profilo</a><br />
    
    <a href=\"map.php?nome=".$_SESSION['nome']."&resize=1&g=1\" rel=\"thumbnail\" title=\"mappa\">
    <img class=\"image\" width=\"200\" height=\"200\" src=\"map.php?nome=".$_SESSION['nome']."&resize=1&g=1\" title=\"G1\" /></a>
    
    <a href=\"map.php?nome=".$_SESSION['nome']."&resize=1&g=2\" rel=\"thumbnail\" title=\"mappa\">
    <img class=\"image\" width=\"200\" height=\"200\" src=\"map.php?nome=".$_SESSION['nome']."&resize=1&g=2\" title=\"G2\" /></a>
    
    <a href=\"map.php?nome=".$_SESSION['nome']."&resize=1&g=3\" rel=\"thumbnail\" title=\"mappa\">
    <img class=\"image\" width=\"200\" height=\"200\" src=\"map.php?nome=".$_SESSION['nome']."&resize=1&g=3\" title=\"G3\" /></a>
    ";
    
    $maxx=0;$maxy=0;$i=0;$minx=0;$miny=0;
    while($pianeti[$i])
    {
        $minx= $pianeti[$i]['x'] < $minx ? $pianeti[$i]['x'] : $minx ;
        $miny= $pianeti[$i]['y'] < $miny ? $pianeti[$i]['y'] : $miny ;
        $maxx= $pianeti[$i]['x'] > $maxx ? $pianeti[$i]['x'] : $maxx ;
        $maxy= $pianeti[$i]['y'] > $maxy ? $pianeti[$i]['y'] : $maxy ;
        $i++;
    }
    $max=$maxx > $maxy ? $maxx : $maxy;
    $min=$minx < $miny ? $minx : $miny;
    $min=0-$min;
    $max=$max > $min ? $max : $min ;
    $max++;
    $gr=intval(400/$max);
    echo "<map name=\"map\" id=\"map\">";
    for($i=0;$pianeti[$i];$i++)
    {
        $x=intval($pianeti[$i]['x']*400/$max);
        $off=intval(400);
        $y=intval($pianeti[$i]['y']*400/$max);
        $off2=$off+$gr;
        $r=intval($gr/2);
        echo "<area shape=\"circle\" coords=\"".($off+$x).",".($off-$y).",".$r."\" title=\"".$pianeti[$i]['nome_pianeta']."(".$pianeti[$i]['x'].",".$pianeti[$i]['y'].")"."\" href=\"#\" />";
    }
    echo "</map>";
}
foot();
?>