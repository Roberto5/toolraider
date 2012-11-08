<?php
require_once 'Zend/Db/Table/Abstract.php';
class Model_Planet extends Zend_Db_Table_Abstract
{
	public $data;
	public $_planet=array();
	function __construct ($option)
	{
		$this->_name=PREFIX.'planet';
		parent::__construct();
		if (is_int($option)) $query="`uid`='$option'";
		elseif (is_array($option)) $query=$option;
		else throw new Zend_Db_Table_Exception(' $option params is not int or array');
		$select=$this->select()->where($query)->order("id");
		$this->data= $this->fetchAll($select);
		foreach ($this->data as $value) {
			$this->_planet[$value['id']]=$value;
		}
	}
	public function getname($pid) {
		return $this->_planet[$pid]['name'];
	}
}

