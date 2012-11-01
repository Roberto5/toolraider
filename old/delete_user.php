<?php
session_start();
include ("functions.php");
include ("my_config.php");
include ("inc/foot.php");
online(6);
Pagina_protetta();

echo "</head><body>";



$action= $_GET['action'];
if($action == "si")
{      
 	cancuser($_SESSION['username']);
    $user=$_SESSION['username'];
	unset ($_COOKIE['login']);
    setcookie ("remember","",time()-86400,"/");
	session_destroy();				
	Ok("login.php", "Inserimento dati corretto", "L'account '".$user."' è stato cancellato correttamente");
}
elseif ($action=="no") 
    echo "<script language=\"javascript\">
    location.href=\"protetta.php\"
    </script>";
else 
    echo "<form action=\"delete_user.php\">
    <table id=\"table_center\">
    <tr>
    <td colspan=\"3\">vuoi avvero cancellare l'account '".$_SESSION['username']."'?</td>
    </tr>
    <tr>
    <td style=\"text-align: right;\"><input name=\"action\" value=\"si\" type=\"submit\" /></td>
    <td></td>
    <td style=\"text-align: left;\"><input name=\"action\" value=\"no\" type=\"submit\" /></td>
    </tr>
    </table>
    </form></body>";
?>