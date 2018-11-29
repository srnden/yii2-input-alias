<?php

namespace srnden\inputalias\assets;

use yii\web\AssetBundle;

/**
 * The InputAliasAsset assets.
 */
class InputAliasAsset extends AssetBundle
{
    /**
     * {@inheritdoc}
     */
    public $sourcePath = '@vendor/srnden/yii2-input-alias/assets';

    /**
     * {@inheritdoc}
     */
    public $css = [];

    /**
     * {@inheritdoc}
     */
    public $js = [
        'js/urlify.js' // @see https://github.com/django/django/blob/master/django/contrib/admin/static/admin/js/urlify.js
    ];

    /**
     * {@inheritdoc}
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    public $publishOptions = [
        'appendTimestamp' => true,
        'linkAssets' => true
    ];
}
