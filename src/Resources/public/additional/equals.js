/**
 * Validates that a value is the same as another
 */
$.validator.addMethod("equals", function(value, element, param) {
    return this.optional( element ) || value == param;
}, "This value should be {0}");
