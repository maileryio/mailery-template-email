<?php

use Mailery\Template\Email\Provider\EditorConfigs;

return [
    EditorConfigs::class => static function () use ($params) {
        $configs = $params['maileryio/mailery-template-email']['editors'] ?? [];
        return new EditorConfigs($configs);
    },
];
