<?php

namespace MagentoHackathon\BatchApi\Api;

/**
 * Class BatchStockRegistryInterface
 *
 * @package \MagentoHackathon\BatchApi\Api
 */
interface BatchStockRegistryInterface
{
    /**
     * @param string[] $productSkus
     * @param \Magento\CatalogInventory\Api\Data\StockItemInterface[] $stockItems
     * @return \MagentoHackathon\BatchApi\Api\Data\BatchResultInterface
     * @throws \Magento\Framework\Exception\InputException
     */
    public function updateStockItemsBySkus($productSkus, $stockItems);
}
