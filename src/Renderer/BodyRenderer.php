<?php

namespace Mailery\Template\Email\Renderer;

use Mailery\Template\Renderer\BodyRendererInterface;
use Mailery\Template\Renderer\ContextInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Twig\Environment;

class BodyRenderer implements BodyRendererInterface
{

    /**
     * @var ContextInterface
     */
    private ContextInterface $context;

    /**
     * @param Environment $twig
     */
    public function __construct(
        private Environment $twig
    ) {}

    /**
     * @inheritdoc
     */
    public function withContext(ContextInterface $context): self
    {
        $new = clone $this;
        $new->context = $context;

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function render(Message $message): void
    {
        if (!$message instanceof Email) {
            throw new \RuntimeException(sprintf('The message must be instance of %s', Email::class));
        }

        $vars = $this->context->toArray();

        $message->subject(
            $this->twig->createTemplate($message->getSubject())->render($vars)
        );

        $message->text(
            $this->twig->createTemplate($message->getTextBody())->render($vars)
        );

        $message->html(
            $this->twig->createTemplate($message->getHtmlBody())->render($vars)
        );
    }

}
