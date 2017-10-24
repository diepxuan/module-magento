<?php
/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */
namespace Diepxuan\Magento\Model;

class StoreManager extends \Magento\Store\Model\StoreManager
{

    /**
     * Logger instance
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * {@inheritdoc}
     */
    public function getStore($storeId = null)
    {
        if (!isset($storeId) || '' === $storeId || $storeId === true) {
            if (!$this->currentStoreId) {
                \Magento\Framework\Profiler::start('store.resolve');

                $this->currentStoreId = $this->storeResolver->getCurrentStoreId();
                // $this->getLogger()->addDebug($this->currentStoreId);

                \Magento\Framework\Profiler::stop('store.resolve');
            }
            $storeId = $this->currentStoreId;
        }
        if ($storeId instanceof \Magento\Store\Api\Data\StoreInterface) {
            return $storeId;
        }

        $store = is_numeric($storeId)
        ? $this->storeRepository->getById($storeId)
        : $this->storeRepository->get($storeId);

        return $store;
    }

    /**
     * Get logger instance
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        if (!$this->_logger) {
            $this->_logger = \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class);
        }
        return $this->_logger;
    }

}
