<?php

namespace Mailery\Template\Email\Renderer;

use Mailery\Template\Renderer\BodyRendererInterface;
use Mailery\Template\Renderer\Context;
use Mailery\Template\Renderer\ContextInterface;
use Mailery\Template\Email\Renderer\WrappedMessage;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Twig\Environment;
use Twig\NodeVisitor\NodeVisitorInterface;

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
    ) {
        $this->context = new Context();
    }

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
    public function addNodeVisitor(NodeVisitorInterface $visitor): self
    {
        $new = clone $this;
        $new->twig->addNodeVisitor($visitor);

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

        if ($this->context->has('message')) {
            throw new \RuntimeException('A context cannot have an "message" entry as this is a reserved variable.');
        }

        $vars = $this->context
            ->add('message', new WrappedMessage($message))
            ->toArray();

        if (($subject = $message->getSubject()) !== null) {
            $message->subject(
                $this->twig->createTemplate($subject)->render($vars)
            );
        }

        if (($textBody = $message->getTextBody()) !== null) {
            $message->text(
                $this->twig->createTemplate($textBody)->render($vars)
            );
        }

        if (($htmlBody = $message->getHtmlBody()) !== null) {
            $message->html(
                $this->twig->createTemplate($htmlBody)->render($vars)
            );
        }
    }

}
