<?php
/**
 * params
 * 
 * @author pagliaccio
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_Params extends Zend_Db_Table_Abstract {

	/**
	 * The default table name 
	 */
	protected $_name;
	protected $_primary="name";
	
	function __construct() {
		$this->_name=PREFIX.'params';
		parent::__construct();
		$this->getall();
	}
	/**
	 * 
	 * @return array
	 */
	function getall() {
		$par=$this->fetchAll()->toArray();
		foreach ($par as $key=>$value) {
			$this->$key=$value;
		}
		return $par;
	}
/**
     * se la variabile non è presente in memoria la estrae dal DB
     * @param String $name nome del parametro
     * @param Mixed $default valore di default
     * @return mixed
     */
    function get($name,$default=null,$refresh=false)
    {
        if ($refresh) {
        	$row=$this->fetchRow("`name`='$name'");
        	if ($row) $this->$name=$row['value'];
        	else $this->$name=$default;
        }
        if (isset ($this->$name)) $default=$this->$name;
        return $default;
    }
    /**
     * setta le variabili
     * @param String $name
     * @param mixed $value
     */
    function set($name,$value)
    {
    	if (isset($this->$name)) $this->update(array('value'=>$value), "`name`='$name'");
        else $this->getDefaultAdapter()->insert($this->_name,array("name"=>$name , "value"=>$value));
        $this->$name=$value;
    }
}
