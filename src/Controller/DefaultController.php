<?php

declare(strict_types=1);

namespace Mailery\Template\Email\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Mailery\Template\Email\Form\TemplateForm;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Mailery\Template\Repository\TemplateRepository;
use Mailery\Brand\Service\BrandLocatorInterface;

class DefaultController
{
    /**
     * @var ViewRenderer
     */
    private ViewRenderer $viewRenderer;

    /**
     * @var ResponseFactory
     */
    private ResponseFactory $responseFactory;

    /**
     * @var TemplateRepository
     */
    private TemplateRepository $templateRepo;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param BrandLocatorInterface $brandLocator
     * @param TemplateRepository $templateRepo
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactory $responseFactory,
        BrandLocatorInterface $brandLocator,
        TemplateRepository $templateRepo
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewBasePath(dirname(dirname(__DIR__)) . '/views')
            ->withCsrf();

        $this->responseFactory = $responseFactory;
        $this->templateRepo = $templateRepo->withBrand($brandLocator->getBrand());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function view(Request $request): Response
    {
        $templateId = $request->getAttribute('id');
        if (empty($templateId) || ($template = $this->templateRepo->findByPK($templateId)) === null) {
            return $this->responseFactory->createResponse(404);
        }

        return $this->viewRenderer->render('view', compact('template'));
    }

    /**
     * @param Request $request
     * @param TemplateForm $templateForm
     * @param UrlGenerator $urlGenerator
     * @return Response
     */
    public function create(Request $request, TemplateForm $templateForm, UrlGenerator $urlGenerator): Response
    {
        $templateForm
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
        ;

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $templateForm->loadFromServerRequest($request);

            if (($template = $templateForm->save()) !== null) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/template/email/view', ['id' => $template->getId()]));
            }
        }

        return $this->viewRenderer->render('create', compact('templateForm', 'submitted'));
    }

    /**
     * @param Request $request
     * @param TemplateForm $templateForm
     * @param UrlGenerator $urlGenerator
     * @return Response
     */
    public function edit(Request $request, TemplateForm $templateForm, UrlGenerator $urlGenerator): Response
    {
        $templateId = $request->getAttribute('id');
        if (empty($templateId) || ($template = $this->templateRepo->findByPK($templateId)) === null) {
            return $this->responseFactory->createResponse(404);
        }

        $templateForm
            ->withTemplate($template)
            ->setAttributes([
                'action' => $request->getUri()->getPath(),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ])
        ;

        $submitted = $request->getMethod() === Method::POST;

        if ($submitted) {
            $templateForm->loadFromServerRequest($request);

            if ($templateForm->save() !== null) {
                return $this->responseFactory
                    ->createResponse(302)
                    ->withHeader('Location', $urlGenerator->generate('/template/email/view', ['id' => $template->getId()]));
            }
        }

        return $this->viewRenderer->render('edit', compact('template', 'templateForm', 'submitted'));
    }
}
