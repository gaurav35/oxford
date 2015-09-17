<?php
namespace Sms;

// Add these import statements:
use Sms\Model\Sms;
use Sms\Model\SmsTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;

class Module
{
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    // getAutoloaderConfig() and getConfig() methods here

    // Add this method:
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Sms\Model\SmsTable' =>  function($sm) {
                    $tableGateway = $sm->get('SmsTableGateway');
                    $table = new SmsTable($tableGateway);
                    return $table;
                },
                'SmsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Sms());
                    return new TableGateway('sms_template', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'sms_helper' => function($sm) {
                    $helper = new View\Helper\Smshelper ;
                    return $helper;
                }
            )
        );   
   }
//    public function onBootstrap($e)
//    {
//        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
//            $controller      = $e->getTarget();
//            $controllerClass = get_class($controller);
//            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
//            $config          = $e->getApplication()->getServiceManager()->get('config');
//
//            $routeMatch = $e->getRouteMatch();
//            $actionName = strtolower($routeMatch->getParam('action', 'not-found')); // get the action name
//
//            if (isset($config['module_layouts'][$moduleNamespace][$actionName])) {
//                $controller->layout($config['module_layouts'][$moduleNamespace][$actionName]);
//            }elseif(isset($config['module_layouts'][$moduleNamespace]['sidebar'])) {
//                $controller->layout($config['module_layouts'][$moduleNamespace]['sidebar']);
//            }
//
//        }, 100);
//    }
}