<?php

namespace Mailery\Template\Email\Form;

use Mailery\Brand\BrandLocatorInterface as BrandLocator;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Repository\TemplateRepository;
use Mailery\Template\Editor\EditorList;
use Mailery\Template\Email\Model\TextAreaEditor;
use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;
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
    private ?string $description = null;

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
    private ?EmailTemplate $entity = null;

    /**
     * @var TemplateRepository
     */
    private TemplateRepository $entityRepo;

    /**
     * @var EditorList
     */
    private EditorList $editorList;

    /**
     * @param BrandLocator $brandLocator
     * @param TemplateRepository $entityRepo
     * @param EditorList $editorList
     * @param TextAreaEditor $textAreaEditor
     */
    public function __construct(
        BrandLocator $brandLocator,
        TemplateRepository $entityRepo,
        EditorList $editorList,
        TextAreaEditor $textAreaEditor
    ) {
        $this->entityRepo = $entityRepo->withBrand($brandLocator->getBrand());
        $this->editorList = $editorList;
        $this->textEditor = $textAreaEditor->getName();

        parent::__construct();
    }

    /**
     * @param EmailTemplate $entity
     * @return self
     */
    public function withEntity(EmailTemplate $entity): self
    {
        $new = clone $this;
        $new->entity = $entity;
        $new->name = $entity->getName();
        $new->description = $entity->getDescription();
        $new->htmlEditor = $entity->getHtmlEditor();
        $new->textEditor = $entity->getTextEditor();

        return $new;
    }

    /**
     * @return bool
     */
    public function hasEntity(): bool
    {
        return $this->entity !== null;
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
    public function getDescription(): ?string
    {
        return $this->description;
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
            'description' => 'Description (optional)',
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
                    $record = $this->entityRepo->findByName($value, $this->entity);

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
