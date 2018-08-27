$(function () {
    $(".clear-icon").click(function () {
        $(this).next("input").val('');
    });
    $(".eye-icon").click(function () {
        if ($(this).prev("input").attr('type') == 'password') {
            $(this).prev("input").attr('type', 'text');
            $(this).addClass('active');
        } else {
            $(this).prev("input").attr('type', 'password');
            $(this).removeClass('active');
        }
    });
});