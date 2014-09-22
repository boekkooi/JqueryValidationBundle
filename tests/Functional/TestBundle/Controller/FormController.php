<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\SimpleDataFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\SimpleFormType;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormController extends Controller
{
    public function simpleAction()
    {
        $form = $this->createForm(new SimpleFormType());

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function simpleDataAction()
    {
        $form = $this->createForm(new SimpleDataFormType());

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }
}
