<?php
$title = ": liste comete";
session_start();
include ("my_config.php");
include ("inc/foot.php");
include ("inc/config.php");
include ("functions.php");
include ("form_comete.php");
online(2);
Pagina_protetta();
clean();
$Db = new db();
echo '
<script language="Javascript"><!--
		var b=false;
		function seltt(oggetto)
		{
			if (oggetto.checked) {
				with (document.modulo) {
					for (var i=0; i < elements.length; i++) {
						if (elements[i].type == \'checkbox\') elements[i].checked = true;
					}
				}
			}
			else { with (document.modulo) 
				{
				for (var i=0; i < elements.length; i++) {
					if (elements[i].type == \'checkbox\' ) elements[i].checked = false;
				}
				}
			}

		}	
		//-->
</script> ';
menu();
$ally = isally();
$_GET = inputctrl($_GET);
$_POST = inputctrl($_POST);
echo "<p><center>
<a href=\"add.php\">aggiungi</a> | <a href=\"listk.php?list=mycomet&start=0&sort=idasc\">visualizza le mie comete</a> | <a href=\"listk.php?list=comet&start=0&sort=idasc\">visualizza tutte le comete</a> | <a href=\"listk.php?list=filter\">filtri comete</a>";
$priv = privilegi($ally,"g","",1);
if ($priv['bool']) echo " | <a href=\"listk.php?list=cronologia\">cronologia comete</a>";
echo "</center></p>";

$list = $_GET['list'];
$start = $_GET['start'];
$sort = $_GET['sort'];

$step = 30;
if ($list == "mycomet") { // stampa lista delle proprie comete*************************************
    $Db->connect();
    // ordina per id****************************
    if ($sort == iddesc) {
        $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '".$_SESSION['nome']."' AND `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` DESC LIMIT $start,$step";
    } else {
        if ($sort == idasc) {
            $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '".$_SESSION['nome']."' AND `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` ASC LIMIT $start,$step";
        }
    }
    // ordina per nick****************************
    if ($sort == nicdesc) {
        $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '".$_SESSION['nome']."' AND `ally`='".$ally."' ORDER BY `ct_comet`.`user_comet` DESC LIMIT $start,$step";
    } else {
        if ($sort == nicasc) {
            $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '".$_SESSION['nome']."' AND `ally`='".$ally."' ORDER BY `ct_comet`.`user_comet` ASC LIMIT $start,$step";
        }
    }
    // ordina per data****************************
    if ($sort == datadesc) {
        $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '".$_SESSION['nome']."' AND `ally`='".$ally."' ORDER BY `ct_comet`.`date_comet` DESC LIMIT $start,$step";
    } else {
        if ($sort == dataasc) {
            $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '".$_SESSION['nome']."' AND `ally`='".$ally."' ORDER BY `ct_comet`.`date_comet` ASC LIMIT $start,$step";
        }
    }
    if ($sort == scaddesc) {
        $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '".$_SESSION['nome']."' AND `ally`='".$ally."' ORDER BY `ct_comet`.`date_scadenza` DESC LIMIT $start,$step";
    } else {
        if ($sort == scadasc) {
            $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '".$_SESSION['nome']."' AND `ally`='".$ally."' ORDER BY `ct_comet`.`date_scadenza` ASC LIMIT $start,$step";
        }
    }
    //visualizzazione
    visualizzac("listk.php?list=".$list,$sort,$start,$step,$query);
} elseif ($list == "comet") {
    //stampa lista totale delle comete****************************************
    $Db->connect(); // ordina per id****************************
    if ($sort == iddesc) {
        $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` DESC LIMIT $start,$step";
    } else {
        if ($sort == idasc) {
            $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` ASC LIMIT $start,$step";
        }
    }
    if ($sort == nicdesc) { // ordina per nick****************************
        $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`user_comet` DESC LIMIT $start,$step";
    } else {
        if ($sort == nicasc) {
            $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`user_comet` ASC LIMIT $start,$step";
        }
    }
    if ($sort == datadesc) { // ordina per data****************************
        $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`date_comet` DESC LIMIT $start,$step";
    } else {
        if ($sort == dataasc) {
            $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`date_comet` ASC LIMIT $start,$step";
        }
    }
    if ($sort == scaddesc) { // ordina per data****************************
        $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`date_scadenza` DESC LIMIT $start,$step";
    } else {
        if ($sort == scadasc) {
            $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`date_scadenza` ASC LIMIT $start,$step";
        }
    }
    //visualizzazione
    visualizzac("listk.php?list=".$list,$sort,$start,$step,$query);
} elseif ($list == "filter") // filtri liste*******************************************
{
    $filter = $_GET['filter'];
    switch ($filter) {
        case "":
            $s1 = "selected=selected";
            break;
        case "nick":
            $s2 = "selected=selected";
            break;
        case "date":
            $s3 = "selected=selected";
            break;
        case "scad":
            $s4 = "selected=selected";
            break;
    }
    echo "
	<center>Seleziona filtro<br />
	<form action=listk.php method=get>
	<input type=hidden name=list value=filter>
	<select name=filter onchange='submit()'>
		<option value= ".$s1.">      </option>
		<option value=nick ".$s2.">utente</option>
		<option value=date ".$s3.">data  </option>
		<option value=scad ".$s4.">scadenza</option>
	</select>";
    if ($filter == "") echo "</form> ";

    if ($filter == "nick") { //filtro user****************************************
        $user = $_GET['user'];
        $Db->connect();
        $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`user_comet` ASC";
        if ($filter == "") echo "<form action=listk.php method=get>";
        echo "<select name=user onchange='submit()'><option value= selected=selected>      </option>";
        $nomep = "";
        $riga = $Db->toarray($query);
        for ($i = 0; $riga[$i]; $i++) {
            $nome1 = $riga[$i]['user_comet'];
            if ($nome1 != $nomep) {
                if ($nome1 != $user) {
                    echo "<option value=".$nome1.">".$nome1."</option>";
                } else {
                    echo "<option value=".$nome1." selected=selected>".$nome1."</option>";
                }
            }
            $nomep = $nome1;
        }
        echo "</select><input type=hidden name=sort value=idasc><input type=hidden name=start value=0>
	</form></center>";
        if ($user != "") {
            $sort = $_GET['sort'];
            $start = $_GET['start'];
            //user selezionato****************************************
            // ordina per id****************************
            if ($sort == iddesc) {
                $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '$user' AND `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` DESC LIMIT $start,$step";
            } else {
                if ($sort == idasc) {
                    $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '$user' AND `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` ASC LIMIT $start,$step";
                }
            }
            // ordina per data****************************
            if ($sort == datadesc) {
                $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '$user' AND `ally`='".$ally."' ORDER BY `ct_comet`.`date_comet` DESC LIMIT $start,$step";
            } else {
                if ($sort == dataasc) {
                    $query = "SELECT * FROM `ct_comet` WHERE user_comet =  '$user' AND `ally`='".$ally."' ORDER BY `ct_comet`.`date_comet` ASC LIMIT $start,$step";
                }
            }
            //visualizza*******************************************
            visualizzac("listk.php?list=filter&filter=nick&user=".$user,$sort,$start,$step,$query);
        }
        $Db->close();
    }
    if ($filter == "date") { //filtro data****************************************
        $dates = $_GET['date'];
        $start = $_GET['start'];
        $Db->connect();
        $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`date_comet` ASC";
        if ($filter == "") echo "<form action=listk.php method=get>";
        echo "<select name=date onchange='submit()'><option value= selected=selected>      </option>";
        $datep = "";
        $riga = $Db->toarray($query);
        for ($i = 0; $riga[$i]; $i++) {
            $date = $riga[$i]['date_comet'];
            if ($date != $datep) {
                if ($date != $dates) echo "<option value=".$date.">".$date."</option>";
                else  echo "<option value=".$date." selected=selected>".$date."</option>";
            }
            $datep = $date;
        }
        echo "</select><input type=hidden name=sort value=idasc><input type=hidden name=start value=0></form></center>";
        if ($dates != "") {
            //data selezionata ********************************
            // ordina per id****************************
            if ($sort == iddesc) {
                $query = "SELECT * FROM `ct_comet` WHERE date_comet =  '$dates' AND `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` DESC LIMIT $start,$step";
            } else {
                if ($sort == idasc) {
                    $query = "SELECT * FROM `ct_comet` WHERE date_comet =  '$dates' AND `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` ASC LIMIT $start,$step";
                }
            }
            // ordina per nick****************************
            if ($sort == nicdesc) {
                $query = "SELECT * FROM `ct_comet` WHERE date_comet =  '$dates' AND `ally`='".$ally."' ORDER BY `ct_comet`.`user_comet` DESC LIMIT $start,$step";
            } else {
                if ($sort == nicasc) {
                    $query = "SELECT * FROM `ct_comet` WHERE date_comet =  '$dates' AND `ally`='".$ally."' ORDER BY `ct_comet`.`user_comet` ASC LIMIT $start,$step";
                }
            }
            //visualizzazione
            visualizzac("listk.php?list=filter&filter=date&date=".$dates,$sort,$start,$step,$query);
        }
        $Db->close();
    }
    if ($filter == "scad") { //filtro scadenza****************************************
        $dates = $_GET['scad'];
        $start = $_GET['start'];
        $Db->connect();
        $query = "SELECT * FROM `ct_comet` WHERE `ally`='".$ally."' ORDER BY `ct_comet`.`date_scadenza` ASC";
        if ($filter == "") echo "<form action=listk.php method=get>";
        echo "<select name=scad onchange='submit()'><option value= selected=selected>      </option>";
        $datep = "";
        $riga = $Db->toarray($query);
        for ($i = 0; $riga[$i]; $i++) {
            $date = $riga[$i]['date_scadenza'];
            if ($riga[$i]['user_comet'] == $_SESSION['nome']) {
                if ($date != $datep) {
                    if ($date != $dates) echo "<option value=".$date.">".$date."</option>";
                    else  echo "<option value=".$date." selected=selected>".$date."</option>";
                }
                $datep = $date;
            }
        }
        echo "</select><input type=hidden name=sort value=idasc><input type=hidden name=start value=0></form></center>";
        if ($dates != "") {
            //data selezionata ********************************
            // ordina per id****************************
            if ($sort == iddesc) {
                $query = "SELECT * FROM `ct_comet` WHERE date_scadenza =  '$dates' AND `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` DESC LIMIT $start,$step";
            } else {
                if ($sort == idasc) {
                    $query = "SELECT * FROM `ct_comet` WHERE date_scadenza =  '$dates' AND `ally`='".$ally."' ORDER BY `ct_comet`.`id_comet` ASC LIMIT $start,$step";
                }
            }
            // ordina per nick****************************
            if ($sort == nicdesc) {
                $query = "SELECT * FROM `ct_comet` WHERE date_scadenza =  '$dates' AND `ally`='".$ally."' ORDER BY `ct_comet`.`user_comet` DESC LIMIT $start,$step";
            } else {
                if ($sort == nicasc) {
                    $query = "SELECT * FROM `ct_comet` WHERE date_scadenza =  '$dates' AND `ally`='".$ally."' ORDER BY `ct_comet`.`user_comet` ASC LIMIT $start,$step";
                }
            }
            //visualizzazione
            visualizzac("listk.php?list=filter&filter=scad&scad=".$dates,$sort,$start,$step,$query);
        }
        $Db->close();
    }
} elseif ($list == "cronologia") {
    privilegi($ally,"g");
    $Db->connect();
    $stats = $Db->totable("SELECT `ct_cronologia`.*,`us_player`.`nome`  FROM `ct_cronologia`,`us_player` WHERE `nome`=`uid` AND `alleanza`='".$ally."' ORDER BY `nome`,`data` ASC");
    $prec="";$data="";$dati="";$dataprec="";
    for($i=0,$j=1;$stats[$i];$i++)
    {
        if ($stats[$i]['nome']!=$prec) 
        {
            $g=substr($data,6,2);
            $m=substr($data,4,2);
            $y=substr($data,0,4);
            //echo "'".$data."'";
            $data=$g."/".$m."/".$y;
            if (substr($g,0,1)=="0") $g=(int)substr($g,1,1);
            $g++;
            if ($data==date("d/m/Y")) $data="oggi";
            if ($g==date("d")) $data="ieri";
            if ($i) echo "<a href=\"stat.php?nome=".$prec.$dati."\" onmouseover=\"return overlib('<img src=\'stat.php?nome=".$prec.$dati."\' alt=\'statistiche\' />')\" onmouseout=\"return nd();\" >".$prec."</a> ultima attività ".$data;
            echo "<br />";
            $data="";
            $dati="";
            $j=1;
            $prec=$stats[$i]['nome'];
        }
        if ($dataprec!=$stats[$i]['data']) {$dati.="&d".$j."=".$stats[$i]['numero']."&r".$j."=".$stats[$i]['data'];$dataprec=$stats[$i]['data'];$j++;}
        $g=substr($stats[$i]['data'],8,2);//yyyy-mm-dd
        $m=substr($stats[$i]['data'],5,2);
        $y=substr($stats[$i]['data'],0,4);
        if (!$data) $data=$y.$m.$g;
        else {
            if ($data<$y.$m.$g) $data=$y.$m.$g;
        }
    }
    $g=substr($data,6,2);
    $m=substr($data,4,2);
    $y=substr($data,0,4);
    $data=$g."/".$m."/".$y;
    if (substr($g,0,1)=="0") $g=(int)substr($g,1,1);  
    $g++;
    if ($data==date("d/m/Y")) $data="oggi";
    if ($g==date("d")) $data="ieri";
    echo "<a href=\"stat.php?nome=".$prec.$dati."\" onmouseover=\"return overlib('<img src=\'stat.php?nome=".$prec.$dati."\' alt=\'statistiche\' />')\" onmouseout=\"return nd();\" >".$prec."</a> ultima attività ".$data;
    $Db->close();
}

foot();
?>