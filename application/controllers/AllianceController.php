<?php

class AllianceController extends Zend_Controller_Action
{
	/**
	 * 
	 * @var Model_User
	 */
	private $user;
	/**
	 * 
	 * @var Zend_Acl
	 */
	private $acl;
    public function init()
    {
        $this->user=Model_User::getInstance();
        $this->view->acl=Zend_Registry::get('acl');
        $this->acl=Zend_Registry::get('acl');
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
    	$id=intval($this->_getParam('id'));
    	$aid=$this->user->ally->data['id'];
    	$role=Model_AllyRole::getRole();
    	if ($this->acl->isAllowed($role,'alliance','recruit')) {
    		$this->_db->update(PREFIX.'ally_role', array('role'=>'MEMBER'),"`uid`='$id' AND `aid`='$aid'");
    		$bool='true';
    	}
    	else $mess='"C403"';
    	$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
    	echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }
    public function deleteAction() {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='""';
    	$id=intval($this->_getParam('id'));
    	$priv=false;
    	$aid=$this->user->ally->data['id'];
    	$role=Model_AllyRole::getRole();
    	if ($id==$this->user->data['id']) {
    		$priv=true;
    		$user=$this->user;
    	}
    	else {
    		$user=new Model_User($id);
    		if ($user->data) {
    			if (Model_AllyRole::getRole($id,$aid)=='WAIT') $type='recruit';
    			else $type='privilege';
    			$priv=$this->acl->isAllowed($role,'alliance',$type);
    		}
    	}
    	if ($priv) {
    		$this->_db->delete(PREFIX.'user_ally',"`uid`='$id' AND `aid`='$aid'");
    		$this->_db->delete(PREFIX.'ally_role',"`uid`='$id' AND `aid`='$aid'");
    		$bool="true";
    	}
    	else $mess='"[C403] '.$role.' '.$type.'"';
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
    	$this->view->ally=new Model_Ally(intval($this->_getParam('name')));
    	if (!$this->view->ally->data) {
    		Zend_Controller_Front::getInstance()->getResponse()->setHttpResponseCode(404);
    		$this->_log->warn("ally not found, id:".$this->_getParam('name'));
    		$this->getRequest()->setModuleName('default')
    		->setControllerName('error')
    		->setActionName('error')->setParam("error", "page not found");
    	}
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
    /**
     * Zend_Controller_Front::getInstance()->getResponse()->setHttpResponseCode(403);
            $log->warn("access deny for $role on $module/$resource/$privilege");
            $request->setModuleName('default')
                ->setControllerName('error')
                ->setActionName('error')->setParam("error", "access deny");
     */
    public function applicationAction() {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='"ERROR"';
    	$aid=intval($this->_getParam('id'));
    	$ally=new Model_Ally($aid);
    	if ($ally->data){
    		$role=new Model_AllyRole($this->user->data['id'], $aid);
    		if (!$role->role) {
    			if ($role->insert(array('uid'=>$this->user->data['id'],'aid'=>$aid,'role'=>'WAIT'))
    					&& $this->_db->insert(PREFIX.'user_ally',array('server'=>$this->user->server->selected,'uid'=>$this->user->data['id'],'aid'=>$aid)))
    				$bool='true';
    			else $bool='false';
    		}
    		else {
    			$mess='"[ERROR_APP]"';
    		}
    	}
    
    	$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
    	echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }
}

