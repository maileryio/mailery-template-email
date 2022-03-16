<?php

use Yiisoft\Html\Html;
use Yiisoft\Form\Widget\Form;

/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\View\WebView $this */
/** @var Mailery\Template\Email\Form\TemplateForm $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>
<div class="row">
    <div class="col-12 col-xl-4">
        <?= Form::widget()
            ->options(
                [
                    'id' => 'form-template',
                    'csrf' => $csrf,
                    'enctype' => 'multipart/form-data',
                ]
            )
            ->begin(); ?>

        <?= $field->config($form, 'name'); ?>
        <?= $field->config($form, 'htmlEditor')
            ->dropDownList($form->getHtmlEditorOptions()); ?>

        <?= Html::submitButton(
            'Save',
            [
                'class' => 'btn btn-primary float-right mt-2',
            ]
        ); ?>

        <?= Form::end(); ?>
    </div>
</div>
