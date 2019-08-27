<?php
/**
 * Copyright © Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Magento\Model\Wysiwyg\Images;

/**
 * Class Storage
 * @package Diepxuan\Magento\Model\Wysiwyg\Images
 * @deprecated 0.0.2.4
 */
class Storage extends \Magento\Cms\Model\Wysiwyg\Images\Storage {
    /**
     * Create sub directories if DB storage is used
     *
     * @param string $path
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     * @deprecated 0.0.2.4
     */
    protected function createSubDirectories( $path ) {
        if ( $this->_coreFileStorageDb->checkDbUsage() ) {
            /** @var \Magento\MediaStorage\Model\File\Storage\Directory\Database $subDirectories */
            $subDirectories = $this->_directoryDatabaseFactory->create();
            $directories    = $subDirectories->getSubdirectories( $path );
            foreach ( $directories as $directory ) {
                if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' ) {
                    $fullPath = ltrim( $directory['name'], '/' );
                } else {
                    $fullPath = rtrim( $path, '/' ) . '/' . $directory['name'];
                }
                $this->_directory->create( $fullPath );
            }
        }
    }

}
