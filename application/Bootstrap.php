<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * caricamento modelli, form, plugin
	 */
	protected function _initAutoload ()
	{
		// Add autoloader empty namespace
		$autoLoader = Zend_Loader_Autoloader::getInstance();
		$resourceLoader = new Zend_Loader_Autoloader_Resource(
				array('basePath' => APPLICATION_PATH, 'namespace' => '',
						'resourceTypes' => array(
								'form' => array('path' => 'forms/', 'namespace' => 'Form_'),
								'model' => array('path' => 'models/', 'namespace' => 'Model_'),
								'plugin' => array('path' => 'plugin/', 'namespace' => 'plugin_'))));
		// viene restituto l'oggetto per essere utilizzato e memorizzato nel bootstrap
		return $autoLoader;
	}
	protected function _initParams() {
		$this->bootstrap('autoload');
		$this->bootstrap('db');
		include_once 'application/models/params.php';
		$param=new Model_params();
		Zend_Registry::set('param',$param);
		return $param;
	}
	/**
	 * inizializza l'autenticazione
	 */
	protected function _initAuth ()
	{
		$this->bootstrap("db");
		$this->bootstrap('autoload');
		$db = $this->getPluginResource('db')->getDbAdapter();
		$adp = new Zend_Auth_Adapter_DbTable($db);
		$adp->setTableName(PREFIX."user")
		->setIdentityColumn('username')
		->setCredentialColumn('password')
		->setCredentialTreatment('sha1(?)');
		$storage = new Zend_Auth_Storage_Session();
		$auth = Zend_Auth::getInstance();
		$auth->setStorage($storage);
	}
	/**
	 * set language of application
	 */
	protected function _initLanguage ()
	{
		$t = new Zend_Translate_Adapter_Csv(array('content' => APPLICATION_PATH . '/language/en.csv', 'locale' => 'en', 'delimiter' => '@'));
	 $t->addTranslation(
	 		array('content' => APPLICATION_PATH . '/language/it.csv',
	 				'locale' => 'it', 'delimiter' => '@'));
	 	if ($_GET['locale']) {
	 		setcookie('locale',$_GET['locale'],time()+604800,'/');$_COOKIE['locale']=$_GET['locale'];
	 	}
	 	try {
	 		if ($_COOKIE['locale']) $t->setLocale($_COOKIE['locale']);
	 		else $t->setLocale("browser");
	 	}
	 	catch (Zend_Translate_Exception $e)
	 	{
	 		$t->setLocale("en");
	 	}
	 	Zend_Validate_Abstract::setDefaultTranslator($t);
	 	Zend_Form::setDefaultTranslator($t);
	 	Zend_Registry::set('translate', $t);
	 	return $t;
	}
	
	/**
	 * log
	 */
	protected function _initLog ()
	{
		$this->bootstrap('db');
		$this->bootstrap("Controller");
		$this->bootstrap('Autoload');
		$this->bootstrap('view');
		$this->bootstrap('layout');
		$this->bootstrap('config');
		$acl = Zend_Registry::get("acl");
		$db = $this->getPluginResource('db')->getDbAdapter();
		$log = new Zend_Log();
		//$firebug = new Zend_Log_Writer_Firebug();
		$web=new Plugin_Logweb();
		$formatter = new Zend_Log_Formatter_Xml();
		$file = new Zend_Log_Writer_Stream(
				APPLICATION_PATH . "/log/log" . date("Ymd") . ".txt");
		if ($this->getResource('config')->toolraider->debug) {
			$file2 = new Zend_Log_Writer_Stream(
				APPLICATION_PATH . "/log/debug" . date("Ymd") . ".txt");
			$file2->addFilter(new Zend_Log_Filter_Priority(Zend_Log::DEBUG,"=="));
			$file2->setFormatter($formatter);
			$log->addWriter($file2);
		}
		$file->addFilter(new Zend_Log_Filter_Priority(Zend_Log::DEBUG,"!="));
		$role = Model_role::getRole();
		$file->setFormatter($formatter);
		if ((APPLICATION_ENV != "production") || ($acl->isAllowed($role, "debug"))) {
			$log->addWriter($web);
			//profilazione query
			/*$profiler = new Zend_Db_Profiler_Firebug(
					'All DB Queries');
			$profiler->setEnabled(true);
			$db->setProfiler($profiler);*/
		} /*else {
		$filter = new Zend_Log_Filter_Priority(Zend_Log::INFO);
		$log->addFilter($filter);
		}*/
		$log->addWriter($file);
		Zend_Registry::set('log', $log);
		$view=$this->getResource('view');
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
				'ViewRenderer');
		$view->log=$log;
		$viewRenderer->setView($view);
		return $log;
	}
	/**
	 * applica i plugin acl
	 *
	 */
	 protected function _initController ()
	 {
	 	require_once 'application/plugin/acl_controller.php';
	 	$acl = null;
	 	include_once (APPLICATION_PATH . "/models/acl.php");
	 	$front = Zend_Controller_Front::getInstance();
	 	$front->registerPlugin(new plugin_acl_controller($acl));
	 	Zend_Registry::set("acl", $acl);
	 }
	 protected function _initView()
	 {
	 // Initialize view
	 	$this->bootstrap('config');
	 	$view = new Zend_View();
	 	//include_once APPLICATION_PATH . "/views/helpers/Image.php";
	 	include_once APPLICATION_PATH . "/views/helpers/Template.php";
	 	//$img = new Zend_View_Helper_image();
	 	$tmp = new Zend_View_Helper_template();
	 //$mymenu=new Zend_View_Helper_MyMenu();
	 	//$view->registerHelper($img, "image");
	 	$view->registerHelper($tmp, "template");
	 //$view->registerHelper($mymenu, "MyMenu");
	 	$this->bootstrap("Language");
	 //$this->bootstrap("costomlayout");
	 //$layout=$this->getResource("costomlayout");
	 	$view->t = $this->getResource("Language");

	 // Add it to the ViewRenderer
	 	$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
	 'ViewRenderer');
	 //$layout->setView($view);
	 	$viewRenderer->setView($view);
	 // Return it, so that it can be stored by the bootstrap
	 	return $view;
	 }
	 /**
	 * configurazione parametri sito
	 */
	protected function _initConfig ()
	{
		//error_reporting(E_ERROR | E_WARNING | E_PARSE);
		$config = new Zend_Config($this->getOptions());
		Zend_Registry::set('config', $config->toolraider);
		//carico le costanti del server
		return $config;
	}

}

?>