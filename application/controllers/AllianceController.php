<?php

class AllianceController extends Zend_Controller_Action
{
	/**
	 * 
	 * @var Model_user
	 */
	private $user;
    public function init()
    {
        $this->user=Model_user::getInstance();
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

