<?php

namespace Mailery\Template\Email\Model;

interface EmailEditorInterface
{
    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @return string
     */
    public function getLabel(): string;
}
