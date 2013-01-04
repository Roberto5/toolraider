<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->auth=Zend_Auth::getInstance();
    }

    public function indexAction()
    {
    	if ($this->view->auth->hasIdentity()) {
    		$user=Model_User::getInstance();
			if ($_POST['submit']) {
				$this->_log->debug($_POST);
				$this->_log->debug(array_keys(Model_Server::$list));
				if (in_array($_POST['server'],array_keys(Model_Server::$list))) {
					$this->_log->debug($_POST);
					$user->updateU(array('server'=>$_POST['server']));
					$user->data['server']=$_POST['server'];
				}
			}
			$this->view->server=$user->data['server'];
    	}
    }

    public function creditsAction()
    {
        $this->view->key=array('PATH'=>$this->view->baseUrl());
    }


}

