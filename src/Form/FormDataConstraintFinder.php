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
        $propertyPath = $form->getPropertyPath();
        if ($form->getPropertyPath() === null) {
            return new ConstraintCollection();
        }

        $class = $this->getDataClass($form);
        if ($class === null) {
            return new ConstraintCollection();
        }

        $metadata = $this->metadataFactory->getMetadataFor($class);
        if (!$metadata instanceof ClassMetadata) {
            return new ConstraintCollection();
        }

        if ($propertyPath->getLength() != 1) {
            throw new UnsupportedException('Not supported please submit a issue with the form that produces this error!');
        }

        // Find index property constraints
        if ($propertyPath->isIndex(0)) {
            return $this->findPropertyConstraints(
                $metadata,
                $form->getParent()->getPropertyPath(),
                true
            );
        }

        // Find property constraints
        return $this->findPropertyConstraints($metadata, $propertyPath);
    }

    private function findPropertyConstraints(ClassMetadata $metadata, PropertyPathInterface $propertyPath, $cascadingOnly = false)
    {
        $element = $propertyPath->getElement(0);
        $constraintCollection = new ConstraintCollection();

        $property = $this->guessProperty($metadata, $element);
        if ($property === null) {
            return $constraintCollection;
        }

        foreach ($metadata->getPropertyMetadata($property) as $propertyMetadata) {
            if (!$propertyMetadata instanceof MemberMetadata) {
                continue;
            }

            // For some reason Valid constraint is not in the list of constraints so we hack it in ....
            $this->addCascadingValidConstraint($propertyMetadata, $constraintCollection);
            if ($cascadingOnly) {
                continue;
            }

            // Add the actual constraints
            $constraintCollection->addCollection(
                new ConstraintCollection($propertyMetadata->getConstraints())
            );
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
        /** @var FormInterface $dataForm */
        $dataForm = $form;

        // If we have a index then we need to use it's parent
        if ($propertyPath->getLength() === 1 && $propertyPath->isIndex(0) && $form->getConfig()->getCompound()) {
            return $this->getDataClass($form->getParent());
        }

        // Now locate the root of the data
        for ($i = $propertyPath->getLength(); $i != 0; $i--) {
            $dataForm = $dataForm->getParent();
        }

        // If the root inherits data, then grab the parent
        if ($dataForm->getConfig()->getInheritData()) {
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

    /**
     * Returns the lowerCamelCase form of a string.
     *
     * @param string $string The string to camelize.
     * @return string The camelized version of the string
     */
    private function camelize($string)
    {
        return lcfirst(strtr(ucwords(strtr($string, array('_' => ' '))), array(' ' => '')));
    }

    /**
     * Guess what property a given element belongs to.
     *
     * @param ClassMetadata $metadata
     * @param string $element
     * @return null|string
     */
    private function guessProperty(ClassMetadata $metadata, $element)
    {
        // Is it the element the actual property
        if ($metadata->hasPropertyMetadata($element)) {
            return $element;
        }

        // Is it a camelized property
        $camelized = $this->camelize($element);
        if ($metadata->hasPropertyMetadata($camelized)) {
            return $camelized;
        }

        return null;
    }
}
