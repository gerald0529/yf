$(document).ready(function () {
    var wrapLeft = $(".wrap").offset().left;
    var fixedl = wrapLeft - 100;
    $(window).scroll(function () {
        var top = $(window).scrollTop();
        if (top >= 440) {
            $(".fixed_left ").css({
                "position": "fixed",
                "top": "0",
                "left": fixedl
            });

        } else {
            $(".fixed_left ").css({
                "position": "absolute",
                "top": "17%",
                "left": "-100px"
            });

        }
        if (top >= 650) {
            $(".fix_index").css({
                "position": "fixed",
                "top": "0",
                "left": fixedl
            });

        } else {
            $(".fix_index").css({
                "position": "absolute",
                "top": "17%",
                "left": "-100px"
            });

        }
    });

    $(window).scroll(function () {
        var tops = $(window).scrollTop();
        var wtwo=$(window).width()-$(".wrap").width();
        var wone=wtwo/2;
        if (tops >= 400) {
            $(".tg ").css({
                "position": "fixed",
                "top": "0px",
                "left": wone,
                "z-index": "10"

            });
        } else {
            $(".tg").css({
                "position": "absolute",
                "top": "47px",
                "left": "0px"
            })
        }
    })
})