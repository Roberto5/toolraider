<?php
require_once 'Zend/Db/Table/Abstract.php';
class Model_Ship extends Zend_Db_Table_Abstract implements ArrayAccess, IteratorAggregate
{
	public $data;
	protected $_ship=array();
	function __construct ($option)
	{
		$this->_name=PREFIX.'ship';
		parent::__construct();
		if (is_array($option)) $query="`pid` IN ('".implode("','",$option)."')";
		else throw new Zend_Db_Table_Exception(' $option params is not array');
		$select=$this->select()->where($query)->order("pid");
		$this->data= $this->fetchAll($select);
		foreach ($this->data as $value) {
			$this->_ship[$value['pid']][$value['type']]=$value['quantity'];
		}
	}
	public function __set($name,$value) {
		$this->_ship[$name]=$value;
	}
	public function __get($key) {
		return $this->_ship[$key];
	}
	public function getIterator() {
		return new ArrayIterator($this->_ship);
	}
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->_ship[] = $value;
		} else {
			$this->_ship[$offset] = $value;
		}
	}
	public function offsetExists($offset) {
		return isset($this->_ship[$offset]);
	}
	public function offsetUnset($offset) {
		unset($this->_ship[$offset]);
	}
	public function offsetGet($offset) {
		return isset($this->_ship[$offset]) ? $this->_ship[$offset] : null;
	}
	public function toArray() {
		return $this->_ship;
	}
}

