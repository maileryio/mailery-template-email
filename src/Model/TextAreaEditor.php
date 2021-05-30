<?php

namespace Mailery\Template\Email\Model;

use Mailery\Template\Email\Model\EditorInterface;
use Mailery\Template\Email\Widget\TextAreaEditorWidget;
use Mailery\Template\Email\Model\EditorWidgetInterface;

class TextAreaEditor implements EditorInterface
{
    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'textarea';
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'Textarea';
    }

    /**
     * @inheritdoc
     */
    public function getWidget(): EditorWidgetInterface
    {
        return TextAreaEditorWidget::widget();
    }
}
