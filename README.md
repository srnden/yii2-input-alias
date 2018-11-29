Yii2 input alias widget.
===========================
Yii2 input alias widget.

Use [URLify.js](https://github.com/django/django/blob/master/django/contrib/admin/static/admin/js/urlify.js)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist srnden/yii2-input-alias "*"
```

or add

```
"srnden/yii2-input-alias": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
echo $form->field($model, 'pagetitle')->textInput();
            
echo $form->field($model, 'alias')->widget(InputAlias::class, [
   'source' => [$model, 'pagetitle']
]);

echo InputAlias::widget([
   'name' => 'alias'
]);
```