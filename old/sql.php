<?php
include ("inc/config.php");
class db
{
    private $db_host = HOST;
    private $db_user = USER;
    private $db_pass = PASS;
    private $db_name = DATABASE;
    private $active = false;
    private $link = null;
    public $n = 0;
    /*public function __construct()
    {
    $this->link = mysql_connect($this->db_host,$this->db_user,$this->db_pass);
    if ($this->link == false) die("<img src=\"images/button_cancel.gif\"> Errore nella connessione. Verificare i parametri del database nel file inc/config.php");
    mysql_query("USE ".$this->db_name,$this->link) or die("<img src=\"images/button_cancel.gif\"> Errore nella selezione del database. Verificare i parametri nel file inc/config.php");
    $this->active = true;
    }*/
    public function connect()
    {
        if (!$this->active) {
            $this->link = mysql_connect($this->db_host,$this->db_user,$this->db_pass);
            if ($this->link == false) die("<img src=\"images/button_cancel.gif\"> Errore nella connessione. Verificare i parametri del database nel file inc/config.php");
            mysql_query("USE ".$this->db_name,$this->link) or die("<img src=\"images/button_cancel.gif\"> Errore nella selezione del database. Verificare i parametri nel file inc/config.php");
            $this->active = true;
        }
    }
    public function close()
    {
        if ($this->active) @mysql_close($this->link);
        $this->link = null;
        $this->active = false;
    }
    public function query($query,$stampa = 0)
    {
        if (!$this->active) Warning("Attenzione","connessione DB non attiva! `".$query."`");
        $risultato = mysql_query($query) or die(reportbug($query,mysql_error()));
        if ($stampa) echo "\" ".$query." \"";
        return $risultato;
    }
    public function toarray($query,$stampa = 0)
    {
        $risultato = $this->query($query,$stampa);
        $riga = mysql_fetch_assoc($risultato);
        if ($stampa) print_r($riga);
        $this->n = $riga != ""?1:0;
        if ($stampa) echo "n='".$this->n."'<br />";
        return $riga;
    }
    public function conta($query,$stampa = 0)
    {
        $risultato = $this->query($query);
        $num = mysql_num_rows($risultato);
        if ($stampa) echo $num." righe per \" ".$query."\"";
        $this->n = $num;
        return $num;
    }
    function totable($query,$stampa = 0)
    {
        $risultato = $this->query($query,$stampa);
        $vet = "";
        $i = 0;
        while ($vet[] = mysql_fetch_assoc($risultato)) $i++;
        if ($stampa) print_r($vet);
        $this->n = $i;
        if ($stampa) echo "n='".$this->n."'<br />";
        return $vet;
    }
    function tocolarray($query,$stampa = 0)
    {
        $risultato = $this->query($query,$stampa);
        $vet = "";
        $i = 0;
        while ($riga = mysql_fetch_array($risultato)) {
            $vet[] = $riga[0];
            $i++;
        }
        $this->n = $i;
        if ($stampa) {
            print_r($vet);
            echo "n='".$this->n."'<br />";
        }
        return $vet;
    }
    function tovariable($query,$stampa = 0)
    {
        $risultato = $this->query($query,$stampa);
        $riga = mysql_fetch_array($risultato);
        $riga = $riga[0];
        $this->n = $riga != ""?1:0;
        if ($stampa) echo "'".$riga."' n='".$this->n."'<br />";
        return $riga;
    }
}

function reportbug($query,$error)
{
    $Db=new db();
    $log = islog();
    $query=addslashes($query);$error=addslashes($error);
    if ($log == "admin") {
        echo '<div class="opaco" style="display: block;"> </div>
<div class="contenent" style="display: block;">errore nella query `'.$query.'` il sistema dice : '.$error.'<br /><input type="button" value="ok" onclick="location.href=\'index.php\'" /></div>';
    } else {
        $Db->connect();
        $ind = $Db->toarray("SHOW TABLE STATUS FROM ".DATABASE." LIKE 'report'");
        $ind = $ind['Auto_increment'];
        $mittente=$log == "no"?"ospite":$_SESSION['username'];
        mysql_query("INSERT INTO `report` SET `oggetto`='reportautomatico' , `testo`='errore nella query `".$query."` il sistema dice : ".$error."' , `tipo`='1' , `mittente`='".$mittente."'")or die (mysql_error());
        echo '<div class="opaco" style="display: block;"> </div>
            <div class="contenent" style="display: block;">
            <form action="index.php?action=bugreport" method="post">
            <input type="'.($log == "no"?"text":"hidden").'" name="mail" value="'.($log == "no"?"":$_SESSION['mail']).'" />
            <input type="hidden" name="nick" value="'.($log == "no"?"ospite":$_SESSION['username']).'" />
            <input type="hidden" name="oggetto" value="'.$ind.'" />
            si è verificao un errore, se non hai già segnalato questo bug, segnalalo al admin scrivendo una breve descrizione qui:<br />
            <textarea name="testo" cols="30" rows="10">mi è comparso questo errore mentre ...
che browser uso ...
altro...</textarea>
            <input type="button" value="ho già segnalato" onclick="location.href=\'index.php\'" /> <input type="submit" value="segnala" />
            </form>
            </div>';
    }
    exit;
}
?>