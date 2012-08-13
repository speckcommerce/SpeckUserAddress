<?php

namespace SpeckUserAddress;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;

class Module implements AutoloaderProviderInterface
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'SpeckUserAddress\Service\UserAddress' => function($sm) {
                    $service = new Service\UserAddress;
                    $service->setMapper($sm->get('SpeckUserAddress\Mapper\UserAddressMapper'));
                    $service->setUserService($sm->get('zfcuser_user_service'));
                    return $service;
                },

                'SpeckUserAddress\Mapper\UserAddressMapper' => function($sm) {
                    $mapper = new Mapper\UserAddressMapper;
                    $mapper->setDbAdapter($sm->get('speckuseraddress_db_adapter'));
                    return $mapper;
                },
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
