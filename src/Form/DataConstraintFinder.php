<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Mapping\Factory\MetadataFactoryInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class DataConstraintFinder
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
        $constraints = [];

        $class = $this->getDataClass($form);
        if ($class === null) {
            return $constraints;
        }

        /** @var \Symfony\Component\Validator\Mapping\ClassMetadata $metadata */
        $metadata = $this->metadataFactory->getMetadataFor($class);
        // TODO support sub forms
        $v = $form->getPropertyPath()->getElement(0);
        foreach($metadata->getPropertyMetadata($v) as $metadata) {
            return $metadata->getConstraints();
        }

        return $constraints;
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
