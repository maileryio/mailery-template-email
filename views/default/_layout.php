<?php declare(strict_types=1);

use Mailery\Activity\Log\Widget\ActivityLogLink;
use Mailery\Icon\Icon;
use Mailery\Widget\Link\Link;
use Mailery\Web\Widget\DateTimeFormat;
use Yiisoft\Yii\Bootstrap5\Nav;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Template\Email\Entity\EmailTemplate $template */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle($template->getName());

?><div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md">
                        <h4 class="mb-0">Template #<?= $template->getId(); ?></h4>
                        <p class="mt-1 mb-0 small">
                            Changed at <?= DateTimeFormat::widget()->dateTime($template->getUpdatedAt())->run() ?>
                        </p>
                    </div>
                    <div class="col-auto">
                        <div class="btn-toolbar float-right">
                            <?= Link::widget()
                                ->csrf($csrf)
                                ->label(Icon::widget()->name('delete')->options(['class' => 'mr-1'])->render() . ' Delete')
                                ->method('delete')
                                ->href($url->generate($template->getDeleteRouteName(), $template->getDeleteRouteParams()))
                                ->confirm('Are you sure?')
                                ->options([
                                    'class' => 'btn btn-sm btn-danger mx-sm-1',
                                ])
                                ->encode(false);
                            ?>
                            <b-dropdown right size="sm" variant="secondary">
                                <template v-slot:button-content>
                                    <?= Icon::widget()->name('settings'); ?>
                                </template>
                                <?= ActivityLogLink::widget()
                                    ->tag('b-dropdown-item')
                                    ->label('Activity log')
                                    ->entity($template); ?>
                            </b-dropdown>
                            <a class="btn btn-sm btn-outline-secondary mx-sm-1" href="<?= $url->generate('/template/default/index'); ?>">
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-body">
                <?= Nav::widget()
                    ->currentPath($currentRoute->getUri()->getPath())
                    ->items([
                        [
                            'label' => 'Overview',
                            'url' => $url->generate($template->getViewRouteName(), $template->getViewRouteParams()),
                        ],
                        [
                            'label' => 'Edit',
                            'url' => $url->generate($template->getEditRouteName(), $template->getEditRouteParams()),
                        ],
                    ])
                    ->options([
                        'class' => 'nav nav-tabs nav-tabs-bordered font-weight-bold',
                    ])
                    ->withoutEncodeLabels();
                ?>

                <div class="mb-4"></div>
                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
