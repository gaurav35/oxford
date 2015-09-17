<?php

namespace Member;

// Add these import statements:
use Member\Model\Member;
use Member\Model\MemberTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module {



    public function getAutoloaderConfig() {
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

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    // getAutoloaderConfig() and getConfig() methods here
    // Add this method:
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Member\Model\MemberTable' => function($sm) {
            $tableGateway = $sm->get('MemberTableGateway');
            $table = new MemberTable($tableGateway);
            return $table;
        },
                'MemberTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Member());
            return new TableGateway('Member', $dbAdapter, null, $resultSetPrototype);
        },
            ),
        );
    }


}
