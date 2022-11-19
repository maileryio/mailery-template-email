<?php

namespace Mailery\Template\Email\Model;

use Mailery\Template\Editor\EditorInterface;
use Mailery\Template\Editor\EditorWidgetInterface;
use Mailery\Template\Email\Widget\TextAreaEditorWidget;

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
