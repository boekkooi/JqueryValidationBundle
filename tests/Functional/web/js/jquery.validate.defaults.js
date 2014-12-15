(function($){
    function locateErrorLabel(element) {
        var id = $(element).attr('id');
        var label = $('label[for="'+id+'"]');
        if (label.length > 0) {
            return label;
        }

        var elt = $(element);
        do {
            elt = elt.parent();
            label = elt.prevAll('label');
        } while(label.length == 0 && elt.length != 0);

        return label;
    }

    $.validator.setDefaults({
        errorElement: 'li',
        errorPlacement: function(error, element) {
            var label = locateErrorLabel(element);

            // Due to error bubbling need to check for then just the label
            var container = label.next('ul');
            if (container.length === 0) {
                container = $('<ul></ul>').insertAfter(label);
            } else if(container.data('jquery-validate-list') == undefined) {
                container.empty();
            }
            container.data('jquery-validate-list', '1');
            container.append(error);
        },
        highlight: function(element) {
            locateErrorLabel(element).next('ul').show();
        },
        unhighlight: function(element) {
            var container = locateErrorLabel(element).next('ul');
            var visibleErrors = container.children('li:visible:not(#'+$(element).attr('id')+'-error)');
            if (visibleErrors.length === 0) {
                container.hide();
            }
        },
        ignore: function(idx, elt) {
            // Only validate a hidden field when it has a rule attached.
            return $(elt).is(':hidden') && $.isEmptyObject($( this ).rules());
        }
    });
})(jQuery);