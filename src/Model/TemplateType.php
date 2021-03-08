<?php

namespace Mailery\Template\Email\Model;

use Mailery\Template\Model\TemplateTypeInterface;
use Mailery\Template\Email\Entity\EmailTemplate;

class TemplateType implements TemplateTypeInterface
{
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
     * @param object $entity
     * @return bool
     */
    public function isEntitySameType(object $entity): bool
    {
        return $entity instanceof EmailTemplate;
    }
}
