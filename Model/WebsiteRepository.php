<?php
/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */
namespace Diepxuan\Magento\Model;

class WebsiteRepository extends \Magento\Store\Model\WebsiteRepository
{

    /**
     * @var Config
     */
    protected $appConfig;

    /**
     * Logger instance
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    public function __construct(
        \Magento\Store\Model\WebsiteFactory                          $factory,
        \Magento\Store\Model\ResourceModel\Website\CollectionFactory $websiteCollectionFactory,
        \Psr\Log\LoggerInterface                                     $logger
    ) {
        parent::__construct($factory, $websiteCollectionFactory);
        $this->_logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault()
    {
        if (!$this->default) {
            _debug($this);
            foreach ($this->entities as $entity) {
                if ($entity->getIsDefault()) {
                    $this->default = $entity;
                    $this->getLogger()->addDebug(print_r(get_class_methods($this->default), true));
                    return $this->default;
                }
            }
            if (!$this->allLoaded) {
                $this->initDefaultWebsite();
            }
            if (!$this->default) {
                throw new \DomainException(__('Default website is not defined'));
            }
        }

        return $this->default;
    }

    /**
     * Initialize default website.
     * @return void
     */
    private function initDefaultWebsite()
    {
        $websites = (array) $this->getAppConfig()->get('scopes', 'websites', []);
        foreach ($websites as $data) {
            if (isset($data['is_default']) && $data['is_default'] == 1) {
                if ($this->default) {
                    throw new \DomainException(__('More than one default website is defined'));
                }
                $website = $this->factory->create([
                    'data' => $data,
                ]);
                $this->default                               = $website;
                $this->entities[$this->default->getCode()]   = $this->default;
                $this->entitiesById[$this->default->getId()] = $this->default;
            }
        }
    }

    /**
     * Retrieve application config.
     *
     * @deprecated
     * @return Config
     */
    protected function getAppConfig()
    {
        if (!$this->appConfig) {
            $this->appConfig = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\App\Config::class);
        }
        return $this->appConfig;
    }

    /**
     * Get logger instance
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->_logger;
    }
}
