<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Util;
use Symfony\Component\Form\FormInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class RecursiveFormIterator extends \IteratorIterator implements \RecursiveIterator
{
    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return new static($this->current());
    }

    /**
     *{@inheritdoc}
     */
    public function hasChildren()
    {
        return (bool) $this->current()->getConfig()->getCompound();
    }
}
