<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Util;

class FormViewRecursiveIterator extends \IteratorIterator implements \RecursiveIterator
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
        return $this->current()->count() > 0;
    }
}
