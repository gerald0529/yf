var key = getCookie("key");

$(function ()
{
     var key=getCookie("key");if(!key){location.href="login.html"}

    function s()
    {
        $.ajax({
            type: "post", url: ApiUrl + "/index.php?ctl=Buyer_User&met=address&typ=json", data: {k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                checkLogin(e.login);
                if (e.data.address_list == null)
                {
                    return false
                }
                var s = e.data;
                var t = template.render("saddress_list", s);
                $("#address_list").empty();
                $("#address_list").append(t);
                $(".deladdress").click(function ()
                {
                    var e = $(this).attr("user_address_id");
                    $.sDialog({
                        skin: "block", content: "确认删除吗？", okBtn: true, cancelBtn: true, okFn: function ()
                        {
                            a(e)
                        }
                    })
                })
            }
        })
    }

    s();
    function a(a)
    {
        $.ajax({
            type: "post", url: ApiUrl + "?ctl=Buyer_User&met=delAddress&typ=json", data: {id: a, k: key, u:getCookie('id')}, dataType: "json", success: function (e)
            {
                checkLogin(e.login);
                if (e)
                {
                    s()
                }
            }
        })
    }

    $(document).on('click','.user_address',function(){
        var user_address_id = $(this).data('user_address_id');
        var user_address_default = $(this).attr("checked") ? 1 : 0;
        $.ajax({
            type:"post",
            url:ApiUrl + "/index.php?ctl=Buyer_User&met=editAddressDefaultInfo&typ=json",
            data:{
                k:getCookie("key"),
                u:getCookie('id'),
                user_address_default:user_address_default,
                user_address_id:user_address_id
            },
            dataType: "json",
            success: function(e){
                if(e.status == 200)
                {
                    $.sDialog({ skin: "block", content: "修改成功！", okBtn: false, cancelBtn: false,});
                }
                else
                {
                    $.sDialog({ skin: "block", content: "修改默认地址失败,请重试！", okBtn: false, cancelBtn: false,});

                }
            }
        })
    })
});