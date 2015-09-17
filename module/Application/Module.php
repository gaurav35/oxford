<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService as AuthenticationService;


class Module {

    protected $whitelist = array(
        'login'
    );

    public function onBootstrap($e) {
        $app = $e->getApplication();
        $em = $app->getEventManager();
        $sm = $app->getServiceManager();

        $list = $this->whitelist;
        $auth = new AuthenticationService();
        
        
        
        $em  = $e->getApplication()->getEventManager();
        $em->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
        $controller      = $e->getTarget();
        $controllerClass = get_class($controller);
        $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $config          = $e->getApplication()->getServiceManager()->get('config');
        if (isset($config['module_layouts'][$moduleNamespace])) {
            $controller->layout($config['module_layouts'][$moduleNamespace]);
        }
    }, 100);
    $moduleRouteListener = new ModuleRouteListener();
    $moduleRouteListener->attach($em);
        
        

        $em->attach(MvcEvent::EVENT_ROUTE, function($e) use ($list, $auth) {
            $match = $e->getRouteMatch();

            // No route match, this is a 404
            if (!$match instanceof RouteMatch) {
                return;
            }

            // Route is whitelisted
            $name = $match->getMatchedRouteName();
            if (in_array($name, $list)) {
                return;
            }

            // User is authenticated
            if ($auth->hasIdentity()) {
                return;
            }

            // Redirect to the user login page, as an example
            $router = $e->getRouter();
            $url = $router->assemble(array(), array(
                'name' => 'login'
            ));

            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);

            return $response;
        }, -100);



        /* Flash Manager Code */
     // Show flashmessages in the view
    $em->attach(MvcEvent::EVENT_RENDER, function($e) {
        $flashMessenger = new \Zend\Mvc\Controller\Plugin\FlashMessenger();
 
        $messages = array();
 
        $flashMessenger->setNamespace('success');
        if ($flashMessenger->hasMessages()) {
            $messages['success'] = $flashMessenger->getMessages();
        }
        $flashMessenger->clearMessages();
 
        $flashMessenger->setNamespace('info');
        if ($flashMessenger->hasMessages()) {
            $messages['info'] = $flashMessenger->getMessages();
        }
        $flashMessenger->clearMessages();
 
        $flashMessenger->setNamespace('default');
        if ($flashMessenger->hasMessages()) {
                $messages['warning'] = $flashMessenger->getMessages();
            }
       $flashMessenger->clearMessages();
 
       $flashMessenger->setNamespace('error');
        if ($flashMessenger->hasMessages()) {
            $messages['danger'] = $flashMessenger->getMessages();
        }
        $flashMessenger->clearMessages();
 
        $e->getViewModel()->setVariable('flashMessages', $messages);
    });
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
