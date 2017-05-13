<?php

namespace MagentoHackathon\BatchApi\Model;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Cms\Api\Data\PageInterface;
use MagentoHackathon\BatchApi\Api\BatchPageRepositoryInterface;
use MagentoHackathon\BatchApi\Api\Data\BatchResultInterfaceFactory;

/**
 * Class BatchPageRepository
 *
 * @package \MagentoHackathon\BatchApi\Model
 */
class BatchPageRepository implements BatchPageRepositoryInterface
{
    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var BatchResultInterfaceFactory
     */
    protected $batchResultFactory;

    public function __construct(
        PageRepositoryInterface $pageRepository,
        BatchResultInterfaceFactory $batchResultFactory
    ) {
        $this->pageRepository = $pageRepository;
        $this->batchResultFactory = $batchResultFactory;
    }

    /**
     * @inheritdoc
     */
    public function save($pages)
    {
        $result = [
            'saved' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => []
        ];

        /** @var PageInterface $page */
        foreach ($pages as $page) {
            try {
                $this->pageRepository->save($page);
                $result['saved']++;
            } catch (\Exception $e) {
                $result['failed']++;
                $result['errors'][] = $e->getMessage();
            }
        }

        $batchResult = $this->batchResultFactory->create(['data' => $result]);

        return $batchResult;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($pageIds)
    {
        $result = [
            'deleted' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => []
        ];

        /** @var int $pageId */
        foreach ($pageIds as $pageId) {
            try {
                $this->pageRepository->deleteById($pageId);
                $result['deleted']++;
            } catch (\Exception $e) {
                $result['failed']++;
                $result['errors'][] = $e->getMessage();
            }
        }

        $batchResult = $this->batchResultFactory->create(['data' => $result]);

        return $batchResult;
    }
}
