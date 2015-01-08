$.validator.addMethod("one_or_other", function(value, element, param) {
    if (this.optional(element)) {
        return true;
    }

    if (!$.isPlainObject(param)) {
        if ( this.settings.debug && window.console ) {
            console.log( "Exception occurred when checking element " + element.id + ", check the 'one_or_other' method parameters." );
        }
        return false;
    }

    var validator = this;
    var valid = false;
    $.each(param, function(ruleMethod, ruleParams) {
        try {
            var result = $.validator.methods[ ruleMethod ].call( validator, value, element, ruleParams );
            if ( !!result ) {
                valid = true;
                return false;
            }
        } catch ( e ) {
            if ( validator.settings.debug && window.console ) {
                console.log( "Exception occurred when checking element " + element.id + ", check the '" + ruleMethod + "' method.", e );
            }
        }
    });

    return valid;
}, "Please enter a valid value.");
