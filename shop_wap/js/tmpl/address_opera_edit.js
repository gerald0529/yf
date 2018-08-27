$(function ()
{
    var a = getQueryString("user_address_id");
    var e = getCookie("key");
    $.ajax({
        type: "post", url: ApiUrl + "/index.php?ctl=Buyer_User&met=address&act=edit&typ=json", data: {k:e,u:getCookie('id'), id: a}, dataType: "json", success: function (a)
        {
            checkLogin(a.login);
            $("#true_name").val(a.data.address_list.user_address_contact);
            $("#mob_phone").val(a.data.address_list.user_address_phone);
            $("#area_info").val(a.data.address_list.user_address_area).attr({"data-areaid1": a.data.address_list.user_address_province_id, "data-areaid2": a.data.address_list.user_address_city_id, "data-areaid3": a.data.address_list.user_address_area_id, "data-areaid": a.data.address_list.user_address_province_id});
            $("#address").val(a.data.address_list.user_address_address);
            var e = a.data.address_list.user_address_default == "1" ? true : false;
            $("#is_default").prop("checked", e);
            if (e)
            {
                $("#is_default").parents("label").addClass("checked")
            }
        }
    });
    $.sValid.init({
        rules:{
            true_name:{required: true, maxlength: 20},
            mob_phone:{required: true, mobile: true},
            area_info:"required",
            address:{required: true, maxlength: 100}
        },
        messages:{
            true_name:{required: "姓名必填！", maxlength: "姓名最多20个字符！"},
            mob_phone:{required: "手机号必填！", mobile: "手机号码不正确！"},
            area_info:"地区必填！",
            address:{required: "街道必填！", maxlength: "地址最多100个字符！"}
        },
        callback: function (a, e, r)
        {
            if (a.length > 0)
            {
                var d = "";
                $.map(e, function (a, e)
                {
                    d += "<p>" + a + "</p>"
                });
                errorTipsShow(d)
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
            var r = $("#true_name").val();
            var d = $("#mob_phone").val();
            var i = $("#address").val();

            var province_id = $("#area_info").attr("data-areaid1");
            var city_id = $("#area_info").attr("data-areaid2");
            var area_id = $("#area_info").attr("data-areaid3");

            var n = $("#area_info").val();

            var o = $("#is_default").attr("checked") ? 1 : 0;
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?ctl=Buyer_User&met=editAddressInfo&typ=json",
                data: {k:e,u:getCookie('id'), user_address_contact: r, user_address_phone: d, province_id: province_id, city_id: city_id, area_id: area_id, user_address_address: i, address_area: n, user_address_default: o, user_address_id: a},
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