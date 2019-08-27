<?php
/**
 * Copyright © Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Magento\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\BaseUrlChecker;

/**
 * Class StoreSwitch
 * @package Diepxuan\Magento\Model
 */
class StoreSwitch extends AbstractModel {
    /**
     * @var \Magento\Store\Model\BaseUrlChecker
     */
    protected $baseUrlChecker;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * @var bool
     */
    protected $storeId = false;

    /**
     * StoreSwitch constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param BaseUrlChecker $baseUrlChecker
     * @param RequestInterface $request
     * @param StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        Context $context,
        Registry $registry,
        BaseUrlChecker $baseUrlChecker,
        RequestInterface $request,
        StoreRepositoryInterface $storeRepository
    ) {
        parent::__construct( $context, $registry );

        $this->baseUrlChecker  = $baseUrlChecker;
        $this->request         = $request;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @return bool|int
     */
    public function getStoreId() {
        if ( $this->isInitialized() ) {
            return $this->storeId;
        }

        $isSecure = $this->request->isSecure();

        foreach ( $this->storeRepository->getList() as $store ) {
            $baseUrl = $store->getBaseUrl( UrlInterface::URL_TYPE_WEB, $isSecure );

            if ( $this->baseUrlChecker( $baseUrl ) ) {
                $this->isInitialized = true;
                $this->storeId       = $store->getId();
                $this->getLogger()->critical( $store->getName() );

                break;
            }
        }

        return $this->storeId;
    }

    /**
     * @return bool
     */
    public function isInitialized() {
        return $this->isInitialized;
    }

    /**
     * @param $baseUrl
     *
     * @return bool
     */
    protected function baseUrlChecker( $baseUrl ) {
        return $this->baseUrlChecker->execute( parse_url( $baseUrl ), $this->request );
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger() {
        return $this->_logger;
    }

}
