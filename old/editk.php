<?php
$title = ": modifica comete";
session_start();
include ("my_config.php");
include ("inc/foot.php");
include ("functions.php");
$Db = new db();
online(2);
Pagina_protetta(1);
?>
<script language="Javascript"><!--
function query()
{
	$("#vis").html("<img src=\"images/loading.gif\">");
    $.ajax({
        url: "query.php" ,
        data: "action=cometa&id="+document.insert.string.value+document.insert.number.value ,
        success : function (data,stato) {
            $("#vis").html(data);
        },
        error : function (richiesta,stato,errori) {
            alert("E' evvenuto un errore. Il stato della chiamata: "+stato+" errore "+errori);
        }
    });
}
//--></script> 
<?php
menu();

$_POST = inputctrl($_POST);
$_GET = inputctrl($_GET);

$Db->connect();
$action = $_GET['action'];
$cid = $_GET['cid'];
if (($action == "edit") && ($cid != "")) {
    // form per editare ****************************************
    $Db->connect();
    $query = "SELECT * FROM ct_comet WHERE id_comet='".$cid."'";
    $riga = $Db->toarray($query);
    if (($riga['user_comet'] != $_SESSION['nome']) && (islog() != "admin")) Errore("index.php","permesso negato","bisogna essere admin o proprietari della cometa");
    echo "<form name=insert action=edit.php?action=do_edit&cid=".$cid." method=post>";
    insertK($riga['string'],$riga['number'],$o1,$o1,$o2);
    $type = "hidden";
    echo '<br /><input type="submit"  value="modifica">
	<input type="hidden" name="user1" value="'.$riga['user_comet'].'">
	<input type="hidden" name="date_scadenza1" value="'.$riga['date_scadenza'].'">
	<input type="hidden" name="ora_scadenza1" value="'.$riga['ora_scadenza'].'">
	</form>';
    $Db->close();
} elseif (($action == "do_edit") && ($cid != "")) {
    $time_r = $_POST['time_r'];
    $string = $_POST['string'];
    $number = $_POST['number'];
    $user = $_POST['user'];
    $user1 = $_POST['user1'];
    $user2 = $user;
    $scadenza1 = $_POST['scadenza1'];
    $scadenza2 = date("Y-m-d H:i:s",strtotime("+".$time_r." hours"));
    if (trim($string) == "" or trim($number) == "" or trim($user) == "") {
        Errore("list.php","Errore","I campi devono essere riempiti");
    } else {
        $string = addslashes(stripslashes($string));
        $number = addslashes(stripslashes($number));
        $string = str_replace("<","&lt;",$string);
        $number = str_replace(">","&gt;",$number);
        $idk = encode($string,$number);
        $exlen = array('string' => 2,'number' => 3,);

        foreach ($exlen as $key => $val) {
            if (strlen($$key) < $val) Errore("pagina.php","Errore","inserimento cometa sbagliato");
        }

        $Db->connect();
        if ($cid != $idk) {
            //controllo id già esistente
            $query = "SELECT * from `ct_comet` WHERE `id_comet` = '".$idk."'";
            $riga = $Db->toarray($query);
            if ($Db->n) {
                $avviso = "La cometa inserita è già utilizzata da ".$riga['user_comet'];
                Errore("pagina.php","Errore",$avviso);
            }
            $query = "UPDATE `ct_comet` SET `id_comet`='".$idk."', `scadenza` = '".$scadenza2."' , `user_comet` = '".$user."' WHERE `id_comet` = '".$cid."'";
            $Db->query($query);
        } else {
            $query = "UPDATE `ct_comet` SET `scadenza` = '".$scadenza2."' , `user_comet` = '".$user."' WHERE `id_comet` = '".$cid."'";
            $Db->query($query);
        }
        $Db->close();
        ok("list.php","modifiche apportate con successo");
    }
}

foot();
?>