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
     * @var Context
     */
    private Context $context;

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
            return;
        }

        $context = $this->context->toArray();

        if (isset($context['email'])) {
            throw new \InvalidArgumentException(sprintf('A "%s" context cannot have an "email" entry as this is a reserved variable.', get_debug_type($message)));
        }

        $vars = array_merge(
            $context,
            [
                'email' => new WrappedEmail($message),
            ]
        );

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
