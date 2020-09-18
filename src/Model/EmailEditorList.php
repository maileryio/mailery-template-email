<?php

namespace Mailery\Template\Email\Model;

use RuntimeException;
use Mailery\Template\Email\Model\EmailEditorInterface;

class EmailEditorList
{
    /**
     * @var EmailEditorInterface[]
     */
    private array $items = [];

    /**
     * @param EmailEditorInterface[] $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            if (!$item instanceof EmailEditorInterface) {
                throw new RuntimeException('Editor must be implement EmailEditorInterface');
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
            $options[$item->getValue()] = $item->getLabel();
        }

        return array_filter($options);
    }

    /**
     * @param string|null $value
     * @return EmailEditorInterface|null
     */
    public function findByValue(?string $value): ?EmailEditorInterface
    {
        foreach ($this->items as $item) {
            if ($item->match($value)) {
                return $item;
            }
        }

        return null;
    }
}