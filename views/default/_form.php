<?php

use Mailery\Widget\Select\Select;
use Yiisoft\Form\Widget\Form;

/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\View\WebView $this */
/** @var Yiisoft\Form\FormModelInterface $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>

<?= Form::widget()
        ->csrf($csrf)
        ->id('template-form')
        ->begin(); ?>

<?= $field->text($form, 'name')->autofocus(); ?>

<?= $field->select(
        $form,
        'htmlEditor',
        [
            'class' => Select::class,
            'items()' => [$form->getHtmlEditorOptions()],
            'clearable()' => [false],
            'searchable()' => [false],
            'disable()' => [$form->hasEntity()],
        ]
    ); ?>

<?= $field->textArea($form, 'description', ['rows()' => [5]]); ?>

<?= $field->submitButton()
        ->class('btn btn-primary float-right mt-2')
        ->value($form->hasEntity() ? 'Save changes' : 'Add template'); ?>

<?= Form::end(); ?>
