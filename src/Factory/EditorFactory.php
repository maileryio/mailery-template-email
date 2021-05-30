<?php

namespace Mailery\Template\Email\Factory;

use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Email\Model\EditorList;
use Mailery\Template\Email\Model\EditorInterface;
use Mailery\Template\Email\Model\TextAreaEditor;

class EditorFactory
{
    /**
     * @var EditorList
     */
    private EditorList $editorList;

    /**
     * @var TextAreaEditor
     */
    private TextAreaEditor $textAreaEditor;

    /**
     * @param EditorList $editorList
     * @param TextAreaEditor $textAreaEditor
     */
    public function __construct(EditorList $editorList, TextAreaEditor $textAreaEditor)
    {
        $this->editorList = $editorList;
        $this->textAreaEditor = $textAreaEditor;
    }

    /**
     * @return EditorInterface
     */
    public function getTextAreaEditor(): EditorInterface
    {
        return $this->textAreaEditor;
    }

    /**
     * @param EmailTemplate $template
     * @return EditorInterface
     */
    public function getHtmlEditor(EmailTemplate $template): EditorInterface
    {
        return $this->editorList->findByName($template->getHtmlEditor());
    }

    /**
     * @param EmailTemplate $template
     * @return EditorInterface
     */
    public function getTextEditor(EmailTemplate $template): EditorInterface
    {
        return $this->editorList->findByName($template->getTextEditor());
    }
}
