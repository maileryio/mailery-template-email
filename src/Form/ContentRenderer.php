<?php

namespace Mailery\Template\Email\Form;

use Mailery\Widget\Form\Renderers\Input;

class ContentRenderer extends Input
{
    /**
     * @param bool $submitted
     * @return string
     */
    public function __invoke(bool $submitted): string
    {
        $error = '';
        $inputClasses = [
            'form-control',
        ];

        if ($submitted) {
            if (($error = $this->input->getError()) !== null) {
                $error = '<div class="error mt-2 text-danger">' . $error . '</div>';
                $inputClasses[] = 'is-invalid';
            } else {
                $inputClasses[] = 'is-valid';
            }
        }

        if ($submitted && ($error = $this->input->getError()) !== null) {
            $error = '<div class="error mt-2 text-danger">' . $error . '</div>';
        }

        $template = '<div class="form-group">'
            . '<label>{{ label }}</label>'
            . '{{ input }}'
            . $error
        . '</div>';

        return (string) $this->input
            ->setTemplate($template)
            ->setAttribute('class', implode(' ', $inputClasses));
    }
}
