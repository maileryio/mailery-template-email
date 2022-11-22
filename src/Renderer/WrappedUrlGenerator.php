<?php

namespace Mailery\Template\Email\Renderer;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Yiisoft\Router\UrlGeneratorInterface;

class WrappedUrlGenerator
{

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param Campaign $campaign
     * @param Recipient $recipient
     */
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private Campaign $campaign,
        private Recipient $recipient
    ) {}

    /**
     * @return string
     */
    public function getWebversion(): string
    {
        return 'http://mailery.localhost/webversion';
    }

    /**
     * @return string
     */
    public function getUnsubscribe(): string
    {
        return 'http://mailery.localhost/unsubscribe';
    }

}