<?php

namespace Diepxuan\Magento\App\FrontController\Plugin;

/**
 * Plugin to set default store
 * @deprecated
 */
class RequestPreprocessor
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
     * @param \Magento\Framework\App\FrontController  $subject
     * @param \Closure                                $proceed
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function aroundDispatch(
        \Magento\Framework\App\FrontController $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        return $this->storeSwitch->execute();
    }
}
