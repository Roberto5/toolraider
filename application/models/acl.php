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
$acl->addResource('login','default');
$acl->addResource('reg','default');
$acl->addResource('profile','default');
$acl->addResource("alliance",'default');
$acl->addResource('shiptool','default');
$acl->addResource('planet','default');

//altro
$acl->addResource("debug");


//permessi del guest
$acl->allow("guest","default");
$acl->deny("guest","debug");
$acl->deny("guest","log");
$acl->deny("guest","alliance");
$acl->deny("guest","shiptool");
$acl->deny('guest','login','logout');
$acl->deny('guest','profile');
$acl->deny('guest','planet');

//permessi user

$acl->allow('user','default');
$acl->deny('user','login','index');
$acl->deny('user','reg','index');
//$acl->allow('user','profile');

//permessi staff
//@todo definire permessi staff
//permessi debugger
$acl->allow("debuger","debug");
//permessi admin
$acl->allow("admin");// do tutti i poteri all'admin
?>