<?php

namespace MagentoHackathon\BatchApi\Model;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use MagentoHackathon\BatchApi\Api\BatchCategoryRepositoryInterface;
use MagentoHackathon\BatchApi\Api\Data\BatchResultInterfaceFactory;

/**
 * Class BatchCategoryRepository
 *
 * @package \MagentoHackathon\BatchApi\Model
 */
class BatchCategoryRepository implements BatchCategoryRepositoryInterface
{

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var BatchResultInterfaceFactory
     */
    protected $batchResultFactory;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        BatchResultInterfaceFactory $batchResultFactory
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->batchResultFactory = $batchResultFactory;
    }

    /**
     * @inheritdoc
     */
    public function save($categories)
    {
        $result = [
            'saved' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => []
        ];

        /** @var CategoryInterface $category */
        foreach ($categories as $category) {
            try {
                $this->categoryRepository->save($category);
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
