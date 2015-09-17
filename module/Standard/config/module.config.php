<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Standard\Controller\Standard' => 'Standard\Controller\StandardController',
        ),
    ),
        // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'standard' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/standard[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Standard\Controller\Standard',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    
    'view_manager' => array(
         //'template_map' => array(
        // use Applications layout insteadApplication\view\layout__DIR__ . 
       // 'layout/layout' => __DIR__ . '/../../Application/view/layout/layout.phtml',
    //),
        'template_path_stack' => array(
            'standard' => __DIR__ . '/../view',
        ),
    ),
);