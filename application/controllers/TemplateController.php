<?php

class TemplateController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->ship_name=array(
    	'TERRAN'=>array(
			'Fighter',
    		'Destroyer',
   			'Cruiser',
   			'Battleship',
   			'Bomber',
   			'Frigate',
   			'Recycler',
   			'Large Recycler',
   			'Terran Freighter',
   			'Probe',
   			'Pulsar',
   			'Colony Ship'
    	),
    	'TITAN'=>array(
    		'Scout',
    		'Delphi',
    		'Corsair',
    		'Terminator',
    		'Carrier',
    		'Phoenix',
    		'Recycler',
    		'Freighter',
    		'Large Freighter',
    		'Observer',
    		'Protector',
    		'Colony Ship'
    	),
    	'XEN'=>array(
    		'Xnair',
    		'Mylon',
    		'Maxtron',
    		'Mothership',
    		'Suikon',
    		'Xen Bomber',
    		'Octopon',
    		'Zek',
    		'Zekkon',
    		'Psikon',
    		'Macid',
    		'Xen Colony Ship'
    	),
    	'ALIEN'=>array(
    		'Pholidoteuthis',
    		'Chiroteuthis',
    		'Sepioteuthis',
    		'Architeuthis'
    	)
    	);
    }

    public function indexAction()
    {
        // action body
    }

    public function shiptableAction()
    {
        $this->view->ship=array('race'=>$this->_getParam('race','TERRAN'),'username'=>'ciao',
        		'uid'=>'3',
        		array('name'=>'prova','ships'=>array('3'=>4),'pid'=>1),
        		array('name'=>'prova2','ships'=>array('2'=>4),'pid'=>2),
        		array('name'=>'prova3','ships'=>array('1'=>4),'pid'=>3)
        );
        //$this->_log->debug($this->view->ship,'ship');
        $this->view->own=false;
    }

    public function planetshipAction()
    {
        // action body
    }


}





