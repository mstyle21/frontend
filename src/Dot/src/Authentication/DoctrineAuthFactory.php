<?php
/**
 * User: gabidj
 * Date: 2019-02-18
 * Time: 17:19
 */

namespace Dot\Authentication;

use Doctrine\ORM\EntityManager;
use Dot\Authentication\Factory\AbstractAdapterFactory;
use Psr\Container\ContainerInterface;

class DoctrineAuthFactory extends AbstractAdapterFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null)
    {
        $options = $options ?? [];
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entity_manager.orm_default');

        // if (is_string($options['identity_prototype'] ?? null))
        // means if is set and is a string, equivalent to isset & is_string
        if (is_string($options['identity_prototype'] ?? null)) {
            $options['identity_prototype'] = new $options['identity_prototype'];
        }
        if (is_string($options['identity_hydrator'] ?? null)) {
            $options['identity_hydrator'] = new $options['identity_hydrator'];
        }

        if (is_string($options['callback_check'] ?? null)) {
            $options['callback_check'] = $container->get($options['callback_check']);
        }

        return new DoctrineAuth($entityManager, $options);
    }
}