<?php

declare(strict_types=1);

namespace Mailery\Template\Email\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Mailery\Template\Email\Form\TemplateForm;
use Mailery\Web\ViewRenderer;

class TemplateController
{
    /**
     * @var ViewRenderer
     */
    private ViewRenderer $viewRenderer;

    /**
     * @param ViewRenderer $viewRenderer
     */
    public function __construct(ViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer->withController($this);
    }

    /**
     * @param Request $request
     * @param TemplateForm $messageForm
     * @param UrlGenerator $urlGenerator
     * @return Response
     */
    public function create(Request $request, TemplateForm $messageForm, UrlGenerator $urlGenerator): Response
    {
        $messageForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
        ;

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $messageForm->loadFromServerRequest($request);

            if (($message = $messageForm->save()) !== null) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/message/email/view', ['id' => $message->getId()]));
            }
        }

        return $this->viewRenderer->render('create', compact('messageForm', 'submitted'));
    }
}
