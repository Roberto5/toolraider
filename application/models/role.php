<?php

/**
 * role
 * 
 * @author pagliaccio
 * @version 
 */

require_once 'Zend/Db/Table/Abstract.php';

class Model_role extends Zend_Db_Table_Abstract {

	/**
	 * The default table name 
	 */
	static public $role=false;
	
	static function getRole() {
		$auth=Zend_Auth::getInstance();
		if (!self::$role && $auth->hasIdentity())
			self::$role=Zend_Db_Table::getDefaultAdapter()
			->fetchOne(
				"SELECT `role` FROM `" . PREFIX . "role` 
				WHERE `uid`='" . $auth->getIdentity()->id . "'");
		else self::$role="guest";
		return self::$role;
	}

}
?>