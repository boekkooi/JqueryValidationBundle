<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Util;

use RecursiveIteratorIterator;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ClickableIterator extends \FilterIterator
{
    public function __construct(FormInterface $form)
    {
        parent::__construct(
            new RecursiveIteratorIterator(
                new RecursiveFormIterator($form)
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function accept()
    {
        return $this->current() instanceof ClickableInterface;
    }

    /**
     * @return int The current depth of the recursive iteration.
     */
    public function getDepth()
    {
        return $this->getInnerIterator()->getDepth();
    }

    /**
     * The current active sub iterator
     *
     * @param $level
     * @return RecursiveFormIterator The current active sub iterator.
     */
    public function getSubIterator($level = null)
    {
        return $this->getInnerIterator()->getSubIterator($level);
    }

    /**
     * @return \RecursiveIteratorIterator
     */
    public function getInnerIterator()
    {
        $innerIterator = parent::getInnerIterator();
        if (!$innerIterator instanceof \RecursiveIteratorIterator) {
            throw new \LogicException();
        }

        return $innerIterator;
    }
}
