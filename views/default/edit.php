<?php declare(strict_types=1);

use Mailery\Web\Widget\FlashMessage;
use Yiisoft\Yii\Widgets\ContentDecorator;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Template\Email\Entity\EmailTemplate $template */
/** @var Mailery\Template\Email\Form\TemplateForm $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle('Edit template #' . $template->getId());

?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-template-email/views/default/_layout.php')
    ->parameters(compact('template', 'csrf'))
    ->begin(); ?>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= FlashMessage::widget(); ?>
    </div>
</div>
<div class="mb-2"></div>

<div class="row">
    <div class="col-12">
        <?= $this->render('_form', compact('csrf', 'form')) ?>
    </div>
</div>

<?= ContentDecorator::end() ?>
