<?php

namespace Mailery\Template\Email\Service;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Email\ValueObject\TemplateValueObject;
use Mailery\Brand\Entity\Brand;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class TemplateCrudService
{
    /**
     * @var Brand
     */
    private Brand $brand;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param Brand $brand
     * @return self
     */
    public function withBrand(Brand $brand): self
    {
        $new = clone $this;
        $new->brand = $brand;

        return $new;
    }

    /**
     * @param TemplateValueObject $valueObject
     * @return EmailTemplate
     */
    public function create(TemplateValueObject $valueObject): EmailTemplate
    {
        $template = (new EmailTemplate())
            ->setBrand($this->brand)
            ->setName($valueObject->getName())
            ->setDescription($valueObject->getDescription())
            ->setHtmlEditor($valueObject->getHtmlEditor())
            ->setTextEditor($valueObject->getTextEditor())
        ;

        (new EntityWriter($this->entityManager))->write([$template]);

        return $template;
    }

    /**
     * @param EmailTemplate $template
     * @param TemplateValueObject $valueObject
     * @return Template
     */
    public function update(EmailTemplate $template, TemplateValueObject $valueObject): EmailTemplate
    {
        $template = $template
            ->setName($valueObject->getName())
            ->setDescription($valueObject->getDescription())
        ;

        (new EntityWriter($this->entityManager))->write([$template]);

        return $template;
    }

    /**
     * @param EmailTemplate $template
     * @param TemplateValueObject $valueObject
     * @return Template
     */
    public function updateContent(EmailTemplate $template, TemplateValueObject $valueObject): EmailTemplate
    {
        $template = $template
            ->setHtmlContent($valueObject->getHtmlContent())
            ->setTextContent($valueObject->getTextContent())
        ;

        (new EntityWriter($this->entityManager))->write([$template]);

        return $template;
    }

    /**
     * @param EmailTemplate $template
     * @return bool
     */
    public function delete(EmailTemplate $template): bool
    {
        (new EntityWriter($this->entityManager))->delete([$template]);

        return true;
    }
}
