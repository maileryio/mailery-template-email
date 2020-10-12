<?php

namespace Mailery\Template\Email\Model;

use RuntimeException;
use Mailery\Template\Email\Model\EditorInterface;

class EditorList
{
    /**
     * @var EditorInterface[]
     */
    private array $items = [];

    /**
     * @param EditorInterface[] $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            if (!$item instanceof EditorInterface) {
                throw new RuntimeException('Editor must be implement EditorInterface');
            }
        }

        $this->items = $items;
    }

    /**
     * @return array
     */
    public function getValueOptions(): array
    {
        $options = [];
        foreach ($this->items as $item) {
            $options[$item->getName()] = $item->getLabel();
        }

        return array_filter($options);
    }

    /**
     * @param string|null $name
     * @return EditorInterface|null
     */
    public function findByName(?string $name): ?EditorInterface
    {
        foreach ($this->items as $item) {
            if ($item->getName() === $name) {
                return $item;
            }
        }

        return null;
    }
}