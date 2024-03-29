<?php

namespace Mailery\Template\Email\ValueObject;

use Mailery\Template\Email\Form\TemplateForm;
use Mailery\Template\Email\Form\ContentForm;

class TemplateValueObject
{

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @var string
     */
    private string $htmlEditor;

    /**
     * @var string
     */
    private string $textEditor;

    /**
     * @var string
     */
    private string $htmlContent;

    /**
     * @var string
     */
    private string $textContent;

    /**
     * @param TemplateForm $form
     * @return self
     */
    public static function fromForm(TemplateForm $form): self
    {
        $new = new self();

        $new->name = $form->getName();
        $new->description = $form->getDescription();
        $new->htmlEditor = $form->getHtmlEditor();
        $new->textEditor = $form->getTextEditor();

        return $new;
    }

    /**
     * @param ContentForm $form
     * @return self
     */
    public static function fromContentForm(ContentForm $form): self
    {
        $new = new self();
        $new->htmlContent = $form->getHtmlContent();
        $new->textContent = $form->getTextContent();

        return $new;
    }

    /**
     * @return string
     */
    public function getName(): string
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
     * @return string
     */
    public function getHtmlEditor(): string
    {
        return $this->htmlEditor;
    }

    /**
     * @return string
     */
    public function getTextEditor(): string
    {
        return $this->textEditor;
    }

    /**
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }

    /**
     * @return string
     */
    public function getTextContent(): string
    {
        return $this->textContent;
    }

}
