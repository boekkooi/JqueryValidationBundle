<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormRuleProcessorContext
{
    /**
     * @var FormView
     */
    private $view;
    /**
     * @var FormInterface
     */
    private $form;
    /**
     * @var ConstraintCollection
     */
    private $constraints;

    public function __construct(FormView $view, FormInterface $form, ConstraintCollection $constraints)
    {
        $this->view = $view;
        $this->form = $form;
        $this->constraints = $constraints;
    }

    /**
     * @return FormView
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return ConstraintCollection
     */
    public function getConstraints()
    {
        return $this->constraints;
    }
}
