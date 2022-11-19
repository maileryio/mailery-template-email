<?php

namespace Mailery\Template\Email\Form;

use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Editor\EditorList;
use Mailery\Template\Email\Model\Field;
use Yiisoft\Form\FormModel;
use Yiisoft\Widget\Widget;

class ContentForm extends FormModel
{
    /**
     * @var string|null
     */
    private ?string $htmlContent = null;

    /**
     * @var string|null
     */
    private ?string $textContent = null;

    /**
     * @var EmailTemplate|null
     */
    private ?EmailTemplate $template;

    /**
     * @var EditorList
     */
    private EditorList $editorList;

    /**
     * @param EditorList $editorList
     */
    public function __construct(EditorList $editorList)
    {
        $this->editorList = $editorList;

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
        $new->htmlContent = $template->getHtmlContent();
        $new->textContent = $template->getTextContent();

        return $new;
    }

    /**
     * @return string|null
     */
    public function getHtmlContent(): ?string
    {
        return $this->htmlContent;
    }

    /**
     * @return string|null
     */
    public function getTextContent(): ?string
    {
        return $this->textContent;
    }

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'htmlContent' => 'HTML content',
            'textContent' => 'Plain text version',
        ];
    }

    /**
     * @return Widget
     */
    public function getField(): Widget
    {
        return (new Field())
            ->withEditorList($this->editorList)
            ->withEntity($this->template);
    }

}
