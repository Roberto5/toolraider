<?php
session_start();
include ("../functions.php");
include ("../my_config.php");
Pagina_protetta_admin();
if ($_POST['action']) {
    // upload file
    $upload_dir = "upload_".$_SESSION['adm_user'];
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir)or die (Errore("javascript:history.back()","ERRORE","impossibile creare la cartella '".$upload_dir."'!"));
    }
    $file_name=$_FILES["upfile"]["name"];
    if (trim($_FILES["upfile"]["name"]) != "") {
        if (@is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
            @move_uploaded_file($_FILES["upfile"]["tmp_name"], "$upload_dir/$file_name") or
                Errore("index.php", "ERRORE", "Impossibile spostare il file $file_name in $upload_dir, contatta l'amministratore del sito.");
        } else {
            die("Problemi nell'upload del file " . $_FILES["upfile"]["name"]);
        }
        Ok("javascript:history.back()","Uploaded","file caricato con successo");
    }
}

?>