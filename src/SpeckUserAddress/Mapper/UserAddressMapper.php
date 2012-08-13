<?php

namespace SpeckUserAddress\Mapper;

use SpeckAddress\Mapper\AddressMapper;

use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

use ZfcBase\Mapper\AbstractDbMapper;

class UserAddressMapper extends AddressMapper
{
    public function getUserAddresses($userId)
    {
        $where = new Where;
        $where->equalTo('user_id', $userId);

        $sql = new Select;
        $sql->from(array('ua' => 'user_addresses'))
            ->join(array('a' => 'address'), 'ua.address_id = a.address_id')
            ->where($where);

        return $this->selectWith($sql);
    }

    public function findByIdAndUser($addressId, $userId)
    {
        $where = new Where;
        $where->equalTo('ua.user_id', $userId)
            ->equalTo('ua.address_id', $addressId);

        $sql = new Select;
        $sql->from(array('ua' => 'user_addresses'))
            ->join(array('a' => 'address'), 'ua.address_id = a.address_id')
            ->where($where);

        return $this->selectWith($sql)->current();
    }

    public function link($user_id, $address_id)
    {
        $data = compact('user_id', 'address_id');

        try {
          $this->insert($data, 'user_addresses');
        } catch (\Exception $e) {
            // already inserted, but that's okay
            return;
        }
    }

    public function unlink($userId, $addressId)
    {
        $adapter = $this->getDbAdapter();
        $statement = $adapter->createStatement();

        $where = new Where;
        $where->equalTo('user_id', $userId)
            ->equalTo('address_id', $addressId);

        $delete = new Delete;
        $delete->from('user_addresses')
            ->where($where);

        $delete->prepareStatement($adapter, $statement);
        $result = $statement->execute();
        return $result;
    }
}
