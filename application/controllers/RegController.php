<?php

class RegController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	global $messRegisterMail;
    	$form = new Application_Form_Register();
    	$form->setAction($this->view->url(array('controller' => 'reg')));
    	$this->view->form = $form;
    	if ($this->getRequest()->isPost()) {
    		$this->view->send = true;
    		if ($form->isValid($_POST)) {
    			$this->view->type = 1;
    			$this->view->text = $this->view->t->_("registrazione avvenuta con successo");
    			$post = $form->getValues();
    			$conf = Zend_Registry::get("config");
    			$code = "";
    			for ($i = 0; $i < 8; $i ++) {
    				$code .= (rand(0, 1) ? chr(rand(65, 122)) : rand(0, 9));
    			}
    			$code = md5($code);
    			$active=($conf->email->validation ? 0 : 1 );
    			$data = array('username' => $post['username'],
    					'password' => sha1($post['password']), 'email' => $post['email'],
    					'active' => $active, 'code' => $code);
    			$user = new Model_user();
    			$user->register($data);
    			if ($conf->email->validation) {
    				$sender = new Zend_Mail();
    				$sender->addTo($post['email'])
    				->setFrom(WEBMAIL, SITO)
    				->setBodyHtml($messRegisterMail)
    				->setBodyText($messRegisterMail)
    				->setSubject($this->view->t->_("Conferma E-mail di registrazione"))
    				->send();
    			}
    		} else {
    			$this->view->type = 2;
    			$this->view->form->populate($_POST);
    		}
    	}
    }

    public function ctrlAction()
    {
    	
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        header("Content-type: application/json");
        $cerca=$this->_getParam('cerca');
        $value=$this->_getParam('valore');
        $db=new Zend_Validate_Db_NoRecordExists(array('table'=>PREFIX.'user','field'=>$cerca));
        switch ($cerca) {
        	case 'username':
        		$alnum= new Zend_Validate_Alnum();
        		$bool= (($alnum->isValid($value)) && ($db->isValid($value)));
        	break;
        	case 'email';
        		$email=new Zend_Validate_EmailAddress();
        		$bool= (($db->isValid($value)) && ($email->isValid($value)));
        	break;
        	default: $bool=true;
        }
        echo json_encode($bool);
    }
}



