<?php

use Mailery\Widget\Select\Select;
use Yiisoft\Html\Tag\Form;
use Yiisoft\Form\Field;

/** @var Yiisoft\View\WebView $this */
/** @var Yiisoft\Form\FormModelInterface $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>

<?= Form::tag()
        ->csrf($csrf)
        ->id('template-form')
        ->post()
        ->open(); ?>

<?= Field::text($form, 'name')->autofocus(); ?>

<?= Field::input(
        Select::class,
        $form,
        'htmlEditor',
        [
            'optionsData()' => [$form->getHtmlEditorOptions()],
            'clearable()' => [false],
            'searchable()' => [false],
            'disable()' => [$form->hasEntity()],
        ]
    ); ?>

<?= Field::textarea($form, 'description', ['rows()' => [5]]); ?>

<?= Field::submitButton()
        ->content($form->hasEntity() ? 'Save changes' : 'Add template'); ?>

<?= Form::tag()->close(); ?>
