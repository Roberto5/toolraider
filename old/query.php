<?php
/**
 * @author roberto
 * @copyright 2010
 */

session_start();
include ("functions.php");
include ("my_config.php");
online();
$Db = new db();
$ally = isally();
$_POST = inputctrl($_POST,array('find','start','list','table','l','ind'));

$action = $_POST['action'];
if ($action == "reg") {
    $Db->connect();
    $cerca = $_POST['cerca'];
    $valore = $_POST['valore'];
    if ($cerca == "user") {
        $num = $Db->conta("SELECT * FROM `dl_user` WHERE `username`='".$valore."'");
        if ($num) echo "<img src=\"images/del.gif\">";
        else  echo "<img src=\"images/ok.gif\">";
    } elseif ($cerca == "mail") {
        $num = $Db->conta("SELECT * FROM `dl_user` WHERE `mail`='".$valore."'");
        if ($num) echo "<img src=\"images/del.gif\">";
        else  echo "<img src=\"images/ok.gif\">";
    }
    if ($cerca == "nome") {
        $num = $Db->conta("SELECT * FROM `dl_user` WHERE `nome`='".$valore."' AND `type`=3");
        if ($num) echo "<select name=\"type\"><option value=\"1\">Sharer</option></select>";
        else  echo "<select name=\"type\" onchange='sel_type(this.value)'><option value=\"3\">Master</option><option value=\"1\">Sharer</option></select>";
    }
} elseif ($action == "list") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $oggi = $_POST['oggi'];
    $step = $_POST['step'];
    $table = $_POST['list'];
    $n_list = $_POST['n_list'];
    $start = $_POST['start'];
    $sort = $_POST['sort'];
    $raid = $_POST['raid'];
    $opt = $_POST['opt'];
    $num = "00";
    if (!$step) $step = 1;
    if ($oggi) $oggi = "AND `date`NOT LIKE '".date("Y-m-d")."%'";
    else  $oggi = "";
    if (!in_array($table,array(1,2))) {
        echo "ERRORE! id lista non corretto (1,2) id='".$table."'";
        exit;
    }
    if (!$sort) $sort = "idasc";
    // ordina per lid****************************
    if ($sort == "iddesc") $sortq = "`nome_lista` , `n` DESC";
    elseif ($sort == "idasc") $sortq = "`nome_lista` , `n` ASC";
    else  $sortq = "`nome_lista` , `n` ASC";
    if ($n_list != "") {

        if (!$raid) $query="SELECT * FROM `us_list` WHERE `uid`='".$_SESSION['nome']."' 
        AND `nome_lista`='".$n_list."' AND `type`=".$table." ".$oggi." ORDER BY ".$sortq."  LIMIT ".$start.",".$step;
        else  $query="SELECT * FROM `us_list` WHERE `uid`='".$_SESSION['nome']."' AND `nome_lista`='".$n_list."' AND `n`>='".$start."'  AND `type`=".$table." ORDER BY `n` ASC LIMIT ".$step;

        //visualizzazione
        visualizza($list,$query,$sort,$start,$step,$table,$opt,$oggi);
        $num = $Db->conta("SELECT * FROM `us_list` WHERE nome_lista = '".$n_list."' AND uid='".$_SESSION['nome']."' AND `type`=".$table." ".$oggi);
    }
    if (($oggi) && ($n_list == "")) {//da fare
        $query="SELECT * FROM `us_list` WHERE `uid`='".$_SESSION['nome']."' AND `type`=".$table." ".$oggi." ORDER BY ".$sortq." LIMIT ".$start.",".$step;
        //visualizzazione
        $num = $Db->conta("SELECT * FROM `us_list` WHERE  uid='".$_SESSION['nome']."' AND `type`=".$table." ".$oggi);
        visualizza($list,$query,$sort,$start,$step,$table,$opt,1);
    }
    echo "@".$num;
} 
elseif ($action == "agg") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $id = (int)$_POST['id'];
    $date = date("Y-m-d H:i:s");
    echo $date;
    $Db->query("UPDATE `us_list` SET `date`='".$date."' WHERE `lid`='".$id."' ");
} 
elseif ($action == "n_list") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $table = $_POST['table'];
    if (!in_array($table,array(1,2))) $table = 2;
    $query = "SELECT `nome_lista` FROM `us_list` WHERE `uid` = '".$_SESSION['nome']."' AND `type`=".$table." GROUP BY `nome_lista` ORDER BY `nome_lista` ASC";
    $riga = $Db->totable($query);
    echo "<option></option>";
    for ($i = 0; $riga[$i]; $i++) 
        echo "<option value=\"".$riga[$i]['nome_lista']."\">".$riga[$i]['nome_lista']."</option>";
} 
elseif ($action == "index") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $n_list = $_POST['n_list'];
    $table = $_POST['table'];
    if (!in_array($table,array(1,2))) $table = 2;
    $query = "SELECT * FROM `us_index` WHERE `uid`='".$_SESSION['nome']."' AND `nome`='".$n_list."' AND `lista`=".$table." ";
    $num = $Db->conta($query);
    // controllo presenza indice
    if (!$num) { // creazione indice se non presente
        $query = "INSERT INTO `us_index` SET `uid`='".$_SESSION['nome']."' , `nome`='".$n_list."' , `n`='1' , `lista`=".$table." ";
        $Db->query($query);
    }
    $ind = $Db->toarray("SELECT `n` FROM `us_index` WHERE `uid`='".$_SESSION['nome']."' AND `nome`='".$n_list."' AND `lista`=".$table);
    echo $ind['n'];
} elseif ($action == "step") {
    //conto le righe della lista
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $n_list = $_POST['n_list'];
    $table = $_POST['table'];
    if (!in_array($table,array(1,2))) $table = 2;
    $num = $Db->conta("SELECT * FROM `us_list` WHERE `nome_lista` = '".$n_list."' AND `type`=".$table." AND uid='".$_SESSION['nome']."'");
    if ($num>40) $num=40; 
    select($num);
} 
elseif ($action == "setind") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $n_list = $_POST['n_list'];
    $table = $_POST['l'];
    $ind = $_POST['ind'];
    if (!in_array($table,array(1,2))) $table = 2;
    $num = $Db->conta("SELECT * FROM `us_list` WHERE `nome_lista` = '".$n_list."' AND `uid`='".$_SESSION['nome']."' AND `type`=".$table);
    if ($ind < 1) $ind = $num;
    if ($ind > $num) $ind = 1;
    $Db->query("UPDATE `us_index` SET `n`='".$ind."' WHERE `uid`='".$_SESSION['nome']."' AND `nome`='".$n_list."' AND `lista`=".$table);
    echo $ind;
} 
elseif ($action == "up") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $list = $_POST['list'];
    $n_list = $_POST['n_list'];
    $Db->query("UPDATE `dl_user` SET `list`='".$list."' , `n_list`='".$n_list."' WHERE `nome`='".$_SESSION['username']."'");
} 
elseif ($action == "down") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $riga = $Db->toarray("SELECT * FROM `dl_user` WHERE `nome`='".$_SESSION['username']."'");
    echo $riga['list'].",".$riga['n_list'];
} 
elseif ($action == "planet") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $find = $_POST['find'];
    if ($find) $query = "SELECT * FROM `us_player`,`us_pianeti` WHERE `us_pianeti`.`id`=`us_player`.`nome` AND `alleanza`='".$ally."'";
    else  $query = "SELECT * FROM `us_pianeti` WHERE `id`='".$_SESSION['nome']."'";
    $pianeti = $Db->totable($query);
    echo "<option></option>";
    for ($i = 0; $pianeti[$i]; $i++) 
        echo "<option value=\"".$pianeti[$i]['nome_pianeta']."\" >".$pianeti[$i]['nome_pianeta']."</option>";
    for ($i = 0; $pianeti[$i]; $i++) {
        echo ",".$pianeti[$i]['x']." ".$pianeti[$i]['y']." ".$pianeti[$i]['g']." ".$pianeti[$i]['id']." ".$pianeti[$i]['nome_pianeta']." ".$pianeti[$i]['razza'];
    }
} 
elseif ($action == "ricerca") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $fields = trim($_POST['fields']);
    $campi="";
    if ($fields!="*") {
        $fields=explode(",",$fields);
        sort($fields);
        $campi="AND `ricerca` IN ('".implode("','",$fields)."')";
    }
    $uid = trim($_POST['nome']);
    $ricerche = $Db->totable("SELECT * FROM `us_ricerche` WHERE `id`='".$uid."' ".$campi." ORDER BY `ricerca` ASC");
    if ($fields!="*") {
        for ($i=0,$j=0;$fields[$i];$i++)
        {
            if ($ricerche[$j]['ricerca']==$fields[$i]) {
                echo $ricerche[$j]['ricerca'].".".$ricerche[$j]['liv'].",";
                $j++;
            }
            else echo $fields[$i].".0,";
        }
    }
    else {
        for ($j=0;$ricerche[$j];$j++)
            echo $ricerche[$j]['ricerca'].".".$ricerche[$j]['liv'].",";
    }
} elseif ($action == "cometa") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $id = $_POST['id'];
    $num = $Db->conta("SELECT * FROM `ct_comet` WHERE  `id_comet`='".$id."' AND `ally`='".$ally."'");
    
        if ($num) {
            $us = $Db->toarray("SELECT * FROM `ct_comet` WHERE  `id_comet`='".$id."' AND `ally`='".$ally."'");
            $us = $us['user_comet'];
            if ($us != $_SESSION['nome']) echo "<img src=\"images/del.gif\" title=\"".$us."\" />";
            else  echo "<img src=\"images/important.png\" title=\"la cometa &egrave; tua\" />";
        } else  echo "<img src=\"images/ok.gif\">";
    
}
elseif ($action=="avvisi") {
    if (islog() == "no") {
        echo "sessione scaduta, premere <a href=\"login.php\">qui</a> per ricollegarsi.";
        exit;
    }
    $Db->connect();
    $id = $_POST['id'];
    $Db->query("INSERT INTO `read` (`id`,`user`) VALUES ('".$id."','".$_SESSION['username']."')");
}
$Db->close();
?>