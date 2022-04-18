<?php

namespace Mailery\Template\Email\Form;

use Mailery\Brand\BrandLocatorInterface as BrandLocator;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Repository\TemplateRepository;
use Mailery\Template\Email\Model\EditorList;
use Mailery\Template\Email\Model\TextAreaEditor;
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
     * @param TextAreaEditor $textAreaEditor
     */
    public function __construct(
        BrandLocator $brandLocator,
        TemplateRepository $templateRepo,
        EditorList $editorList,
        TextAreaEditor $textAreaEditor
    ) {
        $this->templateRepo = $templateRepo->withBrand($brandLocator->getBrand());
        $this->editorList = $editorList;
        $this->textEditor = $textAreaEditor->getName();

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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getHtmlEditor(): ?string
    {
        return $this->htmlEditor;
    }

    /**
     * @return string|null
     */
    public function getTextEditor(): ?string
    {
        return $this->textEditor;
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
                Required::rule(),
                HasLength::rule()->min(3)->max(255),
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
                Required::rule(),
                InRange::rule(array_keys($this->getHtmlEditorOptions())),
            ],
            'textEditor' => [
                Required::rule(),
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
