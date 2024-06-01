Magento 2 module
==================
[![Magento 2](https://img.shields.io/badge/Magento-%3E=2.4-blue.svg)](https://github.com/magento/magento2)
[![Packagist](https://img.shields.io/packagist/v/diepxuan/module-magento)](https://packagist.org/packages/diepxuan/module-magento)
[![Downloads](https://img.shields.io/packagist/dt/diepxuan/module-magento)](https://packagist.org/packages/diepxuan/module-magento)
[![License](https://img.shields.io/packagist/l/diepxuan/module-magento)](https://packagist.org/packages/diepxuan/module-magento)

* fix loading media store path from database Storage
* detect store/website by url without ```index.php``` or ```.htaccess```

Installation
------------

The easiest way to install the extension is to use [Composer](https://getcomposer.org/)

Run the following commands:

- ```$ composer require diepxuan/module-magento```
- ```$ bin/magento module:enable Diepxuan_Magento```
- ```$ bin/magento setup:upgrade && bin/magento setup:static-content:deploy```
