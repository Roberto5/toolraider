<?php

class PlanetController extends Zend_Controller_Action
{
	/**
	 * 
	 * @var Model_User
	 */
	public $user;
    public function init()
    {
        $this->user=Model_User::getInstance();
    }

    public function indexAction()
    {
        $this->view->planet=$this->user->planet;
    }

    public function addAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    public function editAction()
    {
        // action body
    }


}







