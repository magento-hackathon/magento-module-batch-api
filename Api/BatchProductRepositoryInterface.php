<?php

namespace MagentoHackathon\BatchApi\Api;

/**
 * Class BatchProductRepositoryInterface
 *
 * @package \MagentoHackathon\BatchApi\Api
 */
interface BatchProductRepositoryInterface
{

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface[] $products
     *
     * @return \MagentoHackathon\BatchApi\Api\Data\BatchResultInterface
     */
    public function save($products);
}
