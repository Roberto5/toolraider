<?php

/**
 * @author roberto
 * @copyright 2010
 */


session_start();
include("functions.php");
include("my_config.php");
include("inc/foot.php");
Pagina_protetta(1);
online(6);
menu();
$Db=new db();


$sezioni=array('Ingegneria','Difesa','Militare','Civile','Energia');
$ricerche[0][0]['display']="Capacità";
$ricerche[0][0]['nome']="capacita";
$ricerche[0][1]['display']="Teletrasporto";//solo se titano
$ricerche[0][1]['nome']="teletrasporto";
$ricerche[0][1]['razza']="Titani";

$ricerche[1][0]['display']="Quota riparazione";
$ricerche[1][0]['nome']="riparazione";
$ricerche[1][1]['display']="Occultamento";
$ricerche[1][1]['nome']="occultamento";

$ricerche[2][0]['display']="Propulsione militare";
$ricerche[2][0]['nome']="propulsione_mil";
$ricerche[2][1]['display']="Capacità di carico militare";
$ricerche[2][1]['nome']="carico_mil";
$ricerche[2][2]['display']="Consumo militare";
$ricerche[2][2]['nome']="consumo_mil";
$ricerche[2][3]['display']="Serbatoio militare";
$ricerche[2][3]['nome']="serbatoio_mil";

$ricerche[3][0]['display']="Propulsione civile";
$ricerche[3][0]['nome']="propulsione_civ";
$ricerche[3][1]['display']="Capacità di carico civile";
$ricerche[3][1]['nome']="carico_civ";
$ricerche[3][2]['display']="Consumo civile";
$ricerche[3][2]['nome']="consumo_civ";
$ricerche[3][3]['display']="Serbatoio civile";
$ricerche[3][3]['nome']="serbatoio_civ";
$ricerche[3][4]['display']="Commercio";
$ricerche[3][4]['nome']="commercio";
$ricerche[3][5]['display']="Riciclaggio";
$ricerche[3][5]['nome']="riciclaggio";
$ricerche[3][6]['display']="Fisica spazio temporale";
$ricerche[3][6]['nome']="fisica";
$ricerche[3][7]['display']="Spionaggio";
$ricerche[3][7]['nome']="spionaggio";

$ricerche[4][0]['display']="Cea";
$ricerche[4][0]['nome']="cea";
$ricerche[4][1]['display']="Energia nuleare";
$ricerche[4][1]['nome']="nuleare";
$ricerche[4][2]['display']="Energia solare";
$ricerche[4][2]['nome']="solare";
$ricerche[4][3]['display']="Energia termica";//solo xen
$ricerche[4][3]['nome']="termica";
$ricerche[4][3]['razza']="Xen";
$ricerche[4][4]['display']="Energia idrica";// no xen
$ricerche[4][4]['nome']="idrica";
$ricerche[4][4]['razza']="noXen";
$ricerche[4][5]['display']="Energia eolica";
$ricerche[4][5]['nome']="eolica";
$ricerche[4][6]['display']="Sviluppo Qi";
$ricerche[4][6]['nome']="Qi";

//controllo esistenza tebella ricerche
$Db->connect();
$query="SELECT * FROM `us_ricerche` WHERE `id`='".$_SESSION['nome']."'";
$riga=$Db->totable($query);
$n=$Db->n;
$Db->close();

if ($_POST['action']) {
    
    for ($i=0;$ricerche[$i];$i++)
    {
        for ($j=0;$ricerche[$i][$j];$j++)
        {
            $$ricerche[$i][$j]['nome']=(int)$_POST[$ricerche[$i][$j]['nome']];
        }
    }
    $Db->connect();
    if (!$n) {
        $query="INSERT INTO `us_ricerche` (`id`,`ricerca`,`liv`) VALUES ";
        for ($i=0;$ricerche[$i];$i++)
        {
            for ($j=0;$ricerche[$i][$j];$j++)
            {
                if ($$ricerche[$i][$j]['nome'])
                    $query.="('".$_SESSION['nome']."','".$ricerche[$i][$j]['nome']."','".$$ricerche[$i][$j]['nome']."'),";
            }
        }
        if (strlen($query)>56) {
            $query=substr($query,0,-1);// tolgo la virgola finale
            $Db->query($query);
        }
    }
    else {
        $query2="INSERT INTO `us_ricerche` (`id`,`ricerca`,`liv`) VALUES ";
        $bool2=false;
        for ($i=0;$ricerche[$i];$i++)
        {
            for ($j=0;$ricerche[$i][$j];$j++)
            {
                $bool=true;
                for ($k=0;($riga[$k])&&$bool;$k++)
                    if ($riga[$k]['ricerca']==$ricerche[$i][$j]['nome']) $bool=false;
                if (($bool)&&($$ricerche[$i][$j]['nome'])) {$query2.="('".$_SESSION['nome']."','".$ricerche[$i][$j]['nome']."','".$$ricerche[$i][$j]['nome']."'),";$bool2=true;}
                elseif ((!$bool)&&($riga[--$k]['liv']!=$$ricerche[$i][$j]['nome']))
                {
                    if ($$ricerche[$i][$j]['nome']==0) {
                        $Db->query("DELETE FROM `us_ricerche` WHERE `id`='".$_SESSION['nome']."' AND `ricerca`='".$ricerche[$i][$j]['nome']."'");
                    }
                    else $Db->query("UPDATE `us_ricerche` SET `liv`='".$$ricerche[$i][$j]['nome']."' WHERE `id`='".$_SESSION['nome']."' AND `ricerca`='".$ricerche[$i][$j]['nome']."'");
                }
            }
        }
        if ($bool2) {
            $query2=substr($query2,0,-1);// tolgo la virgola finale
            $Db->query($query2);
        }
    }
    $Db->close();
    ok ("ricerche.php","inserimento eseguito","inserimento avvenuto con successo");
}

echo '<center><form name="ricerche" action="ricerche.php" method="post"> ';
for ($i=0;$ricerche[$i];$i++)
{
    echo '<h3>'.$sezioni[$i].'</h3>
    <table>';
    for ($j=0;$ricerche[$i][$j];$j++)
    {
        $razza=false;
        if ($ricerche[$i][$j]['razza']) {
            if (substr($ricerche[$i][$j]['razza'],0,2)=="no") {
                if ($ricerche[$i][$j]['razza']=="no".$_SESSION['razza']) $razza=false;
                else $razza=true;
            }
            elseif ($ricerche[$i][$j]['razza']==$_SESSION['razza']) $razza=true;
        }
        else $razza=true;
        if ($razza) {
            echo '<tr>
            <td>'.$ricerche[$i][$j]['display'].'</td>
            <td><select name="'.$ricerche[$i][$j]['nome'].'">';
            $bool=true;
            for ($k=0;($riga[$k])&&$bool;$k++)
                if ($riga[$k]['ricerca']==$ricerche[$i][$j]['nome']) $bool=false;
            if ($bool) $sel=0;
            else $sel=$riga[--$k]['liv']+1;
            select(10,0,"","",$sel);
            echo '</select>';
        }
    }
    echo '</table>';
}
echo '<input type="submit" name="action" value="salva" />
</form>
</center>';

foot();

?>

