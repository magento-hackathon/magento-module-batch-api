<?php

namespace MagentoHackathon\BatchApi\Model;
use MagentoHackathon\BatchApi\Api\BatchStockRegistryInterface;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use MagentoHackathon\BatchApi\Api\Data\BatchResultInterfaceFactory;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Framework\Exception\InputException;

/**
 * Class BatchPageRepository
 *
 * @package \MagentoHackathon\BatchApi\Model
 */
class BatchStockRegistry implements BatchStockRegistryInterface
{
    /**
     * @var StockRegistryInterface
     */
    protected $stockRegistry;

    /**
     * @var BatchResultInterfaceFactory
     */
    protected $batchResultFactory;

    public function __construct(
        StockRegistryInterface $stockRegistry,
        BatchResultInterfaceFactory $batchResultFactory
    ) {
        $this->stockRegistry = $stockRegistry;
        $this->batchResultFactory = $batchResultFactory;
    }

    /**
     * @inheritDoc
     */
    public function updateStockItemsBySkus($productSkus, $stockItems)
    {
        $result = [
            'saved' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => []
        ];

        $skuSize = count($productSkus);

        if ($skuSize !== count($stockItems)) {
            throw new InputException('The SKU and stockItem arrays need to be equal in size');
        }

        for ($i=0; $i<$skuSize; ++$i) {
            try {
                /** @var string $productSku */
                $productSku = $productSkus[$i];

                /** @var StockItemInterface $stockItem */
                $stockItem = $stockItems[$i];

                $this->stockRegistry->updateStockItemBySku($productSku, $stockItem);
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
