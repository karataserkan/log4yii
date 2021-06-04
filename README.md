log4yii
===============
log4yii

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
