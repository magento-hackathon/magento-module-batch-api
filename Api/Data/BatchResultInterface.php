<?php

namespace MagentoHackathon\BatchApi\Api\Data;

/**
 * Class BatchResultInterface
 *
 * @package \MagentoHackathon\BatchApi\Api\Data
 */
interface BatchResultInterface
{
    const KEY_SAVED = 'saved';
    const KEY_DELETED = 'deleted';
    const KEY_SKIPPED = 'skipped';
    const KEY_FAILED = 'failed';
    const KEY_ERRORS = 'errors';

    /**
     * @return int
     */
    public function getSaved();

    /**
     * @param int $saved
     *
     * @return $this
     */
    public function setSaved($saved);

    /**
     * @return int
     */
    public function getSkipped();

    /**
     * @param int $skipped
     *
     * @return $this
     */
    public function setSkipped($skipped);

    /**
     * @return int
     */
    public function getFailed();

    /**
     * @param int $failed
     *
     * @return $this
     */
    public function setFailed($failed);

    /**
     * @return string[]
     */
    public function getErrors();

    /**
     * @param string[] $errors
     *
     * @return $this
     */
    public function setErrors($errors);

    /**
     * @return string[]
     */
    public function getDeleted();

    /**
     * @param string[] $deleted
     *
     * @return $this
     */
    public function setDeleted($deleted);
}
