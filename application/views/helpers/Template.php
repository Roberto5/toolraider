<?php
/**
 *
 * @author pagliaccio
 * @version 
 */
require_once 'Zend/View/Interface.php';
/**
 * alerts helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_Template extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    public $baseUrl;
    public $ship_name=array(
    	'TERRAN'=>array(
			'Fighter',
    		'Destroyer',
   			'Cruiser',
   			'Battleship',
   			'Bomber',
   			'Frigate',
   			'Recycler',
   			'Large Recycler',
   			'Terran Freighter',
   			'Probe',
   			'Pulsar',
   			'Colony Ship'
    	),
    	'TITAN'=>array(
    		'Scout',
    		'Delphi',
    		'Corsair',
    		'Terminator',
    		'Carrier',
    		'Phoenix',
    		'Recycler',
    		'Freighter',
    		'Large Freighter',
    		'Observer',
    		'Protector',
    		'Colony Ship'
    	),
    	'XEN'=>array(
    		'Xnair',
    		'Mylon',
    		'Maxtron',
    		'Mothership',
    		'Suikon',
    		'Xen Bomber',
    		'Octopon',
    		'Zek',
    		'Zekkon',
    		'Psikon',
    		'Macid',
    		'Xen Colony Ship'
    	),
    	'ALIEN'=>array(
    		'Pholidoteuthis',
    		'Chiroteuthis',
    		'Sepioteuthis',
    		'Architeuthis'
    	)
    	);
    
    function __construct ($baseUrl=false)
    {
        $this->view = new Zend_View();
        $config=Zend_Registry::get("config");
        
        if ($config->local || $baseUrl) $this->baseUrl =$baseUrl ? $baseUrl : "/toolraider";
        else {
        	$baseurl = new Zend_View_Helper_BaseUrl();
        	$this->baseUrl = $baseurl->baseUrl();
        }
    }
    /**
     * 
     * @return Zend_View_Helper_template
     */
    public function template ($template=null,$option=array())
    { 
    	if ($template) {
    		return $this->$template($option);
    	}
        else {
        	if (! $this->baseUrl) {
            	$baseurl = new Zend_View_Helper_BaseUrl();
            	$this->baseUrl = $baseurl->baseUrl();
        	}
        	return $this;
        }
    }
    /**
     * avvisi
     * @param String $text
     * @param String $link
     * @param int $type 0-1
     * @param int $sec
     * @return String
     */
    public function Alerts ($text, $link = null, $type = 1, $sec = 0)
    {
    	if (is_array($text)) {
    		$link=$text['link'];
    		$type=$text['type'] ? $text['type'] : 1 ;
    		$sec=$text['sec'];
    		$text=$text['text'];
    	}
        $html = "";
        $t = Zend_Registry::get('translate');
        $sec = (int) $sec;
        switch ($type) {
            default:
            case 1:
                $class = array('ui-state-highlight', 'ui-icon-info');
                break;
            case 2:
                $class = array('ui-state-error', 'ui-icon-alert');
                break;
        }
        if ($link) $text=' <a href="' . $link . '">'.$text.'</a>';
        $html .= '<div style="padding: 0pt 0.7em;width: 350px;margin: 0 auto;" class="' . $class[0] .
         ' ui-corner-all"> 
				<p>
 				<span style="float: left; clear:both; margin-right: 0.3em;" class="ui-icon ' .
         $class[1] . '"></span>
 				<p>' . $text .'</p></p></div>';
        if ($sec)
            $html .= '<script type="text/javascript">
        <!--
        var ms=' . $sec . '000;
        if(ms<1000){ location.href = \'' . $link .
             '\';}else{window.setTimeout("location.href = \'' . $link . '\';", ms );}
        //-->
        </script>';
        return $html;
    }
    
    /**
     * visualizza uno spoiler
     * @param String $content
     * @return string 
     */
    function spoiler ($content, $status = false,$label="")
    {
    	if (is_array($content)) {
    		$status=$content['status'];
    		$label=$content['label'];
    		$content=$content['content'];
    	}
    	$html='<summary '.($label? 'id="'.$label.'"':'').'><b>'.$label.'</b><details "'.($status? 'open="true"':'').'">'.$content.'</details></summary>';
        return $html;
    }
    /**
     * genera link
     * @param int $idlink
     * @param String $option
     * @return String
     * @todo rifare
     */
    
    function linkmaker($l,$opt)
    {
    	//esempio mappa galattica:
    	//http://u1.imperion.it/map/index/system/967184/#/center/967184/system/967184/planet/96718404
    	//$opt=map
    	//esempio invio navi
    	//http://u1.imperion.it/fleetBase/mission/1/planetId/951191/m/302/ships/,,2
    	//$opt=raidter120  raid->opzione ter->razza 1->tipo di nave 20 ->numero navi
    	$t = array('0','2','3','6');
    	$ter = array('0','2','5','6','10','12');
    	$x = array('0','2','5','10','11');
    	$universe = $_SESSION['universo'];
    	$o = substr($opt,0,4);
    	if ($o == "raid") {
    		$o = substr($opt,4,3);
    		if ($o == "ter") $vet = $ter;
    		elseif ($o == "xen") $vet = $x;
    		elseif ($o == "tit") $vet = $t;
    		else  Errore("list.php","errore","impossibile creare il link. stringa opt \'".$opt."\' stringa 0 \'".$o."\'  contattare il webmaster",".");
    		$t = substr($opt,7,1);
    		$n = substr($opt,8);
    		$init = $universe."/fleetBase/mission/1/planetId/";
    		$f1 = "/m/302/ships/";
    		$f2 = ",";
    		$linkt = $init.$l.$f1;
    		for ($i = 0; $i < $vet[$t]; $i++) $linkt .= $f2;
    		$linkt .= $n;
    		return $linkt;
    	} elseif ($o == "map") {
    		$init = "/map/index/system/";
    		$sid = substr($l,0,6);
    		$mid = "/#/center/";
    		$mid2 = "/planet/";
    		$mid3 = "/system/";
    		$linkt = $universe.$init.$sid.$mid.$sid.$mid3.$sid.$mid2.$l;
    		return $linkt;
    	} else  Errore("list.php","errore","opt errato. stringa opt \'".$opt."\' stringa 0 \'".$o."\'  contattare il webmaster");
    }
    
    /**
     * @todo rifare
     * @param Array $config
     * @return string
     */
    function ship($config)
    {
    	$table=new Zend_View();
    	$table->ship=$config['ship'];
    	$table->own=$config['own'];
    	$table->list=$config['list'];
    	$table->ship_name=$this->ship_name;
    	$table->addFilter('Tmpeng')->addFilterPath(APPLICATION_PATH.'/plugin');
    	$table->setScriptPath(APPLICATION_PATH.'/views/scripts/template/');
    	return $table->render('shiptable.phtml');
    }
    /**
     * @todo rifare
     * @param unknown_type $ally
     */
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
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
