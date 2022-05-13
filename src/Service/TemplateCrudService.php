<?php

namespace Mailery\Template\Email\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Email\ValueObject\TemplateValueObject;
use Mailery\Brand\Entity\Brand;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class TemplateCrudService
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @var Brand
     */
    private Brand $brand;

    /**
     * @param ORMInterface $orm
     */
    public function __construct(ORMInterface $orm)
    {
        $this->orm = $orm;
    }

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

        (new EntityWriter($this->orm))->write([$template]);

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
            ->setHtmlEditor($valueObject->getHtmlEditor())
            ->setTextEditor($valueObject->getTextEditor())
        ;

        (new EntityWriter($this->orm))->write([$template]);

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

        (new EntityWriter($this->orm))->write([$template]);

        return $template;
    }

    /**
     * @param EmailTemplate $template
     * @return bool
     */
    public function delete(EmailTemplate $template): bool
    {
        (new EntityWriter($this->orm))->delete([$template]);

        return true;
    }
}
