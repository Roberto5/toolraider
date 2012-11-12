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
	 * 
	 */
    public function delAction()
    {
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$pid=intval($this->_getParam('pid'));
		$bool='false';$mess='""';
		if ($pid){//pid is a number
			if ($this->user->planet[$pid]) {//pid is own planet
				$this->user->ship->delete("`pid`='$pid'");
				$bool= 'true';
			}
			else $mess= '"'.$this->_t->_('PLANET_ISNT_OWN').'"';
		}
		else $mess= '"'.$this->_t->_('PLANET_ID_ERR').'"';
		$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
		echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }

    public function addAction()
    {
    	
        $this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$pid=intval($this->_getParam('pid'));
		$ship=$_POST['ship'];
		$bool='false';$mess='""';
		//control planet own and if list of ship of this planet not exist
		if (is_array($ship) && $this->user->planet[$pid] && !$this->user->ship[$pid]) {
			foreach ($ship as $key=>$value) {
				if (intval($value)>0)
					$this->user->ship->insert(array('pid'=>$pid,'type'=>intval($key),'quantity'=>intval($value)));
			}
			$bool= 'true';
		}
		else $mess= '"'.$this->_t->_('PLANET_ID_ERR').'"';
		$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
		echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }

    public function updateAction()
    {
    	$this->_helper->layout->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	$pid=intval($this->_getParam('pid',false));
    	$ship=$_POST['ship'];
    	$key=$_POST['key'];
    	$bool='false';
    	$mess='""';
    	if (is_array($ship) && is_array($key) && $this->user->planet[$pid] && $this->user->ship[$pid]) {
    		foreach ($ship as $i=>$v) {
    			$k=$key[$i];
    			if ($this->user->ship[$pid][$k])
    				$this->user->ship->update(array('quantity'=>$v),"`pid`='$pid' AND `type`='$k'");
    			else $this->user->ship->insert(array('quantity'=>$v,'pid'=>$pid,'type'=>$k));
    		}
    		$bool= 'true';
    	}
    	else $mess= '"'.$this->_t->_('PLANET_ID_ERR').'"';
    	$this->view->setScriptPath(APPLICATION_PATH.'/views/scripts/template');
    	echo str_replace(array('BOOL','MESS'), array($bool,$mess), $this->view->render('ajax.phtml'));
    }
}







