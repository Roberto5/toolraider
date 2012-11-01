<html>
<head>
<?php
include("functions.php");
include("my_config.php");
Pagina_protetta(1);
session_start();
$Db=new db();
online(5);
$_POST=inputctrl($_POST);
$_GET=inputctrl($_GET);
$opt="raid".substr(strtolower($_SESSION['razza']),0,3);
$opt2=$_GET['opt'];
$tot=$_POST['tot'];
$n=0;
?>
<script language="Javascript">
var dati= new Array();
<?php
$Db->connect();
for($i=0;$i<$tot;$i++)
{
    $date=date("Y-m-d H:i:s");
	$variabile='$check'.$i;
	$lid=$_POST[$variabile];
    $query="UPDATE `us_list` SET `date`='".$date."' WHERE lid='".$lid."' ";
    $Db->query($query);
    $query="SELECT * FROM `us_list` WHERE lid='".$lid."' ";
    $riga=$Db->toarray($query);
    if ($opt2) $option=$opt2; else {
        
	   $option=$opt.$riga['tipo_nave'].$riga['num_nave'];
    }
    $l=linkmaker($riga['link'],$option);
	if ($lid!="") {?>dati[<?php echo $n;?>] ='<?php echo $l;?>'; <?php $n++;}
	//echo "document.write(\" riga ".$riga['tipo_nave']." lid ".$lid." option ".$option." link ".$l." <br /> \");";
}
$Db->close();

?>
var t=<?php echo $n;?>;


function openlink(i)
{
    ret=true;
    if (i<t) {
        r=window.open(dati[i],'','');
        i++;
        ret=openlink(i);
        if (!r) ret=false;
    }
    return ret;
}
bool=true;
function openl()
{ 
    i=0;
    bool=openlink(i);
}
function closef()
{
    if (bool) window.close(); else document.getElementById('err').innerHTML="blocco popup rilevato";
}
</script> 

</head>
<body>


<script language="javascript">window.setTimeout(openl,500);</script>
<center><a onclick="openl()" href="javascript:void()">se non si aprono i link clikka qui.</a>
<div id="err"></div>

<script language="javascript">
window.setTimeout(closef,2000)

</script>
</center>
</body>
</html>