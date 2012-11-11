<?php

class AllianceController extends Zend_Controller_Action
{
	/**
	 * 
	 * @var Model_User
	 */
	private $user;
    public function init()
    {
        $this->user=Model_User::getInstance();
    }

    public function indexAction()
    {
    	$this->_log->debug($this->user,'user');
        $this->view->ally=$this->user->ally;
        if ($this->user->role=='WHAIT') {
        	$this->view->pending=true;
        }
    }
}

