<?php

class ShiptoolController extends Zend_Controller_Action
{
	/**
	 * 
	 * @var Model_user
	 */
	private $user;
    public function init()
    {
        $this->user=Model_user::getInstance();
        $this->view->user=$this->user;
    }

    public function indexAction()
    {
    	$this->_log->debug($this->user,'user');
    }


}

