<?php

namespace MagentoHackathon\BatchApi\Api;

interface BatchCustomerRepositoryInterface
{

    /**
     * Save or create customers in batch
     *
     * @param \Magento\Customer\Api\Data\CustomerInterface[] $customers
     * @param string[] $passwords
     * @return \MagentoHackathon\BatchApi\Api\Data\BatchResultInterface
     */
    public function save($customers, $passwords = null);
}
