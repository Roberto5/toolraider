<?php
session_start();
include ("functions.php");
include ("my_config.php");
include ("inc/config.php");
online(5);
$Db = new db();
pagina_protetta(1);
$_POST = inputctrl($_POST);

$action = $_GET['action'];

if ($action == "multi_clear") { // multicancellazione link
    $tot = (int)$_POST['tot'];
    $Db->connect();
    for ($i = 0; $i < $tot; $i++) {
        $variabile = '$check'.$i;
        $lid = "";
        $lid = (int)$_POST[$variabile];
        $query = "SELECT * FROM `us_list` WHERE `lid`='".$lid."'";
        $riga = $Db->toarray($query);
        if ($lid != "") {
            if (($riga['uid'] == $_SESSION['nome']) || (islog() == "admin")) { //permessi
                $query = "DELETE FROM `us_list` WHERE `lid` = '".$lid."' LIMIT 1";
                $Db->query($query);
            }
        }
    }
    $Db->close();
    riordina();
    Ok("list.php","cancellazione multipla avvenuta non successo","");
    exit;
} elseif ($action == "canc") {
    $lid = (int)$_GET['lid'];
    if ($lid != "") {
        $Db->connect();
        $query = "SELECT * FROM `us_list` WHERE `lid`='".$lid."'";
        $riga = $Db->toarray($query);
        if (($riga['uid'] == $_SESSION['nome']) || (islog() == "admin")) $Db->query("DELETE FROM `us_list` WHERE `lid` = '".$lid."' LIMIT 1");
        $Db->close();
        riordina();
        ok("list.php","cancellazione singola avvenuta con successo","");
    }
} elseif ($action = "clean") {
    $Db->connect();
    echo "player senza utenti : ".$Db->conta("SELECT * FROM `us_player`  WHERE `nome`!=ALL (SELECT `nome` FROM `dl_user`)")." trovati ".$risultato."<br />";
    $Db->query("DELETE FROM `us_player`  WHERE `nome`!=ALL (SELECT `nome` FROM `dl_user`)");
    echo "ricerche senza utenti : ".$Db->conta("SELECT * FROM `us_ricerche`  WHERE `id`!=ALL (SELECT `nome` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `us_ricerche`  WHERE `id`!=ALL (SELECT `nome` FROM `dl_user`)");
    echo "messaggi letti senza utenti : ".$Db->conta("SELECT * FROM `us_read`  WHERE `user`!=ALL (SELECT `username` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `us_read`  WHERE `user`!=ALL (SELECT `username` FROM `dl_user`)");
    echo "privilegi senza utenti : ".$Db->conta("SELECT * FROM `us_privilegi`  WHERE `nome`!=ALL (SELECT `nome` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `us_privilegi`  WHERE `nome`!=ALL (SELECT `nome` FROM `dl_user`)");
    echo "pianeti senza utenti : ".$Db->conta("SELECT * FROM `us_pianeti`  WHERE `id`!=ALL (SELECT `nome` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `us_pianeti`  WHERE `id`!=ALL (SELECT `nome` FROM `dl_user`)");
    echo "messaggi senza utenti : ".$Db->conta("SELECT * FROM `us_mess`  WHERE `destinatario`!=ALL (SELECT `nome` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `us_mess`  WHERE `destinatario`!=ALL (SELECT `nome` FROM `dl_user`)");
    echo "liste senza utenti : ".$Db->conta("SELECT * FROM `us_list`  WHERE `uid`!=ALL (SELECT `nome` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `us_list`  WHERE `uid`!=ALL (SELECT `nome` FROM `dl_user`)");
    echo "index senza utenti : ".$Db->conta("SELECT * FROM `us_index`  WHERE `uid`!=ALL (SELECT `nome` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `us_index`  WHERE `uid`!=ALL (SELECT `nome` FROM `dl_user`)");
    echo "richieste senza utenti : ".$Db->conta("SELECT * FROM `tt_richieste`  WHERE `player`!=ALL (SELECT `nome` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `tt_richieste`  WHERE `player`!=ALL (SELECT `nome` FROM `dl_user`)");
    echo "navi senza utenti : ".$Db->conta("SELECT * FROM `tt_navi`  WHERE `uid`!=ALL (SELECT `nome` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `tt_navi`  WHERE `uid`!=ALL (SELECT `nome` FROM `dl_user`)");
    echo "ally senza player : ".$Db->conta("SELECT * FROM `tt_ally`  WHERE `id`!=ALL (SELECT `alleanza` FROM `us_player`)")." trovati<br />";
    $Db->query("DELETE FROM `tt_ally`  WHERE `id`!=ALL (SELECT `alleanza` FROM `us_player`)");
    echo "letture avvisi senza utenti : ".$Db->conta("SELECT * FROM `read`  WHERE `user`!=ALL (SELECT `username` FROM `dl_user`)")." trovati<br />";
    $Db->query("DELETE FROM `read`  WHERE `user`!=ALL (SELECT `username` FROM `dl_user`)");
    echo "cronologia comete senza utenti : ".$Db->conta("SELECT * FROM `ct_cronologia`  WHERE `uid`!=ALL (SELECT `nome` FROM `dl_user`) OR `data`='0000-00-00'")." trovati<br />";
    $Db->query("DELETE FROM `ct_cronologia`  WHERE `uid`!=ALL (SELECT `nome` FROM `dl_user`) OR `data`='0000-00-00'");
    $Db->close();
} 
else  Errore("index.php","questa pagina non è visualizzabile");
?>