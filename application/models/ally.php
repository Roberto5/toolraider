<?php
/**
 * params
 *
 * @author pagliaccio
 * @version
 */

require_once 'Zend/Db/Table/Abstract.php';
require_once 'include/costant.php';

class Model_ally extends Zend_Db_Table_Abstract {
	public $_name;
	public $data;
	public $members=array();
	public $pact=array();
	function __construct($config) {
		$this->_name=PREFIX.'ally';
		parent::__construct();
		if (is_int($config)) $query="`id`='$config'";
		elseif (is_array($config)) $query=$config;
		$this->data= $this->fetchRow($query);
		$id=$this->data['id'];
		$this->members=$this->getAdapter()->fetchAll("SELECT `username`,`uid`,`role` FROM `".PREFIX."user`,`".PREFIX."ally_role` WHERE `".PREFIX."user`.`aid`='$id' AND `".PREFIX."user`.`id`=`uid`");
		$this->pact=$this->getAdapter()->fetchAll("SELECT * FROM `".PREFIX."ally_pact` WHERE `aid`='$id' OR `aid2`='$id'");
	}
	function addMember($uid) {
		if (!is_array($udi)) $uid=array($uid);
		foreach ($uid as $value) {
			$this->getAdapter()->insert(PREFIX.'ally_role', array('aid'=>$this->data['id'],'uid'=>$value,'role'=>'MEMBER'));
			$this->getAdapter()->update(PREFIX.'user', array('aid'=>$this->data['aid']),array('id'=>$value));
		}
	}
	function removeMember($uid) {
		if (!is_array($udi)) $uid=array($uid);
		foreach ($uid as $value) {
			$this->getAdapter()->delete(PREFIX.'ally_role', array('uid'=>$value));
			$this->getAdapter()->update(PREFIX.'user', array('aid'=>0),array('id'=>$value));
		}
	}
	function requestPact($aid,$type) {
		$this->getAdapter()->insert(PREFIX.'ally_pact', array('aid'=>$this->data['id'],'aid2'=>$aid,'type'=>$type));
	}
	function acceptPact($aid,$type) {
		$this->getAdapter()->update(PREFIX.'ally_pact', array('status'=>1),array('aid'=>$aid,'aid2'=>$this->data['id'],'type'=>$type));
	}
	function rejectPact($aid,$type) {
		$this->getAdapter()->delete(PREFIX.'ally_pact', array('aid'=>$aid,'aid2'=>$this->data['id'],'type'=>$type));
	}
	function deletePact($aid,$type) {
		$this->getAdapter()->delete(PREFIX.'ally_pact', array('aid'=>$aid,'aid2'=>$this->data['id'],'type'=>$type));
		$this->getAdapter()->delete(PREFIX.'ally_pact', array('aid2'=>$aid,'aid'=>$this->data['id'],'type'=>$type));
	}
	
}
?>