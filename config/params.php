<?php

declare(strict_types=1);

/**
 * Email template module for Mailery Platform
 * @link      https://github.com/maileryio/mailery-template-email
 * @package   Mailery\Template
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2020, Mailery (https://mailery.io/)
 */

use Mailery\Template\Email\TemplateType;

return [
    'maileryio/mailery-template' => [
        'types' => [
            TemplateType::class => TemplateType::class,
        ],
    ],

    'maileryio/mailery-template-email' => [
        'editors' => [],
    ],

    'yiisoft/yii-cycle' => [
        'annotated-entity-paths' => [
            '@vendor/maileryio/mailery-template-email/src/Entity',
        ],
    ],
];
