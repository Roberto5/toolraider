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
    	$server=$this->user->server->selected;
    	$ally=$this->user->ally->fetchAll("`server`='$server'");
    	foreach ($ally as $v) {
    		$value[]=array('label'=>$v['name'],'value'=>$v['id']);
    	}
    	echo json_encode($value);
    }
    public function showAction() {
    	
    }
    public function createAction() {
    	$this->view->option=array('sec'=>5,'link'=>$this->view->baseUrl('/alliance'));
    	$form=new Form_Alliance();
    	if ($form->isValid($_POST)) {
    		$this->view->option['type']=1;
    		$this->view->option['text']='[DONE]';
    		$aid=$this->user->ally->insert($form->getValues());
    		$this->_log->debug($aid);
    		$this->_db->insert(PREFIX.'user_ally', array('aid'=>$aid,'uid'=>$this->user->data['id'],'server'=>$this->user->server->selected));
    		$this->_db->insert(PREFIX.'ally_role', array('aid'=>$aid,'uid'=>$this->user->data['id'],'role'=>'FOUNDER'));
    	}
    	else {
    		$this->view->option['type']=2;
    		$this->view->option['text']='[DATA_ERROR]';
    		$this->view->option['link']=null;
    		$this->view->form=$form->populate($_POST);
    	}
    }
}

