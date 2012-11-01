<?php

/**
 * @author roberto
 * @copyright 2010
 * 
 * incompleto
 */

session_start();
include("functions.php");
include("my_config.php");
include("inc/foot.php");

online(7);

echo "<script language=\"javascript\">
var razza=".$_SESSION['razza'].";
</script>";

Pagina_protetta(1);

?>
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script language="javascript" src="scripts/coord.js"></script>
<script language="javascript" src="inc/navi.js"></script>
<script language="javascript" src="scripts/jquery.dynDateTime.js"></script>
<script language="javascript" src="scripts/calendar-en.js"></script>
<link rel="stylesheet" type="text/css" href="scripts/calendar-win2k-cold-1.css" />
<style>
.tit,.ter,.xen{
    display: none;
}
</style>
<?php
menu();

$ally=isally();
$priv=privilegi($ally,"g","",1);

echo "<form name=\"coord\"><table width=\"100%\" border=\"1\"><tr>
<td width=\"20%\"><b>coordina:</b><br />
<a id=\"accu\" href=\"javascript:;\" onclick=\"done=true;document.coord.coord.value='account';this.style.color='blue';document.getElementById('ally').style.color='white';dispayopt()\">tuo account</a><br />";
if ($priv['bool']) echo "<a id=\"ally\" href=\"javascript:;\" onclick=\"done=true;document.coord.coord.value='ally';this.style.color='blue';document.getElementById('accu').style.color='white';dispayopt()\" >alleanza</a><br />";
echo "<input name=\"coord\" type=\"hidden\" value=\"\" /></td>
<td>";

?>

<table border="1" id="opt" style="display: none;">
<tr>
<td>tipo missione </td>
<td>target</td>
<td>tipo nave</td>
<td><span class="tit">per i titani hai selezionato</span></td>
<td><span class="ter">per i terestri</span></td>
<td><span class="xen">per i xen</span></td>
</tr>
<tr>
<td><select name="type" onchange="changetype(this.value)"><option value="false">Attacco</option><option value="true">Difesa</option></select></td>
<td><select class="coords" name="g"><option value="1">1</option><option value="1">2</option><option value="1">3</option></select> |
<input onchange="document.coord.target.selectedIndex=0" class="coords" name="x" size="4" maxlength="4" value="0" /> |
<input onchange="document.coord.target.selectedIndex=0" class="coords" name="y" size="4" maxlength="4" value="0" />
<select id="target" name="target" disabled="disabled" onchange="addcoord(this)"><option>Seleziona pianeta</option></select>
</td>
<td><select name="nave" onchange="selnave()"><option> </option></select></td>
<td><span class="tit" id="tit"></span></td>
<td><span class="ter" id="ter"></span></td>
<td><span class="xen" id="xen"></span></td>
</tr>
<tr>
<td>data di arrivo</td>
<td>numero di risultati da visualizzare</td>
<td><input type="checkbox" name="optserb" checked="true" onclick="" /> scarta le navi che non hanno abbastanza serbatorio</td>

</tr>
<tr>
<td><script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery("input.data").dynDateTime({
						showsTime: true,
                        ifFormat: "%s",
                        daFormat: "%d/%m/%Y %H:%M:%S", 
                        singleClick: false,
                        displayArea: ".siblings('input.display')" ,
                        button: "" 
						}); 
					});
				</script>

<input class="display" readonly="true" />
<input type="hidden" name="arrivo" class='data display' />


</td>
<td><select name="step">
<?php
	select(15,1,"","",15);
?>
</select></td>
<td><input type="checkbox" name="optplanet" checked="true" onclick="" /> togli i pianeti che non vi arrivano con nessuna nave</td>
</tr>

<tr><td colspan="6"><input type="button" value="cerca" onclick="showp()" /></td></tr>
<tr><td colspan="6"><div id="visualizza"></div></td></tr>
</table>

<?php

echo"</td>
</tr>
</table></form>";

foot();
?>
