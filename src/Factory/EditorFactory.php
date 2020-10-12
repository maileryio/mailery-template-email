<?php

namespace Mailery\Template\Email\Factory;

use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Email\Model\EditorList;
use Mailery\Template\Email\Model\EditorInterface;

class EditorFactory
{
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
    }

    /**
     * @param EmailTemplate $template
     * @return EditorInterface
     */
    public function getEditor(EmailTemplate $template): EditorInterface
    {
        return $this->editorList->findByName($template->getEditor());
    }
}
