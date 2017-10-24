<?php
/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */
namespace Diepxuan\Magento\Block\Adminhtml\Wysiwyg\Images;

class Tree extends \Magento\Cms\Block\Adminhtml\Wysiwyg\Images\Tree
{

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Cms\Helper\Wysiwyg\Images      $cmsWysiwygImages
     * @param \Magento\Framework\Registry             $registry
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Cms\Helper\Wysiwyg\Images      $cmsWysiwygImages,
        \Magento\Framework\Registry             $registry,
        array                                   $data = []
    ) {
        parent::__construct($context, $cmsWysiwygImages, $registry, $data);
    }

    /**
     * Json tree builder
     *
     * @return string
     */
    public function getTreeJson()
    {
        $storageRoot = $this->_cmsWysiwygImages->getStorageRoot();
        $collection  = $this->_coreRegistry->registry(
            'storage'
        )->getDirsCollection(
            $this->_cmsWysiwygImages->getCurrentPath()
        );
        $jsonArray = [];
        foreach ($collection as $item) {
            $jsonArray[] = [
                'text' => $this->_cmsWysiwygImages->getShortFilename($item->getBasename(), 20),
                'id'   => $this->_cmsWysiwygImages->convertPathToId($item->getFilename()),
                'path' => substr($item->getFilename(), strlen($storageRoot)),
                'cls'  => 'folder',
            ];
        }
        return \Zend_Json::encode($jsonArray);
    }

}
