<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorInterface;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Form\FormConfigInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
abstract class ViewTransformerProcessor implements FormRuleProcessorInterface
{
    protected function findTransformer(FormConfigInterface $config, $class)
    {
        $transformers = $config->getViewTransformers();
        foreach ($transformers as $transformer) {
            if (get_class($transformer) === $class) {
                return $transformer;
            }
        }

        return null;
    }

    protected function getFormRuleMessage(FormConfigInterface $config)
    {
        // Get correct error message if one is set.
        if ($config->hasOption('invalid_message')) {
            $params = $config->getOption('invalid_message_parameters');

            return new RuleMessage(
                $config->getOption('invalid_message'),
                is_array($params) ? $params : array()
            );
        }

        return null;
    }
}
