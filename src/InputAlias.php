<?php

namespace srnden\inputalias;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\InputWidget;
use srnden\inputalias\assets\InputAliasAsset;

/**
 * Class InputAlias
 * @package srnden\inputalias
 */
class InputAlias extends InputWidget
{
    /**
     * Source field. Field value transliterate when this focusin.
     *  array: [$model, 'attribute'],
     *  string: input id
     * @var bool|array|string
     */
    public $source = false;

    /**
     * @var bool
     */
    public $showButton = true;

    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'form-control'];

    /**
     * @var array
     */
    public $buttonOption = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->showButton && !$this->source) {
            throw new InvalidConfigException('Set showButton=true or set source field.');
        }

        parent::run();

        $this->registerAssets();

        $id = $this->options['id'];

        $buttonOption = array_merge([
            'id' => 'translit-btn-' . $id,
            'class' => 'btn btn-default',
            'title' => Yii::t('srndeninputalias', 'Translit'),
        ], is_array($this->buttonOption) ? $this->buttonOption : []);


        $js = [];

        $sourceId = null;

        if (is_array($this->source)) {
            if (count($this->source) != 2) {
                throw new InvalidConfigException('valid "source" array: [$model, "attribute"].');
            }

            $this->source = array_values($this->source);

            $sourceId = Html::getInputId($this->source[0], $this->source[1]);
        } elseif (is_string($this->source)) {
            $sourceId = $this->source;
        }

        if ($sourceId !== null) {
            $js[] = <<<JS
$('#{$id}').focusin(function(){
    var sourceInput = $('#{$sourceId}');
    
    if(sourceInput.length) {
        if($(this).val() == '' && sourceInput.val() != '') {
            $(this).val(URLify(sourceInput.val()));
        }
    }else{
        console.error('Can\'t find #$sourceId.');
    }
});
JS;
        }

        $inputHtml = $this->renderInputHtml('text');

        if ($this->showButton) {
            $output = Html::beginTag('div', [
                'class' => 'input-group',
            ]);

            $output .= $inputHtml;
            $output .= Html::beginTag('span', [
                'class' => 'input-group-btn'
            ]);
            $output .= Html::button($buttonOption['title'], $buttonOption);
            $output .= Html::endTag('span');
            $output .= Html::endTag('div');

            $js[] = <<<JS
$('#{$buttonOption['id']}').click(function() {
    var input = $('#{$id}');
    input.val(URLify(input.val()));
    return false;
});
JS;

        } else {
            $output = $inputHtml;
        }

        $this->getView()->registerJs(implode("\n", $js), View::POS_READY);

        echo $output;
    }

    /**
     * Registers assets.
     */
    public function registerAssets()
    {
        InputAliasAsset::register($this->getView());
    }

    /**
     * Register translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['srndeninputalias'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/srnden/yii2-input-alias/src/messages',
        ];
    }
}
