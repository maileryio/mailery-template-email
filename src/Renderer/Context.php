<?php

namespace Mailery\Template\Email\Renderer;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Template\Renderer\ContextInterface;

class Context implements ContextInterface
{

    public function __construct(
        private Campaign $campaign,
        private Recipient $recipient
    ) {}

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [];
    }

}
