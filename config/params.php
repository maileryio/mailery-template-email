<?php

declare(strict_types=1);

/**
 * Email message module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-template-email
 * @package   Mailery\Template
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Mailery\Template\Email\TemplateType;
use Mailery\Template\Email\Controller\TemplateController;
use Yiisoft\Router\Route;

return [
    'maileryio/mailery-message' => [
        'types' => [
            TemplateType::class => TemplateType::class,
        ],
    ],

    'yiisoft/yii-cycle' => [
        'annotated-entity-paths' => [
            '@vendor/maileryio/mailery-template-email/src/Entity',
        ],
    ],

    'router' => [
        'routes' => [
            '/message/email/create' => Route::methods(['GET', 'POST'], '/brand/{brandId:\d+}/message/email/create', [TemplateController::class, 'create'])
                ->name('/message/email/create'),
        ],
    ],
];
