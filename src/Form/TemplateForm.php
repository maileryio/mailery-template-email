<?php

namespace Mailery\Template\Email\Form;

use Mailery\Brand\BrandLocatorInterface as BrandLocator;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Repository\TemplateRepository;
use Mailery\Template\Email\Model\EditorList;
use Mailery\Template\Email\Factory\EditorFactory;
use Yiisoft\Form\FormModel;
use Yiisoft\Form\HtmlOptions\RequiredHtmlOptions;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Form\HtmlOptions\HasLengthHtmlOptions;
use Yiisoft\Validator\Rule\HasLength;
use Yiisoft\Validator\Rule\InRange;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Result;

class TemplateForm extends FormModel
{

    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $htmlEditor = null;

    /**
     * @var string|null
     */
    private ?string $textEditor = null;

    /**
     * @var EmailTemplate|null
     */
    private ?EmailTemplate $template = null;

    /**
     * @var TemplateRepository
     */
    private TemplateRepository $templateRepo;

    /**
     * @var EditorList
     */
    private EditorList $editorList;

    /**
     * @param BrandLocator $brandLocator
     * @param TemplateRepository $templateRepo
     * @param EditorList $editorList
     * @param EditorFactory $editorFactory
     */
    public function __construct(
        BrandLocator $brandLocator,
        TemplateRepository $templateRepo,
        EditorList $editorList,
        EditorFactory $editorFactory
    ) {
        $this->templateRepo = $templateRepo->withBrand($brandLocator->getBrand());
        $this->editorList = $editorList;
        $this->textEditor = $editorFactory->getTextAreaEditor()->getName();

        parent::__construct();
    }

    /**
     * @param EmailTemplate $template
     * @return self
     */
    public function withEntity(EmailTemplate $template): self
    {
        $new = clone $this;
        $new->template = $template;
        $new->name = $template->getName();
        $new->htmlEditor = $template->getHtmlEditor();
        $new->textEditor = $template->getTextEditor();

        return $new;
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'name' => 'Template name',
            'htmlEditor' => 'HTML editor',
            'textEditor' => 'Text editor',
        ];
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'name' => [
                new RequiredHtmlOptions(Required::rule()),
                new HasLengthHtmlOptions(HasLength::rule()->max(255)),
                Callback::rule(function ($value) {
                    $result = new Result();
                    $record = $this->templateRepo->findByName($value, $this->template);

                    if ($record !== null) {
                        $result->addError('Template with this name already exists.');
                    }

                    return $result;
                })
            ],
            'htmlEditor' => [
                new RequiredHtmlOptions(Required::rule()),
                InRange::rule(array_keys($this->getHtmlEditorOptions())),
            ],
            'textEditor' => [
                new RequiredHtmlOptions(Required::rule()),
            ],
        ];
    }

    /**
     * @return array
     */
    public function getHtmlEditorOptions(): array
    {
        return $this->editorList->getValueOptions();
    }

}
