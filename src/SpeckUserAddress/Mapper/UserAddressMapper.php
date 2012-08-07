<?php

namespace SpeckUserAddress\Mapper;

use ZfcBase\Mapper\AbstractDbMapper;

class UserAddressMapper extends AbstractDbMapper
{
    public function link($user_id, $address_id)
    {
        $data = compact('user_id', 'address_id');

        //try {
            $this->insert($data, 'user_addresses');
        //} catch (\Exception $e) {
        //    // already inserted, but that's okay
        //    return;
        //}
    }
}
