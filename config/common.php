<?php

use Mailery\Template\Email\Provider\EmailEditorConfigs;

return [
    EmailEditorConfigs::class => static function () use ($params) {
        $configs = $params['maileryio/mailery-template-email']['editors'] ?? [];
        return new EmailEditorConfigs($configs);
    },
];
