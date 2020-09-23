<?php declare(strict_types=1);

use Mailery\Widget\Form\FormRenderer;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Template\ServerRequestInterface $request */
/** @var FormManager\Form $templateForm */
/** @var string $csrf */
/** @var bool $submitted */
$this->setTitle('New email template');

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
            <h1 class="h2">New email template</h1>
            <div class="btn-toolbar float-right">
                <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/template/default/index'); ?>">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="mb-2"></div>
<div class="row">
    <div class="col-6">
        <?= (new FormRenderer($templateForm->withCsrf($csrf)))($submitted); ?>
    </div>
</div>
