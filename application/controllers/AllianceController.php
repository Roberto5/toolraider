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
    public function addAction() {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='""';
    	
    	
    	$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
    	echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }
    public function leaveAction() {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='""';
    	 
    	 
    	$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
    	echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }
    public function searchAction() {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$term=$_GET['term'];
    	$value=array();
    	for ($i = 0; $i < 10; $i++) {
    		$value[]=array('label'=>$term.rand(0, 5).rand(5, 10),'value'=>$i);
    	}
    	echo json_encode($value);
    }
}

