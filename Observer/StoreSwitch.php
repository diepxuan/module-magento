<?php
/**
 * Copyright © Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Magento\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class StoreSwitch
 * @package Diepxuan\Magento\Observer
 * @deprecated
 */
class StoreSwitch implements ObserverInterface
{
    /**
     * @var \Diepxuan\Magento\Model\StoreSwitch
     */
    protected $storeSwitch;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * StoreSwitch constructor.
     *
     * @param \Diepxuan\Magento\Model\StoreSwitch        $storeSwitch
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface                   $logger
     */
    public function __construct(
        \Diepxuan\Magento\Model\StoreSwitch $storeSwitch,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->storeSwitch  = $storeSwitch;
        $this->storeManager = $storeManager;
        $this->logger       = $logger;
    }

    /**
     * execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(Observer $observer)
    {
        if (!$this->storeSwitch->isInitialized()) {
            $this->storeManager->setCurrentStore($this->storeSwitch->getStoreId());
        }
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

}
