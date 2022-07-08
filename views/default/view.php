<?php declare(strict_types=1);

use Yiisoft\Yii\Widgets\ContentDecorator;
use Yiisoft\Html\Tag\Form;
use Yiisoft\Form\Field;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Template\Email\Entity\EmailTemplate $template */
/** @var Mailery\Template\Email\Form\ContentForm $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle($template->getName());

?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-template-email/views/default/_layout.php')
    ->parameters(compact('template', 'csrf'))
    ->begin(); ?>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= Form::tag()
                ->csrf($csrf)
                ->id('template-form')
                ->post()
                ->open(); ?>

        <?= $form->getField()
                ->config($form, 'htmlContent')
                ->htmlInput([
                    'rows()' => [10],
                    'inputClass()' => ['form-control']
                ]); ?>

        <div class="mb-4"></div>

        <?= $form->getField()
                ->config($form, 'textContent')
                ->textInput([
                    'rows()' => [10],
                    'inputClass()' => ['form-control']
                ]); ?>

        <?= Field::submitButton()
                ->content('Save changes'); ?>

        <?= Form::tag()->close(); ?>
    </div>
</div>

<?= ContentDecorator::end() ?>
