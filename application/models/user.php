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
	/**
	 * @param int $ID
	 */
	function __construct ($ID = 0,$code=false)
	{
		$this->_name=PREFIX.'user';
		parent::__construct();
		$ID = intval($ID);
		if ($code) $query="`code`='$code'";
		else $query="`id`='$ID'";
		$this->data= $this->fetchRow($query);
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
			return $this->update($data,"`uid`='".$this->data['uid']."'");
		}
		return false;
	}
}
?>