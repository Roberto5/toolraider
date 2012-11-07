<?php
/**
 * user
 *
 * @author pagliaccio
 * @version
 */
require_once 'Zend/Db/Table/Abstract.php';
class Model_user extends Zend_Db_Table_Abstract {
	/**
	 * The default table name
	 */
	protected $_primary = 'id';
	/**
	 * 
	 * @var Zend_Db_Table_Row_Abstract
	 */
	public $data;
	static private $instance;
	/**
	 * 
	 * @var Model_ally
	 */
	public $ally;
	public $role;
	/**
	 * 
	 * @var Model_Ship
	 */
	public $ship;
	/**
	 * 
	 * @var Model_Planet
	 */
	public $planet;
	public $race=array('TITAN','XEN','TERRAN');
	function __construct ($option)
	{
		$this->_name=PREFIX.'user';
		parent::__construct();
		if (is_int($option)) $query="`id`='$option'";
		elseif (is_array($option)) $query=$option;
		else throw new Zend_Db_Table_Exception(' $option params is not int or array');
		$this->data= $this->fetchRow($query);
		$id=intval($this->data['id']);
		if ($this->data['aid']) {
			$this->ally=new Model_ally($this->data['aid']);
			$this->role=$this->getAdapter()->fetchOne("SELECT `role` FROM `".PREFIX."ally_role` WHERE `uid`='".$id."'");
		}
		$this->ship=new Model_Ship($id);
		$this->planet=new Model_Planet($id);
		self::$instance=$this;
	}
	static function getInstance($option=0) {
		if (self::$instance) return self::$instance;
		else return new Model_user($option);
	}
	/**
	 * registra un utente, ritorna true se la registrazione &egrave; andata bene.
	 * @param Array $vect indice per il campo e valore come valore
	 * @return bool
	 */
	static function register ($data)
	{
		if ($data) {
			$data['code_time']=time();
			self::getDefaultAdapter()->insert(PREFIX.'user',$data);
			return true;
		} else {
			return false;
		}
	}
	/**
	 * modifica i valori dell'utente
	 * @param Array $data indice per il campo e valore come valore
	 * @return bool
	 */
	function updateU ($data)
	{
		if ($data) {
			if (isset($data['code'])) {
				$data['code_time']=time();
			}
			return $this->update($data,"`id`='".$this->data['id']."'");
		}
		return false;
	}
	function allyRequest($aid) {
		$this->updateU(array('aid'=>$aid));
		$this->getAdapter()->insert(PREFIX.'ally_role', array('uid'=>$this->data['id'],'aid'=>$aid));
	}
	function leaveAlly() {
		$this->getAdapter()->delete(PREFIX.'ally_role',array('aid'=>$this->data['aid']));
		$this->updateU(array('aid'=>0));
	}
	function getRace() {
		return Zend_Registry::get('translate')->_($this->race[$this->data['race']]);
	}
}
?>