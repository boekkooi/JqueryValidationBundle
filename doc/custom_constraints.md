Custom constraints
============
So you probably have a few custom constraints with you application and you want to use them on the client-side.

For this you will need to do a few things:
- Create a constraint mapper
- Add you constraint mapper to the DI

The goal of a constraint mapper is to map a constraint as a jquery validation rule.
In general all constraints are mapped and registered as constraint mappers.
So look the constraint mappers available (under `src/Form/Rule/Mapping`) within the project and create your own.

    **REMARK**
        Some times you need to do more then just a map a simple constraint for this there are form passes.
        These are located under `src/Form/Rule/Compiler`

Once you have create your custom constraint mapper you need to register it in the DI/Service Container.
A example is:
```YAML
acme.form.rule.my_mapper:
    class: Acme\Form\Rule\MyRule
    tags:
     - { name: validator.rule_mapper }
```