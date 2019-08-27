<?php
/**
 * Copyright © Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Magento\Plugin\App;

use Diepxuan\Magento\Model\StoreSwitch;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class FrontController
 * @package Diepxuan\Magento\Plugin\App
 * @deprecated 0.0.2.4
 */
class FrontController {
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
     * FrontController constructor.
     *
     * @param \Diepxuan\Magento\Model\StoreSwitch $storeSwitch
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        StoreSwitch $storeSwitch,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->storeSwitch  = $storeSwitch;
        $this->storeManager = $storeManager;
        $this->logger       = $logger;
    }

    /**
     * @param \Magento\Framework\App\FrontController $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\App\RequestInterface $request
     *
     * @return mixed
     * @deprecated 0.0.2.4
     */
    public function aroundDispatch(
        \Magento\Framework\App\FrontController $subject,
        \Closure $proceed,
        RequestInterface $request
    ) {
        if ( $this->notNeededProcess() ) {
            $this->storeManager->setCurrentStore( $this->storeSwitch->getStoreId() );
        }

        return $proceed( $request );
    }

    /**
     * @return bool
     */
    protected function isNeededProcess() {
        return ! $this->storeSwitch->isInitialized();
    }

    /**
     * @return bool
     */
    protected function notNeededProcess() {
        return true;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger() {
        return $this->logger;
    }

}
