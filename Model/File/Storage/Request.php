<?php
/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\Magento\Model\File\Storage;

use Magento\Framework\HTTP\PhpEnvironment\Request as HttpRequest;

/**
 * Class Request
 * @package Diepxuan\Magento\Model\File\Storage
 */
class Request extends \Magento\MediaStorage\Model\File\Storage\Request
{
    /**
     * Path info
     *
     * @var string
     */
    protected $pathInfo;

    /**
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        parent::__construct($request);
        $this->pathInfo = str_replace('..', '', ltrim(ltrim($request->getPathInfo(), '/media/'), '/'));
    }
}
