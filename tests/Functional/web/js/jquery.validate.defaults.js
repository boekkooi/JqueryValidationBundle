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