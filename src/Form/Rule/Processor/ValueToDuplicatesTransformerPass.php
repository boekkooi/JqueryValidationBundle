<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Processor;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleContextBuilder;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\Condition\FieldDependency;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleProcessorContext;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Rule\TransformerRule;
use Boekkooi\Bundle\JqueryValidationBundle\Form\Util\FormHelper;
use Symfony\Component\Form\Extension\Core\DataTransformer\ValueToDuplicatesTransformer;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ValueToDuplicatesTransformerPass extends ViewTransformerProcessor
{
    /**
     * @var null|\ReflectionProperty
     */
    private $keyReflectionCache = null;

    public function process(FormRuleProcessorContext $context, FormRuleContextBuilder $collection)
    {
        $form = $context->getForm();
        $formConfig = $form->getConfig();
        if (!$formConfig->getCompound()) {
            return;
        }

        $transformer = $this->findTransformer($formConfig, ValueToDuplicatesTransformer::class);
        if ($transformer === null) {
            return;
        }

        $keys = $this->getKeys($transformer);
        if (empty($keys)) {
            return;
        }

        $formView = $context->getView();

        // Use children here since we need the full_name
        $primary = array_shift($keys);
        $primaryView = $formView->children[$primary];

        // Copy all rules to the first child/key element
        $ruleCollection = $collection->get($formView);
        if (!empty($ruleCollection)) {
            $collection->add(
                $primaryView,
                $ruleCollection
            );
        }
        $collection->remove($formView);

        // Get correct error message if one is set.
        $invalidMessage = $this->getFormRuleMessage($formConfig);

        // Create equalTo rules for all other fields
        $equalToPrimaryRule = new TransformerRule(
            'equalTo',
            FormHelper::generateCssSelector($primaryView),
            $invalidMessage,
            array( new FieldDependency($primaryView) )
        );
        foreach ($keys as $childName) {
            $childCollection = new RuleCollection();
            $childCollection->set('equalTo', $equalToPrimaryRule);

            $collection->add(
                $formView->children[$childName],
                $childCollection
            );
        }
    }

    private function getKeys($transformer)
    {
        if ($this->keyReflectionCache === null) {
            // Using reflection since we want to support more then just the repeated form type.
            $reflection = new \ReflectionProperty(get_class($transformer), 'keys');
            $reflection->setAccessible(true);
            $this->keyReflectionCache = $reflection;
        }

        return $this->keyReflectionCache->getValue($transformer);
    }
}
