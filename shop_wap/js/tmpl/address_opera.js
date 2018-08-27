$(function ()
{
    var a = getCookie("key");
    $.sValid.init({
        rules: {true_name: "required", mob_phone: "required", area_info: "required", address: "required"},
        messages: {true_name: "姓名必填！", mob_phone: "手机号必填！", area_info: "地区必填！", address: "街道必填！"},
        callback: function (a, e, r)
        {
            if (a.length > 0)
            {
                var i = "";
                $.map(e, function (a, e)
                {
                    i += "<p>" + a + "</p>"
                });
                errorTipsShow(i)
            }
            else
            {
                errorTipsHide()
            }
        }
    });
    $("#header-nav").click(function ()
    {
        $(".btn").click()
    });
    $(".btn").click(function ()
    {
        if ($.sValid())
        {
            var e = $("#true_name").val();
            var r = $("#mob_phone").val();
            var i = $("#address").val();
            var d = $("#area_info").attr("data-areaid2");
            var t = $("#area_info").attr("data-areaid");
            var n = $("#area_info").val();
            var o = $("#is_default").attr("checked") ? 1 : 0;

            var province_id = $("#area_info").attr("data-areaid1");
            var city_id = $("#area_info").attr("data-areaid2");
            var area_id = $("#area_info").attr("data-areaid3");
            if (e.length > 20 || e.length < 2) {
                errorTipsShow("<p>收货人姓名为2~20个字符</p>");
                return false;
            }
            if(!(/^1[34578]\d{9}$/.test(r))){
                errorTipsShow("<p>手机号码有误，请重填</p>");
                return false;
            }
            if(i.length > 100){
                errorTipsShow("<p>详细地址不能超过100个字符</p>");
                return false;
            }

            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?ctl=Buyer_User&met=addAddressInfo&typ=json",
                data: {k:getCookie('key'),u:getCookie('id'), user_address_contact: e, user_address_phone: r, province_id: province_id, city_id: city_id, area_id: area_id, user_address_address: i, address_area: n, user_address_default: o},

                dataType: "json",
                success: function (a)
                {
                    if (a)
                    {
                        location.href = WapSiteUrl + "/tmpl/member/address_list.html"
                    }
                    else
                    {
                        location.href = WapSiteUrl
                    }
                }
            })
        }
    });
    $("#area_info").on("click", function ()
    {
        $.areaSelected({
            success: function (a)
            {
                $("#area_info").val(a.area_info).attr({"data-areaid1": a.area_id_1, "data-areaid2": a.area_id_2, "data-areaid3": a.area_id_3, "data-areaid": a.area_id, "data-areaid2": a.area_id_2 == 0 ? a.area_id_1 : a.area_id_2})
            }
        })
    })
});