Design/Layout
============
Now that you have the validation working it's time to customize it for your project!

The easiest way to do this is to create a `jquery.validate.defaults.js` file (that you use after `<script src="...jquery.validate.js"></script>`) and add your own error placement defaults.


Symfony
-------------
For the default symfony form layout the `jquery.validate.defaults.js` could look like this:
```javascript
(function($){
    $.validator.setDefaults({
        errorElement: 'li',
        errorPlacement: function(error, element) {
            var label = $('label[for="'+$(element).attr('id')+'"]');
            var container = label.next('ul');
            if (container.length === 1) {
                container.empty();
            } else {
                container = $('<ul></ul>').insertAfter(label);
            }
            container.append(error);
        },
        highlight: function(element) {
            $('label[for="'+$(element).attr('id')+'"] + ul').show();
        },
        unhighlight: function(element) {
            $('label[for="'+$(element).attr('id')+'"] + ul').hide();
        },
        ignore: function(idx, elt) {
            // Only validate a hidden field when it has a rule attached.
            return $(elt).is(':hidden') && $.isEmptyObject($( this ).rules());
        }
    });
})(jQuery);
```
For all possible options please take a look at [validate-options](http://jqueryvalidation.org/validate#validate-options). 


Bootstrap
-------------
If you use [bootstrap](http://getbootstrap.com/) in combination with [BraincraftedBootstrapBundle](http://bootstrap.braincrafted.com/) `jquery.validate.defaults.js` could look like this:
```javascript
(function($){
    $.validator.setDefaults({
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            var serverError = $('#' + error.attr('id'), element.parent());
            if (serverError.length > 0) { serverError.remove(); }
    
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        ignore: function(idx, elt) {
            // We don't validate hidden fields expect if they have rules attached.
            return $(elt).is(':hidden') && $.isEmptyObject($( this ).rules());
        }
    });
})(jQuery);
```

More
-------------
- [Custom constraints](custom_constraints.md)
