<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Model\Payment;
use Application\Model\PaymentTable;

use Application\Model\HuntClub;
use Application\Model\HuntClubTable;

use Application\Model\HuntClubGame;
use Application\Model\HuntClubGameTable;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;


use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;










class Module implements AutoloaderProviderInterface
{
    


    public function getConfig()
    {
	
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
		 'Zend\Loader\ClassMapAutoloader' => array(
            __DIR__ . '/autoload_classmap.php',
        ),
		
		
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
		    // Add this method:
    public function getServiceConfig()
    {
	
	        
        
        return array(
            'factories' => array(
			
                'Application\Model\PaymentTable' =>  function($sm) {
                    $tableGateway = $sm->get('PaymentTableGateway');
                    $table = new PaymentTable($tableGateway);
                    return $table;
                },
                'PaymentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Payment());
                    return new TableGateway('payments_history', $dbAdapter, null, $resultSetPrototype);
                },
				
				'Application\Model\HuntClubTable' =>  function($sm) {
                    $tableGateway = $sm->get('HuntClubTableGateway');
                    $table = new HuntClubTable($tableGateway);
                    return $table;
                },
                'HuntClubTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new HuntClub());
                    return new TableGateway('hunt_clubs', $dbAdapter, null, $resultSetPrototype);
                },
				
				'Application\Model\HuntClubGameTable' =>  function($sm) {
                    $tableGateway = $sm->get('HuntClubGameTableGateway');
                    $table = new HuntClubGameTable($tableGateway);
                    return $table;
                },
                'HuntClubGameTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new HuntClubGame());
                    return new TableGateway('hunt_club_game', $dbAdapter, null, $resultSetPrototype);
                },				
				
				
				
				
				
				
				
				

            ),
			
        );
    }
	
	
	
	
	public function initSession($config)
	{
		$sessionConfig = new SessionConfig();
		$sessionConfig->setOptions($config);
		$sessionManager = new SessionManager($sessionConfig);
		$sessionManager->start();
		Container::setDefaultManager($sessionManager);
	}
	
	public function onBootstrap(MvcEvent $e)
    {
		
		$eventManager        = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
		
		$this->initSession(array(
		'remember_me_seconds' => 180,
		'use_cookies' => true,
		'cookie_httponly' => true,
		));
		
		
		
			
			$eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
			$controller      = $e->getTarget();
			$controllerClass = get_class($controller);
			$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
			//echo  $controllerClass ;  exit;
			$config          = $e->getApplication()->getServiceManager()->get('config');
			//echo  $config['module_layouts'][$moduleNamespace] ; exit ; 
			//echo '<pre>'; print_r(	$config ); exit;
			
						if (isset($config['module_layouts'][$moduleNamespace])) {
								$controller->layout($config['module_layouts'][$moduleNamespace]);
						}
			}, 100);
			
		/*
			$e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
			$controller = $e->getTarget();
			$controllerClass = get_class($controller);
			$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
			//echo $moduleNamespace ; exit;
			$controller->layout($moduleNamespace . '/layout');
			}, 100);
		*/
	
		
    }
	
	
	
	
	
	
	
	
	
	
	
	
}
