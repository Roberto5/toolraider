<?php
//creazione ACL
$acl=new Zend_Acl();
//creazione ruoli
$acl->addRole(new Zend_Acl_Role("guest"));// naviga solo nella parte principale del sito
$acl->addRole(new Zend_Acl_Role("user"),"guest");//da decidere
$acl->addRole(new Zend_Acl_Role("staff"),"user");
$acl->addRole(new Zend_Acl_Role("debuger"),"staff");//privilegi di debug, visualizzazione log e debug tool
$acl->addRole(new Zend_Acl_Role("admin"));//tutti i privilegi

//modulo
$acl->addResource("default");

//creazione risorsa

//controller
$acl->addResource("log");


//altro
$acl->addResource("debug");


//permessi del guest
$acl->allow("guest","default");
$acl->deny("guest","debug");
$acl->deny("guest","log");


//permessi staff
//@todo definire permessi staff
//permessi debugger
$acl->allow("debuger","debug");
//permessi admin
$acl->allow("admin");// do tutti i poteri all'admin
?>