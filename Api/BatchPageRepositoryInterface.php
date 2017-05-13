<?php

namespace MagentoHackathon\BatchApi\Api;

/**
 * Class BatchPageRepositoryInterface
 *
 * @package \MagentoHackathon\BatchApi\Api
 */
interface BatchPageRepositoryInterface
{
    /**
     * @param \Magento\Cms\Api\Data\PageInterface[] $pages
     * @return \MagentoHackathon\BatchApi\Api\Data\BatchResultInterface
     */
    public function save($pages);

    /**
     * @param array $pageIds
     * @return \MagentoHackathon\BatchApi\Api\Data\BatchResultInterface
     */
    public function deleteByIds($pageIds);
}
