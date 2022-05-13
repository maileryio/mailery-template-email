<?php declare(strict_types=1);

use Mailery\Activity\Log\Widget\ActivityLogLink;
use Mailery\Icon\Icon;
use Mailery\Widget\Link\Link;
use Yiisoft\Form\Widget\Form;

/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Template\Email\Entity\EmailTemplate $template */
/** @var Mailery\Template\Email\Form\ContentForm $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle($template->getName());

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
            <h1 class="h3">Template #<?= $template->getId(); ?></h1>
            <div class="btn-toolbar float-right">
                <?= Link::widget()
                    ->csrf($csrf)
                    ->label(Icon::widget()->name('delete')->options(['class' => 'mr-1'])->render() . ' Delete')
                    ->method('delete')
                    ->href($url->generate('/template/default/delete', ['id' => $template->getId()]))
                    ->confirm('Are you sure?')
                    ->options([
                        'class' => 'btn btn-sm btn-danger mx-sm-1 mb-2',
                    ])
                    ->encode(false);
                ?>
                <a class="btn btn-sm btn-secondary mx-sm-1 mb-2" href="<?= $url->generate($template->getEditRouteName(), $template->getEditRouteParams()); ?>">
                    <?= Icon::widget()->name('pencil')->options(['class' => 'mr-1']); ?>
                    Update
                </a>
                <b-dropdown right size="sm" variant="secondary" class="mb-2">
                    <template v-slot:button-content>
                        <?= Icon::widget()->name('settings'); ?>
                    </template>
                    <?= ActivityLogLink::widget()
                        ->tag('b-dropdown-item')
                        ->label('Activity log')
                        ->entity($template); ?>
                </b-dropdown>
                <div class="btn-toolbar float-right">
                    <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $url->generate('/template/default/index'); ?>">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= Form::widget()
                ->csrf($csrf)
                ->id('template-form')
                ->begin(); ?>

        <?= $form->getField()
                ->config($form, 'htmlContent')
                ->htmlInput(); ?>

        <div class="mb-4"></div>

        <?= $form->getField()
                ->config($form, 'textContent')
                ->textInput([
                    'rows()' => [10],
                    'class()' => ['form-control']
                ]); ?>

        <?= $field->submitButton()
                ->class('btn btn-primary float-right mt-2')
                ->value('Save'); ?>

        <?= Form::end(); ?>
    </div>
</div>
