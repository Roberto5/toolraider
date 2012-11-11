<?php
require_once ('Zend/Controller/Plugin/Abstract.php');
class plugin_acl_controller extends Zend_Controller_Plugin_Abstract
{
    /**
     * access control  list
     * @var Zend_Acl
     */
    private $_acl = null;
    function __construct ($acl)
    {
        $this->_acl = $acl;
    }
    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Plugin_Abstract::preDispatch()
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch ($request)
    {
        $log = Zend_Registry::get("log");
        $auth = Zend_Auth::getInstance();
        $db = Zend_Db_Table::getDefaultAdapter();
        $resource = $request->getControllerName();
        $privilege = $request->getActionName();
        $module = $request->getModuleName();
        // assegno un ruolo
        if ($auth->hasIdentity()) {
            $role = Model_Role::getRole();
            $user=Model_User::getInstance();
            if (($user->data['active']!='1')&& (($request->getControllerName()!='reg')&&($request->getControllerName()!='login'))) {
            	$log->notice('user inactive');
            	$request->setModuleName('default')
            	->setControllerName('reg')
            	->setActionName('resend');
            } 
        }
        else
            $role = 'guest';
        ob_start();
        //controllo server manutenzione
        if (Zend_Registry::isRegistered("param")) {
        $par=Zend_Registry::get("param");
        $offline=$par->get("offline");
        if ($offline>0) {
        	$log->warn("server offline!");
        	if (($role!="admin")&&($role!="debuger")&&($resource!="processing")&&($resource!="stats")) {
        		$request->setModuleName('default')
                ->setControllerName('error')
                ->setActionName('error')->setParam("error", "Maintenaince");
        	}
        }}
        // carico le risorse esistenti
        $totres = $this->_acl->getResources();
        //se la risorsa non è presente l'aggiongo come figlia del modulo
        if (! in_array($resource, $totres)) {
            $this->_acl->addResource($resource, $module);
        }
        $log->debug($role,'role');
        // controllo privilegi
        if (! $this->_acl->isAllowed($role, $resource, $privilege)) {
        	Zend_Controller_Front::getInstance()->getResponse()->setHttpResponseCode(403);
            $log->warn("access deny for $role on $module/$resource/$privilege");
            $request->setModuleName('default')
                ->setControllerName('error')
                ->setActionName('error')->setParam("error", "access deny");
        }
    }

}
?>