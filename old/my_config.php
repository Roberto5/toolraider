<?php
/******************/
/*    Login       */
/******************/
function Login()
{

    if (islog() == "yes") Errore("index.php","","","0");
    else {
        echo "
<form name=\"login\" method=\"post\" action=\"login.php?action=login_do\">
<table>
<tr>
<td>Username</td><td><input name=\"user\" type=\"text\" size=\"20\" /></td>
</tr>
<tr>
<td>Password</td><td><input name=\"pass\" type=\"password\" size=\"20\" /></td>
</tr>
<tr>
<td><input type=\"checkbox\" name=\"remember\" value=\"true\" /> ricordami su questo pc</td>
<td><input name=\"submit\" type=\"submit\" value=\"Login\" /></td>
</tr>
</table>
</form>";
    }
}

/**************************/
/*    Registrazione       */
/**************************/
function Registrazione()
{
?>
<form name="reg" method="post" action="add_user.php?action=add_user_do">
<table width="100%" border="0">
  <tr>
    <td style="text-align: left;" width="15%">Username:        </td>
    <td style="text-align: left;" width="20%" height=""><input onchange="jcontrol(this,'username')" name="user" type="text" size="20" />  <span id="user"></span></td>
  </tr>
  <tr>
    <td style="text-align: left;">Password:</td>
    <td style="text-align: left;"><input name="pass" type="password" size="20" /></td>
    
  </tr>
  <tr>
    <td style="text-align: left;" >Controllo Password: </td>
    <td style="text-align: left;" ><input name="pass2" type="password" size="20" /></td>
    
  </tr>
  <tr>
    <td style="text-align: left;" >E-Mail</td>
    <td style="text-align: left;"><input onchange="jcontrol(this,'mail')" name="mail" type="text" size="20" /><span id="mail"></span></td>
    
  </tr>
  <tr>
    <td style="text-align: left;" width="15%">nickname in gioco:        </td>
    <td style="text-align: left;" width="20%"><input onchange="jcontrol(this,'type')" name="name" type="text" size="20" /> <span id="nome"></span>  </td>
    
  </tr>
  <tr>
    <td style="text-align: left;" width="15%">tipo account       </td>
    <td style="text-align: left;" width="20%"><span id="type"><select name="type" onchange='sel_type(this.value)'><option value="3">Master</option><option value="1">Sharer</option></select></span></td>
    
  </tr>
  <tr>
    <td style="text-align: left;" width="15%">razza        </td>
    <td style="text-align: left;" width="20%"><select id="razza" name="razza"><option value="0"></option><option value="1">Titani</option><option value="2">Terrestri</option><option value="3">Xen</option></select>   </td>
    
  </tr>
  <tr>
    <td style="text-align: left;" width="15%">pianeta madre        </td>
    <td style="text-align: left;" width="20%"><input id="madre" type="text" name="madre" size="20" maxlength="20" />   </td>
    
  </tr>
  <tr>
    <td style="text-align: left;" width="15%">Url Server        </td>
    <td style="text-align: left;" width="20%"><input id="server" type="text" name="server" size="20" value="u1.imperion.it" maxlength="20" />   </td>
    
  </tr>
  <tr>
    <td style="text-align: left;" width="15%">
      <div align="left">
        <input type="button" onclick="if (control()) submit()" value="Registra" />
      </div></td>
    <td style="text-align: left;" width="20%"><div align="left"></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<?php
}

/******************************/
/*    Recupero password       */
/******************************/
function Recupero_dati()
{
?>
Tramite questo form verranno inviati i dati di login nella propria casella email.<br /><strong>Attenzione: </strong>Per questioni di sicurezza la password verrà sostituita e mandata direttamente alla casella email selezionata durante la registrazione.    
<br />
<form action="lost_password.php?action=lost_password_do" method="post" name="lostpass">
<table width="100%"  border="0">
  <tr>
    <td width="15%">Email:        </td>
    <td width="20%"><input name="email" type="text"  size="20" />
      </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="15%">
      <div align="left">
        <input name="submit" type="submit" value="Invia" />
      </div></td>
    <td width="20%"><div align="left"></div></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<?php
}

/****************************/
/*    Pagina protetta       */
/****************************/

/**
 * @return String type
 */

function Pagina_protetta($restringi = 0)
{
    $Db = new db();
    session_start();
    if (islog() == "no") {
        unset($_COOKIE['login']);
        setcookie("remember","",time() - 86400,"/");
        session_destroy();
        Errore("index.php","Attenzione: ","Solo gli utenti registrati possono accedere a questa pagina!");
        exit;
    }
    $Db->connect();
    $enum = getenum("dl_user","type");
    $query = "SELECT * FROM `dl_user` WHERE `username`='".$_SESSION['username']."'";
    $riga = $Db->toarray($query);
    if ($riga['type'] == $enum[0]) echo "<center><b><p>sei in attesa che il master ti confermi sharer di questo account.</p></b></center>";
    elseif ($riga['type'] === $enum[1]) echo "<center><b><p>la tua richiesta di sharing è stata rifiutata</p></b></center>";
    if (($restringi == 1) && ($riga['type'] !== $enum[2]) && ($riga['type'] !== $enum[3])) Errore("index.php","ERRORE","solo i master e gli sharer possono accedere a questa pagina");
    if (($restringi == 2) && ($riga['type'] !== $enum[2])) Errore("index.php","ERRORE","solo i master possono accedere a questa pagina");
    return ($riga['type'] == $enum[2]?"3":"4");
}

/**********************************/
/*    Pagina protetta admin       */
/**********************************/
function Pagina_protetta_admin()
{
    session_start();
    if ($ris = islog() != "admin") {
        unset($_COOKIE['login']);
        setcookie("remember","",time() - 86400,"/");
        session_destroy();
        ERRORE("login.php","Attenzione: ","Solo gli amministratori possono accedere a questa pagina! '".$ris."'");
        exit;
    }
}

/**********************************/
/*          benvenuto             */
/**********************************/

function welcome()
{
    echo "<br /><div align=\"left\">benvenuto <b>
    <a href=\"protetta.php\">".$_SESSION['username']."</a>
    </b> nick <b>".$_SESSION['nome']."</b>";
}

/**********************************/
/*             indice             */
/**********************************/

function indice($list,$sort,$start,$step,$num)
{
    $pages = intval(($num - 1) / $step) + 1;
    if ($pages > 1) {
        echo "<center>";
        if ($start > 0) {
            $start_back = $start - $step;
            echo "<a href=\"javascript:;\" onclick=\"richiesta(document.list.listname,'".$list."','".$start_back."','".$sort."','".$step."')\">&laquo; </a>";
        }
        for ($i = 0; $i < $pages and $i < 20; $i++) {
            $start_page = $i * $step;
            if ($start_page == $start) echo "<b><u>".($i + 1)."</b></u> ";
            else  echo "<a href=\"javascript:;\" onclick=\"richiesta(document.list.listname,'".$list."','".$start_page."','".$sort."','".$step."')\">".($i + 1)."</a> ";
        }

        if ($start + $step < $num) {
            $start_next = $start + $step;
            echo "<a href=\"javascript:;\" onclick=\"richiesta(document.list.listname,'".$list."','".$start_next."','".$sort."','".$step."')\">&raquo;</a>";
        }
        echo "</center>";
    }

}

/**********************************/
/*          visualizza            */
/**********************************/

function visualizza($list,$query,$sort,$start,$step,$table,$opt,$bool = 0)
{
    $Db = new db();
    echo "<form name=\"modulo\" action=\"clean.php?action=multi_clear\" method=\"post\">
	<center><input type=\"submit\" value=\"cancella\"><input name=\"open\" type=\"button\" value=\"apri link\" onClick=\"openl(false)\"><input type=\"button\" value=\"apri tutti\" onclick=\"openl(true)\"></center>
    <table border=\"0\" align=\"center\" width=\"400\" >
    	<tr>";
    if ($sort == "iddesc") {
        echo "<td><u><a href=\"javascript:;\" onclick=\"richiesta(document.list.listname,'".$list."','".$start."','idasc','".$step."')\">n<img src=images/freccia_giu.gif border=0></a></u></td>";
    } elseif ($sort == "idasc") {
        echo "<td><u><a href=\"javascript:;\" onclick=\"richiesta(document.list.listname,'".$list."','".$start."','iddesc','".$step."')\">n<img src=images/freccia_su.gif border=0></a></u></td>";
    } else {
        echo "<td><u><a href=\"javascript:;\" onclick=\"richiesta(document.list.listname,'".$list."','".$start."','idasc','".$step."')\">n</a></u></td>";
    }
    if ($bool) echo "<td>Pianeta</td>";
    echo "<td>nome</td>
		<td>link</td>
		<td>commento</td>
		<td>data</td>
		<td>bottino</td>
		<td><input type=\"checkbox\" name=\"all\" value=\"true\" onClick=\"seltt(this)\"></td></tr>";
    $i = 0;
    $Db->connect();
    $riga = $Db->totable($query);
    while ($riga[$i]) {
        $variabile = '$check'.$i;
        $l1 = linkmaker($riga[$i]['link'],"map");
        if ($opt) {
            $l2 = linkmaker($riga[$i]['link'],$opt);
        } else {
            $option = "raid".substr(strtolower($_SESSION['razza']),0,3).$riga[$i]['tipo_nave'].$riga[$i]['num_nave'];
            $l2 = linkmaker($riga[$i]['link'],$option);
        } //'raid3120\' stringa 0 \'312\'
        echo "<tr>
      		<td>".$riga[$i]['n']."</td>";
        if ($bool) echo "<td>".$riga[$i]['nome_lista']."</td>";
        echo "<td><a id=link".$i." href=".$l1." onclick='aggiornadata(".$riga[$i]['lid'].",\"".$table."\")' target=_blank>".$riga[$i]['nome_farm']."</a></td>
      		<td><a href=".$l2." onclick='aggiornadata(".$riga[$i]['lid'].",\"".$table."\")' target=_blank>".$riga[$i]['link']."</a></td>
      		<td>".$riga[$i]['comment']."</td>
      		<td id=\"data".$riga[$i]['lid']."\">".$riga[$i]['date']."</td>
      		<td>".$riga[$i]['bottino']."</td>
      		<td><nobr><a href=edit.php?action=edit&lid=".$riga[$i]['lid']."&table=".$table."><img src=admin/images/edit.gif border=0></a><a href=clean.php?action=canc&lid=".$riga[$i]['lid']."&table=".$table.
            " onClick=\"return confirm('Sei sicuro?');\"><img src=\"images/button_cancel.gif\"></a><input type=\"checkbox\" name=\"".$variabile."\" value=\"".$riga[$i]['lid']."\"></nobr>";
        $i++;
        echo "</td>
   		</tr>";
    }
    echo "</table><input type=\"hidden\" name=\"tot\" value=\"".$i."\"></form>";
    indice($list,$sort,$start,$step,$i);
}

function online($categoria = 0)
{
    $Db = new db();
    $Db->connect();
    $query = "SELECT `testo`,`offline` FROM dl_config";
    $riga = $Db->toarray($query);
    $Db->close();

    if ($riga['offline'] == "0") {
        echo "<img src=\"images/work.jpg\" border=0><br />SERVER IN MANUTENZIONE<br />".$riga['testo'];
        exit;
    }
    if (($riga['offline'] == $categoria) && ($riga['offline'] != null)) {
        echo "<img src=\"images/work.jpg\" border=0><br />QUERSTA PARTE DEL SITO E' IN MANUTENZIONE<br />".$riga['testo'];
        exit;
    }
}

/***********************************/
/*       visualizza truppe         */
/***********************************/

function show($tt,$navi,$bool,$razza)
{
    $navin[1][1] = "Osservatore";
    $navin[1][2] = "Scout";
    $navin[1][3] = "Delphi";
    $navin[1][4] = "Corsair";
    $navin[1][5] = "Terminator";
    $navin[1][6] = "Vettore";
    $navin[1][7] = "Protektor";
    $navin[1][8] = "Phoenix";
    $navin[1][9] = "Piccolo trasportatore";
    $navin[1][10] = "Grande trasportatore";
    $navin[1][11] = "Riciclatore";
    $navin[1][12] = "Colonizzatrice";
    $navin[2][1] = "Sonda";
    $navin[2][2] = "Caccia";
    $navin[2][3] = "Corazzata";
    $navin[2][4] = "Cacciatorpediniere";
    $navin[2][5] = "Incrociatore";
    $navin[2][6] = "Pulsar";
    $navin[2][7] = "Bombardiere";
    $navin[2][8] = "Cisterna";
    $navin[2][9] = "Piccolo trasportatore";
    $navin[2][10] = "Grande Riciclatore";
    $navin[2][11] = "Riciclatore";
    $navin[2][12] = "Colonizzatrice";
    $navin[3][1] = "Zek";
    $navin[3][2] = "Zekkon";
    $navin[3][3] = "Xnair";
    $navin[3][4] = "Mylon";
    $navin[3][5] = "Maxtron";
    $navin[3][6] = "Nave madre";
    $navin[3][7] = "Suikon";
    $navin[3][8] = "Psikon";
    $navin[3][9] = "Macid";
    $navin[3][10] = "Bombardiere";
    $navin[3][11] = "Octopon";
    $navin[3][12] = "Colonizzatrice";
    if (!$bool) {
        $script = "<script language=\"javascript\">\n
        pianeti=new Array();\n";
        $i = 0;
        while ($tt[$i]) {
            $script .= "pianeti[".$i."]=new Array();\n
            pianeti[".$i."]['nome']='".$tt[$i]['nome_pianeta']."';\n
            pianeti[".$i."]['pid']='".$tt[$i]['pianeta']."';\n";
            for ($j = 1; $j <= 12; $j++) $script .= "pianeti[".$i."][".$j."]=".$tt[$i]['n'.$j].";\n";
            $script .= "\n";
            $i++;
        }
        $script .= "</script>";
        echo $script;
    }
    echo "<center>
    <table class=\"itable\" cellpadding=\"2\" cellspacing=\"1\" >
    <tr>
	  <td class=\"header1\" colspan=\"16\">";
    if ($bool) echo "le truppe di ".$tt[0]['uid'];
    else  echo "le tue truppe";
    echo "</td>
	</tr>
	<tr>
	  <td class=\"header1a\">Pianeta</td>";
    for ($i = 1; $i <= 12; $i++) echo "<td class=\"header1a\"><img src=\"images/".$razza."/".$i.".gif\" title=\"".$navin[$raz][$i]."\" /></td>\n";

    echo "<td class=\"header1a\"></td></tr>";
    $i = 0;
    $tot = "";
    while ($tt[$i]) {
        echo "<tr><td class=\"header1a\">".$tt[$i]['nome_pianeta']."</td>";

        for ($j = 1; $j <= 12; $j++) {
            echo "<td class=\"header1a\" id=\"t".$i."\">".$tt[$i]['n'.$j]."</td>";
            $tot[$j] += $tt[$i]['n'.$j];
        }
        echo "<td class=\"header1a\">";
        if (!$bool) {
            echo "<a href=\"javascript:;\" onclick=\"editf(".$i.",".$tt[$i]['pianeta'].");showforms('forms', 'formtext', 1)\" ><img src=\"admin/images/edit.gif\" title=\"modifica\" /></a>
            <a href=\"ship.php?action=canc&pid=".$tt[$i]['pianeta']."\" ><img src=\"images/del.gif\" title=\"rimuovi\" /></a>";
        }
        echo "</td></tr>";
        $i++;
    }
    echo "<tr><td class=\"header1a\">totale</td>\n";
    for ($j = 1; $j <= 12; $j++) {
        if (!$tot[$j]) $tot[$j] = "0";
        echo "<td class=\"header1a\">".$tot[$j]."</td>";
    }
    echo "<td class=\"header1a\">";
    if (!$bool) echo "<a href=\"javascript:;\" onclick=\"showforms('forms', 'formtext', 1)\"><img src=\"images/add.png\" title=\"aggiungi\" /></a>";
    echo "</td></tr></table></center>";
}

/***********************************/
/*     visualizza truppe ally      */
/***********************************/

function showally($ally)
{
    $navin[1][1] = "Osservatore";
    $navin[1][2] = "Scout";
    $navin[1][3] = "Delphi";
    $navin[1][4] = "Corsair";
    $navin[1][5] = "Terminator";
    $navin[1][6] = "Vettore";
    $navin[1][7] = "Protektor";
    $navin[1][8] = "Phoenix";
    $navin[1][9] = "Piccolo trasportatore";
    $navin[1][10] = "Grande trasportatore";
    $navin[1][11] = "Riciclatore";
    $navin[1][12] = "Colonizzatrice";
    $navin[2][1] = "Sonda";
    $navin[2][2] = "Caccia";
    $navin[2][3] = "Corazzata";
    $navin[2][4] = "Cacciatorpediniere";
    $navin[2][5] = "Incrociatore";
    $navin[2][6] = "Pulsar";
    $navin[2][7] = "Bombardiere";
    $navin[2][8] = "Cisterna";
    $navin[2][9] = "Piccolo trasportatore";
    $navin[2][10] = "Grande Riciclatore";
    $navin[2][11] = "Riciclatore";
    $navin[2][12] = "Colonizzatrice";
    $navin[3][1] = "Zek";
    $navin[3][2] = "Zekkon";
    $navin[3][3] = "Xnair";
    $navin[3][4] = "Mylon";
    $navin[3][5] = "Maxtron";
    $navin[3][6] = "Nave madre";
    $navin[3][7] = "Suikon";
    $navin[3][8] = "Psikon";
    $navin[3][9] = "Macid";
    $navin[3][10] = "Bombardiere";
    $navin[3][11] = "Octopon";
    $navin[3][12] = "Colonizzatrice";
    $energy = array(0,array(0,4,2,18,16,14,120,20,65,4,24,7,40),array(0,4,1,19,9,100,20,60,15,9,93,6,30),array(0,0,1,5,6,5,144,6,5,18,50,9,35));
    $Db = new db();
    $Db->connect();
    $tt = $Db->totable("SELECT `us_player`.`nome` , `us_player`.`razza` , `tt_navi`.* FROM `us_player` , `tt_navi` WHERE `nome`=`uid` AND `alleanza`='".$ally."' ORDER BY `razza` , `nome` , `pianeta` ASC");
    $n = "";
    $p = "";
    $navi = "";
    $j = -1;
    $dat = "";
    for ($i = 0; $tt[$i]; $i++) {
        if (($n != $tt[$i]['nome']) || ($p != $tt[$i]['pianeta'])) {
            if ($n == $tt[$i]['nome']) {
                if ($p != $tt[$i]['pianeta']) {

                    if ($navi[$j]['data'] < $tt[$i]['data']) $navi[$j]['data'] = $tt[$i]['data'];
                    for ($k = 1; $k <= 12; $k++) {
                        $navi[$j]['n'.$k] += $tt[$i]['n'.$k];

                    }
                }
            } else {
                $j++;
                $navi[$j]['nome'] = $tt[$i]['nome'];
                for ($k = 1; $k <= 12; $k++) {
                    $navi[$j]['n'.$k] += $tt[$i]['n'.$k];
                }
                $navi[$j]['data'] = $tt[$i]['data'];
                $navi[$j]['razza'] = $tt[$i]['razza'];
            }
        }
        $n = $tt[$i]['nome'];
        $p = $tt[$i]['pianeta'];
    }
    $tt = $navi;
    echo "<center>
        <table class=\"itable\" cellpadding=\"2\" cellspacing=\"1\" >
        <tr>
	       <td class=\"header1\" colspan=\"16\">truppe dell'ally</td>
	</tr>";
    for ($r = 1; $r < 4; $r++) {
        switch ($r) {
            case "1":
                $razza = "Titani";
                $style = "blue";
                break;
            case "2":
                $razza = "Terrestri";
                $style = "#FF8040";
                break;
            case "3":
                $razza = "Xen";
                $style = "green";
                break;
        }
        echo "  
	   <tr>
	  <td class=\"header1a\">Membro</td>
      <td class=\"header1a\">Aggiornate al</td>";
        $raz = intval($r);
        for ($i = 1; $i <= 12; $i++) //
                 echo "<td class=\"header1a\"><img src=\"images/".strtolower($razza)."/".$i.".gif\" title=\"".$navin[$r][$i]."\" /></td>\n";
        echo "<td class=\"header1a\"><img src=\"images/Energy.png\" title=\"Energia\"></td></tr>";
        $i = 0;
        $tot = "";
        $etot = 0;
        while ($tt[$i]) {
            if ($tt[$i]['razza'] == $razza) { //
                echo "<tr><td class=\"header1a\"><a style=\"color: ".$style.";\" href=\"ship.php?action=show&uid=".$tt[$i]['nome']."\" onmouseover=\"return overlib('<img src=\'diagramm.php?uid=".$tt[$i]['nome'].
                    "&ally=".$ally."\' alt=\'".$tt[$i]['nome']."\' border=\'0\' style=\'margin:0px;\'>');\" onmouseout=\"return nd();\">".$tt[$i]['nome']."</a></td>
                    <td class=\"header1a\">".$tt[$i]['data']."</td>";
                $energia = 0;
                for ($j = 1; $j <= 12; $j++) {
                    echo "<td class=\"header1a\" id=\"t".$i."\">".$tt[$i]['n'.$j]."</td>";
                    $energia += $tt[$i]['n'.$j] * $energy[$r][$j];
                    $tot[$j] += $tt[$i]['n'.$j];
                }
                echo "<td class=\"header1a\">".$energia."</td></tr>";
                $etot += $energia;
            }
            $i++;
        }
        echo "<tr><td class=\"header1a\">totale</td>\n
        <td class=\"header1a\"></td>";
        for ($j = 1; $j <= 12; $j++) {
            if (!$tot[$j]) $tot[$j] = "0";
            echo "<td class=\"header1a\">".$tot[$j]."</td>";
        }
        echo "<td class=\"header1a\">".$etot."</td></tr>";
    }
    echo "</table></center>";
    $Db->close();
}

/**
 * 
 * @return String
 * visualizza avvisi da parte dell'admin
 */

function avvisi()
{
    $Db = new db();
    $Db->connect();
    $avvisi = $Db->totable("SELECT * FROM `avvisi` WHERE `id`!=ALL (
        SELECT `id` FROM `read` WHERE `user`='".$_SESSION['username']."'
    )");
    if ($Db->n) { //visualizzo avvisi
        $n=$Db->n;
        echo '<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
        <script language="javascript">
var page=1;
var pagetot='.$n.';
function nextpage(){
    document.getElementById(\'light\'+page).style.display="none";
    id=$("#id"+page).text();
    $.ajax({
    url: "query.php",
    type : "POST" ,
    data: "action=avvisi&id="+id ,
    success : function (data,stato) {
        //alert(data);
    },
    error : function (richiesta,stato,errori) {
        alert("E\' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
    }
    });
    page++;
    if (page<=pagetot) document.getElementById(\'light\'+page).style.display="block";
    else document.getElementById(\'fade\').style.display="none";
}
</script>';
        for ($i = 0; $i<$n; $i++) {
            if ($i==($n-1)) $button="chiudi"; else $button="next";
            echo '<div id="light'.($i+1).'" class="contenent">
            <div style="height: 90%;">'.$avvisi[$i]['testo'].'</div>            
            <div style="text-align: right;">
            <a href="#" onclick="nextpage()">'.$button.'</a>
            <span id="id'.($i+1).'" style="display:none;">'.$avvisi[$i]['id'].'</span>
            </div>
            </div>
            <div id="fade" class="opaco"></div>';
        }
        echo '<script language="javascript">
        document.getElementById(\'light1\').style.display="block";
        document.getElementById(\'fade\').style.display="block";</script>';
    }
}
?>
