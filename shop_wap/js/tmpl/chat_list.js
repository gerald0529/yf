var e = getCookie("key");
if (!e) {
    location.href = "login.html"
}
template.helper("isEmpty", function (e) {
    for (var t in e) {
        return false
    }
    return true
});

$.ajax({
    type: "post",
    url: ImApiUrl + "/index.php?ctl=Api_Chatlog&met=getMessageList&typ=json",
    data: {u: getCookie('user_account')},
    dataType: "json",
    success: function (t) {
        console.log(t);
        $("#messageList").html(template.render("messageListScript", t));
        $(".msg-list-del").click(function () {
            var sender = $(this).data("sender");
            console.log(sender);
            $.ajax({
                type: "post",
                url: ImApiUrl + "/index.php?ctl=Api_Chatlog&met=delMessage&typ=json",
                data: {k: e, u: getCookie('id'), sender: sender},
                dataType: "json",
                success: function (e) {
                    console.log(e);
                    if (e.status == 200) {
                        location.reload();
                    } else {
                        $.sDialog({skin: "red", content: e.data.error, okBtn: false, cancelBtn: false});
                        return false
                    }
                }
            })
        })
    }
});
