<?php
namespace Admin;

// Add these import statements:
use Admin\Model\Admin;
use Admin\Model\AdminTable;

use Admin\Model\Player;
use Admin\Model\PlayerTable;


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
			
                'Admin\Model\AdminTable' =>  function($sm) {
                    $tableGateway = $sm->get('AdminTableGateway');
                    $table = new AdminTable($tableGateway);
                    return $table;
                },
                'AdminTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Admin());
                    return new TableGateway('admin', $dbAdapter, null, $resultSetPrototype);
                },
				'Admin\Model\PlayerTable' =>  function($sm) {
                    $tableGateway = $sm->get('PlayerTableGateway');
                    $table = new PlayerTable($tableGateway);
                    return $table;
                },
                'PlayerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Player());
                    return new TableGateway('player', $dbAdapter, null, $resultSetPrototype);
                },

				
				'Admin\Model\MyAuthStorage' => function($sm){
					return new \Admin\Model\MyAuthStorage('adminstore'); 
				},
				
				'AuthService' => function($sm) {
                    //My assumption, you've alredy set dbAdapter
                    //and has users table with columns : user_name and pass_word
                    //that password hashed with md5
									$dbAdapter           = $sm->get('Zend\Db\Adapter\Adapter');
									$dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter,
                                              'admin','email','password', 'MD5(?)');
             
									$authService = new AuthenticationService();
									$authService->setAdapter($dbTableAuthAdapter);
									$authService->setStorage($sm->get('Admin\Model\MyAuthStorage'));
              
									return $authService;
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

	
		public function onBootstrap($e)
		{
		
			$this->initSession(array(
			'remember_me_seconds' => 180,
			'use_cookies' => true,
			'cookie_httponly' => true,
		));


			$e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
			$controller = $e->getTarget();
			$controllerClass = get_class($controller);
			$moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
			$controller->layout($moduleNamespace . '/layout');
			}, 100);
			
			
			
			 //$e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', array($this, 'authPreDispatch'),1);
			  //$e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_ROUTE, $stopCallBack,-10000);
		} 
		
		
		

	
	
	
}
