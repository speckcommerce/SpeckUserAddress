<?php

return array(
    'speckuseraddress' => array(
        'indexRoute' => 'zfcuser/address',
    ),

    'service_manager' => array(
        'aliases' => array(
            'speckuseraddress_db_adapter' => 'Zend\Db\Adapter\Adapter'
        ),
    ),

    'controllers' => array(
        'invokables' => array(
            'speckuseraddress' => 'SpeckUserAddress\Controller\UserAddressController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'child_routes' => array(
                    'address' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => '/address',
                            'defaults' => array(
                                'controller'    => 'speckuseraddress',
                                'action'        => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'add' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/add',
                                    'defaults' => array(
                                        'controller' => 'speckuseraddress',
                                        'action'     => 'add'
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/edit',
                                    'defaults' => array(
                                        'controller' => 'speckuseraddress',
                                        'action'     => 'edit',
                                    ),
                                ),
                                'may_terminate' => false,
                                'child_routes' => array(
                                    'query' => array(
                                        'type' => 'Query',
                                    ),
                                ),
                            ),
                            'delete' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/delete',
                                    'defaults' => array(
                                        'controller' => 'speckuseraddress',
                                        'action'     => 'delete',
                                    ),
                                ),
                                'may_terminate' => false,
                                'child_routes' => array(
                                    'query' => array(
                                        'type' => 'Query',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
