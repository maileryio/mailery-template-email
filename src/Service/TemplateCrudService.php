<?php

namespace Mailery\Template\Email\Service;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Email\ValueObject\TemplateValueObject;

class TemplateCrudService
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @param ORMInterface $orm
     */
    public function __construct(ORMInterface $orm)
    {
        $this->orm = $orm;
    }

    /**
     * @param TemplateValueObject $valueObject
     * @return EmailTemplate
     */
    public function create(TemplateValueObject $valueObject): EmailTemplate
    {
        $template = (new EmailTemplate())
            ->setName($valueObject->getName())
            ->setEditor($valueObject->getEditor())
            ->setBrand($valueObject->getBrand())
        ;

        $tr = new Transaction($this->orm);
        $tr->persist($template);
        $tr->run();

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
            ->setEditor($valueObject->getEditor())
            ->setBrand($valueObject->getBrand())
        ;

        $tr = new Transaction($this->orm);
        $tr->persist($template);
        $tr->run();

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
            ->setContent($valueObject->getContent())
        ;

        $tr = new Transaction($this->orm);
        $tr->persist($template);
        $tr->run();

        return $template;
    }

    /**
     * @param EmailTemplate $template
     * @return bool
     */
    public function delete(EmailTemplate $template): bool
    {
        $tr = new Transaction($this->orm);
        $tr->delete($template);
        $tr->run();

        return true;
    }
}
