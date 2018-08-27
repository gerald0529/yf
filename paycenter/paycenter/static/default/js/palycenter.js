$(document).ready(function () {
    $(".Second  span").click(function () {
        $(".Second  span").removeClass("btn btn_active");
        $(this).addClass("btn btn_active");
    })
    $(".day  span").click(function () {
            $(".day  span").removeClass("btn btn_active");
            $(this).addClass("btn btn_active");
        })
        //    充值 end
    $(".ulcheak li").click(function () {
        $(".ulcheak li").removeClass("underline-gray");
        $(this).addClass("underline-gray");
    })
})
 