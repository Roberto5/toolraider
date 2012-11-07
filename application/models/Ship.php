<?php
require_once 'Zend/Db/Table/Abstract.php';
class Model_Ship extends Zend_Db_Table_Abstract
{
	public $data;
	function __construct ($option)
	{
		$this->_name=PREFIX.'ship';
		parent::__construct();
		
		if (is_int($option)) $query="`uid`='$option'";
		elseif (is_array($option)) $query=$option;
		else throw new Zend_Db_Table_Exception(' $option params is not int or array');
		$select=$this->select()->where($query)->order("pid");
		$this->data= $this->fetchAll($select);
	}

}

