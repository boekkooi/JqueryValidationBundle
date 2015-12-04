<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Boekkooi\Bundle\JqueryValidationBundle\Exception\UnsupportedException;
use Boekkooi\Bundle\JqueryValidationBundle\Validator\ConstraintCollection;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Mapping\CascadingStrategy;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\MemberMetadata;
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

    /**
     * Constructor.
     * @param MetadataFactoryInterface $metadataFactory
     */
    public function __construct($metadataFactory)
    {
        if (
            !$metadataFactory instanceof MetadataFactoryInterface &&
            !$metadataFactory instanceof \Symfony\Component\Validator\MetadataFactoryInterface
        ) {
            throw new \InvalidArgumentException('metadataFactory must be a instanceof MetadataFactoryInterface');
        }

        $this->metadataFactory = $metadataFactory;
    }

    public function find(FormInterface $form)
    {
        $propertyPath = $form->getPropertyPath();
        if ($form->getPropertyPath() === null) {
            return new ConstraintCollection();
        }

        $class = $this->resolveDataClass($form);
        if ($class === null) {
            return new ConstraintCollection();
        }

        $metadata = $this->metadataFactory->getMetadataFor($class);
        if (!$metadata instanceof ClassMetadata) {
            return new ConstraintCollection();
        }

        if ($propertyPath->getLength() < 1) {
            throw new UnsupportedException('Not supported please submit a issue with the form that produces this error!');
        }

        // Retrieve the last property element
        $propertyLastElementIndex = $propertyPath->getLength() - 1;
        $propertyName = $propertyPath->getElement($propertyLastElementIndex);

        if ($propertyPath->getLength() > 1) {
            // When we have multiple parts to the path then resolve it
            // To return the actual property and metadata

            // Resolve parent data
            list($dataSource, $dataSourceClass) = $this->resolveDataSource($form);
            for ($i = 0; $i < $propertyPath->getLength() - 1; $i++) {
                $element = $propertyPath->getElement($i);

                $property = $this->guessProperty($metadata, $element);

                // If the Valid tag is missing the property will return null.
                // Or if there is no data set on the form
                if ($property === null) {
                    return new ConstraintCollection();
                }

                foreach ($metadata->getPropertyMetadata($property) as $propertyMetadata) {
                    if (!$propertyMetadata instanceof MemberMetadata) {
                        continue;
                    }

                    $dataSourceInfo = $this->findPropertyDataTypeInfo($propertyMetadata, $dataSource, $dataSourceClass);
                    if ($dataSourceInfo === null) {
                        return new ConstraintCollection();
                    }
                    list($dataSourceClass, $dataSource) = $dataSourceInfo;

                    // Handle arrays/index based properties
                    while ($dataSourceClass === null) {
                        $i++;
                        if (!$propertyPath->isIndex($i)) {
                            // For some strange reason the findPropertyDataTypeInfo is wrong
                            // or the form is wrong
                            return new ConstraintCollection();
                        }

                        $dataSource = $dataSource[$propertyPath->getElement($i)];
                        if (is_object($dataSource)) {
                            $dataSourceClass = get_class($dataSource);
                        }
                    }

                    // Ok we failed to find the data source class
                    if ($dataSourceClass === null) {
                        return new ConstraintCollection();
                    }

                    $metadata = $this->metadataFactory->getMetadataFor($dataSourceClass);
                    if (!$metadata instanceof ClassMetadata) {
                        continue;
                    }
                    continue 2;
                }

                // We where unable to locate a class/array property
                return new ConstraintCollection();
            }
        }

        // Handle array properties
        $propertyCascadeOnly = false;
        if ($propertyPath->isIndex($propertyLastElementIndex)) {
            $propertyCascadeOnly = true;

            $elements = $form->getParent()->getPropertyPath()->getElements();
            $propertyName = end($elements);
        }

        // Find property constraints
        return $this->findPropertyConstraints($metadata, $propertyName, $propertyCascadeOnly);
    }

    private function findPropertyConstraints(ClassMetadata $metadata, $propertyName, $cascadingOnly = false)
    {
        $constraintCollection = new ConstraintCollection();

        $property = $this->guessProperty($metadata, $propertyName);
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
    private function resolveDataClass(FormInterface $form)
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
            return $this->resolveDataClass($form->getParent());
        }

        // Now locate the closest data class
        // TODO what is the length really for?
        for ($i = $propertyPath->getLength(); $i !== 0; $i--) {
            $dataForm = $dataForm->getParent();

            # When a data class is found then use that form
            # This happend when property_path contains multiple parts aka `entity.prop`
            if ($dataForm->getConfig()->getDataClass() !== null) {
                break;
            }
        }

        // If the root inherits data, then grab the parent
        if ($dataForm->getConfig()->getInheritData()) {
            $dataForm = $dataForm->getParent();
        }

        return $dataForm->getConfig()->getDataClass();
    }

    /**
     * Gets the form data to which a property path applies
     *
     * @param FormInterface $form
     * @return object|null
     */
    private function resolveDataSource(FormInterface $form)
    {
        if ($form->isRoot()) {
            // Nothing to do if root
            $dataForm = $form->getData();
        } else {
            $dataForm = $form;
            while ($dataForm->getConfig()->getDataClass() === null) {
                $dataForm = $form->getParent();
            }
        }

        $data = $dataForm->getData();
        return array(
            $data,
            $data === null ? $dataForm->getConfig()->getDataClass() : get_class($data)
        );
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

    /**
     * @param MemberMetadata $propertyMetadata
     * @param mixed $dataSource
     * @param string $dataSourceClass
     * @return null|array
     */
    protected function findPropertyDataTypeInfo(MemberMetadata $propertyMetadata, $dataSource, $dataSourceClass)
    {
        if ($dataSource !== null) {
            $dataSource = $propertyMetadata
                ->getReflectionMember($dataSourceClass)
                ->getValue($dataSource);

            if (is_array($dataSource) || $dataSource instanceof \ArrayAccess) {
                return array(null, $dataSource);
            }
            if (is_object($dataSource)) {
                return array(get_class($dataSource), $dataSource);
            }
            return null;
        }

        // Since there is no datasource we need another way to determin the properties class
        foreach ($propertyMetadata->getConstraints() as $constraint) {
            if (!$constraint instanceof Type) {
                continue;
            }

            $type = strtolower($constraint->type);
            $type = $type === 'boolean' ? 'bool' : $constraint->type;
            $isFunction = 'is_' . $type;
            $ctypeFunction = 'ctype_' . $type;
            if (function_exists($isFunction) || function_exists($ctypeFunction)) {
                return null;
            }

            return array($constraint->type, null);
        }

        return null;
    }
}
