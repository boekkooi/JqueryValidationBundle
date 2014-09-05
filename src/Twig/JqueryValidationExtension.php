<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Twig;

use Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection;
use Boekkooi\Bundle\JqueryValidationBundle\Form\RuleMessage;
use Symfony\Component\Form\FormView;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class JqueryValidationExtension extends Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
             new Twig_SimpleFunction(
                'form_jquery_validation',
                array($this, 'buildJavascript'),
                array('pre_escape' => 'html', 'is_safe' => array('html'))
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'boekkooi_jquery_validation';
    }

    public function buildJavascript(FormView $view)
    {
        if (!isset($view->vars['jquery_validation_rules'])) {
            return '';
        }
        /** @var \Boekkooi\Bundle\JqueryValidationBundle\Form\FormRuleCollection $collection */
        $collection = $view->vars['jquery_validation_rules'];
        $selector = $this->getFormSelector($collection);

        if ($collection->isRoot()) {
            return sprintf(
                '$("%s").validate(%s);',
                $selector,
                json_encode($this->generateOptions($view, $collection))
            );
        } else {
            $js = [];
            $formOptions = $this->generateOptions($view, $collection);
            foreach ($formOptions['rules'] as $name => $rules) {
                // TODO better escaping
                $options = $rules;
                if (isset($formOptions['messages'][$name])) {
                    $options['messages'] = $formOptions['messages'][$name];
                }

                $js[] = sprintf(
                    '$("%s *[name=\\"%s\\"]").rules("add", %s)',
                    $selector,
                    $name,
                    json_encode($options)
                );
            }

            if (!empty($js)) {
                return implode(';', $js);
            }

        }
    }

    protected function generateOptions(FormView $view, FormRuleCollection $collection)
    {
        $validationOptions = array(
            'rules' => [],
            'messages' => []
        );
        foreach ($collection as $name => $ruleCollection) {
            /** @var \Boekkooi\Bundle\JqueryValidationBundle\Form\Rule $rule */
            $rules = [];
            $messages = [];
            foreach ($ruleCollection as $rule) {
                $rules[$rule->name] = $rule->options;
                if ($rule->message) {
                    $messages[$rule->name] = $this->translateMessage($rule->message);
                }
            }

            if (!empty($rules)) {
                $validationOptions['rules'][$name] = $rules;
            }
            if (!empty($messages)) {
                $validationOptions['messages'][$name] = $messages;
            }
        }

        return $validationOptions;
    }

    protected function translateMessage(RuleMessage $message)
    {
        $domain = 'validators';
        return null === $message->plural
            ? $this->translator->trans($message->message, $message->parameters, $domain)
            : $this->translator->transChoice($message->message, $message->plural, array_merge(array('%count%' => $message->plural), $message->parameters), $domain);
    }

    /**
     * @param $collection
     * @return string
     */
    public function getFormSelector($collection)
    {
        $rootView = $collection->isRoot() ? $collection->getView() : $collection->getRoot()->getView();
        if (isset($rootView->vars['attr']['id'])) {
            $selector = '#' . $rootView->vars['attr']['id'];
            return $selector;
        } else {
            $selector = sprintf('form[name=\\"%s\\"]', $rootView->vars['full_name']);
            return $selector;
        }
    }
}
