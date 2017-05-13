<?php

namespace MagentoHackathon\BatchApi\Api;

/**
 * Class BatchCategoryRepositoryInterface
 *
 * @package \MagentoHackathon\BatchApi\Api
 */
interface BatchCategoryRepositoryInterface
{
    /**
     * @param \Magento\Catalog\Api\Data\CategoryInterface[] $categories
     *
     * @return \MagentoHackathon\BatchApi\Api\Data\BatchResultInterface
     */
    public function save($categories);

    /**
     * Delete categories in batch
     *
     * @param array $categoryIds
     * @return \MagentoHackathon\BatchApi\Api\Data\BatchResultInterface
     */
    public function deleteByIdentifiers($categoryIds);
}
