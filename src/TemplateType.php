<?php

namespace Mailery\Template\Email;

use Mailery\Template\TemplateTypeInterface;

class TemplateType implements TemplateTypeInterface
{
    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'Email messaging';
    }

    /**
     * @inheritdoc
     */
    public function getShortLabel(): string
    {
        return 'Email';
    }

    /**
     * @inheritdoc
     */
    public function getCreateRouteName(): ?string
    {
        return '/message/email/create';
    }

    /**
     * @inheritdoc
     */
    public function getCreateRouteParams(): array
    {
        return [];
    }
}
