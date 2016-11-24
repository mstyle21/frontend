<?php
/**
 * @copyright: DotKernel
 * @library: dotkernel/dot-frontend
 * @author: n3vrax
 * Date: 7/18/2016
 * Time: 9:55 PM
 */

namespace Dot\Frontend\User\Entity;

use Dot\Ems\Entity\IgnorePropertyProvider;

/**
 * Class UserDetailsEntity
 * @package Dot\Frontend\User\Entity
 */
class UserDetailsEntity implements \JsonSerializable , IgnorePropertyProvider
{
    /** @var  int */
    protected $userId;

    /** @var  string */
    protected $firstName;

    /** @var  string */
    protected $lastName;

    /** @var  string */
    protected $phone;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return UserDetailsEntity
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return UserDetailsEntity
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return UserDetailsEntity
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return UserDetailsEntity
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    public function ignoredProperties()
    {
        return [];
    }

}