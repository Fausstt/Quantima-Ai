$(".hover-modal").hover(function () {
    $(".popup_custom").css("visibility", "visible");
});
$(".close_button").click(function () {
    $(".popup_custom").css("visibility", "hidden");
});
if (device.mobile() || device.ipad() || device.android()) {
    $("#ytplayer").append(
        '<img src="images/preloader_Youtube.gif" id="apYou">'
    );
    $(".hover-modal").remove();
    $(".anticlicker").css("bottom", "65%");
    setTimeout(function () {
        $("#apYou").hide();
        $("#ytplayer").append(
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/RG8ioumKpgg?mute=0&amp;autoplay=1&amp;controls=1&amp;disablekb=0&amp;loop=1&amp;modestbranding=1&amp;rel=0&amp;showinfo=0&amp;playlist=RG8ioumKpgg" frameborder="0" allowfullscreen=""></iframe>'
        );
    }, 2000);
} else {
    $("body").append('<script src="js/youtubeUP.js"></script>');
}
$(".playbtn").click(function () {
    $(".modal-promo").css("display", "block");
});
$(".close-btn").click(function () {
    $("#video").get(0).pause();
    $(".modal-promo").css("display", "none");
});
function getRandomFloat(min, max) {
    return Math.random() * (max - min) + min;
}
$(".randomBalance").text(function () {
    function getRandomFloat(min, max) {
        return Math.random() * (max - min) + min;
    }
    return getRandomFloat(3, 10).toFixed(3);
});
