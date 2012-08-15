<?php

namespace SpeckUserAddress\Controller;

use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserAddressController extends AbstractActionController
{
    protected $userAddressService;
    protected $options;

    public function indexAction()
    {
        $addresses = $this->getUserAddressService()->getAddresses();

        $vm = new ViewModel(array(
            'addresses' => $addresses,
            'editRoute' => 'zfcuser/address/edit/query',
            'deleteRoute' => 'zfcuser/address/delete/query',
            'addRoute' => 'zfcuser/address/add',
        ));

        $vm->setTemplate('speck-address/address/index');
        return $vm;
    }

    public function addAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('speck-address/address/add');

        $form = $this->getAddForm();
        $prg = $this->prg('zfcuser/address/add');

        if ($prg instanceof Response) {
            return $prg;
        } else if ($prg === false) {
            $vm->form = $form;
            return $vm;
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            $vm->form = $form;
            return $vm;
        }

        $this->getUserAddressService()->create($prg);
        return $this->redirect()->toRoute($this->getOptions()->getIndexRoute());
    }

    public function editAction()
    {
        $vm = new ViewModel();
        $vm->setTemplate('speck-address/address/edit');

        $addressId = $this->getRequest()->getQuery()->get('id');
        $form = $this->getEditForm($addressId);
        $prg = $this->prg('zfcuser/address/add');

        if ($prg instanceof Response) {
            return $this->redirect()->toRoute('zfcuser/address/edit/query', array('id' => $addressId));
        } else if ($prg === false) {
            $vm->form = $form;
            return $vm;
        }

        $form->setData($prg);

        if (!$form->isValid()) {
            $vm->form = $form;
            return $vm;
        }

        $this->getUserAddressService()->update($prg);
        return $this->redirect()->toRoute($this->getOptions()->getIndexRoute());
    }

    public function deleteAction()
    {
        $addressId = $this->getRequest()->getQuery()->get('id');

        $this->getUserAddressService()->delete($addressId);
        return $this->redirect()->toRoute($this->getOptions()->getIndexRoute());
    }

    public function getAddForm()
    {
        $form = $this->getServiceLocator()->get('SpeckAddress\Form\Address');
        $form->setInputFilter($this->getServiceLocator()->get('SpeckAddress\Form\AddressFilter'));
        return $form;
    }

    public function getEditForm($id)
    {
        $form = $this->getServiceLocator()->get('SpeckAddress\Form\EditAddress');
        $form->setInputFilter($this->getServiceLocator()->get('SpeckAddress\Form\AddressFilter'));

        $userAddressService = $this->getUserAddressService();
        $form->setAddress($userAddressService->findById($id));

        return $form;
    }

    public function getUserAddressService()
    {
        if (!isset($this->userAddressService)) {
            $this->userAddressService = $this->getServiceLocator()->get('SpeckUserAddress\Service\UserAddress');
        }

        return $this->userAddressService;
    }

    public function setUserAddressService($userAddressService)
    {
        $this->userAddressService = $userAddressService;
        return $this;
    }

    public function getOptions()
    {
        if (!isset($this->options)) {
            $this->options = $this->getServiceLocator()->get('SpeckUserAddress\Options\ModuleOptions');
        }

        return $this->options;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}
