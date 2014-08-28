<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Twig;

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
        if (isset($view->vars['attr']['id'])) {
            $selector = '#' . $view->vars['attr']['id'];
        } else {
            $selector = sprintf('form[name=\\"%s\\"]', $view->vars['full_name']);
        }

        return sprintf(
            '<script>$("%s").validate(%s);</script>',
            $selector,
            json_encode($this->generateOptions($view))
        );
    }

    protected function generateOptions(FormView $view)
    {
        $validationOptions = array(
            'rules' => [],
            'messages' => []
        );
        foreach ($view->vars['jquery_validation_rules'] as $name => $ruleCollection) {
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
}
