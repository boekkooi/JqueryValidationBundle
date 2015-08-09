Usage
============
So your done [installing](install.md). Time to get the validation to the client-side!

It's really simple just open a twig template that has a form and add the following:
```twig
{# These are the required libs, you really should move them somewhere else! #}
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js"></script>

{# The code below generates the form validation #}
<script>
    {{ form_jquery_validation(form) }}
</script>
```
Now go to you page and enjoy.

Additional rules
-------------
To get the best result and most client side validations you can enable the additional-methods provided by the bundle.
To enable addition-methods you need to enable it in your `config.yml` by adding:
```YAML
boekkooi_jquery_validation:
    form:
        ...
        additionals: true
```
After this you will need to include the `additional-methods.min.js` within your twig template after `jquery.validate.js`.
```twig
{% javascripts '@BoekkooiJqueryValidationBundle/Resources/public/additional-methods.min.js' %}
    <script type="text/javascript" src="{{ asset_url }}"></script>
{% endjavascripts %}
```

Collection prototype
-------------
O no you have a form with a collection that has `allow_add` set..... Now you need to do more work!

The simple way to enjoy client-side validation with collections is to open you twig template and add the following:
```twig
{% form_theme form _self %}

{% block collection_widget %}
    {% if prototype is defined %}
        {# The change here is that we add the javascript for a new row here #}
        {%- set attr = attr|merge({'data-prototype': form_row(prototype) ~ '<script>' ~ form_jquery_validation(form) ~ '</script>'}) -%}
    {% endif %}
    {{- block('form_widget') -}}
{%- endblock collection_widget %}
```
Now refresh your page and hit that add button.

Validation groups (and buttons)
-------------
O yes it's time to abuse the power of the FORM by your usage of buttons with validation groups! No problem we can do that!

**Remark** If you are using callable `validation_groups` then please set the `jquery_validation_groups` option with a array or a string.

More
-------------
- [Error layout/design](layout.md)
- [Custom constraints](custom_constraints.md)
