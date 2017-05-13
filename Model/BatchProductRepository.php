<?php

namespace MagentoHackathon\BatchApi\Model;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use MagentoHackathon\BatchApi\Api\BatchProductRepositoryInterface;
use MagentoHackathon\BatchApi\Api\Data\BatchResultInterface;
use MagentoHackathon\BatchApi\Api\Data\BatchResultInterfaceFactory;

/**
 * Class BatchProductRepository
 *
 * @package \MagentoHackathon\BatchApi\Model
 */
class BatchProductRepository implements BatchProductRepositoryInterface
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var BatchResultInterfaceFactory
     */
    protected $batchResultFactory;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        BatchResultInterfaceFactory $batchResultFactory
    ) {
        $this->productRepository = $productRepository;
        $this->batchResultFactory = $batchResultFactory;
    }

    /**
     * @inheritdoc
     */
    public function save($products)
    {
        $result = [
            'saved' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => []
        ];

        foreach ($products as $product) {
            try {
                $this->productRepository->save($product);
                $result['saved']++;
            } catch (\Exception $e) {
                $result['failed']++;
                $result['errors'][] = $e->getMessage();
            }
        }

        $batchResult = $this->batchResultFactory->create(['data' => $result]);

        return $batchResult;
    }
}
