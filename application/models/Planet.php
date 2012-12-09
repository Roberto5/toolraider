<?php
require_once 'Zend/Db/Table/Abstract.php';
class Model_Planet extends Zend_Db_Table_Abstract implements ArrayAccess, IteratorAggregate
{
	public $data;
	protected $_planet=array();
	public $length=0;
	static $galaxy=array('Fornax','Centaurus','Phoenix');
	/**
	 * 
	 * @var Zend_Translate
	 */
	public $t;
	public static $type=array('desert','ice','terran','volcano','water');
	public static $bonus=array('Wind power','Solar power','Idro power','Thermal power','Research','Population Growth','Metal production','Crystal production','Tritium production','Building cost','Building production speed','Building cost','Ship cost','Ship production speed');
	function __construct ($option)
	{
		$this->_name=PREFIX.'planet';
		parent::__construct();
		if (is_int($option)) $query="`uid`='$option'";
		elseif (is_array($option)) $query=$option;
		else throw new Zend_Db_Table_Exception(' $option params is not int or array');
		$select=$this->select()->where($query)->order("id");
		$this->data= $this->fetchAll($select);
		$l=0;
		$this->t=Zend_Registry::get('translate');
		foreach ($this->data->toArray() as $value) {
			$l++;
			$value['type']=$this->t->_($value['type']);
			$value['bonus']=unserialize($value['bonus']);
			$this->_planet[$value['id']]=$value;
		}
		$this->length=$l;
	}
	public function getGalaxy($pid) {
		return self::$galaxy[$this->_planet[$pid]['galaxy']];
	}
	public function getname($pid) {
		return $this->_planet[$pid]['name'];
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

