<?php

use Dot\Hydrator\ClassMethodsCamelCase;
use Dot\User\Service\PasswordCheck;
use Frontend\User\Entity\UserEntity;

return [
    'dot_authentication' => [
        'adapter' => [
            'type' => 'DoctrineAdapter',
            'options' => [
                'adapter' => 'database',

                // 'identity_prototype' => UserEntity::class,
                'identity_hydrator' => ClassMethodsCamelCase::class,
                'identity_prototype' => \Dot\User\Entity\UserEntity::class,
                // 'identity_prototype' => \Frontend\User\Entity\UserEntity::class,

                'table' => 'user',

                'identity_columns' => ['username', 'email'],
                'credential_column' => 'password',

                'callback_check' => PasswordCheck::class
            ]
        ],
        'storage' => [
            'type' => 'Session',
            'options' => [
                'namespace' => 'frontend_authentication',
                'member' => 'storage',
            ]
        ],

        'adapter_manager' => [
            //register custom adapters here, like you would do in a normal container
        ],

        'storage_manager' => [
            //register custom storage adapters
        ],

        'resolver_manager' => [
            //define custom http authentication resolvers here
        ]
    ]
];
