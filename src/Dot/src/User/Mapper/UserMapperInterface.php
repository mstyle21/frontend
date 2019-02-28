<?php
/**
 * @see https://github.com/dotkernel/dot-user/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-user/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\User\Mapper;

use Dot\Mapper\Entity\EntityInterface;
use Dot\Mapper\Mapper\MapperInterface;
use Dot\User\Entity\UserEntity;
use Zend\Hydrator\HydratorInterface;

/**
 * Interface UserMapperInterface
 * @package Dot\User\Mapper
 */
interface UserMapperInterface extends MapperInterface
{
    /**
     * @param string $email
     * @param array $options
     * @return UserEntity|null
     */
    public function getByEmail(string $email, array $options = []): ?UserEntity;

    // from abstract db mapper
    // generic
    public function find(string $type = 'all', array $options = []): array;

    public function count(string $type = 'all', array $options = []): int;

    public function get($primaryKey, array $options = []);

    public function save(EntityInterface $entity, array $options = []);

    public function delete(EntityInterface $entity, array $options = []);

    public function deleteAll(array $conditions);

    public function updateAll(array $fields, array $conditions);

    public function load(array $data, array $options = []);

    // required for transactions
    public function beginTransaction();

    public function inTransaction(): bool;

    public function commit();

    public function rollback();

    // is this required ?
    public function getHydrator(): HydratorInterface;

    public function lastGeneratedValue(string $name = null);

    public function newEntity(): EntityInterface;
}
