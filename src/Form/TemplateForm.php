<?php

namespace Mailery\Template\Email\Form;

use FormManager\Factory as F;
use FormManager\Form;
use Mailery\Brand\Entity\Brand;
use Mailery\Brand\BrandLocatorInterface as BrandLocator;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Repository\TemplateRepository;
use Mailery\Template\Email\Service\TemplateCrudService;
use Mailery\Template\Email\ValueObject\TemplateValueObject;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Mailery\Template\Email\Model\EditorList;
use Mailery\Template\Email\Factory\EditorFactory;

class TemplateForm extends Form
{
    /**
     * @var Brand
     */
    private Brand $brand;

    /**
     * @var EmailTemplate|null
     */
    private ?EmailTemplate $template = null;

    /**
     * @var TemplateRepository
     */
    private TemplateRepository $templateRepo;

    /**
     * @var TemplateCrudService
     */
    private TemplateCrudService $templateCrudService;

    /**
     * @var EditorList
     */
    private EditorList $editorList;

    /**
     * @var EditorFactory
     */
    private EditorFactory $editorFactory;

    /**
     * @param BrandLocator $brandLocator
     * @param TemplateRepository $templateRepo
     * @param TemplateCrudService $templateCrudService
     * @param EditorList $editorList
     * @param EditorFactory $editorFactory
     */
    public function __construct(
        BrandLocator $brandLocator,
        TemplateRepository $templateRepo,
        TemplateCrudService $templateCrudService,
        EditorList $editorList,
        EditorFactory $editorFactory
    ) {
        $this->brand = $brandLocator->getBrand();
        $this->templateRepo = $templateRepo->withBrand($this->brand);
        $this->templateCrudService = $templateCrudService;
        $this->editorList = $editorList;
        $this->editorFactory = $editorFactory;
        parent::__construct($this->inputs());
    }

    /**
     * @param string $csrf
     * @return \self
     */
    public function withCsrf(string $value, string $name = '_csrf'): self
    {
        $this->offsetSet($name, F::hidden($value));

        return $this;
    }

    /**
     * @param EmailTemplate $template
     * @return self
     */
    public function withTemplate(EmailTemplate $template): self
    {
        $this->template = $template;
        $this->offsetSet('', F::submit('Update'));

        $this['name']->setValue($template->getName());
        $this['htmlEditor']->setValue($template->getHtmlEditor())->setAttribute('readonly', true);
        $this['textEditor']->setValue($template->getTextEditor());

        return $this;
    }

    /**
     * @return EmailTemplate|null
     */
    public function save(): ?EmailTemplate
    {
        if (!$this->isValid()) {
            return null;
        }

        $this['textEditor']->setValue($this->editorFactory->getTextAreaEditor()->getName());

        $valueObject = TemplateValueObject::fromForm($this)
            ->withBrand($this->brand);

        if (($template = $this->template) === null) {
            $template = $this->templateCrudService->create($valueObject);
        } else {
            $this->templateCrudService->update($template, $valueObject);
        }

        return $template;
    }

    /**
     * @return array
     */
    private function inputs(): array
    {
        $nameConstraint = new Constraints\Callback([
            'callback' => function ($value, ExecutionContextInterface $context) {
                if (empty($value)) {
                    return;
                }

                $template = $this->templateRepo->findByName($value, $this->template);
                if ($template !== null) {
                    $context->buildViolation('Template with this name already exists.')
                        ->atPath('name')
                        ->addViolation();
                }
            },
        ]);

        $htmlEditorOptions = $this->getHtmlEditorOptions();

        return [
            'name' => F::text('Template name')
                ->addConstraint(new Constraints\NotBlank())
                ->addConstraint(new Constraints\Length([
                    'min' => 4,
                ]))
                ->addConstraint($nameConstraint),
            'htmlEditor' => F::select('HTML editor', $htmlEditorOptions)
                ->addConstraint(new Constraints\NotBlank())
                ->addConstraint(new Constraints\Choice([
                    'choices' => array_keys($htmlEditorOptions)
                ])),
            'textEditor' => F::hidden($this->editorFactory->getTextAreaEditor()->getName()),

            '' => F::submit($this->template === null ? 'Create' : 'Update'),
        ];
    }

    /**
     * @return array
     */
    private function getHtmlEditorOptions(): array
    {
        return $this->editorList->getValueOptions();
    }
}
