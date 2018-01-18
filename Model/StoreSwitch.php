<?php

namespace Diepxuan\Magento\Model;

/**
 * Class StoreSwitch
 * @package Diepxuan\Magento\Model
 */
class StoreSwitch extends \Magento\Framework\Model\AbstractModel
{
    /**
     * @var \Magento\Store\Model\BaseUrlChecker
     */
    protected $baseUrlChecker;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\BaseUrlChecker $baseUrlChecker,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context, $registry);

        $this->baseUrlChecker = $baseUrlChecker;
        $this->request        = $request;
        $this->storeManager   = $storeManager;
    }

    /**
     * @param \Magento\Framework\App\FrontController|null  $subject
     * @param \Closure|null                                $proceed
     * @param \Magento\Framework\App\RequestInterface|null $request
     *
     * @return mixed
     */
    public
    function execute(
        \Magento\Framework\App\FrontController $subject = null,
        \Closure $proceed = null,
        \Magento\Framework\App\RequestInterface $request = null
    ) {
        if ($request) {
            $this->request = $request;
        }

        $this->storeSwitch();

        if ($proceed) {
            return $proceed($request);
        }
    }

    protected function storeSwitch()
    {
        $currentStoreBaseUrl = $this->storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_WEB,
            $this->storeManager->getStore()->isCurrentlySecure()
        );
        if ($this->baseUrlChecker($currentStoreBaseUrl)) {
            return;
        }

        $this->autoStoreSwitch();
    }

    /**
     * @param $baseUrl
     *
     * @return bool
     */
    protected function baseUrlChecker($baseUrl)
    {
        return $this->baseUrlChecker->execute(parse_url($baseUrl), $this->request);
    }

    protected function autoStoreSwitch()
    {
        foreach ($this->storeManager->getStores() as $store) {

            $baseUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB, $this->request->isSecure());
            if ($this->baseUrlChecker($baseUrl)) {
                $this->storeManager->setCurrentStore($store->getId());

                return;
            }
        }
    }

}
