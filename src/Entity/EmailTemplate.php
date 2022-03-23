<?php

namespace Mailery\Template\Email\Entity;

use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Inheritance\SingleTable;
use Mailery\Template\Entity\Template;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Mailery\Common\Entity\RoutableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityInterface;

#[Entity]
#[SingleTable(value: EmailTemplate::class)]
class EmailTemplate extends Template implements RoutableEntityInterface, LoggableEntityInterface
{
    use LoggableEntityTrait;

    #[Column(type: 'string(255)')]
    private string $htmlEditor;

    #[Column(type: 'string(255)')]
    private string $textEditor;

    #[Column(type: 'text', nullable: true)]
    private ?string $htmlContent = null;

    #[Column(type: 'text', nullable: true)]
    private ?string $textContent = null;

    /**
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->htmlContent ?? '';
    }

    /**
     * @param string $htmlContent
     * @return self
     */
    public function setHtmlContent(string $htmlContent): self
    {
        $this->htmlContent = $htmlContent;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextContent(): string
    {
        return $this->textContent ?? '';
    }

    /**
     * @param string $textContent
     * @return self
     */
    public function setTextContent(string $textContent): self
    {
        $this->textContent = $textContent;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlEditor(): string
    {
        return $this->htmlEditor;
    }

    /**
     * @param string $htmlEditor
     * @return self
     */
    public function setHtmlEditor(string $htmlEditor): self
    {
        $this->htmlEditor = $htmlEditor;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextEditor(): string
    {
        return $this->textEditor;
    }

    /**
     * @param string $textEditor
     * @return self
     */
    public function setTextEditor(string $textEditor): self
    {
        $this->textEditor = $textEditor;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getIndexRouteName(): ?string
    {
        return '/template/default/index';
    }

    /**
     * @inheritdoc
     */
    public function getIndexRouteParams(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getViewRouteName(): ?string
    {
        return '/template/email/view';
    }

    /**
     * @inheritdoc
     */
    public function getViewRouteParams(): array
    {
        return ['id' => $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getPreviewRouteName(): ?string
    {
        return '/template/email/view';
    }

    /**
     * @inheritdoc
     */
    public function getPreviewRouteParams(): array
    {
        return ['id' => $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getEditRouteName(): ?string
    {
        return '/template/email/edit';
    }

    /**
     * @inheritdoc
     */
    public function getEditRouteParams(): array
    {
        return ['id' => $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getDeleteRouteName(): ?string
    {
        return '/template/default/delete';
    }

    /**
     * @inheritdoc
     */
    public function getDeleteRouteParams(): array
    {
        return ['id' => $this->getId()];
    }
}
