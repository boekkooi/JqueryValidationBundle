(function($) {
    "use strict";

    function methodTest( methodName ) {
        var v = jQuery("#form").validate(),
            method = $.validator.methods[methodName],
            element = $("#testField")[0];
        v.settings.debug = true;
        return function(value, param) {
            element.value = value;
            return method.call( v, value, element, param );
        };
    }

    module("methods");

    test("time", function() {
        var method = methodTest("time");
        ok( method( "00:00" ), "Valid time, lower bound" );
        ok( method( "23:59" ), "Valid time, upper bound" );
        ok(!method( "12" ), "Invalid time" );
        ok(!method( "29:59" ), "Invalid time" );
        ok(!method( "00:60" ), "Invalid time" );
        ok(!method( "24:60" ), "Invalid time" );
        ok(!method( "24:00" ), "Invalid time" );
        ok(!method( "30:00" ), "Invalid time" );
        ok(!method( "29:59" ), "Invalid time" );
        ok(!method( "120:00" ), "Invalid time" );
        ok(!method( "12:001" ), "Invalid time" );
        ok(!method( "12:00a" ), "Invalid time" );
    });

    test("ipv4", function() {
        var method = methodTest("ipv4");
        ok( method( "10.0.0.1" ), "Valid ip" );
        ok( method( "172.16.0.1" ), "Valid ip" );
        ok( method( "192.168.0.1" ), "Valid ip" );
        ok( method( "0.0.0.0" ), "Valid ip" );
        ok( method( "216.17.184.1" ), "Valid ip" );
        ok(!method( "..." ), "Invalid ip" );
        ok(!method( "216.17.184.G" ), "Invalid ip" );
        ok(!method( "216.17.184" ), "Invalid ip" );
        ok(!method( "216.17.184." ), "Invalid ip" );
        ok(!method( "256.17.184.1" ), "Invalid ip" );
        ok(!method( "ABCD:EF01:2345:6789:ABCD:EF01:2345:6789" ), "Invalid ip" );
        ok(!method( "::1" ), "Invalid ip" );
        ok(!method( "::" ), "Invalid ip" );
    });

    test("ipv6", function() {
        var method = methodTest("ipv6");
        ok( method( "ABCD:EF01:2345:6789:ABCD:EF01:2345:6789" ), "Valid ip" );
        ok( method( "2001:DB8:0:0:8:800:200C:417A" ), "Valid ip" );
        ok( method( "FF01:0:0:0:0:0:0:101" ), "Valid ip" );
        ok( method( "2001:DB8::8:800:200C:417A" ), "Valid ip" );
        ok( method( "FF01::101" ), "Valid ip" );
        ok( method( "::1" ), "Valid ip" );
        ok( method( "0:0:0:0:0:0:13.1.68.3" ), "Valid ip" );
        ok( method( "0:0:0:0:0:FFFF:129.144.52.38" ), "Valid ip" );
        ok( method( "::13.1.68.3" ), "Valid ip" );
        ok( method( "::FFFF:129.144.52.38" ), "Valid ip" );
        ok( method( "2001:0DB8:0:CD30:123:4567:89AB:CDEF" ), "Valid ip" );
        ok(!method( "2067:FA88" ), "Invalid ip" );
        ok(!method( "216.17.184.1" ), "Invalid ip" );
        ok(!method( "www.neely.cx" ), "Invalid ip" );
    });

    test("pattern", function() {
        var method = methodTest("pattern");
        ok( method( "AR1004", "AR\\d{4}" ), "Correct format for the given RegExp" );
        ok( method( "AR1004", /^AR\d{4}$/ ), "Correct format for the given RegExp" );
        ok(!method( "BR1004", /^AR\d{4}$/ ), "Invalid format for the given RegExp" );
        ok( method( "1ABC", "[0-9][A-Z]{3}" ), "Correct format for the given RegExp" );
        ok(!method( "ABC", "[0-9][A-Z]{3}" ), "Invalid format for the given RegExp" );
        ok(!method( "1ABC DEF", "[0-9][A-Z]{3}" ), "Invalid format for the given RegExp" );
        ok( method( "1ABCdef", "[a-zA-Z0-9]+" ), "Correct format for the given RegExp" );
        ok(!method( "1ABC def", "[a-zA-Z0-9]+" ), "Invalid format for the given RegExp" );
        ok( method( "2014-10-02", "[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" ), "Correct format for the given RegExp" );
        ok(!method( "02-10-2014", "[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" ), "Invalid format for the given RegExp" );
    });

    test("one_or_other", function() {
        var method = methodTest("one_or_other");
        ok( method( "192.168.1.1", {"ipv4": true, "ipv6": true} ), "Valid first rule" );
        ok( method( "12:12", {"time": true, "pattern": /^hello$/} ), "Valid first rule" );
        ok( method( "ABCD:EF01:2345:6789:ABCD:EF01:2345:6789", {"ipv4": true, "ipv6": true} ), "Valid second rule" );
        ok( method( "hello", {"time": true, "pattern": /^hello$/} ), "Valid second rule" );
        ok(!method( "1", [] ), "Invalid param" );
        ok(!method( "1", {} ), "Invalid param" );
        ok(!method( "hello", {"ipv4": true, "ipv6": true} ), "Invalid" );
        ok(!method( "time", {"time": true, "pattern": /^hello$/} ), "Invalid" );
    });

    test("required_group", function() {
        var method = methodTest("required_group");
        var field1 = $("#testField1");
        var field2 = $("#testField2");

        field1.val('');
        field2.val('');
        ok( method( "", ["test_field_1"] ), "Valid" );
        ok( method( "", ["test_field_1", "test_field_2"] ), "Valid" );

        ok(!method( "1", ["test_field_1"] ), "Invalid" );
        ok(!method( "1", ["test_field_1", "test_field_2"] ), "Invalid" );

        field1.val('2');
        ok(!method( "", ["test_field_1", "test_field_2"] ), "Invalid" );
        ok(!method( "1", ["test_field_1", "test_field_2"] ), "Invalid" );

        field2.val('2');
        ok(!method( "", ["test_field_1", "test_field_2"] ), "Invalid" );
        ok( method( "1", ["test_field_1", "test_field_2"] ), "Valid" );
    });

    test("iban", function() {
        var method = methodTest("iban");
        ok( method( "NL20INGB0001234567"), "Valid IBAN");
        ok( method( "DE68 2105 0170 0012 3456 78"), "Valid IBAN");
        ok( method( "NL20 INGB0001234567"), "Valid IBAN: invalid spacing");
        ok( method( "NL20 INGB 00 0123 4567"), "Valid IBAN: invalid spacing");
        ok( method( "XX40INGB000123456712341234"), "Valid (more or less) IBAN: unknown country, but checksum OK");

        ok(!method( "NL20INGB000123456"), "Invalid IBAN: too short");
        ok(!method( "NL20INGB00012345678"), "Invalid IBAN: too long");
        ok(!method( "NL20INGB0001234566"), "Invalid IBAN: checksum incorrect");
        ok(!method( "DE68 2105 0170 0012 3456 7"), "Invalid IBAN: too short");
        ok(!method( "DE68 2105 0170 0012 3456 789"), "Invalid IBAN: too long");
        ok(!method( "DE68 2105 0170 0012 3456 79"), "Invalid IBAN: checksum incorrect");

        ok(!method( "NL54INGB00012345671234"), "Invalid IBAN too long, BUT CORRECT CHECKSUM");
        ok(!method( "XX00INGB000123456712341234"), "Invalid IBAN: unknown country and checksum incorrect");

        // sample IBANs for different countries
        ok( method( "AL47 2121 1009 0000 0002 3569 8741"), "Valid IBAN - AL");
        ok( method( "AD12 0001 2030 2003 5910 0100"), "Valid IBAN - AD");
        ok( method( "AT61 1904 3002 3457 3201"), "Valid IBAN - AT");
        ok( method( "AZ21 NABZ 0000 0000 1370 1000 1944"), "Valid IBAN - AZ");
        ok( method( "BH67 BMAG 0000 1299 1234 56"), "Valid IBAN - BH");
        ok( method( "BE62 5100 0754 7061"), "Valid IBAN - BE");
        ok( method( "BA39 1290 0794 0102 8494"), "Valid IBAN - BA");
        ok( method( "BG80 BNBG 9661 1020 3456 78"), "Valid IBAN - BG");
        ok( method( "HR12 1001 0051 8630 0016 0"), "Valid IBAN - HR");
        ok( method( "CH93 0076 2011 6238 5295 7"), "Valid IBAN - CH");
        ok( method( "CY17 0020 0128 0000 0012 0052 7600"), "Valid IBAN - CY");
        ok( method( "CZ65 0800 0000 1920 0014 5399"), "Valid IBAN - CZ");
        ok( method( "DK50 0040 0440 1162 43"), "Valid IBAN - DK");
        ok( method( "EE38 2200 2210 2014 5685"), "Valid IBAN - EE");
        ok( method( "FO97 5432 0388 8999 44"), "Valid IBAN - FO");
        ok( method( "FI21 1234 5600 0007 85"), "Valid IBAN - FI");
        ok( method( "FR14 2004 1010 0505 0001 3M02 606"), "Valid IBAN - FR");
        ok( method( "GE29 NB00 0000 0101 9049 17"), "Valid IBAN - GE");
        ok( method( "DE89 3704 0044 0532 0130 00"), "Valid IBAN - DE");
        ok( method( "GI75 NWBK 0000 0000 7099 453"), "Valid IBAN - GI");
        ok( method( "GR16 0110 1250 0000 0001 2300 695"), "Valid IBAN - GR");
        ok( method( "GL56 0444 9876 5432 10"), "Valid IBAN - GL");
        ok( method( "HU42 1177 3016 1111 1018 0000 0000"), "Valid IBAN - HU");
        ok( method( "IS14 0159 2600 7654 5510 7303 39"), "Valid IBAN - IS");
        ok( method( "IE29 AIBK 9311 5212 3456 78"), "Valid IBAN - IE");
        ok( method( "IL62 0108 0000 0009 9999 999"), "Valid IBAN - IL");
        ok( method( "IT40 S054 2811 1010 0000 0123 456"), "Valid IBAN - IT");
        ok( method( "LV80 BANK 0000 4351 9500 1"), "Valid IBAN - LV");
        ok( method( "LB62 0999 0000 0001 0019 0122 9114"), "Valid IBAN - LB");
        ok( method( "LI21 0881 0000 2324 013A A"), "Valid IBAN - LI");
        ok( method( "LT12 1000 0111 0100 1000"), "Valid IBAN - LT");
        ok( method( "LU28 0019 4006 4475 0000"), "Valid IBAN - LU");
        ok( method( "MK07 2501 2000 0058 984"), "Valid IBAN - MK");
        ok( method( "MT84 MALT 0110 0001 2345 MTLC AST0 01S"), "Valid IBAN - MT");
        ok( method( "MU17 BOMM 0101 1010 3030 0200 000M UR"), "Valid IBAN - MU");
        ok( method( "MD24 AG00 0225 1000 1310 4168"), "Valid IBAN - MD");
        ok( method( "MC93 2005 2222 1001 1223 3M44 555"), "Valid IBAN - MC");
        ok( method( "ME25 5050 0001 2345 6789 51"), "Valid IBAN - ME");
        ok( method( "NL39 RABO 0300 0652 64"), "Valid IBAN - NL");
        ok( method( "NO93 8601 1117 947"), "Valid IBAN - NO");
        ok( method( "PK36 SCBL 0000 0011 2345 6702"), "Valid IBAN - PK");
        ok( method( "PL60 1020 1026 0000 0422 7020 1111"), "Valid IBAN - PL");
        ok( method( "PT50 0002 0123 1234 5678 9015 4"), "Valid IBAN - PT");
        ok( method( "RO49 AAAA 1B31 0075 9384 0000"), "Valid IBAN - RO");
        ok( method( "SM86 U032 2509 8000 0000 0270 100"), "Valid IBAN - SM");
        ok( method( "SA03 8000 0000 6080 1016 7519"), "Valid IBAN - SA");
        ok( method( "RS35 2600 0560 1001 6113 79"), "Valid IBAN - RS");
        ok( method( "SK31 1200 0000 1987 4263 7541"), "Valid IBAN - SK");
        ok( method( "SI56 1910 0000 0123 438"), "Valid IBAN - SI");
        ok( method( "ES80 2310 0001 1800 0001 2345"), "Valid IBAN - ES");
        ok( method( "SE35 5000 0000 0549 1000 0003"), "Valid IBAN - SE");
        ok( method( "CH93 0076 2011 6238 5295 7"), "Valid IBAN - CH");
        ok( method( "TN59 1000 6035 1835 9847 8831"), "Valid IBAN - TN");
        ok( method( "TR33 0006 1005 1978 6457 8413 26"), "Valid IBAN - TR");
        ok( method( "AE07 0331 2345 6789 0123 456"), "Valid IBAN - AE");
        ok( method( "GB29 NWBK 6016 1331 9268 19"), "Valid IBAN - GB");
    });

    test("luhn", function() {
        var method = methodTest("luhn");
        ok( method("42424242424242424242"), "Valid LUHN");
        ok( method("378282246310005"), "Valid LUHN");
        ok( method("371449635398431"), "Valid LUHN");
        ok( method("378734493671000"), "Valid LUHN");
        ok( method("5610591081018250"), "Valid LUHN");
        ok( method("30569309025904"), "Valid LUHN");
        ok( method("38520000023237"), "Valid LUHN");
        ok( method("6011111111111117"), "Valid LUHN");
        ok( method("6011000990139424"), "Valid LUHN");
        ok( method("3530111333300000"), "Valid LUHN");
        ok( method("3566002020360505"), "Valid LUHN");
        ok( method("5555555555554444"), "Valid LUHN");
        ok( method("5105105105105100"), "Valid LUHN");
        ok( method("4111111111111111"), "Valid LUHN");
        ok( method("4012888888881881"), "Valid LUHN");
        ok( method("4222222222222"), "Valid LUHN");
        ok( method("5019717010103742"), "Valid LUHN");
        ok( method("6331101999990016"), "Valid LUHN");

        ok(!method("1234567812345678"), "Invalid LUHN");
        ok(!method("4222222222222222"), "Invalid LUHN");
        ok(!method("0000000000000000"), "Invalid LUHN");
    });
})(jQuery);
