<?php
//creazione ACL
$acl=new Zend_Acl();
//creazione ruoli
$acl->addRole(new Zend_Acl_Role("guest"));// naviga solo nella parte principale del sito
$acl->addRole(new Zend_Acl_Role("user"));//da decidere
$acl->addRole(new Zend_Acl_Role("staff"),"user");
$acl->addRole(new Zend_Acl_Role("debuger"),"staff");//privilegi di debug, visualizzazione log e debug tool
$acl->addRole(new Zend_Acl_Role("admin"));//tutti i privilegi
//alleanza
$acl->addRole(new Zend_Acl_Role('NONE'),'user');
$acl->addRole(new Zend_Acl_Role('WAIT'),'NONE');
$acl->addRole(new Zend_Acl_Role('MEMBER'),'WAIT');
$acl->addRole(new Zend_Acl_Role('ADVISER'),'MEMBER');
$acl->addRole(new Zend_Acl_Role('FOUNDER'),'user');

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

//permessin NONE
$acl->deny('NONE','alliance','edit');
$acl->deny('NONE','alliance','profile');
$acl->deny('NONE','alliance','editname');
$acl->deny('NONE','alliance','read');
$acl->deny('NONE','alliance','write');
$acl->deny('NONE','alliance','privilege');
$acl->deny('NONE','alliance','recruit');
//permessi wait
$acl->allow('WAIT','alliance','profile');;

//permessi member
$acl->allow('MEMBER','alliance','read');

//permessi adviser
$acl->allow('ADVISER','alliance','edit');
$acl->allow('ADVISER','alliance','write');
$acl->allow('ADVISER','alliance','recruit');
//permessi founder


//permessi staff
//@todo definire permessi staff
//permessi debugger
$acl->allow("debuger","debug");
//permessi admin
$acl->allow("admin");// do tutti i poteri all'admin
?>