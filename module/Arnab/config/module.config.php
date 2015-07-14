<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Arnab\Controller\Index' => 'Arnab\Controller\IndexController',
			
        ),
    ),

    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(    			
	           'arnab' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/arnab/index[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Arnab\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
			
		
			
        ),
    ),	

	 'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'Arnab/layout'           => __DIR__ . '/../view/layout/layout.phtml',            
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
	

	
);