<?php

namespace Mailery\Template\Email\Form;

use Cycle\ORM\ORMInterface;
use FormManager\Factory as F;
use FormManager\Form;
use Mailery\Brand\Entity\Brand;
use Mailery\Brand\Service\BrandLocatorInterface as BrandLocator;
use Mailery\Template\Email\Entity\Template;
use Mailery\Template\Repository\TemplateRepository;
use Mailery\Template\Email\Service\TemplateCrudService;
use Mailery\Template\Email\ValueObject\TemplateValueObject;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @var Template|null
     */
    private ?Template $template;

    /**
     * @var TemplateCrudService
     */
    private $templateCrudService;

    /**
     * @param BrandLocator $brandLocator
     * @param TemplateCrudService $templateCrudService
     * @param ORMInterface $orm
     */
    public function __construct(BrandLocator $brandLocator, TemplateCrudService $templateCrudService, ORMInterface $orm)
    {
        $this->orm = $orm;
        $this->brand = $brandLocator->getBrand();
        $this->templateCrudService = $templateCrudService;
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
     * @param Template $template
     * @return self
     */
    public function withTemplate(Template $template): self
    {
        $this->template = $template;
        $this->offsetSet('', F::submit('Update'));

        $this['name']->setValue($template->getSubject());

        return $this;
    }

    /**
     * @return Template|null
     */
    public function save(): ?Template
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
//            'textContent' => F::textarea('Content (plaintext)'),
//            'htmlContent' => F::textarea('Content (HTML)'),

            '' => F::submit($this->template === null ? 'Create' : 'Update'),
        ];
    }

    /**
     * @return TemplateRepository
     */
    private function getTemplateRepository(): TemplateRepository
    {
        return $this->orm->getRepository(Template::class)
            ->withBrand($this->brand);
    }
}
