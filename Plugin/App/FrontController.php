<?php

namespace Diepxuan\Magento\Plugin\App;

/**
 * Class FrontController
 * @package Diepxuan\Magento\Plugin\App
 */
class FrontController
{
    /**
     * @var \Diepxuan\Magento\Model\StoreSwitch
     */
    protected $storeSwitch;

    /**
     * FrontController constructor.
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
        return $this->storeSwitch->execute($subject, $proceed, $request);
    }

}
