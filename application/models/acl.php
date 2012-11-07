<?php
//creazione ACL
$acl=new Zend_Acl();
//creazione ruoli
$acl->addRole(new Zend_Acl_Role("guest"));// naviga solo nella parte principale del sito
$acl->addRole(new Zend_Acl_Role("user"));//da decidere
$acl->addRole(new Zend_Acl_Role("staff"),"user");
$acl->addRole(new Zend_Acl_Role("debuger"),"staff");//privilegi di debug, visualizzazione log e debug tool
$acl->addRole(new Zend_Acl_Role("admin"));//tutti i privilegi

//modulo
$acl->addResource("default");
$acl->addResource('admin');
//creazione risorsa

//controller
$acl->addResource("log");
$acl->addResource("alliance",'default');
$acl->addResource('shiptool','default');

//altro
$acl->addResource("debug");


//permessi del guest
$acl->allow("guest","default");
$acl->deny("guest","debug");
$acl->deny("guest","log");
$acl->deny("guest","alliance");
$acl->deny("guest","shiptool");

//permessi user

$acl->allow('user','default');

//permessi staff
//@todo definire permessi staff
//permessi debugger
$acl->allow("debuger","debug");
//permessi admin
$acl->allow("admin");// do tutti i poteri all'admin
?>