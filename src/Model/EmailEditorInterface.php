<?php

namespace Mailery\Template\Email\Model;

interface EmailEditorInterface
{
    /**
     * @param string|null $value
     * @return bool
     */
    public function match(?string $value): bool;
}
