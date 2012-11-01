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
    /**
     * 
     * @var Model_civilta
     */
    private $civ;
    function __construct ()
    {
        $this->view = new Zend_View();
        $config=Zend_Registry::get("config");
        
        if ($config->local) $this->baseUrl ="/gifeditor";
        else {
        	$baseurl = new Zend_View_Helper_BaseUrl();
        	$this->baseUrl = $baseurl->baseUrl();
        }
    }
    /**
     * 
     * @return Zend_View_Helper_template
     */
    public function template ()
    {
        if (! $this->baseUrl) {
            $baseurl = new Zend_View_Helper_BaseUrl();
            $this->baseUrl = $baseurl->baseUrl();
        }
        return $this;
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
        $html .= '<div style="padding: 0pt 0.7em;" class="' . $class[0] .
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
        /*$this->ids ++;
        $html = '<a href="javascript:;" onclick="if ($(\'#spoiler' . $this->ids .
         '\').css(\'display\')==\'block\') $(\'#spoiler' . $this->ids .
         '\').css(\'display\',\'none\'); else $(\'#spoiler' . $this->ids .
         '\').css(\'display\',\'block\');">' . ($status ? $hide : $show) . '</a>
            <div id="spoiler' . $this->ids . '" ' .
         (! $status ? 'style="display:none;"' : "") . ' >';
        $html .= $content;
        $html .= '</div>';*/
    	$html='<summary '.($label? 'id="'.$label.'"':'').'><b>'.$label.'</b><details "'.($status? 'open="true"':'').'">'.$content.'</details></summary>';
        return $html;
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
