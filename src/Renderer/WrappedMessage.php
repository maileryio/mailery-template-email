<?php

namespace Mailery\Template\Email\Renderer;

use Symfony\Component\Mime\Email;

class WrappedMessage
{

    /**
     * @param Email $message
     */
    public function __construct(
        private Email $message
    ) {}

    /**
     * @return string
     */
    public function getToName(): string
    {
        return $this->message->getTo()[0]->getName();
    }

    /**
     * @return string
     */
    public function getToEmail(): string
    {
        return $this->message->getTo()[0]->getAddress();
    }

    /**
     * @return string
     */
    public function getReplyToName(): string
    {
        return $this->message->getReplyTo()[0]->getName();
    }

    /**
     * @return string
     */
    public function getReplyToEmail(): string
    {
        return $this->message->getReplyTo()[0]->getAddress();
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->message->getFrom()[0]->getName();
    }

    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->message->getFrom()[0]->getAddress();
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->message->getSubject();
    }

    /**
     * @return string|null
     */
    public function getReturnPath(): ?string
    {
        return $this->message->getReturnPath()?->toString();
    }

}
