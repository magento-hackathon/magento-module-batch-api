<?php

namespace MagentoHackathon\BatchApi\Model;
use Magento\CatalogInventory\Model\StockRegistry;
use MagentoHackathon\BatchApi\Api\BatchStockRegistryInterface;
use Magento\CatalogInventory\Api\StockConfigurationInterface;
use Magento\CatalogInventory\Api\StockItemRepositoryInterface;
use Magento\CatalogInventory\Model\Spi\StockRegistryProviderInterface;
use Magento\CatalogInventory\Api\StockItemCriteriaInterfaceFactory;
use Magento\Catalog\Model\ProductFactory;
use MagentoHackathon\BatchApi\Api\Data\BatchResultInterfaceFactory;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Framework\Exception\InputException;

/**
 * Class BatchPageRepository
 *
 * @package \MagentoHackathon\BatchApi\Model
 */
class BatchStockRegistry extends StockRegistry implements BatchStockRegistryInterface
{
    /**
     * @var BatchResultInterfaceFactory
     */
    protected $batchResultFactory;

    public function __construct(
        StockConfigurationInterface $stockConfiguration,
        StockRegistryProviderInterface $stockRegistryProvider,
        StockItemRepositoryInterface $stockItemRepository,
        StockItemCriteriaInterfaceFactory $criteriaFactory,
        ProductFactory $productFactory,
        BatchResultInterfaceFactory $batchResultFactory
    ) {
        parent::__construct($stockConfiguration, $stockRegistryProvider, $stockItemRepository, $criteriaFactory, $productFactory);
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

                $productId = $this->resolveProductId($productSku);
                $websiteId = $stockItem->getWebsiteId() ?: null;
                $origStockItem = $this->getStockItem($productId, $websiteId);
                $data = $stockItem->getData();
                if ($origStockItem->getItemId()) {
                    unset($data['item_id']);
                }
                $origStockItem->addData($data);
                $origStockItem->setProductId($productId);
                $this->stockItemRepository->save($origStockItem)->getItemId();

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
