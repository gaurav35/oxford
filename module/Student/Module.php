<?php
namespace Student;

// Add these import statements:
use Student\Model\Student;
use Student\Model\StudentTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
//use Zend\Mvc\ModuleRouteListener;

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
                'Student\Model\StudentTable' =>  function($sm) {
                    $tableGateway = $sm->get('StudentTableGateway');
                    $table = new StudentTable($tableGateway);
                    return $table;
                },
                'StudentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Student());
                    return new TableGateway('student_detail', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
    
//     public function onBootstrap($e)
//        {
//                $eventManager    = $e->getApplication()->getEventManager();
//                $eventManager->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
//                $controller      = $e->getTarget();
//                $controllerClass = get_class($controller);
//                $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
//                $config          = $e->getApplication()->getServiceManager()->get('config');
//                if (isset($config['module_layouts'][$moduleNamespace])) {
//                    $controller->layout($config['module_layouts'][$moduleNamespace]);
//                }
//            }, 100);
//            $moduleRouteListener = new ModuleRouteListener();
//            $moduleRouteListener->attach($eventManager);
//        }
    
   }