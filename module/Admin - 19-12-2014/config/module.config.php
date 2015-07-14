<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
			'Admin\Controller\Player' => 'Admin\Controller\PlayerController',
			'Admin\Controller\PlayerApi' => 'Admin\Controller\PlayerApiController',

        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
           /* 'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'index',
                    ),
                ),
				'may_terminate' => true,
				 'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/:id]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id' => '[a-zA-Z0-9]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ), */
			
			
            'admin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/admin[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Admin',
                        'action'     => 'index',
                    ),
                ),
            ),
			
			'player' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/admin/player[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\Player',
                        'action'     => 'index',
                    ),
                ),
            ),
			'playerapi' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/api/playerapi[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Admin\Controller\PlayerApi',
                        'action'     => 'index',
                    ),
                ),
            ),
        
			
			
			
			/*
			
			'player' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/player[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9]*',
                    ),
                    'defaults' => array(
						'__NAMESPACE__' => 'Admin\Controller',
                        'controller' => 'player',
                        'action' => 'index',
                    ),
                ),
                
            ),
			
			*/
			
			
			
			
			
			
			
			
        ),
    ),	
	/*'view_manager' => array(
        'template_path_stack' => array(
            'SanAuth' => __DIR__ . '/../view',
        ),
    ),*/
	
	
	 'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'Admin/layout'           => __DIR__ . '/../view/layout/layout.phtml',            
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
	

	
);