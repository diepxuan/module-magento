<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-04-03 07:36:40
 */

namespace Diepxuan\Magento\Helper;

use Magento\Catalog\Model\Category\Attribute\Backend\DefaultSortby;

class ClassChecker
{
    public static function checkAndCreateVirtualClass($className, $virtualClassName)
    {
        if (class_exists($className)) {
            return $virtualClassName;
        }

        return $className; // Nếu không tồn tại, trả về class gốc
    }

    public static function isNewVersion()
    {
        return class_exists(DefaultSortby::class);
    }
}
