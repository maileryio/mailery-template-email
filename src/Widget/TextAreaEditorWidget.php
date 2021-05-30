<?php

namespace Mailery\Template\Email\Widget;

use Mailery\Template\Email\Model\EditorWidgetInterface;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget as BaseWidget;

class TextAreaEditorWidget extends BaseWidget implements EditorWidgetInterface
{

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $value;

    /**
     * @param string $name
     * @return self
     */
    public function withName(string $name): self
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * @param string $value
     * @return self
     */
    public function withValue(string $value): self
    {
        $new = clone $this;
        $new->value = $value;

        return $new;
    }

    /**
     * {@inheritdoc}
     */
    protected function run(): string
    {
        return Html::textarea($this->name, $this->value)
            ->class('form-control')
            ->rows(5);
    }

}
