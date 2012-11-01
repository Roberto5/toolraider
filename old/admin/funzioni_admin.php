<?php

function newreport() {
    $sql = mysql_query("SELECT * FROM `report`");
    $num = mysql_num_rows($sql);
    if($num > 0) {
        return true;
    }else {
        return false;
    }
}

function newreportcon() {
    $sql = mysql_query("SELECT * FROM `report`");
    $num = mysql_num_rows($sql);
    return $num;
}

function newbugcon() {
    $sqlbug = mysql_query("SELECT * FROM `report` WHERE `tipo`='1'");
    $numbug = mysql_num_rows($sqlbug);
    return $numbug;
}

function newsuggcon() {
    $sqlsugg = mysql_query("SELECT * FROM `report` WHERE `tipo`='0'");
    $numsugg = mysql_num_rows($sqlsugg);
    return $numsugg;
}
function getsugg() {
    $Db=new db();
    $Db->connect();
    $sugg=$Db->totable("SELECT * FROM `report` WHERE `tipo`='0'");
    $sugg['n']=$Db->n;
    return $sugg;
}
function getbug() {
    $Db=new db();
    $Db->connect();
    $bug=$Db->totable("SELECT * FROM `report` WHERE `tipo`='1'");
    $bug['n']=$Db->n;
    return $bug;
}

?>