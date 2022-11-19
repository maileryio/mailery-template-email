<?php

namespace Mailery\Template\Email\Widget;

use Mailery\Template\Editor\EditorWidgetInterface;
use Yiisoft\Widget\Widget;
use Yiisoft\Form\Field;
use Yiisoft\Form\FormModelInterface;
use Mailery\Web\Vue\Directive;

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
        return Directive::pre(Field::textarea($this->data, $this->attribute, $this->options));
    }

}
