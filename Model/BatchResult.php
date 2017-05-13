<?php

namespace MagentoHackathon\BatchApi\Model;

use Magento\Framework\DataObject;
use MagentoHackathon\BatchApi\Api\Data\BatchResultInterface;

/**
 * Class BatchResult
 *
 * @package \MagentoHackathon\BatchApi\Model
 */
class BatchResult extends DataObject implements BatchResultInterface
{

    /**
     * @inheritdoc
     */
    public function getSaved()
    {
        return $this->getData(self::KEY_SAVED);
    }

    /**
     * @inheritdoc
     */
    public function setSaved($saved)
    {
        return $this->setData(self::KEY_SAVED, $saved);
    }

    /**
     * @inheritdoc
     */
    public function getSkipped()
    {
        return $this->getData(self::KEY_SKIPPED);
    }

    /**
     * @inheritdoc
     */
    public function setSkipped($skipped)
    {
        return $this->setData(self::KEY_SAVED, $skipped);
    }

    /**
     * @inheritdoc
     */
    public function getFailed()
    {
        return $this->getData(self::KEY_FAILED);
    }

    /**
     * @inheritdoc
     */
    public function setFailed($failed)
    {
        return $this->setData(self::KEY_FAILED, $failed);
    }

    /**
     * @inheritdoc
     */
    public function getErrors()
    {
        return $this->getData(self::KEY_ERRORS);
    }

    /**
     * @inheritdoc
     */
    public function setErrors($errors)
    {
        return $this->setData(self::KEY_ERRORS, $errors);
    }
}
