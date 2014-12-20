(function() {
    function required(value, element) {
        if (element.nodeName.toLowerCase() === "select") {
            // could be an array for select-multiple or a string, both are fine this way
            var val = $(element).val();
            return val && val.length > 0;
        }
        if (this.checkable(element)) {
            return this.getLength(value, element) > 0;
        }
        return $.trim(value).length > 0;
    }

    $.validator.addMethod("required_group", function(value, element, param) {
        if (!$.isArray(param)) {
            if ( this.settings.debug && window.console ) {
                console.log( "Exception occurred when checking element " + element.id + ", check the 'required_group' method parameters." );
            }
            return false;
        }

        var v = this;
        var valid = true;
        var isRequired = required.call( this, value, element );
        $.each(param, function(i, depName) {
            var depElt = v.findByName(depName)[0];
            var depValue = v.elementValue(depElt);
            if (required.call( v, depValue, depElt ) === isRequired) {
                return;
            }

            // bind to the blur event of the dependency in order to revalidate whenever the target field is updated
            // TODO find a way to bind the event just once, avoiding the unbind-rebind overhead
            if ( v.settings.onfocusout ) {
                $(depElt).unbind( ".validate-required_group" ).bind( "blur.validate-required_group", function() {
                    $( element ).valid();
                });
            }

            valid = false;
            return false;
        });

        return valid;
    }, "Please fill in all fields.");
})();
