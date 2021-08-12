log4yii
===============
log4yii

[![Latest Stable Version](http://poser.pugx.org/karataserkan/log4yii/v)](https://packagist.org/packages/karataserkan/log4yii) [![Total Downloads](http://poser.pugx.org/karataserkan/log4yii/downloads)](https://packagist.org/packages/karataserkan/log4yii) [![Monthly Downloads](http://poser.pugx.org/karataserkan/log4yii/d/monthly)](https://packagist.org/packages/karataserkan/log4yii) [![License](http://poser.pugx.org/karataserkan/log4yii/license)](https://packagist.org/packages/karataserkan/log4yii)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist karataserkan/log4yii "*"
```

or add

```
"karataserkan/log4yii": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= 'targets' => [
	[
      'class' => 'karataserkan\log4yii\HttpLogTarget',
      'levels' => ['info', 'error', 'warning'],
      'logVars' => ['_GET', '_POST', '_FILES'],
      'categories' => ['test'],
      'baseUrl' => 'http://url'
    ],
    [
      'class' => 'karataserkan\log4yii\StreamLogTarget',
      'levels' => ['info', 'error', 'warning'],
      'logVars' => ['_GET', '_POST', '_FILES'],
      'url' => 'php://stdout'
    ]
], ?>```
