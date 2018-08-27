if (getQueryString("key") != "")
{
    var key = getQueryString("key")
}
else
{
    var key = getCookie("key")
}
var nodeSiteUrl = "";
var memberInfo = {};
var resourceSiteUrl = "";
var smilies_array = new Array;
smilies_array[1] = [["1", ":smile:", "smile.gif", "28", "28", "28", "微笑"], ["2", ":sad:", "sad.gif", "28", "28", "28", "难过"], ["3", ":biggrin:", "biggrin.gif", "28", "28", "28", "呲牙"], ["4", ":cry:", "cry.gif", "28", "28", "28", "大哭"], ["5", ":huffy:", "huffy.gif", "28", "28", "28", "发怒"], ["6", ":shocked:", "shocked.gif", "28", "28", "28", "惊讶"], ["7", ":tongue:", "tongue.gif", "28", "28", "28", "调皮"], ["8", ":shy:", "shy.gif", "28", "28", "28", "害羞"], ["9", ":titter:", "titter.gif", "28", "28", "28", "偷笑"], ["10", ":sweat:", "sweat.gif", "28", "28", "28", "流汗"], ["11", ":mad:", "mad.gif", "28", "28", "28", "抓狂"], ["12", ":lol:", "lol.gif", "28", "28", "28", "阴险"], ["13", ":loveliness:", "loveliness.gif", "28", "28", "28", "可爱"], ["14", ":funk:", "funk.gif", "28", "28", "28", "惊恐"], ["15", ":curse:", "curse.gif", "28", "28", "28", "咒骂"], ["16", ":dizzy:", "dizzy.gif", "28", "28", "28", "晕"], ["17", ":shutup:", "shutup.gif", "28", "28", "28", "闭嘴"], ["18", ":sleepy:", "sleepy.gif", "28", "28", "28", "睡"], ["19", ":hug:", "hug.gif", "28", "28", "28", "拥抱"], ["20", ":victory:", "victory.gif", "28", "28", "28", "胜利"], ["21", ":sun:", "sun.gif", "28", "28", "28", "太阳"], ["22", ":moon:", "moon.gif", "28", "28", "28", "月亮"], ["23", ":kiss:", "kiss.gif", "28", "28", "28", "示爱"], ["24", ":handshake:", "handshake.gif", "28", "28", "28", "握手"]];
var t_id = getQueryString("t_id");
var chat_goods_id = getQueryString("goods_id");
$(function ()
{
    $.getJSON(ApiUrl + "/index.php?ctl=Buyer_Message&met=getNodeInfo&typ=json", {k: key, u: getCookie('id'), u_id: t_id, chat_goods_id: chat_goods_id}, function (t)
    {
        checkLogin(t.login);
        init_chat_data(t.data);
        if (!$.isEmptyObject(t.data.chat_goods))
        {
            var a = t.data.chat_goods;
            var s = '<div class="nctouch-chat-product"> <a href="' + WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + a.goods_id + '" target="_blank"><div class="goods-pic"><img src="' + a.pic36 + '" alt=""/></div><div class="goods-info"><div class="goods-name">' + a.goods_name + '</div><div class="goods-price">￥' + a.goods_promotion_price + "</div></div></a> </div>";
            $("#chat_msg_html").append(s)
        }
    });
    var init_chat_data = function (e)
    {
        nodeSiteUrl = e.node_site_url;
        memberInfo = e.member_info;
        userInfo = e.userInfo;
        $("h1").html(userInfo.store_name != "" ? userInfo.store_name : userInfo.member_name);
        resourceSiteUrl = e.resource_site_url;
        if (!e.node_chat)
        {
            $.sDialog({skin: "red", content: "在线聊天系统暂时未启用", okBtn: false, cancelBtn: false});
            return false
        }

        //获取历史记录

        s();

        /*
        var t = document.createElement("script");
        t.type = "text/javascript";
        t.src = nodeSiteUrl + "/socket.io/socket.io.js";
        document.body.appendChild(t);
        a();
        */
        function a()
        {
            setTimeout(function ()
            {
                if (typeof io === "function")
                {
                    s()
                }
                else
                {
                    a()
                }
            }, 500)
        }

        function s()
        {
            var e = nodeSiteUrl;
            var t = 0;
            var a = {};

            /*
            a["u_id"] = memberInfo.member_id;
            a["u_name"] = memberInfo.member_name;
            a["avatar"] = memberInfo.member_avatar;
            a["s_id"] = memberInfo.shop_id;
            a["s_name"] = memberInfo.store_name;
            a["s_avatar"] = memberInfo.store_avatar;
            socket = io(e, {path: "/socket.io", reconnection: false});
            socket.on("connect", function ()
            {
                t = 1;
                socket.emit("update_user", a);
                socket.on("get_msg", function (e)
                {
                    get_msg(e)
                });
                socket.on("disconnect", function ()
                {
                    t = 0
                })
            });
            */

            $.ajax({
                type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Message&met=message&op=get_chat_msg&typ=json", data: {user_id:t_id, k: key, u: getCookie('id')}, dataType: "json", success: function (e)
                {
                    if (e.status == 200)
                    {
                        get_msg(e.data);
                    }
                    else
                    {
                        $.sDialog({skin: "red", content: e.data.msg, okBtn: false, cancelBtn: false});
                        return false
                    }
                }
            })

            function s(e)
            {
                if (true || t === 1)
                {
                    console.info(e);
                    var send_msg_obj = e;
                    $.ajax({
                        type: "post", url: ApiUrl + "/index.php?ctl=Buyer_Message&met=addMessageDetail&typ=json", data: e, dataType: "json", success: function (e)
                        {
                            if (e.status == 200)
                            {
                                //socket.emit("send_msg", t);
                                var it = {};
                                it['user_message_content'] = send_msg_obj.user_message_content;
                                it['user_message_send_id'] = send_msg_obj.u;

                                it.avatar = memberInfo.member_avatar;
                                it.class = "msg-me";

                                var user = {};
                                user[send_msg_obj.u] = {};
                                user[send_msg_obj.u]['user_logo'] = memberInfo.member_avatar;;
                                n(it, user);
                            }
                            else
                            {
                                $.sDialog({skin: "red", content: e.data.msg, okBtn: false, cancelBtn: false});
                                return false
                            }
                        }
                    })
                }
            }

            function i(e, a)
            {
                if (t === 1)
                {
                    socket.emit("del_msg", {max_id: e, f_id: a})
                }
            }

            function get_msg(data)
            {
                var e = data.items;
                var user = data.user;
                var t;
                for (var a in e)
                {
                    var s = e[a];

                    if (e[a].message_user_id != t_id)
                    {
                        //continue
                    }
                    t = a;
                    s.avatar = !$.isEmptyObject(userInfo.shop_id) ? userInfo.store_avatar : userInfo.member_avatar;
                    s.class = "msg-other";

                    n(s, user);
                }
                if (typeof t != "undefined")
                {
                    i(t, t_id)
                }
            }

            $("#submit").click(function ()
            {
                var e = $("#msg").val();
                $("#msg").val("");
                if (e == "")
                {
                    $.sDialog({skin: "red", content: "请填写内容", okBtn: false, cancelBtn: false});
                    return false
                }
                s({k: key, u: getCookie('id'), t_id: t_id, user_message_receive: userInfo.member_name, user_message_content: e, chat_goods_id: chat_goods_id});
                $("#chat_smile").addClass("hide");
                $(".nctouch-chat-con").css("bottom", "2rem")
            })
        }

        for (var i in smilies_array[1])
        {
            var o = smilies_array[1][i];
            var r = '<img title="' + o[6] + '" alt="' + o[6] + '" data-sign="' + o[1] + '" src="' + resourceSiteUrl + "/images/smilies/images/" + o[2] + '">';
            $("#chat_smile > ul").append("<li>" + r + "</li>")
        }
        $("#open_smile").click(function ()
        {
            if ($("#chat_smile").hasClass("hide"))
            {
                $("#chat_smile").removeClass("hide");
                $(".nctouch-chat-con").css("bottom", "7rem")
            }
            else
            {
                $("#chat_smile").addClass("hide");
                $(".nctouch-chat-con").css("bottom", "2rem")
            }
        });
        $("#chat_smile").on("click", "img", function ()
        {
            var e = $(this).attr("data-sign");
            var t = $("#msg")[0];
            var a = t.selectionStart;
            var s = t.selectionEnd;
            var i = t.scrollTop;
            t.value = t.value.substring(0, a) + e + t.value.substring(s, t.value.length);
            t.setSelectionRange(a + e.length, s + e.length)
        });
        $("#chat_msg_log").click(function ()
        {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?ctl=Buyer_Message&met=message&op=get_chat_msg&typ=json&page=50",
                data: {k: key, u: getCookie('id'), t_id: t_id, t: 30},
                dataType: "json",
                success: function (e)
                {
                    if (e.status == 200)
                    {
                        if (e.data.items.length == 0)
                        {
                            $.sDialog({skin: "block", content: "暂无聊天记录", okBtn: false, cancelBtn: false});
                            return false
                        }
                        e.data.items.reverse();
                        $("#chat_msg_html").html("");
                        for (var t = 0; t < e.data.items.length; t++)
                        {
                            var a = e.data.items[t];
                            if (a.f_id != t_id)
                            {
                                var s = {};
                                s.class = "msg-me";
                                s.avatar = memberInfo.member_avatar;
                                s.t_msg = a.t_msg;
                                n(s)
                            }
                            else
                            {
                                var s = {};
                                s.class = "msg-other";
                                s.avatar = userInfo.store_avatar == "" ? userInfo.member_avatar : userInfo.store_avatar;
                                s.t_msg = a.t_msg;
                                n(s)
                            }
                        }
                    }
                    else
                    {
                        $.sDialog({skin: "red", content: e.data.error, okBtn: false, cancelBtn: false});
                        return false
                    }
                }
            })
        });
        function n(e, user)
        {
            e.t_msg = c(e.user_message_content);
            var t = '<dl class="' + e.class + '"><dt><img src="' + user[e.user_message_send_id].user_logo + '" alt=""/><i></i></dt><dd>' + e.t_msg + "</dd></dl>";
            $("#chat_msg_html").append(t);

            if (!$.isEmptyObject(e.goods_info))
            {
                var a = e.goods_info;
                var t = '<div class="nctouch-chat-product"> <a href="' + WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + a.goods_id + '" target="_blank"><div class="goods-pic"><img src="' + a.pic36 + '" alt=""/></div><div class="goods-info"><div class="goods-name">' + a.goods_name + '</div><div class="goods-price">￥' + a.goods_promotion_price + "</div></div></a> </div>";
                $("#chat_msg_html").append(t)
            }
            $("#anchor-bottom")[0].scrollIntoView()
        }

        function c(e)
        {
            if (typeof smilies_array !== "undefined")
            {
                e = "" + e;
                for (var t in smilies_array[1])
                {
                    var a = smilies_array[1][t];
                    var s = new RegExp("" + a[1], "g");
                    var i = '<img title="' + a[6] + '" alt="' + a[6] + '" src="' + resourceSiteUrl + "/js/smilies/images/" + a[2] + '">';
                    e = e.replace(s, i)
                }
            }
            return e
        }
    }
});