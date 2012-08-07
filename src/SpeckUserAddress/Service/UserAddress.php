<?php

namespace SpeckUserAddress\Service;

use RuntimeException;

use SpeckAddress\Service\AddressEvent;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class UserAddress implements ServiceManagerAwareInterface
{
    protected $sm;
    protected $mapper;

    public function onAddAddress(AddressEvent $e)
    {
        $authService = $this->sm->get('zfcuser_user_service')->getAuthService();

        if (!$authService->hasIdentity()) {
            throw new RuntimeException("No authorized user");
        }

        $userId = $authService->getIdentity()->getId();
        $addressId = $e->getAddress()->getAddressId();

        $this->mapper->link($userId, $addressId);
    }

    public function attachDefaultListeners()
    {
        $events = $this->sm->get('SpeckAddress\Service\Address')->getEventManager();
        $events->attach(AddressEvent::EVENT_ADD_ADDRESS_POST, array($this, 'onAddAddress'));
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
}
