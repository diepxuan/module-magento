<?php

namespace Diepxuan\Magento\Observer;

/**
 * Class StoreSwitch
 * @package Diepxuan\Magento\Observer
 * @deprecated
 */
class StoreSwitch implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Diepxuan\Magento\Model\StoreSwitch
     */
    protected $storeSwitch;

    /**
     * StoreSwitch constructor.
     *
     * @param \Diepxuan\Magento\Model\StoreSwitch $storeSwitch
     */
    public function __construct(
        \Diepxuan\Magento\Model\StoreSwitch $storeSwitch
    ) {
        $this->storeSwitch = $storeSwitch;
    }

    /**
     * execute
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $this->storeSwitch->execute();
    }

}
