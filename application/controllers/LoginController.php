<?php

class LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$form = new Form_LoginForm();
        $form->setAction($this->view->url(array('controller' => 'login', 
    'action' => 'index')));
        $this->view->type = 0;
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            //Se il form è valido, lo processiamo   
            if ($form->isValid($_POST)) {
                //recuperiamo i dati così .....
                $user = $this->getRequest()->getParam(
                'username');
                $password = $this->getRequest()->getParam('password');
                $auth = Zend_Auth::getInstance();
                $adapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table::getDefaultAdapter());
                $adapter->setTableName(PREFIX."user")
                    ->setIdentityColumn('username')
                    ->setCredentialColumn('password')
                    ->setCredentialTreatment('sha1(?)');
                $adapter->setIdentity($user);
                $adapter->setCredential($password);
                $result = $adapter->authenticate();
                if ($result->isValid()) {
                    $user = $adapter->getResultRowObject(array('id', 
                    'username'));
                    $auth->getStorage()->write(
                    $user);
                    $this->view->type = 1;
                    $this->view->text = $this->_t->_("SUCCESS");
                } else {
                	$this->view->type = 2;
               		switch ($result->getCode()) {
                        case Zend_Auth_Result::FAILURE:
                            $this->view->text = $this->_t->_("FAILURE");
                            break;
                        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                            $this->view->text = $this->_t->_("PASS_ERR");
                            break;
                        case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                            $this->view->text = $this->_t->_("USER_NOT_FOUND");
                            break;
                        case Zend_Auth_Result::FAILURE_UNCATEGORIZED:
                            $this->view->text = $result->getMessages();
                            break;
                    }
                }
            } 
            else 
                $this->view->form->populate($_POST);
        }
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity())
            $auth->clearIdentity();
    }

// @todo add recovery 
}



