<?php

use Mailery\Template\Email\Provider\EditorConfigs;

$templates = $params['menu']['sidebar']['items']['templates'];
$templatesChildItems = $templates->getChildItems();
$activeRouteNames = $templatesChildItems['templates']->getActiveRouteNames();

$templatesChildItems['templates'] = $templatesChildItems['templates']->withActiveRouteNames(array_merge(
    $activeRouteNames,
    [
        '/template/email/view',
        '/template/email/create',
        '/template/email/edit',
        '/template/email/delete',
    ]
));

$templates->setChildItems($templatesChildItems);

return [
    EditorConfigs::class => static function () use ($params) {
        $configs = $params['maileryio/mailery-template-email']['editors'] ?? [];
        return new EditorConfigs($configs);
    },
];
