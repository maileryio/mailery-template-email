<?php

namespace Mailery\Template\Email\Widget;

use Mailery\Template\Email\Model\EditorWidgetInterface;
use Yiisoft\Widget\Widget;
use Yiisoft\Form\Widget\Field;
use Yiisoft\Form\FormModelInterface;

class TextAreaEditorWidget extends Widget implements EditorWidgetInterface
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
     * @var array
     */
    private array $options = [];

    /**
     * @inheritdoc
     */
    public function config(FormModelInterface $data, string $attribute): self
    {
        $new = clone $this;
        $new->data = $data;
        $new->attribute = $attribute;
        return $new;
    }

    /**
     * @inheritdoc
     */
    public function options(array $options = []): self
    {
        $new = clone $this;
        $new->options = $options;
        return $new;
    }

    /**
     * @inheritdoc
     */
    protected function run(): string
    {
        return Field::widget()
            ->textArea($this->data, $this->attribute, $this->options);
    }

}
