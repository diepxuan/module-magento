<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-20 12:23:50
 */

namespace Diepxuan\Magento\Block\Product;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogWidget\Model\Rule;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Rule\Model\Condition\Sql\Builder as SqlBuilder;
use Magento\Widget\Helper\Conditions;

class ProductsList extends \Magento\CatalogWidget\Block\Product\ProductsList
{
    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        HttpContext $httpContext,
        SqlBuilder $sqlBuilder,
        Rule $rule,
        Conditions $conditionsHelper,
        array $data = [],
        ?Json $json = null,
        ?LayoutFactory $layoutFactory = null,
        ?EncoderInterface $urlEncoder = null,
        ?CategoryRepositoryInterface $categoryRepository = null
    ) {
        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $httpContext,
            $sqlBuilder,
            $rule,
            $conditionsHelper,
            $data,
            $json,
            $layoutFactory,
            $urlEncoder,
            $categoryRepository
        );
    }

    /**
     * Prepare and return product collection.
     *
     * @return Collection
     *
     * @SuppressWarnings(PHPMD.RequestAwareBlockMethod)
     *
     * @throws LocalizedException
     */
    public function createCollection()
    {
        $collection = parent::createCollection();
        $collection->getSelect()->orderRand();

        return $collection;
    }

    /**
     * Prepare and return product collection without visibility filter.
     *
     * @throws LocalizedException
     */
    public function getBaseCollection(): Collection
    {
        // $collection = parent::getBaseCollection();
        $collection = $this->productCollectionFactory->create();
        if (null !== $this->getData('store_id')) {
            $collection->setStoreId($this->getData('store_id'));
        }

        /**
         * Change sorting attribute to entity_id because created_at can be the same for products fastly created
         * one by one and sorting by created_at is indeterministic in this case.
         */
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            // ->addAttributeToSort('entity_id', 'desc')
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getRequest()->getParam($this->getData('page_var_name'), 1))
        ;

        $conditions = $this->getConditions();
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);

        /*
         * Prevent retrieval of duplicate records. This may occur when multiselect product attribute matches
         * several allowed values from condition simultaneously
         */
        $collection->distinct(true);

        return $collection;
    }

    /**
     * Get key pieces for caching block content.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.RequestAwareBlockMethod)
     *
     * @throws NoSuchEntityException
     */
    public function getCacheKeyInfo()
    {
        return parent::getCacheKeyInfo();
    }

    protected function _beforeToHtml()
    {
        $this->setProductCollection($this->createCollection());

        return parent::_beforeToHtml();
    }

    /**
     * Internal constructor, that is called from real constructor.
     */
    protected function _construct(): void
    {
        parent::_construct();
    }
}
