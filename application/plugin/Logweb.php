<?php

class Plugin_Logweb extends Zend_Log_Writer_Abstract {
	private $layout;
	function __construct() {
		//$layout=new Zend_View_Helper_Layout();
		$this->layout=Zend_Layout::getMvcInstance()->getView();
		$this->layout->logger=array();
	}
	function write($event) {
		$this->layout->logger[]=$event;
	}
	protected function _write($event) {
		$this->layout->logger[]=$event;
	}
	static function factory($config) {
		$web=new Plugin_Logweb();
		return $web;
	}
}

?>