<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type as CoreType;
use Symfony\Component\HttpFoundation\Request;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue7;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue8;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Issue16;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\Type as FormType;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Form\TypeHelper;
use Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\Issue20;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormController extends Controller
{
    public function simpleAction(Request $request)
    {
        $form = $this->createForm(FormType\SimpleFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function simpleDataAction(Request $request)
    {
        $form = $this->createForm(FormType\SimpleDataFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function dateTimeAction(Request $request)
    {
        $form = $this->createForm(FormType\DateTimeFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function includeSimpleDataAction(Request $request)
    {
        $form = $this->createForm(FormType\IncludeSimpleFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function buttonsAction(Request $request)
    {
        $form = $this->createForm(FormType\ButtonsFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView(), 'button' => true));
    }

    public function collectionAction(Request $request)
    {
        $form = $this->createForm(FormType\CollectionFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function collectionWithGroupAction(Request $request)
    {
        $form = $this->createForm(FormType\CollectionWithGroupsFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView(), 'button' => true));
    }

    public function collectionCompoundAction(Request $request)
    {
        $form = $this->createForm(FormType\CollectionCompoundFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function childDataAction(Request $request)
    {
        $form = $this->createForm(FormType\RootDataFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function viewTransformAction(Request $request)
    {
        $form = $this->createForm(FormType\ViewTransformRulesFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView(), 'button' => true));
    }

    public function collectionDateTimeAction(Request $request)
    {
        $form = $this->createForm(FormType\CollectionDateTimeFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView(), 'button' => true));
    }

    public function additionalRulesAction(Request $request)
    {
        $form = $this->createForm(FormType\AdditionalRulesFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function manualGroupsAction(Request $request)
    {
        $form = $this->createForm(FormType\ManualGroupsFormType::class);
        $this->handleForm($request, $form);

        return $this->render('::form_manual_groups.html.twig', array('form' => $form->createView()));
    }

    public function isTrueOrFalseAction(Request $request)
    {
        $form = $this->createForm(FormType\IsTrueOrFalseFormType::class);
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

        $form = $this->createForm(Issue7\Type\RecourseType::class);
        $form->setData($resource);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function issue8Action(Request $request)
    {
        $resource = new Issue8\Model\TestRangeEntity();

        $form = $this->createForm(Issue8\Type\TestRangeType::class);
        $form->setData($resource);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function issue16Action(Request $request)
    {
        $collection = new Issue16\Model\EntityRefCollection();
        $collection->entityReferences[] = new Issue16\Model\EntityReferenceModel();

        $form = $this->createForm(Issue16\Type\EntityRefCollectionType::class, $collection);
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function issue17EmptyNameAction(Request $request)
    {
        /** @var FormInterface $form */
        $form = $this->get('form.factory')->createNamed('', TypeHelper::type(FormType\SimpleFormType::class));
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView()));
    }

    public function issue18Action(Request $request)
    {
        /** @var FormInterface $form */
        $form = $this->get('form.factory')->createNamedBuilder('')
            ->add('submit', TypeHelper::type(CoreType\SubmitType::class))
            ->getForm();
        $this->handleForm($request, $form);

        return $this->render('::form.html.twig', array('form' => $form->createView(), 'button' => true));
    }

    public function issue20Action(Request $request)
    {
        $form = $this->createForm(
            Issue20\Form\Type\ScheduleFormType::class,
            new Issue20\Model\Schedule()
        );
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

    public function createForm($type, $data = null, array $options = array())
    {
        return parent::createForm(TypeHelper::type($type), $data, $options);
    }

    private function addNotice($message)
    {
        $this->get('session')->getFlashBag()->add(
            'notice',
            $message
        );
    }
}
