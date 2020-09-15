<?php

namespace Mailery\Template\Email\Service;

use Cycle\ORM\ORMInterface;
use Cycle\ORM\Transaction;
use Mailery\Template\Email\Entity\Template;
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
     * @return Template
     */
    public function create(TemplateValueObject $valueObject): Template
    {
        $template = (new Template())
            ->setName($valueObject->getName())
            ->setBrand($valueObject->getBrand())
        ;

        $tr = new Transaction($this->orm);
        $tr->persist($template);
        $tr->run();

        return $template;
    }

    /**
     * @param Template $template
     * @param TemplateValueObject $valueObject
     * @return Template
     */
    public function update(Template $template, TemplateValueObject $valueObject): Template
    {
        $template = $template
            ->setName($valueObject->getName())
            ->setBrand($valueObject->getBrand())
        ;

        $tr = new Transaction($this->orm);
        $tr->persist($template);
        $tr->run();

        return $template;
    }

    /**
     * @param Template $template
     * @return bool
     */
    public function delete(Template $template): bool
    {
        $tr = new Transaction($this->orm);
        $tr->delete($template);
        $tr->run();

        return true;
    }
}
