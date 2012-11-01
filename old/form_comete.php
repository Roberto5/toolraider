<?php
/**********************************/
/*      inserimento comete        */
/**********************************/

function insertK($string,$number)
{
    $b = 0;
    if (($string == "") && ($number == "")) {
        $number = "001";
        $string = "aa";
        $b = 1;
        echo "<form name=\"insert\" method=\"post\" action=\"add.php?action=insert_do\">";
    }
    echo "inserisci cometa:<br /> K-
    <input name=\"name\" onchange='query()' size=\"10\" maxlength=\"10\" value=".$string.">
 
    <span id=\"vis\"></span> ";
    if ($b == 1) {
        echo "<br /><br /><input name=\"submit\" type=\"submit\" value=\"insert\"></form>";
    }
}

/**********************************/
/*        codifica comete         */
/**********************************/

function encode($s,$n)
{
    return $s.$n;
}

/**********************************/
/*             indice             */
/**********************************/

function indicec($ref,$start,$step,$num) // implementare ajax

{
    echo "<center>";
    if ($start > 0) {
        $start_back = $start - $step;
        echo "<a href=".$ref."&start=".$start_back.">&laquo; </a>";
    }
    $pages = intval(($num - 1) / $step) + 1;
    //***********************
    for ($i = 0; $i < $pages and $i < 20; $i++) {
        $start_page = $i * $step;
        if ($start_page == $start) echo "<b><u>".($i + 1)."</b></u> ";
        else  echo "<a href=".$ref."&start=".$start_page.">".($i + 1)."</a> ";
    }

    if ($start + $step < $num) {
        $start_next = $start + $step;
        echo "<a href=".$ref."&start=".$start_next.">&raquo;</a>";
    }
    echo "</center>";
}

/**********************************/
/*          visualizza            */
/**********************************/

function visualizzac($ref,$sort,$start,$step,$query) // implementare ajax
{
    $Db=new db();
    $Db->connect();
    echo "<table border=\"0\" align=\"center\" width=\"400\" ><tr>";
    if ($sort == "iddesc") {
        echo "<td><u><a href=".$ref."&start=0&sort=idasc>nome cometa<img src=images/freccia_giu.gif border=0></a></u></td>";
    } elseif ($sort == "idasc") {
        echo "<td><u><a href=".$ref."&start=0&sort=iddesc>nome cometa<img src=images/freccia_su.gif border=0></a></u></td>";
    } else {
        echo "<td><u><a href=".$ref."&start=0&sort=idasc>nome cometa</a></u></td>";
    }
    if ($sort == "nicdesc") {
        echo "<td><u><a href=".$ref."&start=0&sort=nicasc>utente<img src=images/freccia_giu.gif border=0></a></u></td>";
    } elseif ($sort == "nicasc") {
        echo "<td><u><a href=".$ref."&start=0&sort=nicdesc>utente<img src=images/freccia_su.gif border=0></a></u></td>";
    } else {
        echo "<td><u><a href=".$ref."&start=0&sort=nicasc>utente</a></u></td>";
    }
    if ($sort == "datadesc") {
        echo "<td><u><a href=".$ref."&start=0&sort=dataasc>data<img src=images/freccia_giu.gif border=0></a></u></td>";
    } elseif ($sort == "dataasc") {
        echo "<td><u><a href=".$ref."&start=0&sort=datadesc>data<img src=images/freccia_su.gif border=0></a></u></td>";
    } else {
        echo "<td><u><a href=".$ref."&start=0&sort=dataasc>data</a></u></td>";
    }
    if ($sort == "scaddesc") {
        echo "<td><u><a href=".$ref."&start=0&sort=scadasc>scadenza<img src=images/freccia_giu.gif border=0></a></u></td>";
    } elseif ($sort == "scadasc") {
        echo "<td><u><a href=".$ref."&start=0&sort=scaddesc>scadenza<img src=images/freccia_su.gif border=0></a></u></td>";
    } else {
        echo "<td><u><a href=".$ref."&start=0&sort=scadasc>scadenza</a></u></td>";
    }

    echo "<form name=modulo action=clean.php?action=multi_clear method=post>
		<td><input type=checkbox name=all value=true onClick=\"seltt(this)\"></td></tr>";
    //modificare
    $riga=$Db->totable($query);
    $i = 0;
    while ($riga[$i]) {
        $bool = privilegi($ally,"g","",1);
        if (($bool[$bool]) || ($_SESSION['nome'] == $riga[$i]['user_comet'])) $variabile = '$check'.$i;

        echo "<tr>
      		<td>P".$riga[$i]['id_comet']."</td>
      		<td>".$riga[$i]['user_comet']."</td>
      		<td>".$riga[$i]['inserita']."</td>
      		<td>";
        if (($bool[$bool]) || ($_SESSION['nome'] == $riga[$i]['user_comet'])) {
            echo $riga[$i]['scadenza']."</td><td><a href=editk.php?action=edit&cid=".$riga[$i]['id_comet'].
                "><img src=admin/images/edit.gif border=0></a><a href=cleank.php?action=canc&cid=".$riga[$i]['id_comet']." onClick=\"return confirm('Sei sicuro?');\"><img src=images/button_cancel.gif border=0></a><input type=checkbox name=".
                $variabile." value=".$riga[$i]['id_comet'].">";
            
        } else  echo "</td><td>";
        echo "</td>
   		</tr>";
        $i++;
    }
    echo "<input type=hidden name=tot value=".$i.">
   	<center>
   	<input type=submit value=cancella>
   	<input type=reset value=reset>
   	</form>
   	</center></table>";
    indicec($ref."&sort=".$sort,$start,$step,$i);
}

function clean() //testare

{
    $Db = new db();
    $Db->connect();
    $query = "SELECT * FROM `db_clean` WHERE `id`='1' AND `date`<'".date("Y-m-d H:i:s",strtotime("-1 hours"))."'";
    $num = $Db->conta($query);
    if ($num) { // controllo ogni ora se ci sono comete da cancellare
        $Db->query("DELETE FROM `ct_comet` WHERE `scadenza`<CURRENT_TIMESTAMP");
        $Db->query("UPDATE `db_clean` SET `date`=CURRENT_TIMESTAMP WHERE `id`='1'");
    }
    $Db->close();
}
?>