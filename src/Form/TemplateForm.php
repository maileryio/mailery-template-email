<?php

namespace Mailery\Template\Email\Form;

use Cycle\ORM\ORMInterface;
use FormManager\Factory as F;
use FormManager\Form;
use Mailery\Brand\Entity\Brand;
use Mailery\Brand\Service\BrandLocatorInterface as BrandLocator;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Repository\TemplateRepository;
use Mailery\Template\Email\Service\TemplateCrudService;
use Mailery\Template\Email\ValueObject\TemplateValueObject;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Mailery\Template\Email\Model\EmailEditorList;
use Psr\Http\Message\ServerRequestInterface;

class TemplateForm extends Form
{
    /**
     * @var Brand
     */
    private Brand $brand;

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
     * @var EmailEditorList
     */
    private EmailEditorList $emailEditorList;

    /**
     * @param BrandLocator $brandLocator
     * @param TemplateCrudService $templateCrudService
     * @param EmailEditorList $emailEditorList
     * @param ORMInterface $orm
     */
    public function __construct(
        BrandLocator $brandLocator,
        TemplateCrudService $templateCrudService,
        EmailEditorList $emailEditorList,
        ORMInterface $orm
    ) {
        $this->orm = $orm;
        $this->brand = $brandLocator->getBrand();
        $this->templateCrudService = $templateCrudService;
        $this->emailEditorList = $emailEditorList;
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
        $this->offsetSet('', F::submit('Update'));

        $this['name']->setValue($template->getName());
        $this['editor']->setValue($template->getEditor());

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

        $valueObject = TemplateValueObject::fromForm($this)
            ->withBrand($this->brand);

        if (($template = $this->template) === null) {
            $template = $this->templateCrudService->create($valueObject);
        } else {
            $this->templateCrudService->update($template, $valueObject);
        }

        return $template;
    }

    /**
     * @return array
     */
    private function inputs(): array
    {
        $editorOptions = $this->getEditorOptions();

        $nameConstraint = new Constraints\Callback([
            'callback' => function ($value, ExecutionContextInterface $context) {
                if (empty($value)) {
                    return;
                }

                $template = $this->getTemplateRepository()->findByName($value, $this->template);
                if ($template !== null) {
                    $context->buildViolation('Template with this name already exists.')
                        ->atPath('name')
                        ->addViolation();
                }
            },
        ]);

        return [
            'name' => F::text('Template name')
                ->addConstraint(new Constraints\NotBlank())
                ->addConstraint(new Constraints\Length([
                    'min' => 4,
                ]))
                ->addConstraint($nameConstraint),
            'editor' => F::select('Editor', $editorOptions, ['readonly' => 'readonly'])
                ->addConstraint(new Constraints\NotBlank())
                ->addConstraint(new Constraints\Choice([
                    'choices' => array_keys($editorOptions)
                ])),
            '' => F::submit($this->template === null ? 'Create' : 'Update'),
        ];
    }

    /**
     * @return array
     */
    private function getEditorOptions(): array
    {
        return $this->emailEditorList->getValueOptions();
    }

    /**
     * @return TemplateRepository
     */
    private function getTemplateRepository(): TemplateRepository
    {
        return $this->orm->getRepository(EmailTemplate::class)
            ->withBrand($this->brand);
    }
}
