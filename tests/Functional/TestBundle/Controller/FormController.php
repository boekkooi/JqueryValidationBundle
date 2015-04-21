<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\AdditionalRulesFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\ButtonsFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\CollectionCompoundFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\CollectionDateTimeFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\CollectionFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\CollectionWithGroupsFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\DateTimeFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\RootDataFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\SimpleDataFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\SimpleFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\IncludeSimpleFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type\ViewTransformRulesFormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7;

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

    public function dateTimeAction(Request $request)
    {
        $form = $this->createForm(new DateTimeFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function includeSimpleDataAction(Request $request)
    {
        $form = $this->createForm(new IncludeSimpleFormType());
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

    public function collectionWithGroupAction(Request $request)
    {
        $form = $this->createForm(new CollectionWithGroupsFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView(), 'button' => true));
    }

    public function collectionCompoundAction(Request $request)
    {
        $form = $this->createForm(new CollectionCompoundFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function childDataAction(Request $request)
    {
        $form = $this->createForm(new RootDataFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function viewTransformAction(Request $request)
    {
        $form = $this->createForm(new ViewTransformRulesFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView(), 'button' => true));
    }

    public function collectionDateTimeAction(Request $request)
    {
        $form = $this->createForm(new CollectionDateTimeFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView(), 'button' => true));
    }

    public function additionalRulesAction(Request $request)
    {
        $form = $this->createForm(new AdditionalRulesFormType());
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function issue7Action(Request $request)
    {
        $resource = new Issue7\Model\Recourse();
        $resource->setContents(array(
            new Issue7\Model\Content(),
            new Issue7\Model\Content()
        ));
        $resource->setInvalidContents(array(
            new Issue7\Model\Content()
        ));

        $form = $this->createForm(new Issue7\Type\RecourseType());
        $form->setData($resource);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    private function handleForm(Request $request, FormInterface $form)
    {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->addNotice(date('H:i:s').': Valid');
        } elseif ($request->isMethod('POST')) {
            $this->addNotice(date('H:i:s').': Invalid');
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
