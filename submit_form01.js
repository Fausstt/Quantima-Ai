// pixel
let pixel = [];
// debugger;
fetch("./pixel.php")
    .then((d) => d.json())
    .then((d) => {
        pixel = d;
        d.map((p) => {
            fetch(`https://www.facebook.com/tr/?id=${p}&ev=PageView`);
        });
    });
const Sub = () => {
    pixel.map((p) => {
        fetch(`https://www.facebook.com/tr?id=${p}&ev=Lead&noscript=1`);
    });
};
// keitaro
if (!window.KTracking) {
    window.KTracking = {
        collectNonUniqueClicks: false,
        multiDomain: false,
        R_PATH: "https://imperialls.site/p85xgr",
        P_PATH: "https://imperialls.site/5ce67dc/postback",
        listeners: [],
        reportConversion: function () {
            this.queued = arguments;
        },
        getSubId: function (fn) {
            this.listeners.push(fn);
        },
        ready: function (fn) {
            this.listeners.push(fn);
        },
    };
}
(function () {
    var a = document.createElement("script");
    a.type = "application/javascript";
    a.async = true;
    a.src = "https://imperialls.site/js/k.min.js";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(a, s);
})();

// submit
document.getElementById("sub_pl").style.display = "none";
const cof = () => {
    document.getElementById("sub_pl").style.display = "none";
};
document.getElementById("form1").addEventListener("submit", (e) => {
    e.preventDefault();
    let res = "";
    const FName = document.getElementById("FName1").value;
    const LName = document.getElementById("LName1").value;
    const Email = document.getElementById("Email1").value;
    const Phone = document.getElementById("Phone1").value;
    let PhoneFul = "";
    if (Phone.length >= 17) {
        for (let i = 0; i < Phone.length; i++) {
            if (Number(Phone[i]) || Phone[i] === "0") {
                PhoneFul += Phone[i];
            }
            if (i + 1 === Phone.length) {
                document.getElementById("sub").innerHTML = "...Wait";
                document.getElementById("sub").disabled = true;
                $.ajax({
                    method: "post",
                    url: "ff.php",
                    data: {
                        FName: FName,
                        LName: LName,
                        Email: Email,
                        Phone: PhoneFul,
                    },
                    success: (response) => {
                        if (response != 1) {
                            document.getElementById("sub").innerHTML = response;
                            document.getElementById("sub").disabled = false;
                            document.getElementById("FName1").value = "";
                            document.getElementById("FName1").value = "";
                            document.getElementById("LName1").value = "";
                            document.getElementById("Email1").value = "";
                            document.getElementById("Phone1").value = "";
                            console.log("no res");
                            if (response == "test") {
                                pop_ap_open = true;
                                KTracking.reportConversion(0, "lead");
                                Sub();
                                console.log("res test");
                            }
                        } else {
                            document.getElementById("sub").innerHTML =
                                "Send Data";
                            document.getElementById("sub").style.background =
                                "green";
                            document.getElementById("FName1").value = "";
                            document.getElementById("FName1").value = "";
                            document.getElementById("LName1").value = "";
                            document.getElementById("Email1").value = "";
                            document.getElementById("Phone1").value = "";
                            document.getElementById("FName1").disabled = true;
                            document.getElementById("FName1").disabled = true;
                            document.getElementById("LName1").disabled = true;
                            document.getElementById("Email1").disabled = true;
                            document.getElementById("Phone1").disabled = true;
                            document.getElementById("sub_pl").style.display =
                                "flex";
                            pop_ap_open = true;
                            KTracking.reportConversion(0, "lead");
                            Sub();
                            console.log("res");
                        }
                    },
                });
                break;
            }
        }
    } else {
        // document.getElementById("sub").innerHTML = "Number is not true";
    }
});

// доп код телефона
// вспомогательный код
let countryCode = "";
fetch("https://ipapi.co/json/")
    .then((d) => d.json())
    .then((d) => {
        countryCode = d.country_calling_code;
    });

var $jscomp = $jscomp || {};
$jscomp.scope = {};
$jscomp.arrayIteratorImpl = function (a) {
    var b = 0;
    return function () {
        return b < a.length ? { done: !1, value: a[b++] } : { done: !0 };
    };
};
$jscomp.arrayIterator = function (a) {
    return { next: $jscomp.arrayIteratorImpl(a) };
};
$jscomp.makeIterator = function (a) {
    var b =
        "undefined" != typeof Symbol && Symbol.iterator && a[Symbol.iterator];
    return b ? b.call(a) : $jscomp.arrayIterator(a);
};
document.addEventListener("DOMContentLoaded", function () {
    var a = function (e) {
            var c = e.target,
                n = c.dataset.phoneClear;
            c = (c = c.dataset.phonePattern)
                ? c
                : `${countryCode}(___) ___-__-___`;
            var g = 0,
                k = c.replace(/\D/g, ""),
                d = e.target.value.replace(/\D/g, "");
            "false" !== n &&
            "blur" === e.type &&
            d.length < c.match(/([_\d])/g).length
                ? (e.target.value = "")
                : (k.length >= d.length && (d = k),
                  (e.target.value = c.replace(/./g, function (l) {
                      return /[_\d]/.test(l) && g < d.length
                          ? d.charAt(g++)
                          : g >= d.length
                          ? ""
                          : l;
                  })));
        },
        b = document.querySelectorAll("[data-phone-pattern]");
    b = $jscomp.makeIterator(b);
    for (var f = b.next(); !f.done; f = b.next()) {
        f = f.value;
        for (
            var m = $jscomp.makeIterator(["input", "blur", "focus"]),
                h = m.next();
            !h.done;
            h = m.next()
        )
            f.addEventListener(h.value, a);
    }
});

// pop-ap
let pop_ap_open = false;
document.addEventListener("mouseleave", function () {
    if (pop_ap_open) {
        document.getElementById("box").style.display = "flex";
    }
});
document.getElementById("get").addEventListener("click", function (e) {
    document.getElementById("box").style.display = "none";
});
let min = 15;
let sec = "00";
setInterval(() => {
    if (pop_ap_open) {
        if (min != 0 || sec != 0) {
            if (sec == 0) {
                min--;
                sec = 59;
                document.getElementById("pop_min").innerHTML = min;
                document.getElementById("pop_sec").innerHTML = sec;
            } else {
                sec--;
                if (sec < 10) {
                    document.getElementById("pop_sec").innerHTML = "0" + sec;
                } else {
                    document.getElementById("pop_sec").innerHTML = sec;
                }
            }
        }
        console.log(min, ":", sec);
    }
}, 1000);

//
