<?php

class PlanetController extends Zend_Controller_Action
{
	/**
	 * 
	 * @var Model_User
	 */
	private $user;
	/**
	 * 
	 * @var Form_Planet
	 */
	private $form;
    public function init()
    {
        $this->user=Model_User::getInstance();
        $this->form=new Form_Planet();
    }

    public function indexAction()
    {
        $this->view->planet=$this->user->planet;
        $this->_log->debug($this->user->planet->info(),'info');
    }

    public function addAction()
    {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='""';
        $pid=intval($this->_getParam('id'));
        $bonus_name=$_POST['bonus_name'];
        $bonus_value=$_POST['bonus'];
        //planet must not exist
        if (!array_key_exists($pid, $this->user->planet->toArray())) {
        	if ($pid) {
        		if ($this->form->isValid($_POST)&&is_array($bonus_value)&&is_array($bonus_name)) {
        			$data=$this->form->getValues();
        			$data['id']=$pid;
        			$data['uid']=$this->user->data['id'];
        			$bonus=array_combine($bonus_name, $bonus_value);
        			$data['bonus']=serialize($bonus);
        			$this->user->planet->insert($data);
        			$bool='true';
        		}
        	}
        }
        $this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
        echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='""';
        $pid=intval($this->_getParam('id'));
        if (!array_key_exists($pid, $this->user->planet->toArray())) {
        	if ($pid) {
        		$bool='true';
        		$this->user->planet->delete("`id`='$pid'");
        	}
        }
        $this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
        echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }

    public function editAction()
    {
        $this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='""';
        $pid=intval($this->_getParam('id'));
        $bonus_name=$_POST['bonus_name'];
        $bonus_value=$_POST['bonus'];
        if (array_key_exists($pid, $this->user->planet->toArray())) {
        	$bool='true';
        	if ($pid) {
        		if ($this->form->isValid($_POST)&&is_array($bonus_value)&&is_array($bonus_name)) {
        			$data=$this->form->getValues();
        			//$data['id']=$pid;
        			$data['uid']=$this->user->data['id'];
        			$bonus=array_combine($bonus_name, $bonus_value);
        			$data['bonus']=serialize($bonus);
        			$this->user->planet->update($data,array('id'=>$pid));
        			$bool='true';
        		}
        	}
        }
        $this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
        echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }


}







