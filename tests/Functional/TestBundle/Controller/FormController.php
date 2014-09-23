<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\ButtonsFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\CollectionCompoundFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\CollectionFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\SimpleDataFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\SimpleFormType;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormController extends Controller
{
    public function simpleAction(Request $request)
    {
        $form = $this->createForm(new SimpleFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function simpleDataAction(Request $request)
    {
        $form = $this->createForm(new SimpleDataFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function buttonsAction(Request $request)
    {
        $form = $this->createForm(new ButtonsFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView(), 'button' => true));
    }

    public function collectionAction(Request $request)
    {
        $form = $this->createForm(new CollectionFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function collectionCompoundAction(Request $request)
    {
        $form = $this->createForm(new CollectionCompoundFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    private function handleForm(Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->addNotice(date('H:i:s') . ': Valid');
        } elseif ($request->isMethod('POST')) {
            $this->addNotice( date('H:i:s') . ': Invalid');
        }
    }

    private function addNotice($message)
    {
        $this->get('session')->getFlashBag()->add(
            'notice',
            $message
        );
    }
}
