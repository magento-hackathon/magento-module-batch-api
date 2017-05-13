<?php

namespace MagentoHackathon\BatchApi\Model;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use MagentoHackathon\BatchApi\Api\BatchCustomerRepositoryInterface;
use MagentoHackathon\BatchApi\Api\Data\BatchResultInterface;
use MagentoHackathon\BatchApi\Api\Data\BatchResultInterfaceFactory;

/**
 * Class BatchCustomerRepository
 *
 * @package \MagentoHackathon\BatchApi\Model
 */
class BatchCustomerRepository implements BatchCustomerRepositoryInterface
{

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var BatchResultInterfaceFactory
     */
    protected $batchResultFactory;

    /**
     * @var string
     */
    protected $passwordIdentifierField;

    /**
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $accountManagement
     * @param BatchResultInterfaceFactory $batchResultFactory
     * @param string $passwordIdentifierField
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $accountManagement,
        BatchResultInterfaceFactory $batchResultFactory,
        $passwordIdentifierField = 'email'
    ) {
        $this->customerRepository = $customerRepository;
        $this->accountManagement = $accountManagement;
        $this->batchResultFactory = $batchResultFactory;
        $this->passwordIdentifierField = $passwordIdentifierField;
    }

    /**
     * @inheritdoc
     */
    public function save($customers, $passwords = null)
    {
        $result = [
            'saved' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => 0
        ];

        foreach ($customers as $customer) {
            if ($customer->getId()) {
                try {
                    $this->customerRepository->save($customer);
                    $result['saved']++;
                } catch (\Exception $e) {
                    $result['failed']++;
                    $result['errors'][] = $e->getMessage();
                }
            } else {
                $passwordIdentifierMethod = 'get' . ucfirst($this->passwordIdentifierField);
                $passwordIdentifier = $customer->$passwordIdentifierMethod();
                $password = isset($passwords[$passwordIdentifier]) ? $passwords[$passwordIdentifier] : null;
                try {
                    $this->accountManagement->createAccount($customer, $password);
                    $result['saved']++;
                } catch (\Exception $e) {
                    $result['failed']++;
                    $result['errors'][] = $e->getMessage();
                }
            }
        }

        /** @var BatchResultInterface $batchResult */
        $batchResult = $this->batchResultFactory->create(['data' => $result]);

        return $batchResult;
    }

    /**
     * @inheritdoc
     */
    public function deleteByIds($customerIds)
    {
        $result = [
            'deleted' => 0,
            'failed' => 0,
            'errors' => []
        ];

        foreach ($customerIds as $customerId) {
            try {
                $this->customerRepository->deleteById($customerId);
                $result['deleted']++;
            } catch (\Exception $e) {
                $result['failed']++;
                $result['errors'][] = $e->getMessage();
            }
        }

        /** @var BatchResultInterface $batchResult */
        $batchResult = $this->batchResultFactory->create(['data' => $result]);
        return $batchResult;
    }
}
