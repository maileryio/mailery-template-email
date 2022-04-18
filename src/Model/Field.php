<?php

namespace Mailery\Template\Email\Model;

use Mailery\Template\Entity\Template;
use Mailery\Template\Email\Model\EditorList;
use Yiisoft\Widget\Widget;
use Yiisoft\Form\FormModelInterface;

class Field extends Widget
{

    /**
     * @var FormModelInterface
     */
    private FormModelInterface $data;

    /**
     * @var string
     */
    private string $attribute;

    /**
     * @var Template
     */
    private Template $template;

    /**
     * @var EditorList
     */
    private EditorList $editorList;

    /**
     * @var string
     */
    private string $content = '';

    /**
     * @param EditorList $editorList
     * @return self
     */
    public function withEditorList(EditorList $editorList): self
    {
        $new = clone $this;
        $new->editorList = $editorList;
        return $new;
    }

    /**
     * @param Template $template
     * @return self
     */
    public function withEntity(Template $template): self
    {
        $new = clone $this;
        $new->template = $template;
        return $new;
    }

    /**
     * @param FormModelInterface $data
     * @param string $attribute
     * @return self
     */
    public function config(FormModelInterface $data, string $attribute): self
    {
        $new = clone $this;
        $new->data = $data;
        $new->attribute = $attribute;
        return $new;
    }

    /**
     * @param array $options
     * @return self
     */
    public function textInput(array $options = []): self
    {
        $new = clone $this;
        $new->content = $this->editorList
            ->findByName($this->template->getTextEditor())
            ->getWidget()
            ->config($new->data, $new->attribute)
            ->options($options);

        return $new;
    }

    /**
     * @param array $options
     * @return self
     */
    public function htmlInput(array $options = []): self
    {
        $new = clone $this;
        $new->content = $this->editorList
            ->findByName($this->template->getHtmlEditor())
            ->getWidget()
            ->config($new->data, $new->attribute)
            ->options($options);

        return $new;
    }

    /**
     * @inheritdoc
     */
    protected function run(): string
    {
        return $this->content;
    }

}
