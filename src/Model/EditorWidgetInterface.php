<?php

namespace Mailery\Template\Email\Model;

interface EditorWidgetInterface
{
    /**
     * @param string $name
     * @return self
     */
    public function withName(string $name): self;

    /**
     * @param string $value
     * @return self
     */
    public function withValue(string $value): self;
}
