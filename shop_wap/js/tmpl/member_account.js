$(function ()
{
    var e = getCookie("key");
    if (e)
    {
//        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
//        return
        $.ajax({
            type: "get", url: ApiUrl + "/index.php?act=member_account&op=get_mobile_info", data: {k:e,u:getCookie('id'),}, dataType: "json", success: function (e)
            {
                if (e.status == 200)
                {
                    if (e.data.state)
                    {
                        $("#mobile_link").attr("href", "member_mobile_modify.html");
                        $("#mobile_value").html(e.data.mobile)
                    }
                }
                else
                {
                }
            }
        });
        $.ajax({
            type: "get", url: ApiUrl + "/index.php?act=member_account&op=get_paypwd_info", data: {k:e,u:getCookie('id')}, dataType: "json", success: function (e)
            {
                if (e.status == 200)
                {
                    if (!e.data.state)
                    {
                        $("#paypwd_tips").html("未设置")
                    }
                }
                else
                {
                }
            }
        })
    } else {
        $('#member_mobile_bind').hide();
        $('#logoutbtn_ul').hide();
    }
    
});