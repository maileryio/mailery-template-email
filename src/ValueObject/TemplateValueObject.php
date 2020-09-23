<?php

namespace Mailery\Template\Email\ValueObject;

use Mailery\Brand\Entity\Brand;
use Mailery\Template\Email\Form\TemplateForm;

class TemplateValueObject
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $editor;

    /**
     * @var Brand
     */
    private Brand $brand;

    /**
     * @param TemplateForm $form
     * @return self
     */
    public static function fromForm(TemplateForm $form): self
    {
        $new = new self();

        $new->name = $form['name']->getValue();
        $new->editor = $form['editor']->getValue();

        return $new;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEditor(): string
    {
        return $this->editor;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
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
}
