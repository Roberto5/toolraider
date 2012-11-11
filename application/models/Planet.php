<?php
require_once 'Zend/Db/Table/Abstract.php';
class Model_Planet extends Zend_Db_Table_Abstract implements ArrayAccess, IteratorAggregate
{
	public $data;
	protected $_planet=array();
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
	public function __set($name,$value) {
		$this->_planet[$name]=$value;
	}
	public function __get($key) {
		return $this->_planet[$key];
	}
	public function getIterator() {
		return new ArrayIterator($this->_planet);
	}
	public function offsetSet($offset, $value) {
		if (is_null($offset)) {
			$this->_planet[] = $value;
		} else {
			$this->_planet[$offset] = $value;
		}
	}
	public function offsetExists($offset) {
		return isset($this->_planet[$offset]);
	}
	public function offsetUnset($offset) {
		unset($this->_planet[$offset]);
	}
	public function offsetGet($offset) {
		return isset($this->_planet[$offset]) ? $this->_planet[$offset] : null;
	}
	public function toArray() {
		return $this->_planet;
	}
}

