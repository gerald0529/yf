$(function ()
{
    var e = getCookie("key");
    if (e === null)
    {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    $("#feedbackbtn").click(function ()
    {
        var a = $("#feedback").val();
        var feed_url = $(".feed_url").val();
        if (a == "")
        {
            $.sDialog({skin: "red", content: "请填写反馈内容", okBtn: false, cancelBtn: false});
            return false
        }
        $.ajax({
            url: ApiUrl + "/index.php?ctl=Feed&met=addFeed&typ=json", type: "post", dataType: "json", data: {k: e, u: getCookie('id'), feed_desc: a, feed_url:feed_url}, success: function (e)
            {
                if (checkLogin(e.login))
                {
                    if (!e.data.error)
                    {
                        errorTipsShow("<p>提交成功</p>");
                        setTimeout(function ()
                        {
                            window.location.href = WapSiteUrl + "/tmpl/member/member.html"
                        }, 3e3)
                    }
                    else
                    {
                        errorTipsShow("<p>" + e.data.error + "</p>")
                    }
                }
            }
        })
    })
});