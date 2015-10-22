<?php

return array(
        'controllers' => array(
        'invokables' => array(
            'Contact\Controller\Index' => 'Contact\Controller\IndexController',
        ),
    ),
        // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'contact' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/contact[/][:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Contact\Controller\Index',
                        'action' => 'index',
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
            'contact' => __DIR__ . '/../view',
        ),
    ),
);
