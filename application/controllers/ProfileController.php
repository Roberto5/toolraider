<?php
function translate($a) {
	return "[$a]";
}
class ProfileController extends Zend_Controller_Action
{

    /**
     * @var Model_User
     *
     *
     */
    private $user = null;

    public function init()
    {
    	$this->user=Model_User::getInstance();
        $this->view->option=array(
        		'race'=>array(
        				'name'=>'[RACE]',
        				'value'=>$this->user->data['race'],
        				'label'=>'['.$this->user->getRace().']',
        				'option'=>array_map(translate, $this->user->race),
        				'type'=>'Select'
        		)
        );
    }

    public function indexAction()
    {
        $this->view->can=Zend_Registry::get('config')->editusername;
        
        $this->view->key=array('NICK'=>$this->user->data['username'],'EMAIL'=>$this->user->data['email']);
    }

    public function editAction()
    {
        $this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='""';
    	$form=new Form_Register();
    	if ($e=$form->getElement($_POST['key'])) {
    		if ($e->isValid($_POST['value'])) {
    			$bool='true';
    			$this->user->updateU(array($_POST['key']=>$_POST['value']));
    		}
    		else $mess='"'.$this->_t->_('DATA_ERROR').'"';
    	}
    	else {
    		$mess='"'.$this->_t->_('DATA_ERROR').'"';
    	}
    	$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
    	echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }

    public function ctrlAction()
    {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	header("Content-type: application/json");
    	$username=$_POST['username'];
    	$email=$_POST['email'];
    	$db=new Zend_Validate_Db_NoRecordExists(array('table'=>PREFIX.'user','field'=>'username'));
    	$bool=true;
    	if ($username) {
    		$alnum= new Zend_Validate_Alnum();
    		$db->setField('username');
    		$bool= (($alnum->isValid($username)) && ($db->isValid($username)));
    	}
    	if ($email) {
    		$db->setField('email');
    		$vemail=new Zend_Validate_EmailAddress();
    		$bool= (($db->isValid($email)) && ($vemail->isValid($email)));
    	}
    	echo json_encode($bool);
    }

    public function passwordAction()
    {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='""';
    	$form=new Form_Register();
    	$e=$form->getElement('password');
    	if (sha1($_POST['password'])==$this->user->data['password']) {
    		if ($e->isValid($_POST['new'])&&($_POST['new']==$_POST['new2'])) {
    			$bool='true';
    		$this->user->updateU(array('password'=>$_POST['new']));
    		}
    		else $mess='"'.$this->_t->_('DATA_ERROR').'"';
    	}
    	else $mess='"'.$this->_t->_('PASS_ERR').'"';
    	  	$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
    	echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$bool='false';$mess='""';
    	if (sha1($_POST['password'])==$this->user->data['password']) {
    		$id=$this->user->data['id'];
			$this->user->delete("`id`='$id'");
    		$auth = Zend_Auth::getInstance();
    		if ($auth->hasIdentity())
    			$auth->clearIdentity();
    		$bool='true';
    	}
    	else $mess='"'.$this->_t->_('PASS_ERR').'"';
    	  	$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
    	echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }


}









