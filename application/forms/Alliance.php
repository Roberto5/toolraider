<?php

class Form_Alliance extends Zend_Form
{

    public function init()
    {
        $name=new Zend_Form_Element_Text('name');
        $name->setRequired(true)->setLabel('NAME')
        	->addValidator('alnum',null,array('allowWhiteSpace'=>true));
        $description=new Zend_Form_Element_Textarea('description');
        $description->setRequired(true)
        	->setLabel('DESCRIPTION2')
        	->addFilter('HtmlEntities');
        $submit=$this->createElement('submit', 'submit');
        $this->addElements(array($name,$description,$submit));
    }


}

