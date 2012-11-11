<?php

class ShiptoolController extends Zend_Controller_Action
{

    /**
     * @var Model_User
     */
    private $user = null;

    public function init()
    {
        $this->user=Model_User::getInstance();
        $this->view->user=$this->user;
    }

    public function indexAction()
    {
    	$this->_log->debug($this->user,'user');
    	$this->view->ship=array('race'=>$this->user->getRace(),'username'=>$this->user->data['username'],'uid'=>$this->user->data['id']);
    	$i=-1;$prev=0;
    	$this->view->list=$this->user->planet->toArray();

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
	/**
	 * @toto create a ajax templare phtml
	 */
    public function delAction()
    {
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$pid=intval($this->_getParam('pid'));
		if ($pid){//pid is a number
			if ($this->user->planet[$pid]) {//pid is own planet
				$this->user->ship->delete(array('pid'=>$pid));
				echo 'true';
			}
			else echo 'false';
		}
		else echo 'flase';

    }

    public function addAction()
    {
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$pid=intval($this->_getParam('pid',false));
		$ship=$_POST['ship'];
		//control planet own and if list of ship of this planet not exist
		if (is_array($ship) && $this->user->planet[$pid] && !$this->user->ship[$pid]) {
			foreach ($ship as $key=>$value) {
				$this->user->ship->insert(array('id'=>$pid,'type'=>intval($key),'quantity'=>intval($value)));
			}
			echo 'true';
		}
		else echo 'false';

    }

    public function updateAction()
    {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$pid=intval($this->_getParam('pid',false));
    	$ship=$_POST['ship'];
    	$key=$_POST['key'];
    	if (is_array($ship) && $this->user->planet[$pid] && $this->user->ship[$pid]) {
    		foreach ($ship as $i=>$v) {
    			$this->user->ship->update(array('quantity'=>$v),array('pid'=>$pid,'type'=>$key[$i]));
    		}
    		echo 'true';
    	}
    	else echo 'false';
    }
}







