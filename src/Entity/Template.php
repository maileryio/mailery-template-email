<?php

namespace Mailery\Template\Email\Entity;

use Mailery\Template\Entity\Template as BaseTemplate;
use Mailery\Activity\Log\Entity\LoggableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Mailery\Common\Entity\RoutableEntityInterface;

/**
 * @Cycle\Annotated\Annotation\Entity
 */
class Template extends BaseTemplate implements RoutableEntityInterface, LoggableEntityInterface
{
    use LoggableEntityTrait;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "text")
     * @var string
     */
    private $textContent;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "text")
     * @var string
     */
    private $htmlContent;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "text")
     * @var string
     */
    private $mjmlContent;

    /**
     * @return string
     */
    public function getTextContent(): string
    {
        return $this->textContent;
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
    public function getHtmlContent(): string
    {
        return $this->htmlContent;
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
    public function getMjmlContent(): string
    {
        return $this->mjmlContent;
    }

    /**
     * @param string $mjmlContent
     * @return self
     */
    public function setMjmlContent(string $mjmlContent): self
    {
        $this->mjmlContent = $mjmlContent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEditRouteName(): ?string
    {
        return '/message/email/edit';
    }

    /**
     * {@inheritdoc}
     */
    public function getEditRouteParams(): array
    {
        return ['id' => $this->getId()];
    }

    /**
     * {@inheritdoc}
     */
    public function getViewRouteName(): ?string
    {
        return '/message/email/view';
    }

    /**
     * {@inheritdoc}
     */
    public function getViewRouteParams(): array
    {
        return ['id' => $this->getId()];
    }
}
