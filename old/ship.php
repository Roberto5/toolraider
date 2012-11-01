<?php

/**
 * @author roberto
 * @copyright 2010
 */

session_start();
include("functions.php");
include("my_config.php");
include("inc/foot.php");
online(3);
Pagina_protetta(1);

echo '<script language="javascript" src="scripts/ship.js"></script>
<script type="text/javascript" src="scripts/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>';

menu();
//array nomi navi
$Db=new db();
$ally=isally();
$_POST=inputctrl($_POST);
$_GET=inputctrl($_GET);
$action=$_GET['action'];

if ($action=="show") {
    $uid=$_GET['uid'];
    privilegi($ally,"g","",0);
    $Db->connect();
    $raz1=$Db->toarray("SELECT * FROM `us_player` WHERE `nome`='".$uid."'");
    if ($ally!=$raz1['alleanza']) Errore("index.php","ACCESSO NEGATO","non puoi accedere ad informazioni di altre alleanze");
    $raz=$raz1['razza'];
    $tt=$Db->totable("SELECT * FROM `tt_navi` WHERE `uid`='".$uid."'");
    $pianeti=$Db->totable("SELECT * FROM `us_pianeti` WHERE `id`='".$uid."'");
    for ($i=0;$tt[$i];$i++)
    {
        for ($j=0;$pianeti[$j];$j++)
        {
            if ($tt[$i]['pianeta']==$pianeti[$j]['pid']) {$tt[$i]['nome_pianeta']=$pianeti[$j]['nome_pianeta'];}
        }
    }
    $tt[0]['uid']=$uid;
    show($tt,$navi,1,$raz);
    $Db->close();
}
elseif (($action=="add")&&($_POST['pianeta'])) {
    $pianeta=$_POST['pianeta'];
    for($i=1;$i<=12;$i++)
    {
        $t[$i]=(int)$_POST['t'.$i];
    }
    $Db->connect();
    $num=$Db->conta("SELECT * FROM `tt_navi` WHERE `uid`='".$_SESSION['nome']."' AND `pianeta`='".$pianeta."'");
    if ($num) {
        $query="UPDATE";$fine="WHERE `uid`='".$_SESSION['nome']."' AND `pianeta`='".$pianeta."'";
    }
    else {
        $query="INSERT INTO";$fine="";
    }
    $query.=" `tt_navi` SET `uid`='".$_SESSION['nome']."' , `ally`='".$ally."' , `pianeta`='".$pianeta."' , `data`='".date("Y-m-d H:i:s")."' ";
    for ($i=1;$i<=12;$i++)
        $query.=", `n".$i."`='".$t[$i]."' ";
    $query.=$fine;
    $Db->query($query);
    $Db->close();
    echo "<script language=\"javascript\">location.href=\"ship.php\";</script>";
}
elseif ($action=="canc") {
    $pid=$_GET['pid'];;
    $Db->connect();
    $Db->query("DELETE FROM `tt_navi` WHERE `uid`='".$_SESSION['nome']."' AND `pianeta`='".$pid."'");
    $Db->close();
    echo "<script language=\"javascript\">location.href=\"ship.php\";</script>";
}
else {// visualizza truppe
    $Db->connect();
    $tt=$Db->totable("SELECT * FROM `tt_navi` WHERE `uid`='".$_SESSION['nome']."' ORDER BY `pianeta`");
    $pianeti=$Db->totable("SELECT * FROM `us_pianeti` WHERE `id`='".$_SESSION['nome']."'");
    for ($i=0;$tt[$i];$i++)
    {
        for ($j=0;$pianeti[$j];$j++)
        {
            if ($tt[$i]['pianeta']==$pianeti[$j]['pid']) {$tt[$i]['nome_pianeta']=$pianeti[$j]['nome_pianeta'];}
        }
    }
    $razza=strtolower($_SESSION['razza']);
    show($tt,$navi,0,$razza);
    echo "
    <div align=\"center\" id=\"forms\" class=\"formular\" style='display: none; color: black;'>

<form name=\"edit\" action=\"ship.php?action=add\" method=\"post\">
  
    <table class=\"itable\" cellpadding=\"2\" cellspacing=\"1\" width=\"350\">

        <tr class=\"header1\">
            <td colspan=\"4\" class=\"header1\">Inserisci truppe<a href=\"javascript:showforms('forms', 'formtext', 1)\"><img align=\"right\" src=\"images/close.gif\" style=\"height:18px; width:18p;\" /></a></td>
        </tr>
        <tr>
            <td class=\"zeile2\"><label class=\"fieldLabel\">Create in:</label></td>
            <td colspan=\"2\" align=\"left\" class=\"zeile2\">";
    if ($pianeti) {
        echo"<select name=\"pianeta\"><option value=\"\">Seleziona</option>";
        $i=0;
        while($pianeti[$i])
        {
            echo "<option value=\"".$pianeti[$i]['pid']."\">".$pianeti[$i]['nome_pianeta']."</option>";
            $i++;
        }    
        echo " </select>";
     }
     else echo "Nessun pianeta";
     echo "
            </td>
            <td class=\"zeile2\" align=\"right\"><!--
                <nobr>
            	X: <input class=\"ibox f30\" type=\"text\" name=\"x\" id=\"x1\" value=\"0\" maxlength=\"4\" size=\"4\" />
                Y: <input class=\"ibox f30\" type=\"text\" name=\"y\" id=\"y1\" value=\"0\" maxlength=\"4\" size=\"4\" />
                </nobr>-->
            </td>
        </tr>
		<tbody id=\"unitAuto\">
        <tr>
          <td class=\"zeile2\">

          <label class=\"fieldLabel\">Truppe	:</label><p></p><a href=\"javascript:switchtroops();\" title=\"Inserisci Manualmente\">Manuale</a>
          </td>
		  <td colspan=\"3\" class=\"zeile2\">
		  	<textarea onchange=\"autoins(this)\" name=\"auto\" class=\"ibox\" style=\"width:250px; height:100px;\"></textarea>
		  	<a href=\"javascript:void(0)\" onmouseover=\"return overlib('Apri Imperion, e vai sulla base militare. Qui premi Ctrl+A e Ctrl+C. Dopo, torna in questo sito e premi Ctrl+V nel campo vuoto. Poi devi inserire le coordinate di dove hai creato le truppe e dove stanno ora. Le truppe verranno inserite automaticamente.');\" onmouseout=\"return nd();\">?</a>
		  </td>
		</tr>

		</tbody>
		<tbody id=\"unitManual\" style='display: none'>
		<tr>
          <td class=\"zeile2\" rowspan=\"12\">
          <label class=\"fieldLabel\">Navi	:</label><p></p><a href=\"javascript:switchtroops();\" title=\"Inserisci Automaticamente\">Auto</a>
          
          <!-- inserimento manuale -->";
                   
        
	for ($i=1;$i<=12;$i++)
    {
        echo "<td width=\"20\" class=\"zeile2\"><img src=\"images/".$razza."/".$i.".gif\" title=\"".$navi[$_SESSION['razza']][$i]."\"><td class=\"zeile2\" width=\"170\" align=\"left\">".$navi[$_SESSION['razza']][$i].":</td><td class=\"zeile2\" width=\"110\" align=\"right\"><input onkeyup=\"control(this)\" class=\"ibox f80\" type=\"text\" size=\"9\" name=\"t".$i."\" value=\"\"></td>\n";
        if ($i!="12") echo "</tr><tr>\n";
    }
    echo "
          </td>
        </tr>		
        </tbody>
		<tr>
        	<td colspan=\"4\" class=\"footer\"><input class=\"submit\" type=\"submit\" value=\"Salva\" /></td>
        </tr>

    </table>
  </form>
</div>";
 
    $Db->close();
}

foot();

?>