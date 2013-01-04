<?php
/**
 * user
 *
 * @author pagliaccio
 * @version
 */
require_once 'Zend/Db/Table/Abstract.php';
class Model_Server extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_primary = 'id';
	/**
	 *
	 * @var Zend_Db_Table_Row_Abstract
	 */
	public $data;
	public $selected=0;
	static public $list=array();
	function __construct ($option)
	{
		$this->_name=PREFIX.'server';
		parent::__construct();
		$this->data=$this->fetchAll();
		$i=0;$first=0;
		foreach ($this->data as $value) {
			self::$list[$value['id']]=$value['name'];
			if (!$i) {
				$first=$value['id'];$i++;
			}
		}
		if (array_key_exists($option, self::$list))
			$this->selected=intval($option);
		else $this->selected=$first;
	}
}
?>