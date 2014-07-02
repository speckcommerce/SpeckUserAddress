<?php

namespace SpeckUserAddress\Service;

use RuntimeException;

use SpeckAddress\Entity\Address;
use SpeckAddress\Service\AddressEvent;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserAddress implements ServiceManagerAwareInterface
{
    protected $sm;
    protected $mapper;
    protected $userService;

    public function getAddresses()
    {
        $authService = $this->getUserService()->getAuthService();

        if (!$authService->hasIdentity()) {
            throw new RuntimeException("No authorized user");
        }

        $userId = $authService->getIdentity()->getId();

        return $this->getMapper()->getUserAddresses($userId);
    }

    public function findById($addressId)
    {
        $authService = $this->getUserService()->getAuthService();

        if (!$authService->hasIdentity()) {
            throw new RuntimeException("No authorized user");
        }

        $userId = $authService->getIdentity()->getId();

        return $this->getMapper()->findByIdAndUser($addressId, $userId);
    }

    public function create($address, $userId = null)
    {
        $authService = $this->getUserService()->getAuthService();

        if ($userId == null) {
            if (!$authService->hasIdentity()) {
                throw new RuntimeException("No authorized user");
            }

            $userId = $authService->getIdentity()->getId();
        }

        if (is_array($address)) {
            $hydrator = new ClassMethods;
            $address = $hydrator->hydrate($address, new Address);
        }

        $address = $this->getMapper()->persist($address);
        $addressId = $address->getAddressId();

        $this->getMapper()->link($userId, $addressId);
        return $address;
    }

    public function update($address)
    {
        if (is_array($address)) {
            $hydrator = new ClassMethods;
            $address = $hydrator->hydrate($address, new Address);
        }

        if (!$this->findById($address->getAddressId())) {
            throw new RuntimeException('Could not update address');
        }

        $this->getMapper()->persist($address);
        return $address;
    }

    public function delete($address)
    {
        if ($address instanceof Address) {
            $address = $address->getAddressId();
        }

        $authService = $this->getUserService()->getAuthService();

        if (!$authService->hasIdentity()) {
            throw new RuntimeException("No authorized user");
        }

        if (!$this->findById($address)) {
            throw new RuntimeException('Could not update address');
        }

        $userId = $authService->getIdentity()->getId();

        $this->getMapper()->unlink($userId, $address);
        $this->getMapper()->deleteAddress($address);
    }

    public function setServiceManager(ServiceManager $sm)
    {
        $this->sm = $sm;
        return $this;
    }

    public function getMapper()
    {
        return $this->mapper;
    }

    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    public function getUserService()
    {
        return $this->userService;
    }

    public function setUserService($userService)
    {
        $this->userService = $userService;
        return $this;
    }
}
