<?php

/**
 * @author roberto
 * @copyright 2010
 */

include("functions.php");
$table="us_list";

Db_connect();
    $query="SELECT * FROM `".$table."` ORDER BY `nome_lista`";
    $risultato=mysql_query($query) or die(mysql_error());
    $num=mysql_num_rows($risultato);
    $nome_lista="";
    $unome="";
    while($riga=mysql_fetch_array($risultato))
    {
        if ($unome!=$riga['nome_lista']) {$unome=$riga['nome_lista'];$nome_lista[]=$riga['nome_lista'];}
    }
    $contenuto="";
    $i=0;
    $query_aggiungi="INSERT INTO `".$table."` (`lid`, `n`, `uid`, `nome_lista`, `nome_farm`, `link`, `comment`, `date`, `bottino`, `tipo_nave`, `num_nave`) VALUES ";
    while($nome_lista[$i])
    {
        echo $nome_lista[$i]."<br>";
        $query="SELECT * FROM `".$table."` WHERE `nome_lista`='".$nome_lista[$i]."' ORDER BY `n`";
        echo $query."<br>";
        $risultato=mysql_query($query) or die(mysql_error());
        $j=1;
        while($riga=mysql_fetch_array($risultato))
        {
            echo $riga['n']."<br>";
            $riga['n']=$j;
            $query_aggiungi.="(".$riga['lid'].", ".$j.", '".$riga['uid']."', '".$riga['nome_lista']."', '".$riga['nome_farm']."', '".$riga['link']."', '".$riga['comment']."', '".$riga['date']."', '".$riga['bottino']."', ".$riga['tipo_nave'].", ".$riga['num_nave']."), ";
            $j++;
        }
        $i++;
    }
    $query_aggiungi=substr($query_aggiungi,0,-2);
    echo $query_aggiungi;
    
    $query="TRUNCATE TABLE `".$table."`";
    $risultato=mysql_query($query) or die(mysql_error());
    $risultato=mysql_query($query_aggiungi) or die(mysql_error());

?>