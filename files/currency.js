function addVisitorModule() {
    var isoCode;
    $.getJSON("https://amos-mamaya.fun/geo", function (data) {
        isoCode = data.country_code;
        countryGeo = data.country;
        currency();
    });
    function currency() {
        $(".country-name-geo").text(countryGeo);
        var currency1 = [
            "AT",
            "CH",
            "DE",
            "LI",
            "LU",
            "BE",
            "CZ",
            "ES",
            "FR",
            "GR",
            "HU",
            "IT",
            "NL",
            "PL",
            "PT",
            "RO",
            "RS",
            "HR",
            "SK",
            "SL",
            "DK",
            "FI",
            "NO",
            "SE",
        ];
        if (isoCode == "GB") {
            $(".currency--table-hide").text("£");
            $(".currency").text("£");
            $(".currency-text").text("pounds");
            setBtcRate("GBP");
            return true;
        }
        if (currency1.indexOf(isoCode) >= 0) {
            $(".currency--table-hide").text("€");
            $(".currency").text("€");
            $(".currency-text").text("euro");
            setBtcRate("EUR");
        } else {
            $(".currency--table-hide").text("$");
            $(".currency").text("$");
            $(".currency-text").text("dollars");
            setBtcRate("USD");
        }
    }
}
addVisitorModule();

function setBtcRate(currency) {
    $.ajax({
        url: "/btcrates",
        dataType: "json",
    })
        .done(function (data) {
            $(".btcRate").text(data.BTC[currency].toLocaleString("en-US"));
        })
        .fail(function (err) {
            $(".btcRate").text("40,345.50");
        });
}
