/**
 * Validates a PAN using the LUHN Algorithm
 */
$.validator.addMethod("luhn", function(value, element) {
    // some quick simple tests to prevent needless work
    if (this.optional(element)) {
        return true;
    }

    // trim spaces
    var digits = $.trim(value);

    if (!(/^[0-9]+$/.test(digits))) {
        return false;
    }

    var checkSum = 0,
        i;

    // Starting with the last digit and walking left, add every second
    // digit to the check sum
    // e.g. 7  9  9  2  7  3  9  8  7  1  3
    //      ^     ^     ^     ^     ^     ^
    //    = 7  +  9  +  7  +  9  +  7  +  3
    for (i = digits.length - 1; i >= 0; i -= 2) {
        checkSum += parseInt(digits[i], 10);
    }

    // Starting with the second last digit and walking left, double every
    // second digit and add it to the check sum
    // For doubles greater than 9, sum the individual digits
    // e.g. 7  9  9  2  7  3  9  8  7  1  3
    //         ^     ^     ^     ^     ^
    //    =    1+8 + 4  +  6  +  1+6 + 2
    for (i = digits.length - 2; i >= 0; i -= 2) {
        var partSum = 0,
            parts = (digits[i] * 2).toString().split("");
        for (var j = parts.length - 1; j >= 0; j--) {
            partSum += parseInt(parts[j], 10);
        }
        checkSum += partSum;
    }

    return (0 !== checkSum && 0 === checkSum % 10);
}, "Please specify a valid card number");
