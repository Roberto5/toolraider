<?php

/**
 * ally role
 * 
 * @author pagliaccio
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_AllyRole extends Zend_Db_Table_Abstract {

	/**
	 * The default table name 
	 */
	static public $r=false;
	public $role;
	private $id;
	private $aid;
	static function getRole($uid=0,$aid=0) {
		if (Model_Role::getRole()=='user') {
			$aid=$aid ? $aid : Model_User::getInstance()->ally->data['id'];
			$uid=$uid ? $uid : Model_User::getInstance()->data['id'];
			if (!self::$r[$uid][$aid]) 	self::$r[$uid][$aid]=Zend_Db_Table::getDefaultAdapter()
			->fetchOne(
					"SELECT `role` FROM `" . PREFIX . "ally_role`
					WHERE `uid`='$uid' AND `aid`='$aid'");
			if (!self::$r[$uid][$aid]) self::$r[$uid][$aid]='NONE';
		}
		return self::$r[$uid][$aid];
	}
	function __construct($id,$aid) {
		$this->_name=PREFIX.'ally_role';
		parent::__construct();
		$id = intval($id);
		$aid=intval($aid);
		$this->id=$id;
		$this->aid=$aid;
		$data=$this->fetchRow("`uid`='$id' AND `aid`='$aid'");
		if ($data) $this->role=$data['role'];
	}
}
?>