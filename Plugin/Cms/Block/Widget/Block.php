<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-05 18:14:48
 */

namespace Diepxuan\Magento\Plugin\Cms\Block\Widget;

class Block
{
    private $identifiers = ['lienhe'];

    /**
     * Override getTemplate() method.
     *
     * @param string $result
     *
     * @return string
     */
    public function afterGetTemplate(\Magento\Cms\Block\Widget\Block $subject, $result)
    {
        // Kiểm tra điều kiện và thay đổi template nếu cần
        if (\in_array($subject->getData('block_id'), $this->identifiers, true)) {
            return 'Diepxuan_Magento::widget/static_block/default.phtml';
        }

        return $result; // Trả về template gốc nếu không có điều kiện nào phù hợp
    }
}
