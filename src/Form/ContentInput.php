<?php

namespace Mailery\Template\Email\Form;

use Mailery\Template\Email\Model\EditorInterface;
use FormManager\Inputs\Textarea;
use FormManager\Node;
use Mailery\Template\Email\Form\ContentRenderer;

class ContentInput extends Textarea
{
    /**
     * @var EditorInterface|null
     */
    private ?EditorInterface $editor = null;

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        if ($this->editor !== null) {
            $content = $this->editor
                ->getWidget()
                ->withName($this->getAttribute('name'))
                ->withValue($this->getValue());
        } else {
            $content = Node::__toString();
        }

        if ($this->label) {
            return strtr($this->template, [
                '{{ label }}' => (string) $this->label,
                '{{ input }}' => $content
            ]);
        }

        return $content;
    }

    /**
     * @param EditorInterface $editor
     * @return self
     */
    public function withEditor(EditorInterface $editor): self
    {
        $new = clone $this;
        $new->editor = $editor;

        return $new;
    }

    /**
     * @return ContentRenderer
     */
    public function getRenderer(): ContentRenderer
    {
        return new ContentRenderer($this);
    }
}
