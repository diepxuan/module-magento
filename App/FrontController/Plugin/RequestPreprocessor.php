<?php
/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */
namespace Diepxuan\Magento\App\FrontController\Plugin;

/**
 * Plugin to set default store
 */
class RequestPreprocessor extends \Magento\Store\App\FrontController\Plugin\RequestPreprocessor
{

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Store\Model\BaseUrlChecker
     */
    protected $baseUrlChecker;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     * @param \Magento\Framework\UrlInterface                    $url
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\App\ResponseFactory             $responseFactory
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface         $storeManager,
        \Magento\Framework\UrlInterface                    $url,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\ResponseFactory             $responseFactory
    ) {
        parent::__construct($storeManager, $url, $scopeConfig, $responseFactory);
    }

    /**
     * Auto-redirect to base url (without SID) if the requested url doesn't match it.
     * By default this feature is enabled in configuration.
     *
     * @param  \Magento\Framework\App\FrontController  $subject
     * @param  \Closure                                $proceed
     * @param  \Magento\Framework\App\RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundDispatch(
        \Magento\Framework\App\FrontController  $subject,
        \Closure                                $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        if (!$request->isPost() && $this->getBaseUrlChecker()->isEnabled()) {
            $this->_request = $request;
            $baseUrl        = $this->_storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_WEB,
                $this->_storeManager->getStore()->isCurrentlySecure()
            );
            if ($baseUrl) {
                $uri = parse_url($baseUrl);
                if (!$this->getBaseUrlChecker()->execute($uri, $this->_request)) {
                    $uri = $this->autoDetectStore() ?: $uri;
                }
                if (!$this->getBaseUrlChecker()->execute($uri, $this->_request)) {
                    $redirectUrl = $this->_url->getRedirectUrl(
                        $this->_url->getUrl(ltrim($this->_request->getPathInfo(), '/'), ['_nosid' => true])
                    );
                    $redirectCode = (int) $this->_scopeConfig->getValue(
                        'web/url/redirect_to_base',
                        \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                    ) !== 301 ? 302 : 301;

                    $response = $this->_responseFactory->create();
                    $response->setRedirect($redirectUrl, $redirectCode);
                    $response->setNoCacheHeaders();
                    return $response;
                }
            }
        }
        $this->_request->setDispatched(false);

        return $proceed($this->_request);
    }

    /**
     * @return array
     */
    protected function autoDetectStore()
    {
        $curentBaseUrl = $this->_request->getDistroBaseUrl();
        foreach ($this->_storeManager->getStores() as $store) {
            $baseUrl = $store->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_WEB,
                $this->_request->isSecure()
            );

            if (strcmp($curentBaseUrl, $baseUrl) === 0) {
                $this->_storeManager->setCurrentStore($store->getid());
                break;
            }
        }

        if (!$baseUrl) {
            return false;
        }

        return parse_url($baseUrl);
    }

    /**
     * Gets base URL checker.
     *
     * @return \Magento\Store\Model\BaseUrlChecker
     * @deprecated
     */
    protected function getBaseUrlChecker()
    {
        if ($this->baseUrlChecker === null) {
            $this->baseUrlChecker = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Magento\Store\Model\BaseUrlChecker'
            );
        }

        return $this->baseUrlChecker;
    }

    /**
     * @return \Magento\Store\Model\StoreManagerInterface
     */
    protected function getStoreManager()
    {
        return $this->_storeManager;
    }

    /**
     * @return \Magento\Framework\App\RequestInterface
     */
    protected function getRequest()
    {
        return $this->_request;
    }
}
