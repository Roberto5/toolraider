<?php

class ShiptoolController extends Zend_Controller_Action
{

    /**
     * @var Model_user
     *
     *
     */
    private $user = null;

    public function init()
    {
        $this->user=Model_user::getInstance();
        $this->view->user=$this->user;
    }

    public function indexAction()
    {
    	$this->_log->debug($this->user,'user');
    	$this->view->ship=array('race'=>$this->user->getRace(),'username'=>$this->user->data['username'],'uid'=>$this->user->data['id']);
    	$i=-1;$prev=0;
    	$this->view->list=$this->user->planet->_planet;
    	
    	foreach ($this->user->ship->data as $value) {
    		if ($value['pid']!=$prev) {
    			$i++;
    			$this->view->ship[$i]['pid']=$value['pid'];
    			$prev=$value['pid'];
    			$this->view->ship[$i]['name']=$this->user->planet->getname($value['pid']);
    			$this->view->list[$value['pid']]=null;
    		}
    		$this->view->ship[$i]['ships'][$value['type']]=$value['quantity'];
    	}
    }

    public function delAction()
    {
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
    }

    public function addAction()
    {
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
    }


}





