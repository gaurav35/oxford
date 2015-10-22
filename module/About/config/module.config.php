<?php

return array(
        'controllers' => array(
        'invokables' => array(
            'About\Controller\Index' => 'About\Controller\IndexController',
        ),
    ),
        // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'about' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/about[/][:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'About\Controller\Index',
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
            'about' => __DIR__ . '/../view',
        ),
    ),
);
