<?php

namespace Mailery\Template\Email\Form;

use Cycle\ORM\ORMInterface;
use FormManager\Factory as F;
use FormManager\Form;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Email\Service\TemplateCrudService;
use Mailery\Template\Email\ValueObject\TemplateValueObject;
use Symfony\Component\Validator\Constraints;
use Mailery\Template\Email\Factory\EditorFactory;

class ContentForm extends Form
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @var EmailTemplate|null
     */
    private ?EmailTemplate $template;

    /**
     * @var TemplateCrudService
     */
    private TemplateCrudService $templateCrudService;

    /**
     * @var EditorFactory
     */
    private EditorFactory $editorFactory;

    /**
     * @param TemplateCrudService $templateCrudService
     * @param EditorFactory $editorFactory
     * @param ORMInterface $orm
     */
    public function __construct(
        TemplateCrudService $templateCrudService,
        EditorFactory $editorFactory,
        ORMInterface $orm
    ) {
        $this->orm = $orm;
        $this->templateCrudService = $templateCrudService;
        $this->editorFactory = $editorFactory;
        parent::__construct($this->inputs());
    }

    /**
     * @param string $csrf
     * @return \self
     */
    public function withCsrf(string $value, string $name = '_csrf'): self
    {
        $this->offsetSet($name, F::hidden($value));

        return $this;
    }

    /**
     * @param EmailTemplate $template
     * @return self
     */
    public function withTemplate(EmailTemplate $template): self
    {
        $this->template = $template;
        $this->offsetSet(
            'htmlContent',
            $this['htmlContent']
                ->withEditor($this->editorFactory->getHtmlEditor($template))
                ->setValue($template->getHtmlContent())
        );
        $this->offsetSet(
            'textContent',
            $this['textContent']
                ->withEditor($this->editorFactory->getTextEditor($template))
                ->setValue($template->getTextContent())
        );

        return $this;
    }

    /**
     * @return EmailTemplate|null
     */
    public function save(): ?EmailTemplate
    {
        if (!$this->isValid()) {
            return null;
        }

        $valueObject = TemplateValueObject::fromContentForm($this);

        $this->templateCrudService->updateContent($this->template, $valueObject);

        return $this->template;
    }

    /**
     * @return array
     */
    private function inputs(): array
    {
        return [
            'htmlContent' => (new ContentInput('HTML content'))
                ->addConstraint(new Constraints\NotBlank()),
            'textContent' => (new ContentInput('Plain text content'))
                ->addConstraint(new Constraints\NotBlank()),
            '' => F::submit('Save'),
        ];
    }
}
