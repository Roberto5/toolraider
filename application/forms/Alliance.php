<?php

class Form_Alliance extends Zend_Form
{

    public function init()
    {
        $name=new Zend_Form_Element_Text('name');
        $name->setRequired(true)->setLabel('NAME')
        	->addValidator('alnum',null,array('allowWhiteSpace'=>false))
        	->addValidator('StringLength',null,array('max'=>30,'min'=>4))
        	->addValidator('Db_NoRecordExists',null,array('table'=>PREFIX.'ally','field'=>'name'));
        $server=new Zend_Form_Element_Select('server');
        $server->addMultiOptions(Model_Server::$list)->setLabel('Server');
        $description=new Zend_Form_Element_Textarea('description');
        $description->setRequired(true)
        	->setLabel('DESCRIPTION2')
        	->addFilter('HtmlEntities')->setAttribs(array('cols'=>40,'rows'=>10));
        $submit=$this->createElement('submit', 'submit');
        $this->addElements(array($name,$server,$description,$submit));
    }


}

