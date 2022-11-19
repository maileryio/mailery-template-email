<?php

use Mailery\Template\Editor\EditorList;
use Mailery\Template\Renderer\BodyRendererInterface;
use Mailery\Template\Email\Renderer\BodyRenderer;

return [
    EditorList::class => [
        '__construct()' => [
            'elements' => $params['maileryio/mailery-template-email']['editors'],
        ],
    ],

    BodyRendererInterface::class => [
        'class' => BodyRenderer::class,
    ],
];
