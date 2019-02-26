<?php
/**
 * User: gabidj
 * Date: 2019-02-26
 * Time: 15:38
 */

use Doctrine\ORM\Mapping\DefaultEntityListenerResolver;
use Doctrine\ORM\Mapping\EntityListenerResolver;

return [
    'dependencies' => [
        'factories' => [
            'doctrine.entity_manager.orm_default' => \ContainerInteropDoctrine\EntityManagerFactory::class,
        ],
        'invokables' => [
            EntityListenerResolver::class => DefaultEntityListenerResolver::class,
        ],
        'aliases' => [
            \Doctrine\ORM\EntityManager::class => 'doctrine.entity_manager.orm_default',
            \Doctrine\ORM\EntityManagerInterface::class => 'doctrine.entity_manager.default',
            'doctrine.entitymanager.orm_default' => 'doctrine.entity_manager.orm_default'
        ]
    ],

    'doctrine' => [
        'driver' => [
            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [],
            ],
        ],
    ],
];