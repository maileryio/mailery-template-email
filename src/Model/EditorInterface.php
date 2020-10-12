<?php

namespace Mailery\Template\Email\Model;

interface EditorInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return EditorWidgetInterface
     */
    public function getWidget(): EditorWidgetInterface;
}
