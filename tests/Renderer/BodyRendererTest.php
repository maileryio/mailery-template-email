<?php

declare(strict_types=1);

namespace Mailery\Template\Email\Tests\Renderer;

use Mailery\Template\Renderer\Context;
use Mailery\Template\Email\Renderer\BodyRenderer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email as Message;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class BodyRendererTest extends TestCase
{

    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * @var BodyRenderer
     */
    private BodyRenderer $bodyRenderer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->twig = new Environment(
            new ArrayLoader(),
            [
                'charset' => 'utf-8',
            ]
        );

        $this->bodyRenderer = new BodyRenderer($this->twig);
    }

    /**
     * @return void
     */
    public function testRender(): void
    {
        $message = (new Message())
            ->subject('sebject {{ variable1 }}')
            ->text('text {{ variable2 }}')
            ->html('html {{ variable3|upper }}');

        $this->bodyRenderer
            ->withContext(new Context([
                'variable1' => 'a',
                'variable2' => 'b',
                'variable3' => 'c',
            ]))
            ->render($message);

        $this->assertEquals($message->getSubject(), 'sebject a');
        $this->assertEquals($message->getTextBody(), 'text b');
        $this->assertEquals($message->getHtmlBody(), 'html C');
    }

    /**
     * @return void
     */
    public function testUnablePassMessageVariableToContext(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('A context cannot have an "message" entry as this is a reserved variable.');

        $this->bodyRenderer
            ->withContext(new Context([
                'message' => 'message',
            ]))
            ->render(new Message());
    }

    /**
     * @return void
     */
    public function testAccessWrappedMessge(): void
    {
        $message = (new Message())
            ->to(new Address('test@email.test', 'Test Name'))
            ->subject('sebject {{ message.toEmail }} {{ message.toName }}');

        $this->bodyRenderer->render($message);

        $this->assertEquals($message->getSubject(), 'sebject test@email.test Test Name');
    }

}
