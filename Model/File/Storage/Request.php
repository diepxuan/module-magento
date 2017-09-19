<?php
/**
 * Copyright © 2017 Dxvn, Inc. All rights reserved.
 */
namespace Diepxuan\Magento\Model\File\Storage;

use Magento\Framework\HTTP\PhpEnvironment\Request as HttpRequest;

class Request extends \Magento\MediaStorage\Model\File\Storage\Request
{
    /**
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        $this->pathInfo = str_replace('..', '', ltrim(ltrim($request->getPathInfo(), '/media/'), '/'));
    }
}
