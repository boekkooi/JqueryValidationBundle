<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\Form;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class FormRuleCompiler implements FormRuleCompilerInterface
{
    /**
     * @var FormRuleCompilerInterface[]
     */
    protected $compilers = array();

    public function __construct(array $compilers)
    {
        foreach ($compilers as $compiler) {
            $this->addCompiler($compiler);
        }
    }

    /**
     * @param FormRuleContextBuilder $formRuleContext
     */
    public function compile(FormRuleContextBuilder $formRuleContext)
    {
        foreach ($this->compilers as $compiler) {
            $compiler->compile($formRuleContext);
        }
    }

    private function addCompiler(FormRuleCompilerInterface $compiler)
    {
        $this->compilers[] = $compiler;
    }
}
