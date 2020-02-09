// Primary nav animations
(function($) {
    var nav = $("#nav-primary");
    nav.find("li").each(function() {
        if ($(this).find("ul").length > 0) {
            $(this).mouseenter(function() {
                $(this).find("ul").stop(true, true).slideDown();
            });
            $(this).mouseleave(function() {
                $(this).find("ul").stop(true, true).slideUp();
            });
        }
    });
})(jQuery);

// Current link highlighting
$(function() {
    var pathname = window.location.pathname;
    $(".nav-primary__link").each(function() {
        if ($(this).attr("href") == pathname) {
            $(this).addClass("current");
        }
    });
});

// Animations on loading
$(function() {
    $(".container").addClass("load");
    $(".content-overlay").addClass("load");
});