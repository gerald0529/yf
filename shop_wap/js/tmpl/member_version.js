$(function ()
{
    var e = getCookie("key");

    $.ajax({
        url: ApiUrl + "/index.php?ctl=Api_Wap&met=versionImage&typ=json", type: "post", dataType: "json", data: {k: e, u: getCookie('id')}, success: function (data)
        {
            $('#img').attr('src', data.data.shop_logo);
            $('.shop_version').html('V'+data.data.version);
        }
    })

});