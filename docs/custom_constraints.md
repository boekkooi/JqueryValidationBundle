Custom constraints
============
So you probably have a few custom constraints with you application and you want to use them on the client-side.

For this you will need to do a few things:
- Create a constraint mapper
- Add you constraint mapper to the DI
- Implement the constraint as a jquery validate rule

The goal of a constraint mapper is to map a constraint as a jquery validation rule.
In general all constraints are mapped and registered as constraint mappers.
So look the constraint mappers available (under `src/Form/Rule/Mapping`) within the project and create your own.

Once you have create your custom constraint mapper you need to register it in the DI/Service Container.
A example is:
```YAML
acme.form.rule.my_mapper:
    class: Acme\Form\Rule\MyRule
    tags:
     - { name: validator.rule_mapper }
```

After this you need to make sure you include your jquery validate rule into the page.

DataTransformer Rules/Constraints
-------------
In some cases you maybe using a custom 'DataTransformer' that validates your data (or throw a exception when it's invalid).
If this is the case you (probably) need to implement a FormRuleProcessor to add a TransformerRule.
A good example is `src/Form/Rule/Processor/DateTimeToArrayTransformerPass`.
