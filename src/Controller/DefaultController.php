<?php

declare(strict_types=1);

namespace Mailery\Template\Email\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Http\Method;
use Yiisoft\Http\Status;
use Yiisoft\Http\Header;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Yiisoft\Validator\ValidatorInterface;
use Mailery\Template\Email\Form\TemplateForm;
use Mailery\Template\Email\Form\ContentForm;
use Mailery\Template\Email\ValueObject\TemplateValueObject;
use Mailery\Template\Email\Service\TemplateCrudService;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Mailery\Template\Repository\TemplateRepository;
use Mailery\Brand\BrandLocatorInterface;
use Yiisoft\Session\Flash\FlashInterface;

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
     * @var UrlGenerator
     */
    private UrlGenerator $urlGenerator;

    /**
     * @var TemplateRepository
     */
    private TemplateRepository $templateRepo;

    /**
     * @var TemplateCrudService
     */
    private TemplateCrudService $templateCrudService;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param UrlGenerator $urlGenerator
     * @param BrandLocatorInterface $brandLocator
     * @param TemplateRepository $templateRepo
     * @param TemplateCrudService $templateCrudService
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactory $responseFactory,
        UrlGenerator $urlGenerator,
        BrandLocatorInterface $brandLocator,
        TemplateRepository $templateRepo,
        TemplateCrudService $templateCrudService
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewPath(dirname(dirname(__DIR__)) . '/views');

        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->templateRepo = $templateRepo->withBrand($brandLocator->getBrand());
        $this->templateCrudService = $templateCrudService->withBrand($brandLocator->getBrand());
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param ContentForm $form
     * @return Response
     */
    public function view(Request $request, ValidatorInterface $validator, ContentForm $form): Response
    {
        $body = $request->getParsedBody();
        $templateId = $request->getAttribute('id');
        if (empty($templateId) || ($template = $this->templateRepo->findByPK($templateId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = $form->withEntity($template);

        if ($request->getMethod() === Method::POST && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = TemplateValueObject::fromContentForm($form);
            $this->templateCrudService->updateContent($template, $valueObject);

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/template/email/view', ['id' => $template->getId()]));
        }

        return $this->viewRenderer->render('view', compact('form', 'template'));
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param TemplateForm $form
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator, TemplateForm $form): Response
    {
        $body = $request->getParsedBody();

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = TemplateValueObject::fromForm($form);
            $template = $this->templateCrudService->create($valueObject);

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/template/email/view', ['id' => $template->getId()]));
        }

        return $this->viewRenderer->render('create', compact('form'));
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param FlashInterface $flash
     * @param TemplateForm $form
     * @return Response
     */
    public function edit(Request $request, ValidatorInterface $validator, FlashInterface $flash, TemplateForm $form): Response
    {
        $body = $request->getParsedBody();
        $templateId = $request->getAttribute('id');
        if (empty($templateId) || ($template = $this->templateRepo->findByPK($templateId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = $form->withEntity($template);

        if ($request->getMethod() === Method::POST && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = TemplateValueObject::fromForm($form);
            $this->templateCrudService->update($template, $valueObject);

            $flash->add(
                'success',
                [
                    'body' => 'Data have been saved!',
                ],
                true
            );

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $this->urlGenerator->generate('/template/email/view', ['id' => $template->getId()]));
        }

        return $this->viewRenderer->render('edit', compact('form', 'template'));
    }
}
