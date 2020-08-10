<?php

declare(strict_types=1);

namespace Mailery\Template\Email\Controller;

use Mailery\Template\Email\WebController;
use Psr\Http\Template\ResponseInterface as Response;
use Psr\Http\Template\ServerRequestInterface as Request;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Mailery\Template\Email\Form\TemplateForm;

class TemplateController extends WebController
{
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
                return $this->redirect($urlGenerator->generate('/message/email/view', ['id' => $message->getId()]));
            }
        }

        return $this->render('create', compact('messageForm', 'submitted'));
    }
}
