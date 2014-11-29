<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\UnsupportedException;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\CascadingStrategy;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormDataConstraintFinder
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    public function find(FormInterface $form)
    {
        $class = $this->getDataClass($form);
        if ($class === null) {
            return new ConstraintCollection();
        }

        /** @var \Symfony\Component\Validator\Mapping\ClassMetadata $metadata */
        $metadata = $this->metadataFactory->getMetadataFor($class);
        $propertyPath = $form->getPropertyPath();
        if ($propertyPath->getLength() != 1) {
            throw new UnsupportedException('Not supported please submit a issue with the form that produces this error!');
        }

        $property = $propertyPath->getElement(0);
        $constraintCollection = new ConstraintCollection();
        if (!$metadata->hasPropertyMetadata($property)) {
            return $constraintCollection;
        }

        /** @var \Symfony\Component\Validator\Mapping\PropertyMetadata $propertyMetadata */
        foreach ($metadata->getPropertyMetadata($property) as $propertyMetadata) {
            $constraintCollection->addCollection(
                new ConstraintCollection($propertyMetadata->getConstraints())
            );
            // For some reason Valid constraint is not in the list of constraints so we hack it in ....
            if ($propertyMetadata->cascadingStrategy === CascadingStrategy::CASCADE) {
                $constraintCollection->add(new Valid());
            }
        }

        return $constraintCollection;
    }

    /**
     * Gets the form root data class used by the given form.
     *
     * @param FormInterface $form
     * @return string|null
     */
    private function getDataClass(FormInterface $form)
    {
        $dataForm = $form;
        if (!$form->isRoot()) {
            $propertyPath = $form->getPropertyPath();

            for ($i = $propertyPath->getLength(); $i != 0; $i--) {
                $dataForm = $dataForm->getParent();
            }
        }

        return $dataForm->getConfig()->getDataClass();
    }
}
