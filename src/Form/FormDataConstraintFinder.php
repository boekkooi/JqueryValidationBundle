<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\UnsupportedException;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\CascadingStrategy;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\MemberMetadata;
use Symfony\Component\Validator\MetadataFactoryInterface;

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

        $metadata = $this->metadataFactory->getMetadataFor($class);
        if (!$metadata instanceof ClassMetadata) {
            return new ConstraintCollection();
        }

        $propertyPath = $form->getPropertyPath();
        if ($propertyPath->getLength() != 1) {
            throw new UnsupportedException('Not supported please submit a issue with the form that produces this error!');
        }

        // Find index property constraints
        if ($propertyPath->isIndex(0)) {
            return $this->findIndexConstraints($form, $metadata);
        }

        // Find property constraints
        return $this->findPropertyConstraints($metadata, $propertyPath);
    }

    private function findPropertyConstraints(ClassMetadata $metadata, PropertyPathInterface $propertyPath)
    {
        $property = $propertyPath->getElement(0);
        $constraintCollection = new ConstraintCollection();

        // Ensure that the property has metadata
        if (!$metadata->hasPropertyMetadata($property)) {
            return $constraintCollection;
        }

        foreach ($metadata->getPropertyMetadata($property) as $propertyMetadata) {
            if (!$propertyMetadata instanceof MemberMetadata) {
                continue;
            }

            $constraintCollection->addCollection(
                new ConstraintCollection($propertyMetadata->getConstraints())
            );

            // For some reason Valid constraint is not in the list of constraints so we hack it in ....
            $this->addCascadingValidConstraint($propertyMetadata, $constraintCollection);
        }

        return $constraintCollection;
    }

    private function findIndexConstraints(FormInterface $form, ClassMetadata $metadata)
    {
        $property = $form->getParent()->getPropertyPath()->getElement(0);
        $constraintCollection = new ConstraintCollection();

        // Ensure that the property has metadata
        if (!$metadata->hasPropertyMetadata($property)) {
            return $constraintCollection;
        }

        foreach ($metadata->getPropertyMetadata($property) as $propertyMetadata) {
            if (!$propertyMetadata instanceof MemberMetadata) {
                continue;
            }

            // Since the parent has a cascading the current index requires a Valid constraint
            $this->addCascadingValidConstraint($propertyMetadata, $constraintCollection);
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
        // Nothing to do if root
        if ($form->isRoot()) {
            return $form->getConfig()->getDataClass();
        }

        $propertyPath = $form->getPropertyPath();
        $dataForm = $form;

        // If we have a index then we need to use it's parent
        if ($propertyPath->getLength() === 1 && $propertyPath->isIndex(0) && $form->getConfig()->getCompound()) {
            return $this->getDataClass($form->getParent());
        }

        for ($i = $propertyPath->getLength(); $i != 0; $i--) {
            $dataForm = $dataForm->getParent();
        }

        return $dataForm->getConfig()->getDataClass();
    }

    private function addCascadingValidConstraint(MemberMetadata $propertyMetadata, ConstraintCollection $constraintCollection)
    {
        if (method_exists($propertyMetadata, 'getCascadingStrategy')) {
            if ($propertyMetadata->getCascadingStrategy() === CascadingStrategy::CASCADE) {
                $constraintCollection->add(new Valid());
            }
        } else {
            if ($propertyMetadata->isCollectionCascaded()) {
                $constraintCollection->add(new Valid());
            }
        }
    }
}
