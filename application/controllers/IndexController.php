<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->auth=Zend_Auth::getInstance();
    }

    public function indexAction()
    {
		
    }


}

