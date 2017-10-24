Magento 2 module
==================
[![Latest version](https://img.shields.io/badge/latest-0.0.1-green.svg)](https://github.com/diepxuan/module-magento)
[![Packagist](https://img.shields.io/badge/packagist-0.0.1-green.svg)](https://packagist.org/packages/diepxuan/module-magento)
[![Magento 2](https://img.shields.io/badge/Magento-%3E=2.1-blue.svg)](https://github.com/magento/magento2/tree/2.1)
[![PHP >= 5.5.22](https://img.shields.io/badge/PHP-%3E=5.6.5-blue.svg)](https://packagist.org/packages/diepxuan/module-magento)

* fix loading media store path from database Storage
* detect store/website by url without ```index.php``` or ```.htaccess```

Installation
------------

The easiest way to install the extension is to use [Composer](https://getcomposer.org/)

Run the following commands:

- ```$ composer require diepxuan/module-magento```
- ```$ bin/magento module:enable Diepxuan_Magento```
- ```$ bin/magento setup:upgrade && bin/magento setup:static-content:deploy```
