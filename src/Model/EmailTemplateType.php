<?php

namespace Mailery\Template\Email\Model;

use Mailery\Template\Entity\Template;
use Mailery\Template\Model\TemplateTypeInterface;

class EmailTemplateType implements TemplateTypeInterface
{

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return self::class;
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'Email template';
    }

    /**
     * @inheritdoc
     */
    public function getCreateLabel(): string
    {
        return 'Email template';
    }

    /**
     * @inheritdoc
     */
    public function getCreateRouteName(): ?string
    {
        return '/template/email/create';
    }

    /**
     * @inheritdoc
     */
    public function getCreateRouteParams(): array
    {
        return [];
    }

    /**
     * @param Template $entity
     * @return bool
     */
    public function isEntitySameType(Template $entity): bool
    {
        return $entity->getType() === $this->getName();
    }
}
