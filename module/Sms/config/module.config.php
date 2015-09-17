<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Sms\Controller\Sms' => 'Sms\Controller\SmsController',
        ),
    ),
        // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'sms' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/sms[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Sms\Controller\Sms',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    
    
     'view_manager' => array(
//       'template_map' => array(
//            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
//         ),
        'template_path_stack' => array(
            'sms' =>  __DIR__ . '/../view',
        ),
    ),
    
    
   
    'view_helpers' => array(
        'invokables'=> array(
            'sms_helper' => 'Sms\View\Helper\Smshelper'  
        )
    ),
);