Iugu
====
https://iugu.com/referencias/api

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist hotelja/yii2-iugu "*"
```

or add

```
"hotelja/yii2-iugu": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
    'components' => [
        'iugu' => [
            'class' => 'hotelja\yii2-iugu\Iugu',
            'api_token'=>'PutYourApiKeyHere',
        ],
        // ...
    ],
    // ...
];
```

You can then use any iugu command like:

```php
Yii::$app->iugu->

```
