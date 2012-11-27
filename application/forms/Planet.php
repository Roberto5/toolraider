<?php

class Form_Planet extends Zend_Form
{

    public function init()
    {
    	$name=$this->createElement('text', 'name');
    	/*$uid->addValidator('Db_RecordExists', null, 
        	array('table' => PREFIX.'user', 'field' => 'username'));*/
    	$name->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator("alnum")
            ->addValidator("StringLength", null, array('max' => 20, 'min' => 1));
    	$x=$this->createElement('text', 'x');
    	$x->setRequired(true)
            ->addFilter('StringTrim')
            ->addValidator('int')
    		->addValidator('GreaterThan',null,array('min'=>-400))
    		->addValidator('LessThan',null,array('max'=>400));
    	$y=$this->createElement('text', 'y');
    	$y->setRequired(true)
    	->addFilter('StringTrim')
    	->addValidator('int')
    	->addValidator('GreaterThan',null,array('min'=>-400))
    	->addValidator('LessThan',null,array('max'=>400));
    	$system=$this->createElement('text', 'system');
    	$system->setRequired(false)
    	->addFilter('StringTrim')
    	->addValidator('int');
    	$galaxy=new Zend_Form_Element_Select('galaxy');
    	$galaxy->setRequired(true);
    	$galaxy->addMultiOptions(Model_Planet::$galaxy);
    	$type=new Zend_Form_Element_Select('type');
    	$type->addMultiOptions(Model_Planet::$type);
    	$this->addElements(array($name,$x,$y,$system,$galaxy,$type));
    }
}

