<?php

namespace Mailery\Template\Email\Form;

use Cycle\ORM\ORMInterface;
use FormManager\Factory as F;
use FormManager\Form;
use Mailery\Brand\Entity\Brand;
use Mailery\Brand\Service\BrandLocatorInterface as BrandLocator;
use Mailery\Template\Email\Entity\Template;
use Mailery\Template\Repository\TemplateRepository;
use Mailery\Template\Email\Service\TemplateService;
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
    private ?Template $message;

    /**
     * @var TemplateService
     */
    private $messageService;

    /**
     * @param BrandLocator $brandLocator
     * @param TemplateService $messageService
     * @param ORMInterface $orm
     */
    public function __construct(BrandLocator $brandLocator, TemplateService $messageService, ORMInterface $orm)
    {
        $this->orm = $orm;
        $this->brand = $brandLocator->getBrand();
        $this->messageService = $messageService;
        parent::__construct($this->inputs());
    }

    /**
     * @param Template $message
     * @return self
     */
    public function withTemplate(Template $message): self
    {
        $this->message = $message;
        $this->offsetSet('', F::submit('Update'));

        $this['name']->setValue($message->getSubject());
        $this['description']->setValue($message->getDescription());

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

        if (($message = $this->message) === null) {
            $message = $this->messageService->create($valueObject);
        } else {
            $this->messageService->update($message, $valueObject);
        }

        return $message;
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

                $message = $this->getTemplateRepository()->findBySubject($value, $this->message);
                if ($message !== null) {
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
            'description' => F::textarea('Description'),

            '' => F::submit($this->message === null ? 'Create' : 'Update'),
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
