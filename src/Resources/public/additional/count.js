/**
 * Validates the collection count
 */
$.validator.addMethod("collection_count_exact", function(value, element, param) {
    if (!$.isPlainObject(param)) {
        if ( this.settings.debug && window.console ) {
            console.log( "Exception occurred when checking element " + element.id + ", check the 'collection_count_exact' method parameters." );
        }
        return false;
    }

    return ($(param.field).length !== param.limit);
}, "Invalid number of items");

$.validator.addMethod("collection_count_min", function(value, element, param) {
    if (!$.isPlainObject(param)) {
        if ( this.settings.debug && window.console ) {
            console.log( "Exception occurred when checking element " + element.id + ", check the 'collection_count_min' method parameters." );
        }
        return false;
    }

    return ($(param.field).length >= param.min);
}, "Not enough items");

$.validator.addMethod("collection_count_max", function(value, element, param) {
    if (!$.isPlainObject(param)) {
        if ( this.settings.debug && window.console ) {
            console.log( "Exception occurred when checking element " + element.id + ", check the 'collection_count_max' method parameters." );
        }
        return false;
    }

    return ($(param.field).length <= param.max);
}, "To many items");