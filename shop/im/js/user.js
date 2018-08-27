var interval = 1;//断开后计时
var connect = 0;//连接状态
var new_msg = 0;//新消息数
var obj = {};
var msg_dialog = '';
var socket = {};
var chat_log = {};
var connect_list = {};
var connect_n = 0;
var friend_list = {};
var talk_list = {};
var recent_list = {};
var user_list = {};//所有会员信息
var msg_list = {};//收到消息
var left_list = new Array();//左侧的会员
var right_list = new Array();//右侧的会员
var dialog_show = 0;//对话框是否打开
var user_show = null;//当前选择的会员
var msg_max = 20;//消息数
var time_max = 10;//定时(分钟)刷新防止登录超时退出,为0时关闭

var content_you = '';
var content_type = '';


//var user_account='99999999999';
//var pwd='111';


window.show_interval_id = null;
window.interval_id = null;

/**
 * 选择表情
 *
 * @param unified
 * @param unicode
 * @constructor
 */
function DO_chooseEmoji(unified, unicode) {

    var content_emoji = '<img imtype="content_emoji" emoji_value_unicode="'
        + unicode
        + '" style="width:18px; height:18px; margin:0 1px 0 1px;" src="/im/img/img-apple-64/'
        + unified + '.png"/>';


    if ($('#send_message').children().length <= 1) {
        $('#send_message').find('p').detach();
        $('#send_message').find('br').detach();
        $('#send_message').find('div').detach();
    }

    var brlen = $('#send_message').find('br').length - 1;
    $('#send_message').find('br').each(function(i) {
        if (i == brlen) {
            $(this).replaceWith('');
        }
    });

    var plen = $('#send_message').find('p').length - 1;
    $('#send_message').find('p').each(function(i) {
        if (i < plen) {
            $(this).replaceWith($(this).html() + '<br>');
        } else {
            $(this).replaceWith($(this).html());
        }
    });

    $('#send_message').find('div').each(function(i) {
        if ('<br>' == $(this).html()) {
            $(this).replaceWith('<br>');
        } else {
            $(this).replaceWith('<br>' + $(this).html());
        }
    });

    var im_send_content = $('#send_message').html();

    if ('<br>' == im_send_content) {
        im_send_content = '';
    } else {
        im_send_content = im_send_content.replace(/(<(br)[/]?>)+/g,
            '\u000A');
    }

    $('#send_message').html(im_send_content + content_emoji);

}


function send_to_server_bysun(_user_account,content_you,msgid,im_send_content,content_type){
    /*
    聊天记录发送到远丰数据库
    
    weichat:sunkangchina

    */
    
    $.post(imbuilder_url+"?ctl=Api_Chatlog&met=add&typ=json",{u:_user_account,to:content_you,msgid:msgid,content:im_send_content,type:content_type},function(){

    });

}

/**
 * 样式，发送消息
 */
function DO_sendMsg() {  
    var str = DO_pre_replace_content_to_db();

    


    
    
    //HTML_sendMsg_addHTML('msg', 1, msgid, content_type, content_you,str);
    //alert_box(im_send_content);
    //URL网址输出 带跳转
 
    $.post(IM_URL+'/ajax.php',{str:str},function(d){
                    str = d;
                    if(!str){
                        alert_box('发送内容有误');
                        return;
                    }
                    $('#im_send_content_copy').html(str);
                    $('#im_send_content_copy').find('img[imtype="content_emoji"]').each(function() {
                        var emoji_value_unicode = $(this).attr('emoji_value_unicode');
                        $(this).replaceWith(emoji_value_unicode);
                    });
                    var im_send_content = $('#im_send_content_copy').html();

                    // 清空pre中的内容
                    $('#im_send_content_copy').html('');
                    // 隐藏表情框
                    $('#emoji_div').hide();

                    var msgid = new Date().getTime();
                    var content_type = '';
                    var content_you = '';
                    var b = false;


                    content_type = 'C';

                    $('#user_list').find('li').each(function() {
                        if ($(this).hasClass('select_user')) {
                            content_you = $(this).attr('select_u_id');
                        }
                    });
                    //alert_box(content_you);
                    if(content_you)
                    {
                        b=true;
                    }
                    if (!b) {
                        alert_box("请选择要对话的联系人或群组");
                        return;
                    };

                    if(_serverNo == content_you){
                        alert_box("系统消息禁止回复");
                        return;
                    }

                    if (im_send_content == undefined || im_send_content == null
                        || im_send_content == '')
                        return;
                    im_send_content = im_send_content.replace(/&lt;/g, '<').replace(
                        /&gt;/g, '>').replace(/&quot;/g, '"')
                        .replace(/&amp;/g, '&').replace(/&nbsp;/g, ' ');

                    if(im_send_content == "")
                    {
                        alert_box("发送内容不能为空");
                        return;
                    }


                    
                    show_t_msg('msg','1',msgid,content_type,content_you,im_send_content);

                    var str = '<pre msgtype="content" class="bubble" style="margin-top:15px;">' + im_send_content + '</pre>';
                    
                     // 发送消息至服务器
                    if (_contact_type_c == content_type) {
                        console.log('cont1:'+content_you);
                        EV_sendTextMsg(msgid, im_send_content, content_you,false);
                    } else if (_contact_type_g == content_type) {
                        EV_sendTextMsg(msgid, im_send_content, content_you,false);
                    } else {
                        EV_sendMcmMsg(msgid, im_send_content, content_you,false);
                    };

    });
     
    
     

   

}

/**
 * 发送本地附件
 */
function DO_im_attachment_files()
{
    var msgid = new Date().getTime();
    var content_type = '';
    var content_you = '';
    var b = false;
    content_type = 'C';

    $('#user_list').find('li').each(function() {
        if ($(this).hasClass('select_user')) {
            content_you = $(this).attr('select_u_id');
        }
    });

    if(content_you)
    {
        b=true;
    }
    if (!b) {
        alert_box("请选择要对话的联系人或群组");
        $('#im_attachment_file').val('');
        return;
    };


    if(_serverNo == content_you){
        alert_box("系统消息禁止回复");
        return;
    }
    var str = '<div class="progress progress-striped active">'
        + '<div class="bar" style="width: 40%;"></div>'
        + '</div>'
        + '<span imtype="msg_attach" class="bubble">'
        + '<a imtype="msg_attach_href" href="javascript:void(0);" target="_blank">'
        + '<span>'
        + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="img/attachment_icon.png"/>'
        + '</span>'
        + '<span imtype="msg_attach_name"></span>'
        + '</a>'
        + '<span style="font-size: small;margin-left:15px;"></span>'
        + '<input imtype="msg_attach_resend" type="file" accept="" style="display:none;margin: 0 auto;" onchange="DO_im_attachment_file_up(\''
        + content_you + '_' + msgid + '\', \'' + msgid + '\')">'
        + '</span>';
    // 添加右侧消息
    var id = show_t_msg('temp_msg', 6, msgid,
        content_type, content_you, str);
    $(document.getElementById(id)).find('input[imtype="msg_attach_resend"]').click();


}


/**
 * 打开本地文件时触发本方法
 *
 * @param id --
 *            dom元素消息体的id
 * @param msgid
 * @constructor
 */
function DO_im_attachment_file_up(id, oldMsgid)
{
    var msg = $(document.getElementById(id));
    var oFile = msg.find('input[imtype="msg_attach_resend"]')[0].files[0];
    var msgType = 0;

    window.URL = window.URL || window.webkitURL || window.mozURL
        || window.msURL;
    var url = window.URL.createObjectURL(oFile);
    var num = oFile.size;
    var size = 0;
    if(num < 1024){
        size = num + "byte";
    }else if(num/1024 >= 1 && num/Math.pow(1024,2) <1){
        size = Number(num/1024).toFixed(2) + "KB";
    }else if(num/Math.pow(1024,2) >= 1 && num/Math.pow(1024,3) <1){
        size = Number(num/Math.pow(1024,2)).toFixed(2) + "MB";
    }else if(num/Math.pow(1024,3) >= 1 && num/Math.pow(1024,4) <1){
        size = Number(num/Math.pow(1024,3)).toFixed(2)+"G";
    };
    var receiver = msg.attr('content_you');
    //判断如果该浏览器支持拍照，那么在这里做个附件图片和文件的区别化展示；
    if($("#camera_button").find("i").hasClass("icon-picture")){
        msg.find('a[imtype="msg_attach_href"]').attr('href', url);
        msg.find('span[imtype="msg_attach_name"]').html(oFile.name);
        msg.find('a[imtype="msg_attach_href"]').next().html(size);
        msgType = 6;
    }else{
        if("image" == oFile.type.substring(0,oFile.type.indexOf("/"))){
            var windowWid = $(window).width();
            var imgWid = 0;
            var imgHei = 0;
            if (windowWid < 666) {
                imgWid = 100;
                imgHei = 150;
            } else {
                imgWid = 150;
                imgHei = 200;
            }
            var str = '<img imtype="msg_attach_src" src="'+ url + '" style="max-width:'
                + imgWid + 'px; max-height:' + imgHei + 'px;" '
                + '  onclick="open_img(this)"/>';
            msg.find('a[imtype="msg_attach_href"]').replaceWith(str);

            msgType = 4;
        }else{
            msg.find('a[imtype="msg_attach_href"]').attr('href', url);
            msg.find('span[imtype="msg_attach_name"]').html(oFile.name);
            msg.find('a[imtype="msg_attach_href"]').next().html(size);
            msgType = 6;
        }
    }

    // 查找当前选中的contact_type值 1、IM上传 2、MCM上传
    var current_contact_type = 'C';

    if (_contact_type_m == current_contact_type) {
        EV_sendToDeskAttachMsg(oldMsgid, oFile, msgType, receiver);
    } else {
        EV_sendAttachMsg(oldMsgid, oFile, msgType, receiver);
    }



}

/**
 * 发送附件
 *
 * @param msgid
 * @param file --
 *            file对象
 * @param type --
 *            附件类型 2 语音消息 3 视频消息 4 图片消息 5 位置消息 6 文件消息
 * @param receiver --
 *            接收者
 * @constructor
 */
function EV_sendAttachMsg(oldMsgid, file, type, receiver,isresend) {

    var obj = new RL_YTX.MsgBuilder();
    obj.setFile(file);
    obj.setType(type);
    obj.setReceiver(receiver);
    var remsid = receiver + '_' + oldMsgid;
    var oldMsg = $(document.getElementById(remsid));
    //oldMsg.show();
    oldMsg.attr('msg', 'msg');
    oldMsg.css('display', 'block');
    if(4 == type){
        oldMsg.attr('im_carousel', 'real');
        oldMsg.attr('im_msgtype', '4');
    }



    var msgid = RL_YTX.sendMsg(obj, function(obj) {
        setTimeout(function() {
            var id = receiver + "_" + obj.msgClientNo;
            var msg = $(document.getElementById(id));
            msg.find('span[imtype="resend"]').css(
                'display', 'none');
            msg.find('div[class="bar"]').parent().css(
                'display', 'none');
            msg.find('span[imtype="msg_attach"]').css(
                'display', 'block');
            /*
                把信息发到im/ajax_img.php
                把一大段HTML，提取附件地址

                weichat:sunkangchina

            */
             
            var d =  '<img imtype="msg_attach_src" src="'+obj.fileUrl+'" style="max-width:130px; max-height:200px;" onclick="open_img(this)">';

            send_to_server_bysun(_user_account,receiver,oldMsgid,d ,type);
             

            if(isresend){
                $('#msg_list').append(msg.prop("outerHTML"));
                msg.remove();// 删掉原来的展示
            }
        }, 100)
    }, function(obj) {// 失败
        setTimeout(function() {
            var msg = $(document.getElementById(receiver + "_" + obj.msgClientNo));
            msg.find('span[imtype="resend"]').css(
                'display', '');
            msg.find('div[class="bar"]').parent().css(
                'display', 'none');
            msg.find('span[imtype="msg_attach"]').css(
                'display', '');
        }, 100)
    }, function(sended, total, msgId) {// 进度条
        setTimeout(function() {
            var msg = $(document.getElementById(receiver + "_" + msgId));
            // sended;//已发送字节数
            // total;//总字节数
            if (sended < total) {
                msg.find('div[class="bar"]').css(
                    'width',
                    (sended / total * 100 + '%'));
            } else {
                msg.find('div[class="bar"]').parent()
                    .css('display', 'none');
                msg.find('span[imtype="msg_attach"]')
                    .css('display', 'block');
            };
        }, 100)
    });
    oldMsg.attr("id", receiver + '_' + msgid);
    if(file instanceof Blob){
        oldMsg.find("object").val(file);
    }
//    setTimeout('$("#chat_show_img").trigger("click")', 100);
    $('#msg_list').trigger("scroll");
    $('#msg_list').scrollTop($('#msg_list')[0].scrollHeight);
}

/**
 * 发送附件
 *
 * @param msgid
 * @param file --
 *            file对象
 * @param type --
 *            附件类型 2 语音消息 3 视频消息 4 图片消息 5 位置消息 6 文件消息
 * @param receiver --
 *            接收者
 * @constructor
 */
function EV_sendToDeskAttachMsg(oldMsgid, file, type, receiver,isresend) {
   
    var obj = new RL_YTX.DeskMessageBuilder();
    obj.setFile(file);
    obj.setType(type);
    obj.setOsUnityAccount(receiver);

    if($("#fireMessage").attr("class").indexOf("active")>-1){//domain
        obj.setDomain("fireMessage");
    };
    var oldMsg = $(document.getElementById(receiver + '_' + oldMsgid));
    oldMsg.attr('msg', 'msg');
    oldMsg.css('display', 'block');
    $('#msg_list').scrollTop($('#msg_list')[0].scrollHeight);
    var msgid = RL_YTX.sendToDeskMessage(obj, function(obj) {// 成功
        setTimeout(function() {
            var msg = $(document.getElementById(receiver + "_" + obj.msgClientNo));
            msg.find('span[imtype="resend"]').css(
                'display', 'none');
            msg.find('div[class="bar"]').parent().css(
                'display', 'none');
            msg.find('span[imtype="msg_attach"]').css(
                'display', 'block');
            msg.attr('msg', 'msg');
            if(isresend){
                $('#msg_list').append(msg.prop("outerHTML"));
                msg.remove();// 删掉原来的展示
            }
        }, 100);
    }, function(obj) {// 失败
        setTimeout(function() {
            var msg = $(document.getElementById(receiver + "_" + obj.msgClientNo));
            msg.find('span[imtype="resend"]').css(
                'display', 'block');
            msg.find('div[class="bar"]').parent().css(
                'display', 'none');
            msg.find('span[imtype="msg_attach"]').css(
                'display', 'block');
        }, 100);
    }, function(sended, total, msgId) {// 进度条
        setTimeout(function() {
            var msg = $(document.getElementById(receiver + "_" + msgId));

            // sended;//已发送字节数
            // total;//总字节数
            if (sended < total) {
                msg.find('div[class="bar"]').css(
                    'width',
                    (sended / total * 100 + '%'));
            } else {
                msg.find('div[class="bar"]').parent()
                    .css('display', 'none');
                msg.find('span[imtype="msg_attach"]')
                    .css('display', 'block');
            }
        }, 100);
    });
    oldMsg.attr("id", receiver + '_' + msgid);
}

/**
 * 事件，发送消息
 *
 * @param msgid
 * @param text
 * @param receiver
 * @param isresend
 * @constructor
 */
function EV_sendTextMsg(oldMsgid, text, receiver,isresend) {
    var obj = new RL_YTX.MsgBuilder();
    obj.setText(text);
    obj.setType(1);
    obj.setReceiver(receiver);
    is_online(receiver);
    
    var msgId = RL_YTX.sendMsg(obj, function(obj) {
  
//        setTimeout(function() {
            /* $(document.getElementById(receiver + '_' + obj.msgClientNo)).find('span[imtype="resend"]').css('display', 'none');
             console.log('send Text message succ');*/
            /*if(isresend){
             var msg = $(document.getElementById(receiver + '_' + obj.msgClientNo));
             $('#im_content_list').append(msg.prop("outerHTML"));
             msg.remove();// 删掉原来的展示
             };*/
//        }, 300)
    }, function(obj) {
//        setTimeout(function() {
            /* var msgf = $(document.getElementById(receiver + '_' + obj.msgClientNo));
             if(msgf.find('pre [msgtype="resendMsg"]').length == 0){
             var resendStr = '<pre msgtype="resendMsg" style="display:none;">'+text+'</pre>'
             msgf.append(resendStr);
             }

             msgf.find('span[imtype="resend"]').css('display', 'block');*/
//            alert_box("IM错误码： " + obj.code+"; 错误描述："+obj.msg);
//        }, 300)
    });
    $(document.getElementById(receiver + '_' + oldMsgid)).attr("id",receiver + "_" + msgId);
}



function DO_pre_replace_content_to_db() {
    var str = $('#send_message').html();
    str = str.replace(/<(div|br|p)[/]?>/g, '\u000A');
    str = str.replace(/\u000A+/g, '\u000D');
    str = str.replace(/<[^img][^>]+>/g, '');// 去掉所有的html标记
    str = str.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(
        /&quot;/g, '"').replace(/&amp;/g, '&').replace(/&nbsp;/g,
        ' ');
    if ('\u000D' == str) {
        str = '';
    }
    return str;
}

function show_t_msg(msg,msgtype,msgid,content_type,content_you,send_content)
{

    var time = new Date();
    var ymdhis = "";

    ymdhis += '今天';
    if(time.getHours() < 10)
    {
        ymdhis += " " +'0'+ time.getHours() + ":";
    }else{
        ymdhis += " " + time.getHours() + ":";
    }

    if(time.getMinutes() < 10)
    {
        ymdhis += '0'+time.getMinutes() + ":";
    }else{
        ymdhis += time.getMinutes() + ":";
    }

    if(time.getSeconds() < 10)
    {
        ymdhis += '0'+time.getSeconds();
    }else{
        ymdhis += time.getSeconds();
    }

    send_content = emoji.replace_unified(send_content);

    var display = ('temp_msg' == msg) ? 'none' : 'block';
    //var display = 'block';
    var carou = '';
    if(msgtype == 4 || msgtype == 3)
    {
        carou = "real";
    };

    
      //保存记录
      // weichat: sunkangchina
      if(msgtype != 6){ //附件的要等上传结束才能处理，需单独处理
            send_to_server_bysun(_user_account,content_you,msgid,update_chat_msg(send_content) ,msgtype);
      }
     
      


                //显示发出的消息
                var user_info = user;
                var u_id = content_you;
                var text_append = '';
                var obj_msg = obj.find("div[select_user_msg='" + u_id + "']");


                text_append += '<div class="to_msg" m_id="' + msgid + '" id="'+content_you+'_'+msgid+'" content_type="'+content_type+'" content_you="'+content_you+'" style="display:'+display+'">';
                text_append += '<span class="user-avatar sss"><img src="' + user_logo + '"></span>';
                text_append += '<dl><dt class="to-msg-time">';
                text_append += ymdhis + '</dt>';
                text_append += '<dd class="to-msg-text" style="margin-left: 0px;">  ';
                text_append += update_chat_msg(send_content) + '</dd>';
                text_append += '<dd class="arrow"></dd>';
                text_append += '</dl>';
                text_append += '</div>';

                obj_msg.append(text_append);
                var n = obj_msg.find("div[m_id]").size();
 


                obj.find("#msg_list").scrollTop(obj_msg.height());

                $('#send_message').html('');

                return content_you + '_' + msgid;

             
}

/**
 * 样式，发送消息时添加右侧页面样式
 *
 * @param msg --
 *            是否为临时消息 msg、temp_msg;msg
 *            右侧对话消息display为block；temp_msg用于发送本地文件；需要点击确定的时候resendMsg方法中修改属性为block
 * @param msgtype --
 *            消息类型1:文本消息 2：语音消息 3：视频消息 4：图片消息 5：位置消息 6：文件
 * @param msgid --
 *            消息版本号，本地发出时均采用时间戳long
 * @param content_type --
 *            C G or M
 * @param content_you --
 *            对方帐号：发出消息时对方帐号，接收消息时发送者帐号
 * @param im_send_content --
 *            消息内容
 * @constructor
 */
function HTML_sendMsg_addHTML(msg, msgtype, msgid, content_type,
                              content_you, im_send_content) {
    var time = new Date();
    var ymdhis = "";

    im_send_content = emoji.replace_unified(im_send_content);
 
 
            var display = 'block';
            var carou = '';
            if(msgtype==4||msgtype==3){
                carou="real";
            };

            if(!user_logo)
            {
                var user_logo = './img/avatar.jpg';
            }
            var str = '<div contactor="sender" im_carousel="'+carou+'" msg="'
                + msg
                + '" im_msgtype="'
                + msgtype
                + '" id="'
                + content_you
                + '_'
                + msgid
                + '" content_type="'
                + content_type
                + '" content_you="'
                + content_you
                + '" class="alert alert-right alert-success" style="display:'
                + display
                + '">'
                + '<span imtype="resend" class="add-on" onclick="IM.EV_resendMsg(this)"'
                + ' style="display:none; cursor:pointer; position: relative; left: -40px; top: 0px;"><i class="icon-repeat"></i></span>'
                + '<img src="'+ user_logo +'" style="float: right; width: 38px; height: 38px; border: 1px solid #ccc; background: #dcdcdc none repeat scroll 0 0 padding-box; border-radius: 8px; margin:10px 10px 0;">' + im_send_content + '<div class="name name-right" style="float:right;text-align:right;padding-right:60px;width:100%;">'+ymdhis+'</div></div>';

            $('#im_content_list').append(str);

            $('#im_send_content').html('');

            $('#im_content_list').scrollTop($('#im_content_list')[0].scrollHeight);


            return content_you + '_' + msgid;           

     
    
}

function DO_login_user()
{
    var login_url = $('.login_url').val();
    window.location.href = login_url;
}
function DO_login(user_account) {
    _login(user_account);
}

function isNull(value){
    if(value == '' || value == undefined
        || value == null){
        return true;
    }
}



_appid = window.appID;
_onUnitAccount = 'KF10089'; // 多渠道客服帐号，目前只支持1个
_3rdServer = 'http://123.57.230.158:8886/authen/'; // 3rdServer，主要用来虚拟用户服务器获取SIG


/** 以下不要动，不需要改动 */
_timeoutkey = null;
_username = null;
_user_account = null;
_contact_type_c = 'C'; // 代表联系人
_contact_type_g = 'G'; // 代表群组
_contact_type_m = 'M'; // 代表多渠道客服
_onMsgReceiveListener = null;
_onDeskMsgReceiveListener = null;
_noticeReceiveListener = null;
_onConnectStateChangeLisenter = null;
_onCallMsgListener = null;
_isMcm_active = false;
_local_historyver = 0;
_msgId = null;// 消息ID，查看图片时有用
_pre_range = null;// pre的光标监控对象
_pre_range_num = 0; // 计数，记录pre中当前光标位置，以childNodes为单位
_fireMessage = 'fireMessage';
_serverNo = 'XTOZ';
_baiduMap=null;
_loginType=1;//登录类型: 1账号登录，3voip账号密码登录
_Notification=null;



/**
 * 初始化
 *
 * @private
 */

// 初始化SDK
var resp = RL_YTX.init(_appid);
if (!resp) {
    alert_box('IM SDK初始化错误');
    
};
if (200 == resp.code) {// 初始化成功

}else if(174001 == resp.code){// 不支持HTML5
    var r = confirm(resp.msg);
    if (r == true || r == false) {
        window.close();
    }
}else if(170002 == resp.code){//缺少必须参数
    alert_box("IM错误码：170002,错误码描述"+resp.msg);
} else {
    alert_box('IM未知状态码');
}


/**
 * 初始化表情
 */
function initEmoji() {

    var emoji_div = $('#emoji_div').find('div[class="popover-content"]');
    for (var i in emoji.show_data) {
        var c = emoji.show_data[i];
        var out = emoji.replace_unified(c[0][0]);

        var content_emoji = '<span style="cursor:pointer; margin: 0 2px 0 4px;" ' +
            'onclick="DO_chooseEmoji(\''+ i + '\', \'' + c[0][0] + '\')" ' +
            'imtype="content_emoji">' + out + '</span>';
        emoji_div.append(content_emoji);
    }

}








/**
 * 初始化一些页面需要绑定的事件
 */
$('#send_message').bind('paste', function() {
    DO_pre_replace_content();
});
function DO_pre_replace_content() {
    setTimeout(function() {
        var str = DO_pre_replace_content_to_db();
        $('#send_message').html(str);
    }, 20);
};

/**
 * 正式处理登录逻辑，此方法可供断线监听回调登录使用 获取时间戳，获取SIG，调用SDK登录方法
 *
 * @param user_account
 * @param pwd 密码
 * @private
 */
function _login(user_account) {
    var timestamp = getTimeStamp();
    var flag = false;//是否从第三方服务器获取sig
    
    if(flag){
        _privateLogin(user_account, timestamp, function(obj) {
            EV_login(user_account, obj.sig, timestamp);
        }, function(obj) {
            $('#navbar_user_account').removeAttr("readonly");
//            console.log("错误码_login："+obj.code+"; 错误描述："+obj.msg);
        });
    }else{
        //仅用于本地测试，官方不推荐这种方式应用在生产环境
        //没有服务器获取sig值时，可以使用如下代码获取sig
        var appToken = window.appToken;//使用是赋值为应用对应的appToken
        var sig = hex_md5(_appid + user_account + timestamp + appToken);
        EV_login(user_account,sig, timestamp);
    }
}

/**
 * 获取当前时间戳 YYYYMMddHHmmss
 *
 * @returns {*}
 */
function getTimeStamp() {
    var now = new Date();
    var timestamp = now.getFullYear() + ''
        + ((now.getMonth() + 1) >= 10 ?""+ (now.getMonth() + 1) : "0"
        + (now.getMonth() + 1))
        + (now.getDate() >= 10 ? now.getDate() : "0"
        + now.getDate())
        + (now.getHours() >= 10 ? now.getHours() : "0"
        + now.getHours())
        + (now.getMinutes() >= 10 ? now.getMinutes() : "0"
        + now.getMinutes())
        + (now.getSeconds() >= 10 ? now.getSeconds() : "0"
        + now.getSeconds());
    return timestamp;
}

/**
 * SIG获取 去第三方（客服）服务器获取SIG信息 并将SIG返回，传给SDK中的登录方法做登录使用
 *
 * @param user_account
 * @param timestamp -- 时间戳要与SDK登录方法中使用的时间戳一致
 * @param callback
 * @param onError
 * @private
 */
function _privateLogin(user_account, timestamp, callback, onError) {
    var data = {
        "appid" : _appid,
        "username" : user_account,
        "timestamp" : timestamp
    };
    var url = _3rdServer+'genSig';
    $.ajax({
        url : url,
        dataType : 'jsonp',
        data : data,
        jsonp : 'cb',
        success : function(result) {
            if (result.code != 000000) {
                var resp = {};
                resp.code = result.code;
                resp.msg = "Get SIG fail from 3rd server!...";
                onError(resp);
                return;
            } else {
                var resp = {};
                resp.code = result.code;
                resp.sig = result.sig;
                callback(resp);
                return;
            }
        },
        error : function() {
            var resp = {};
            resp.msg = 'Get SIG fail from 3rd server!';
            onError(resp);
        },
        timeout : 5000
    });
}

/**
 * 事件，登录 去SDK中请求登录
 *
 * @param user_account
 * @param sig
 * @param timestamp --
 *            时间戳要与生成SIG参数的时间戳保持一致
 * @constructor
 */
function EV_login(user_account, sig, timestamp) {
    var loginBuilder = new RL_YTX.LoginBuilder();
    loginBuilder.setType(_loginType);
    loginBuilder.setUserName(user_account);

    if(1 == _loginType){//1是自定义账号，3是voip账号
        loginBuilder.setSig(sig);
    }else{
        loginBuilder.setPwd(pwd);
    }
    loginBuilder.setTimestamp(timestamp);
    
    //$.post(ucenter_url+"?ctl=Login&met=checkStatus&typ=json",{} ,function(result) {

    $.ajax({
        type: "get",
        url: ucenter_url + "?ctl=Login&met=checkStatus&typ=json",
        dataType: "jsonp",
        jsonp: "jsonp_callback",
        success: function (result)
        {
            if (result.status == 200)
            {
                $.post(imbuilder_url + "?ctl=Login&met=checkToLogin&typ=json", {
                    ks: result.data.ks,
                    us: result.data.us
                }, function (data)
                {
                    if (data.status == 200)
                    {
                        k = data.data.k;
                        u = data.data.user_id;
                        RL_YTX.login(loginBuilder, function (obj)
                        {
                            _user_account = user_account;
                            _username = user_account;
                            EV_getMyInfo();

                           console.log('Version:'+_local_historyver)

                            // 注册PUSH监听
                            _onMsgReceiveListener = RL_YTX.onMsgReceiveListener(
                                function (obj)
                                {
                                    u = data.data.id;
                                    /**
                                     * 此处需要从服务器拉取聊天消息版本号
                                     * 当从服务器拉取的信息与容联云版本号比较。
                                     * 如 
                                     *   
                                     */
                                    var userId = u ? u : user['u_id'];
                                    $.post(imbuilder_url+"?ctl=UserApi&met=getUserInfo&typ=json&yf_cache=open",{"k":k,"u":userId} ,function(data1) {
                                        if(data1.status == 200)
                                        {

                                            _local_historyver = data1.data.message_version;

                                            /*alert(_local_historyver);
                                            alert(obj.version);*/

                                            if(parseInt(_local_historyver) < parseInt(obj.version))
                                            {
                                               console.info(userId+'tankuang'+u);
                                               u = userId;
                                                //版本号比容联云版本号小时，执行。
                                                EV_onMsgReceiveListener(obj);
                                            }

                                        }
                                    });


                                     
                                });
                            // 登录后拉取群组列表
                            EV_getGroupList();


                            console.log('Version Ser:'+obj.historyver)

                             // 登录后拉取未读过的消息

                            if (_local_historyver <= parseInt(obj.historyver)
                                && parseInt(obj.historyver) < parseInt(obj.version))
                            {
                                //alert_box(obj.version);
                                _local_historyver = parseInt(obj.historyver)
                                //EV_syncMsg(parseInt(obj.historyver) + 1, obj.version);
                            }

                            



                        }, function (obj)
                        {

//                            console.log("错误码： " + obj.code + "; 错误描述：" + obj.msg);
                        });

                        $("#navbar").hide();
                    }
                })
            }
            else
            {
                /*
                    index.html首页 不要提示没登录。
                    首页是都可以查看的
                    weichat: sunkangchina
                */
                if(window.parent.location.href.indexOf('index.html') == -1){
                    
                }
            }
        }
    });

}

/**
 * 事件，push消息的监听器，被动接收信息
 *
 * @param obj
 * @constructor
 */
function EV_onMsgReceiveListener(obj) {
  
    DO_push_createMsgDiv(obj);


}

/**
 * 添加消息列表的辅助方法 消息的联系人(you_sender)，是否是当前展示的联系人
 * 并处理左侧联系人列表的展示方式（新增条数，及提醒数字变化）
 *
 * @param you_sender
 * @param b_isGroupMsg --
 *            true:group消息列表 false:点对点消息列表
 * @returns {boolean} -- true:是当前展示的联系人；false:不是
 * @constructor
 */
function DO_createMsgDiv_Help(you_sender, name, b_isGroupMsg) {
    var flag = false;
    if($('#new_msg_dialog').is(":hidden"))
    {
        var flag = true;
    }

    $('#user_list').find('li').each(function() {
        if ($(this).hasClass('select_user')) {
            content_you = $(this).attr('select_u_id');
        }
    });
    // 处理联系人列表，如果新联系人添加一条新的到im_contact_list，如果已经存在给出数字提示
    var b_current_contact_you = false; // push消息的联系人(you_sender)，是否是当前展示的联系人
    $('#chat_user_friends').find('dd').each(function() {
        if (you_sender == $(this).attr('u_id') && (you_sender !=content_you || flag)) {
            $(this).find('a').addClass('showmsg');
        }
    });

    // 新建联系人到“最近联系人”
    update_recent(you_sender);

    return b_current_contact_you;
}
var push_count = 0;
/**
 * 添加PUSH消息，只做页面操作 供push和拉取消息后使用
 *
 * @param obj
 * @constructor
 */
function DO_push_createMsgDiv(obj) {
    //判断是否是阅后即焚消息
    var isFireMsg = false;
    console.info('DO_push_createMsgDiv1');
    if(_fireMessage == obj.msgDomain){
        isFireMsg = true;
    }
    console.info('DO_push_createMsgDiv2');
    var b_isGroupMsg = ('g' == obj.msgReceiver.substr(0, 1));
    var you_sender = (b_isGroupMsg) ? obj.msgReceiver : obj.msgSender;
    var you_senderNickName = obj.senderNickName;
    var name = obj.msgSender;
    if (!!you_senderNickName) {
        name = you_senderNickName;
    }
    console.info('DO_push_createMsgDiv3');
    // push消息的联系人，是否是当前展示的联系人
    var b_current_contact_you = DO_createMsgDiv_Help(you_sender,
        name, b_isGroupMsg);
    // 是否为mcm消息 0普通im消息 1 start消息 2 end消息 3发送mcm消息
    var you_msgContent = obj.msgContent;
    var content_type = null;
    //var version = obj.version;//改版
    var version = obj.msgId;
    var time = obj.msgDateCreated;
    var userId = u ? u : user['u_id'];
    var ajaxurl = imbuilder_url+'?ctl=UserApi&met=getUserInfo&typ=json&k='+k+'&u='+userId+'&user_account='+you_sender;
    $.ajax({
        type: "POST",
        url: ajaxurl,
        dataType: "json",
        async: false,
        success: function (rs)
        {
            console.info('DO_push_createMsgDiv4');
            if (rs.status == 200)
            {
                member = rs.data;
            }

            u_name = member['user_account'];
            avatar = member['user_avatar'];
         //   is_online(u_name);

            set_user_info(you_sender, "u_name", u_name);
            /*set_user_info(you_sender, "s_id", member['userid']);
             set_user_info(you_sender, "s_name", member['nickname']);*/
            set_user_info(you_sender, "avatar", avatar);
console.info('DO_push_createMsgDiv5');
            show_msg(u_name);
            //将用户的version修改成最新的version
            EV_editMyInfo(obj.version);
        }
    });


    //显示历史记录，1天的
    if(push_count == 0){
        show_msg_list(u_name);
        bottom_bar();
    }
    push_count++;
    

    //

    if (0 == obj.mcmEvent) {// 0普通im消息

        var msgType = obj.msgType;
        var str = '';
        //消息类型1:文本消息 2：语音消息 3：视频消息 4：图片消息 5：位置消息 6：文件
        if (1 == msgType) {

            str = emoji.replace_unified(you_msgContent);

            /*if(isFireMsg){
             str = '<pre fireMsg="yes" >' + str + '</pre>';
             }else{
             str = '<pre class="bubble" style="margin-top:-8px;">' + str + '</pre>';
             }*/

            /* alert_box(you_msgContent);  //str  接受的信息内容
             alert_box(b_isGroupMsg);    //分组
             alert_box(version);         //msgid
             alert_box(time);            //时间
             alert_box(obj.msgType);     //信息类型 1
             alert_box(obj.mcmEvent);    //0 普通信息*/
            //alert_box(str);
            //show_f_msg(msgType,str,you_sender,avatar,version,time);
        } else if (2 == msgType) {
            //判断是否支持支持audio标签
            str = '<pre>您有一条语音消息,请用其他设备接收</pre>';
            /*
             var url = obj.msgFileUrl;
             // str = '你接收了一条语音消息['+ url +']';
             if(isFireMsg){
             str = '<audio fireMsg="yes" style="display:none" controls="controls" src="' + url
             + '">your browser does not surpport the audio element</audio>';
             }else{
             str = '<audio controls="controls" src="' + url
             + '">your browser does not surpport the audio element</audio>';
             }
             */

        } else if (3 == msgType) {// 3：视频消息

            var urlShow = obj.msgFileUrlThum;//小视频消息的缩略图地址
            var urlReal = obj.msgFileUrl;
            var windowWid = $(window).width();
            var imgWid = 0;
            var imgHei = 0;
            if (windowWid < 666) {
                imgWid = 100;
                imgHei = 150;
            } else {
                imgWid = 150;
                imgHei = 200;
            };
            var num = obj.msgFileSize;
            var size = 0;
            if(num < 1024){
                size = num + "byte";
            }else if(num/1024 >= 1 && num/Math.pow(1024,2) <1){
                size = Number(num/1024).toFixed(2) + "KB";
            }else if(num/Math.pow(1024,2) >= 1 && num/Math.pow(1024,3) <1){
                size = Number(num/Math.pow(1024,2)).toFixed(2) + "MB";
            }else if(num/Math.pow(1024,3) >= 1 && num/Math.pow(1024,4) <1){
                size = Number(num/Math.pow(1024,3)).toFixed(2)+"G";
            };
            if(isFireMsg){
                str = '<div style="display:inline"><img fireMsg="yes" onclick="IM.DO_pop_phone(\''+you_sender+'\', \''
                    + version + '\')" videourl="'+urlReal+'" src="'+urlShow+'" style="max-width:'
                    + imgWid + 'px;max-height:' + imgHei + 'px;display:none;cursor:pointer" />'
                    + '<span style="font-size: small;margin-left:15px;">'+size+'</span></div>';
            }else{
                str = '<div style="display:inline"><img onclick="IM.DO_pop_phone(\''+you_sender+'\', \''
                    + version + '\')" videourl="'+urlReal+'" src="'+urlShow+'" style="cursor:pointer;max-width:'
                    + imgWid + 'px;max-height:' + imgHei + 'px;" />'
                    + '<span style="font-size: small;margin-left:15px;">'+size+'</span></div>';
            }

        } else if (4 == msgType) {// 4：图片消息
            str = obj.msgFileUrl;

        } else if (5 == msgType) {// 位置消息
            //str = '你接收了一条位置消息...';
            var jsonObj = eval('(' + you_msgContent + ')');
            var lat = jsonObj.lat; //纬度
            var lon = jsonObj.lon; //经度
            var title = jsonObj.title; //位置信息描述
            var windowWid = $(window).width();
            var imgWid = 0;
            var imgHei = 0;
            if (windowWid < 666) {
                imgWid = 100;
                imgHei = 150;
            } else {
                imgWid = 150;
                imgHei = 200;
            };
            var str = '<img src="img/baidu.png" style="cursor:pointer;max-width:'
                + imgWid + 'px; max-height:' + imgHei
                + 'px;" onclick="IM.DO_show_map(\'' + lat
                + '\', \'' + lon + '\', \'' + title + '\')"/>';


        } else if (6 == msgType) {// 文件
            var url = obj.msgFileUrl;
            var num = obj.msgFileSize;
            var size = 0;
            if(num < 1024){
                size = num + "byte";
            }else if(num/1024 >= 1 && num/Math.pow(1024,2) <1){
                size = Number(num/1024).toFixed(2) + "KB";
            }else if(num/Math.pow(1024,2) >= 1 && num/Math.pow(1024,3) <1){
                size = Number(num/Math.pow(1024,2)).toFixed(2) + "MB";
            }else if(num/Math.pow(1024,3) >= 1 && num/Math.pow(1024,4) <1){
                size = Number(num/Math.pow(1024,3)).toFixed(2)+"G";
            };

            var fileName = obj.msgFileName;

            if(isFireMsg){
                str = '<div style="display:inline"><a fireMsg="yes" href="' + url + '" target="_blank">'
                    + '<span>'
                    + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="assets/img/attachment_icon.png" />'
                    + '</span>' + '<span>' + fileName + '</span>' //+ '<span style="font-size: small;margin-left:15px;">'+size+'</span>'
                    + '</a>'+ '<span style="font-size: small;margin-left:15px;">'+size+'</span></div>';
            }else{
                str = '<div style="display:inline"><a href="' + url + '" target="_blank">'
                    + '<span>'
                    + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="assets/img/attachment_icon.png" />'
                    + '</span>' + '<span>' + fileName + '</span>' //+ '<span style="font-size: small;margin-left:15px;">'+size+'</span>'
                    + '</a>'+ '<span style="font-size: small;margin-left:15px;">'+size+'</span></div>';
            }
        }

        /*
         HTML_pushMsg_addHTML(msgType, you_sender, version,
         content_type, b_current_contact_you, name, str);
         */
        show_f_msg(msgType,str,you_sender,avatar,version,time);
        //桌面提醒通知
        DO_deskNotice(you_sender,name,you_msgContent,msgType,isFireMsg,false);

    } else if (1 == obj.mcmEvent) {// 1 start消息
        HTML_pushMsg_addHTML(obj.msgType, you_sender, version,
            _contact_type_m, b_current_contact_you, name,
            you_msgContent);
    } else if (2 == obj.mcmEvent) {// 2 end消息
        HTML_pushMsg_addHTML(obj.msgType, you_sender, version,
            _contact_type_m, b_current_contact_you,
            name, "结束咨询");
    } else if (3 == obj.mcmEvent) {// 3发送mcm消息
        HTML_pushMsg_addHTML(obj.msgType, you_sender, version,
            _contact_type_m, b_current_contact_you, name,
            you_msgContent);
    } else if (53 == obj.mcmEvent) {// 3发送mcm消息

        content_type = IM._contact_type_m;
        var msgType = obj.msgType;
        var str = '';

        //消息类型1:文本消息 2：语音消息 3：视频消息 4：图片消息 5：位置消息 6：文件
        if (1 == msgType) {
            msgType = 1;
            str = emoji.replace_unified(you_msgContent);
            if(isFireMsg){
                str = '<pre fireMsg="yes">' + str + '</pre>';
            }else{
                str = '<pre class="bubble">' + str + '</pre>';
            }

        } else if (2 == msgType) {
            var url = obj.msgFileUrl;
            if(isFireMsg){
                str = '<audio fireMsg="yes" style="display:none" controls="controls" src="' + url
                    + '">your browser does not surpport the audio element</audio>';
            }else{
                str = '<audio controls="controls" src="' + url
                    + '">your browser does not surpport the audio element</audio>';
            }
        } else if (3 == msgType) {// 3：视频消息
            var urlShow = obj.msgFileUrlThum;//小视频消息的缩略图地址
            var urlReal = obj.msgFileUrl;
            var windowWid = $(window).width();
            var imgWid = 0;
            var imgHei = 0;
            if (windowWid < 666) {
                imgWid = 100;
                imgHei = 150;
            } else {
                imgWid = 150;
                imgHei = 200;
            };
            if(isFireMsg){
                str = '<img fireMsg="yes" onclick="IM.DO_pop_phone(\''+you_sender+'\', \''
                    + version + '\')" videourl="'+urlReal+'" src="'+urlShow+'" style="max-width:'
                    + imgWid + 'px;max-height:' + imgHei + 'px;display:none" />';
            }else{
                str = '<img onclick="IM.DO_pop_phone(\''+you_sender+'\', \''
                    + version + '\')" videourl="'+urlReal+'" src="'+urlShow+'" style="max-width:'
                    + imgWid + 'px;max-height:' + imgHei + 'px;" />';
            }
        } else if (4 == msgType) {// 4：图片消息

            var url = obj.msgFileUrl;
            var windowWid = $(window).width();
            var imgWid = 0;
            var imgHei = 0;
            if (windowWid < 666) {
                imgWid = 100;
                imgHei = 150;
            } else {
                imgWid = 150;
                imgHei = 200;
            };
            if(isFireMsg){
                var str = '<img fireMsg="yes" src="' + url + '" style="max-width:'
                    + imgWid + 'px; max-height:' + imgHei
                    + 'px;display:none" onclick="IM.DO_pop_phone(\'' + you_sender
                    + '\', \'' + version + '\')"/>';
            }else{
                var str = '<img src="' + url + '" style="max-width:'
                    + imgWid + 'px; max-height:' + imgHei
                    + 'px;" onclick="IM.DO_pop_phone(\'' + you_sender
                    + '\', \'' + version + '\')"/>';
            }
        } else if (5 == msgType) {// 位置消息
            str = '你接收了一条位置消息...';
        } else if (6 == msgType) {// 文件
            var url = obj.msgFileUrl;
            var num = obj.msgFileSize;
            var size = 0;
            if(num < 1024){
                size = num + "byte";
            }else if(num/1024 >= 1 && num/Math.pow(1024,2) <1){
                size = Number(num/1024).toFixed(2) + "KB";
            }else if(num/Math.pow(1024,2) >= 1 && num/Math.pow(1024,3) <1){
                size = Number(num/Math.pow(1024,2)).toFixed(2) + "MB";
            }else if(num/Math.pow(1024,3) >= 1 && num/Math.pow(1024,4) <1){
                size = Number(num/Math.pow(1024,3)).toFixed(2)+"G";
            };
            var fileName = obj.msgFileName;
            if(isFireMsg){
                str = '<a fireMsg="yes" style="display:none" href="' + url + '" target="_blank">'
                    + '<span>'
                    + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="assets/img/attachment_icon.png" />'
                    + '</span>' + '<span>' + fileName + '</span>' + '<span style="font-size: small;margin-left:15px;">'+size+'</span>'
                    + '</a>';
            }else{
                str = '<a href="' + url + '" target="_blank">'
                    + '<span>'
                    + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="assets/img/attachment_icon.png" />'
                    + '</span>' + '<span>' + fileName + '</span>' + '<span style="font-size: small;margin-left:15px;">'+size+'</span>'
                    + '</a>';
            }
        }

        IM.HTML_pushMsg_addHTML(msgType, you_sender, version,
            content_type, b_current_contact_you, name, str);
    }
}

/**
 * 事件，主动拉取消息
 *
 * @param sv
 * @param ev
 * @constructor
 */
function EV_syncMsg(sv, ev) {

    var obj = new RL_YTX.SyncMsgBuilder();
    obj.setSVersion(sv);
    obj.setEVersion(ev);

    RL_YTX.syncMsg(obj, function(obj) {
        alert_box("错误码： " + obj.code+"; 错误描述："+obj.msg);
    });
}

/**
 * 事件，获取登录者个人信息
 *
 * @constructor
 */
function EV_getMyInfo() { 

    RL_YTX.getMyInfo(function(obj) {
        if (!!obj && !!obj.nickName) {
            _username = obj.nickName;
        };
        user_logo = './img/avatar.jpg';
        receiver_logo = '';
        
        var userId = u ? u : user['u_id'];
        $.post(imbuilder_url+"?ctl=UserApi&met=getUserInfo&typ=json",{"k":k,"u":userId} ,function(data) {
            if(data.status == 200)
            {
                console.info("---------");
                console.info(data);
                console.info("----------");
                _local_historyver = data.data.message_version;

                console.info(data.data.user_avatar);
                user_logo = data.data.user_avatar ? data.data.user_avatar : './img/avatar.jpg';
 
                $('.chat-box .chat-list-top h1 i').html('<img width="35" height="32" src="'+user_logo+'">');
                $('.chat-box .chat-list-top h1 i').css('background','none');
                   

            }
        });

    }, function(obj) {
        if (520015 != obj.code) {
            alert_box("错误码： " + obj.code+"; 错误描述："+obj.msg);
        }
    });
}


/**
 * 事件，修改登录者个人信息
 *
 * @constructor
 */
function EV_editMyInfo(version) {
    var userId = u ? u : user['u_id'];
    $.post(imbuilder_url+"?ctl=UserApi&met=editUserVersion&typ=json",{"k":k,"u":userId,"version":version} ,function(data) {
        if(data.status == 200)
        {
            console.info('修改用户信息版本号成功'+version);
        }
    });
}
 
 

function DO_pre_replace_content_to_db() {
    var str = $('#send_message').html();
    str = str.replace(/<(div|br|p)[/]?>/g, '\u000A');
    str = str.replace(/\u000A+/g, '\u000D');
    str = str.replace(/<[^img][^>]+>/g, '');// 去掉所有的html标记
    str = str.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(
        /&quot;/g, '"').replace(/&amp;/g, '&').replace(/&nbsp;/g,
        ' ');
    if ('\u000D' == str) {
        str = '';
    }
    return str;
}


function EV_getGroupList()
{
    if (user['u_id'] != '')
    {
        msg_dialog = '<div id="new_msg_dialog" class="msg-windows"><div class="user-tab-bar"><ul class="user-list" id="user_list"></ul></div><div class="msg-dialog">' +
            '<div id="dialog-body"  class="dialog-body">' +
            '<div class="msg-top"><dl class="user-info"><dt class="user-name"></dt><dd class="user-avatar avatar-0"><img src="" alt=""></dd><dd class="store-name"></dd></dl>' +
            '<span class="dialog-close" onclick="msg_dialog_close(\'new_msg_dialog\');">&nbsp;</span></div>' +
            '<div id="msg_list" class="msg-contnet"><div id="user_msg_list"></div></div>' +
            '<div class="msg-input-box"><div class="msg-input-title">' +
            '<div id="emoji_div" class="span8 popover top" style="display:none;">'+
            '<div class="popover-content" style="max-height: 97px; font-size:18px; overflow-y: auto">'+
            '</div>'+
            '</div>'+
            '<a id="chat_show_smilies" onclick="show_emoji_div()" class="chat_smiles">表情</a>' +
            '<a id="chat_show_img" href="javascript:void(0)" onclick="DO_im_attachment_files()" class="chat_img wkbutton_3">图片</a>' +
            '<span class="title">输入聊天信息</span><span class="chat-log-btn off" onclick="show_chat_log();">聊天记录<i></i></span></div>' +
         
            '<form id="msg_form"><pre name="send_message" id="send_message" class="textarea" oncopy="return false;" oncut="return false;"  onkeyup="send_keyup(event);" contenteditable="true" onfocus="send_focus();" ></pre><pre id="im_send_content_copy" style="display:none;"></pre>' +
            '<div class="msg-bottom"><div id="msg_count"></div><a href="JavaScript:void(0);" onclick="DO_sendMsg();" class="msg-button"><i></i>发送</a><div id="send_alert"></div></div></form></div></div>' +
            '<div id="dialog_chat_log" class="dialog_chat_log"></div><div id="dialog_right_clear" class="dialog_right_clear"></div></div><div id="dialog_clear" class="dialog_clear"></div></div>';
        var chat_user_list = '<div class="chat-box"><div class="chat-list"><div class="chat-list-top"><h1><i></i>联系人</h1><span class="minimize-chat-list" onclick="chat_show_list();"></span></div>' +
            '<div id="chat_user_list" class="chat-list-content"><div>'+
            '<dl id="chat_user_talks" style="margin-bottom:-17px"><dt onclick="chat_show_user_lists(\'talks\');"><span class="show" style="margin:10px -8px;"></span>最近联系人</dt><dd id="chat_talks"></dd></dl>' +
            '<dl id="chat_user_friends" style="margin-bottom:-17px"><dt onclick="chat_show_user_list(\'friends\');"><span class="show" style="margin:10px -8px;"></span>我的好友</dt><dd id="chat_friends"></dd></dl>' +
            //'<dl id="chat_user_admin" style="margin-bottom:-17px"><dt>管理平台</dt><dd id="chat_admin"></dd></dl>' +
            '</div></div>' +
            '</div><div class="bottom-bar"><span id="new_msg" class="ico" onclick="chat_show_list();"></span><a href="javascript:void(0)" onclick="chat_show_list();"><i></i></a></div></div>';


        var rand = Math.random(1);
        var ajaxurl = imbuilder_url+'?ctl=UserApi&met=getFriendList&typ=json';
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data:{"k":k,"u":u},
            dataType: "json",
            async: true,
            success: function (respone)
            {
                if (respone.status == 200)
                {
                    u_list = respone.data;
                    show_mse_user();
                }
                else
                {

                    u_list = []
                }


                i = 0;
                for (var user_name in u_list)
                {

                    var user_info = u_list[i];
                    var u_id = user_info['user_name'];

                    connect_list[u_id] = 0;
                    connect_n++;
                    set_user_info(u_id, "u_name", user_info['nickname']?user_info['nickname']:user_info['user_name']);
                    set_user_info(u_id, "avatar", user_info['user_avatar']);
                    set_user_info(u_id, "online", 1);


                    friend_list[u_id] = user_info;
                    // if (user_info['recent'] == 1)
                    // {
                    //     recent_list[u_id] = user_info;
                    // }
                    i++;
                }



               // setTimeout("getconnect()", 1000);

                $("#web_chat_dialog").prepend(chat_user_list);
 
                $('#chat_user_list').perfectScrollbar();

                //连接成功后
                connect = 1;
                //$("#web_chat_dialog").show();


                //显示，注册时间
                $(".tbar-tab-online-contact").show();
              

                //$('.tbar-tab-online-contact').click(function(){
                  //  $(".chat-list").slideDown();
                    $("#web_chat_dialog").toggle();
                //});

              

                if($("#new_msg_dialog").size()==0) $("#web_chat_dialog").after(msg_dialog);
                obj = $("#new_msg_dialog");

                update_friends();
                update_admin();
                update_recent();
                // 初始化表情
                initEmoji();
                /*
                 setInterval(function ()
                 {
                 $.get(index_url + 'act=web_chat&op=get_session&key=member_id');
                 }, time_max * 60000);
                 */

                //getMsgUnread
                getMsgUnread();
                window.interval_id =  setInterval("getMsgUnread()", 8000);

                window.show_interval_id =  setInterval("showMsgUnread()", 5000);

                //clearInterval(window.show_interval_id);
                //clearInterval(window.interval_id); 

            }
        });
    }
    else
    {
        //setTimeout("getconnect()", 2000);
    }
}
function show_mse_user()
{
        var talkurl = imbuilder_url+'?ctl=UserApi&met=getNewMesList&typ=json';
        $.ajax({
            type: "POST",
            url: talkurl,
            data:{"k":k,"u":u},
            dataType: "json",
            async: true,
            success: function (respone)
            {
                if (respone.status == 200)
                {
                    u_list = respone.data;
                }
                else
                {
                    u_list = []
                }

                i = 0;
                for (var user_name in u_list)
                {

                    var user_info = u_list[i];
                    var u_id = user_info['user_name'];

                    connect_list[u_id] = 0;
                    connect_n++;
                    set_user_info(u_id, "u_name", user_info['nickname']?user_info['nickname']:user_info['user_name']);
                    set_user_info(u_id, "avatar", user_info['user_avatar']);
                    set_user_info(u_id, "online", 1);

                    talk_list[u_id] = user_info;
                    i++;
                }

                update_talks();
                // 初始化表情
                initEmoji();
                getMsgUnread();
                window.interval_id =  setInterval("getMsgUnread()", 8000);
                window.show_interval_id =  setInterval("showMsgUnread()", 5000);
            }
        });
}


window.onerror = function ()
{
    return true;
}

 
function msg_dialog_close(id)
{
    $("#" + id).hide();

    $("#" + id).hide("slide", {direction: 'right'}, 300);

    close_chat_log(user_show);
    if (connect === 1)
    {
        $("#web_chat_dialog").show();
    }
    check_iframe_dialog();
    // return;
}

function msg_dialog_show(id)
{
     
        $("#" + id).show();
return;
        $("#" + id).show("slide", {direction: 'right'}, 600,
            function ()
            {
                $("#send_message").focus();
                var obj_msg = obj.find("div[select_user_msg='" + user_show + "']");
                obj.find("#msg_list").scrollTop(obj_msg.height());
            });
   
        $("#send_message").focus();
    
 
    // if ($("#msg_count").html() == '')
    // {
    //     $("#send_message").charCount({//输入字数控制
    //         allowed: 255,
    //         warning: 10,
    //         counterContainerID: 'msg_count',
    //         firstCounterText: '还可以输入',
    //         endCounterText: '个字',
    //         errorCounterText: '已经超出'
    //     });
    //     $("#chat_show_smilies").smilies({smilies_id: "send_message"});
    // }
}






function send_state()
{   //向服务器请求页面中的相关会员的在线状态
    var u_list = connect_list;
    var n = connect_n;
    if (layout == 'layout/store_layout.php')
    {
        $("a.message").each(function ()
        {
            n++;
            var url = $(this).attr("href");
            var re = /member_id=(\w+)$/g;
            re.exec(url);
            var u_id = RegExp.$1;
            if (u_id > 0 && u_id != user['u_id'])
            {
                u_list[u_id] = 0;
            }
        });
    }
    else
    {
        switch (act_op)
        {
            case "act_op"://不显示状态
                break;
            case "member_snsfriend_findlist"://会员中心好友中"查找好友"不显示状态
            case "member_snsfriend_follow"://会员中心好友中"我关注的"不显示状态
            case "member_snsfriend_fan"://会员中心好友中"关注我的"不显示状态
                break;
            case "brand_list":
            case "search_index":
                $(".shop a[member_id]").each(function ()
                {
                    n++;
                    var u_id = $(this).attr("member_id");
                    if (u_id > 0 && u_id != user['u_id'])
                    {
                        u_list[u_id] = 0;
                    }
                });
                break;
            default:
                $("a.message").each(function ()
                {
                    n++;
                    var url = $(this).attr("href");
                    var re = /member_id=(\w+)$/g;
                    re.exec(url);
                    var u_id = RegExp.$1;
                    if (u_id > 0 && u_id != user['u_id'])
                    {
                        u_list[u_id] = 0;
                    }
                });
                break;
        }
    }
    $('[nctype="mcard"]').each(function ()
    {
        var data_str = $(this).attr('data-param');
        eval('var mcard_obj = ' + data_str);
        var u_id = mcard_obj["id"];
        if (u_id > 0 && u_id != user['u_id'])
        {
            n++;
            u_list[u_id] = 0;
        }
    });
    if (n > 0)
    {
        socket.emit('get_state', u_list);
    }
}

function get_state(list)
{//返回会员的状态并在页面显示
    var u_list = list['u_state'];
    set_user_list(list['user']);
    if (layout == 'layout/store_layout.php')
    {//店铺页面
        $("a.message").each(function ()
        {
            var message_obj = $(".shop-head-info").find(".message");
            var url = message_obj.attr("href");
            var re = /member_id=(\w+)$/g;
            re.exec(url);
            var u_id = RegExp.$1;
            if ($(".shop-head-info").find(".chat").size() == 0)
            {//头部
                message_obj.after(get_chat(u_id, u_list[u_id]));
            }
            message_obj = $(".shop-card").find(".message");
            url = message_obj.attr("href");
            re = /member_id=(\w+)$/g;
            re.exec(url);
            u_id = RegExp.$1;
            if ($(".shop-card").find(".chat").size() == 0)
            {//中部店标处
                message_obj.after(get_chat(u_id, u_list[u_id]));
            }
        });
    }
    else
    {
        switch (act_op)
        {
            case "member_snsfriend_findlist"://会员中心好友中"查找好友"不显示状态
            case "member_snsfriend_follow"://会员中心好友中"我关注的"不显示状态
            case "member_snsfriend_fan"://会员中心好友中"关注我的"不显示状态
                break;
            case "brand_list":
            case "search_index":
                $(".shop a[member_id]").each(function ()
                {
                    var u_id = $(this).attr("member_id");
                    if ($(this).next(".chat").size() == 0)
                    {
                        $(this).after(get_chat(u_id, u_list[u_id]));
                    }
                });
                break;
            default:
                $("a.message").each(function ()
                {
                    var url = $(this).attr("href");
                    var re = /member_id=(\w+)$/g;
                    re.exec(url);
                    var u_id = RegExp.$1;
                    if ($(this).next(".chat").size() == 0)
                    {
                        $(this).after(get_chat(u_id, u_list[u_id]));
                    }
                });
                break;
        }
    }
    if (user['u_id'] != '')
    {
        update_recent();
        update_friends();
        update_talks();
    }
}

function show_obj()
{//弹出框
    if (user_show == null)
    {
        chat_show_list();
        return false;
    }
    msg_dialog_show('new_msg_dialog');
}

function send_focus()
{
    $("#send_alert").html('');
}

function send_keyup(event)
{//回车发消息
    var t_msg = $.trim($("#send_message").html());
    if (event.keyCode == 13 && t_msg.length > 0)
    {
        DO_sendMsg();
    }
}

function send_msg()
{//发消息
    if (user_show == null)
    {
        $("#send_alert").html('未选择聊天会员');
        return false;
    }

    var msg = {};
    msg['f_id'] = user['u_id'];
    msg['f_name'] = user['u_name'];
    msg['t_id'] = user_show;
    msg['t_name'] = user_list[user_show]['u_name'];
    msg['t_msg'] = $.trim($("#send_message").html());
    

    if (msg['t_msg'].length < 1)
    {
        $("#send_alert").html('发送内容不能为空');
        return false;
    }

    if (msg['t_msg'].length > 255)
    {
        $("#send_alert").html('一次最多只能发送255字');
        return false;
    }

    if (connect < 1)
    {
        $("#send_alert").html('处于离线状态,稍后再试');
        return false;
    }

    var op =
    {
        touserid : msg['t_id'],
        fromuserid : msg['f_id'],
        fromInfo : msg['t_msg'],
        msgtype : 1
    };

    if (msg['f_id'] == msg['t_id'])
    {
        alert_box('不能和自己聊天');
    }
    else
    {
    }

    //show_t_msg(msg);

    var rand = Math.random(1);
}

function getMsgUnread()
{
    var fromuserid     = user_show;
    var user_id  = user['u_id'];

    var op_data =
    {
        iflook : 2,
        user_id:user_id,
        //fromuserid:fromuserid,
        flag:0
    };

    var rand = Math.random(1);
}


function showMsgUnread()
{
    if (null == user_show)
    {
        return;
    }

    var fromuserid     = user_show;
    var user_id  = user['u_id'];

    var op_data =
    {
        iflook : 2,
        user_id:user_id,
        fromuserid:fromuserid,
        flag:1
    };

    var rand = Math.random(1);

    /*$.ajax({
     type: "POST",
     url: index_url + "?ctl=Message&met=get&data_type=json" + '&r=' + rand,
     dataType: "json",
     data: op_data,
     async: false,
     success: function (data)
     {
     get_msg(data[0]["b"]);

     /*
     $.each(data[0]["b"], function(i, d){
     show_t_msg(d);
     });
     */
    //     }
    // });
}



function get_msg(list)
{
    //接收消息
    var msg = {};

    /*
     {"id":"59","uid":"62","touserid":"62","fromuserid":"55","fromInfo":"fdaf","msgtype"
     :"1","sub":"fdaf","con":"fdaf","iflook":"2","date":"2015-06-09 16:24:22","contype":null,"tid":null,"receive_type"
     :null,"reply_by":null,"attachments":null,"is_save":"0","from_name":"test","from_logo":"http:\/\/192.168
     .0.8\/mallbuilder\/uploadfile\/member\/55\/2015\/06\/04\/1433406890.jpg
     */
    for (var k in list)
    {
        msg = list[k];
        var m_id = msg['id'];
        var u_id = msg['fromuserid'];
        set_user(u_id, msg['from_name']);

        /*
         msg['user'] = {};
         msg['user']['avatar'] = msg['from_logo'];
         msg['user']['u_name'] = msg['from_name'];
         msg['user']['avatar'] = msg['from_logo'];
         msg['user']['avatar'] = msg['from_logo'];
         */
        if (typeof msg['user'] === "object" && typeof msg['user']['avatar'] !== "undefined")
        {
            var user_info = msg['user'];
            var u_name = user_info['u_name'];
            set_user_info(u_id, "u_name", u_name);
            set_user_info(u_id, "s_id", user_info['s_id']);
            set_user_info(u_id, "s_name", user_info['s_name']);
            set_user_info(u_id, "avatar", user_info['avatar']);
            if (user_info['online'] > 0)
            {
                set_user_info(u_id, "online", 1);
            }
        }

        if (u_id == 0)
        {
            user_list[u_id]['avatar'] = './img/avatar.png';
        }

        if (typeof user_list[u_id]['avatar'] === "undefined")
        {
            //当没获得会员信息时调用一次
            var ajaxurl = CHAT_SITE_URL + '/index.php?act=web_chat&op=get_info&t=member&u_id=' + u_id;
            $.ajax({
                type: "GET",
                url: ajaxurl,
                dataType: "jsonp",
                async: false,
                success: function (member)
                {
                    var u_name = member['member_name'];
                    set_user_info(u_id, "s_id", member['store_id']);
                    set_user_info(u_id, "s_name", member['store_name']);
                    set_user_info(u_id, "avatar", member['member_avatar']);
                }
            });
        }

        if (typeof msg_list[u_id] === "undefined")
        {
            msg_list[u_id] = {};
        }

        if (typeof msg_list[u_id][m_id] === "undefined")
        {
            msg_list[u_id][m_id] = msg;

            if (dialog_show == 0 || obj.find("li[select_u_id='" + u_id + "']").size() == 0)
            {
                //没有打开对话窗口时计数

                user_list[u_id]['new_msg']++;
                new_msg++;
            }
            else
            {
                if (user_show == u_id)
                {
                    show_msg(u_id);//当前对话的会员消息设为已读
                }
                else
                {
                    user_list[u_id]['new_msg']++;
                    new_msg++;
                }
            }
        }

        alert_user_msg(u_id);
    }

    alert_msg();
}
function get_chat_log(time_from)
{

    /*
        聊天记录获取，根据天数
        weichat: sunkangchina
    */
     $.get(ajax_chatlog_url+time_from,function(json){  
        var hl = json.data.html;
        $('#dialog_chat_log #chat_log_msg').html(hl);
        var h = $('.dialog_chat_log  .chat_log_list');
        h.scrollTop (h[0].scrollHeight - h.height());
                        
    },'json');


    var obj_chat_log = $("#dialog_chat_log");
    if (obj_chat_log.html() == '')
    {
        var chat_log_list = '<div class="chat-log-top"><h1><i></i>聊天记录</h1><span class="close-chat-log" onclick="show_chat_log();"></span></div>' +
            '<div id="chat_log_list" class="chat_log_list"><div id="chat_log_msg" class="chat-log-msg"></div></div><div class="chat-log-bottom"><div id="chat_time_from" class="chat_time_from">' +
            '<span time_id="7" onclick="get_chat_log(7);" class="current">7天</span><span time_id="15" onclick="get_chat_log(15);">15天</span><span time_id="30" onclick="get_chat_log(30);">30天</span></div>' + '</div>';
        //'<div class="chat_log_first"><p>已到第一页</p></div><div class="chat_log_last"><p>已到最后一页</p></div>' +
        //'<div id="chat_log_page" class="chat_log_page"><span onclick="get_chat_previous();" class="previous" title="上一页"></span><span onclick="get_chat_next();" class="next" title="下一页"></span></div></div>';
        obj_chat_log.append(chat_log_list);
    }
    obj_chat_log.show();
    chat_log['u_id'] = user_show;
    chat_log['now_page'] = 0;
    chat_log['total_page'] = 0;
    chat_log['time_from'] = 7;
    chat_log['list'] = new Array();
    var time_id = obj_chat_log.find("span.current").attr("time_id");
    if (time_from != time_id)
    {
        obj_chat_log.find("span.current").removeClass("current");
        obj_chat_log.find("span[time_id='" + time_from + "']").addClass("current");
        chat_log['time_from'] = time_from;
    }
    get_chat_msg(false);
}
function get_chat_next()
{
    var now_page = chat_log['now_page'] - 1;
    if (now_page >= 1)
    {
        show_chat_msg(now_page);
        chat_log['now_page'] = now_page;
    }
    else
    {
        $('.chat_log_last').show();
        setTimeout("$('.chat_log_last').hide()", 2000);
    }
}
function get_chat_previous()
{
    var now_page = chat_log['now_page'] + 1;
    if (chat_log['total_page'] >= now_page)
    {
        if (typeof chat_log['list'][now_page] === "undefined")
        {
            get_chat_msg(false);
        }
        else
        {
            show_chat_msg(now_page);
            chat_log['now_page'] = now_page;
            if (chat_log['total_page'] > now_page && typeof chat_log['list'][now_page + 1] === "undefined")
            {
                get_chat_msg(true);
            }
        }
    }
    else
    {
        $('.chat_log_first').show();
        setTimeout("$('.chat_log_first').hide()", 2000);
    }
}
function get_chat_msg(t)
{
    var rand = Math.random(1);
}
function update_msg(u_id)
{//更新已读
    var u_name = user_list[u_id]['u_name'];
    user_list[u_id]['new_msg'] = 0;
    alert_user_msg(u_id);
    new_msg--;
    alert_msg();
}

function is_online(u_name){
    $('.user-info .user-avatar').removeClass('avatar-0');
    return ;
    var getUserStateBuilder = new RL_YTX.GetUserStateBuilder();
    getUserStateBuilder.setNewUserstate(true);//使用新SDK的用户状态
    getUserStateBuilder.setUseracc(u_name);
    RL_YTX.getUserState(getUserStateBuilder, function(obj) {
            //获取成功
            //obj[i].useracc 对方账号
            //obj[i].state 对方在线状态1:在线2:离线当用户为离线状态时，obj.state,obj.network和obj.device为undefined
            //obj[i].network对方网络状态 1:WIFI 2:4G 3:3G 4:2G(EDGE) 5: INTERNET  6: other
            //obj[i].device对方登录终端1:Android 2:iPhone10:iPad11:Android Pad20:PC 21:H5
             
            if(obj[0].state == 1 ){
          
                $('.user-info .user-avatar').removeClass('avatar-0');

            }
             
     });
}

var chat_count = 0;
/*
 *
 * 弹出对话框
 *
 */
function chat(u_id)
{ 
    //cookie获取方式更改，by:sunkang
    if(!getCookie('key')){
        parent.$("#login_content").show();
    }
 

    //打开对话窗口
    if (user['u_id'] == '')
    {//未登录时弹出登录窗口
        $("#chat_login").trigger("click");
        
        return;
    }
    if (u_id == user['u_id'])
    {
        return;
    }

    if (typeof user_list[u_id] === "undefined" || typeof user_list[u_id]['avatar'] === "undefined")
    {
        var rand = Math.random(1);
//        var ajaxurl = imbuilder_url+'?ctl=UserApi&met=getUserInfo&typ=json&k='+k+'&u='+u+'&user_account='+u_id;
        var ajaxurl = imbuilder_url+'?ctl=UserApi&met=getUserInfo&typ=json&k='+k+'&u='+user['u_id']+'&user_account='+u_id;
        $.ajax({
            type: "GET",
            url: ajaxurl,
            dataType: "json",
            async: false,
            success: function (rs)
            {
                if (rs.status == 200)
                {
                    member = rs.data;
                }
                else
                {
                    alert_box('无此用户');
                }
                var u_name = member['user_name'];

                                


                if (typeof u_name === "undefined" || u_name == '')
                {
                    return false;
                }
               
                set_user_info(u_id, "u_name", u_name);
                /*set_user_info(u_id, "s_id", member['userid']);
                 set_user_info(u_id, "s_name", member['nickname']);*/
                set_user_info(u_id, "avatar", member['user_avatar']);
            }
        });
    }
    bottom_bar();
    update_user(u_id);
    show_msg(u_id);
    show_obj();

       /*
        聊天记录获取，根据天数
        weichat: sunkangchina
    */
 
   if(chat_count == 0){ 
        show_msg_list(u_id);
   }
   chat_count++;



}

function show_msg_list(u_id){
        var DEFAULT_GREETINGS = typeof(IM_DEFAULT_GREETINGS) == 'undefined' ? '您好，请问有什么可以为您服务的吗？' : IM_DEFAULT_GREETINGS;
        var ajax_chatlog_url = ApiUrl+"?ctl=Api_Chatlog&met=get&u="+getCookie('user_account')+"&to="+u_id+"&day=1&typ=json";   
        $.get(ajax_chatlog_url,function(json){  
            var new_msg = '<div class="from_msg" style="display:block"><dl><dd class="to-msg-text" style="margin-left: 0px;"> '+DEFAULT_GREETINGS+'</dd></dl></div></div>';
             var data = json.data.html;
             var h = $('#msg_list #user_msg_list .msg_list');
             if(data){
                h.html(data);
             } else{
                h.html(new_msg);
             }
            
             var div = document.getElementById('msg_list'); 
             div.scrollTop = div.scrollHeight;

        },'json');
}
function show_dialog()
{//显示窗口
    update_dialog();
    show_obj();
}
function update_dialog()
{
    //显示会员的对话
    if (new_msg < 1)
    {
        return true;
    }
    var select_user = 0;
    for (var u_id in user_list)
    {
        if (user_list[u_id]['new_msg'] > 0)
        {
            update_user(u_id);
            obj.find("em[unread_id='" + u_id + "']").addClass("unread");
            obj.find("em[unread_id='" + u_id + "']").html(user_list[u_id]['new_msg']);
        }
    }
    select_user = obj.find(".unread").first().attr("unread_id");
    if (select_user > 0)
    {
        show_msg(select_user);
    }
}


var ajax_chatlog_url = null;
function show_chat_log()
{
    if (user_show == null)
    {
        $("#send_alert").html('未选择聊天会员');
        return false;
    }
    if (typeof chat_log['u_id'] === "undefined" || chat_log['u_id'] != user_show)
    {
        
        /*
            聊天记录获取
            weichat: sunkangchina
        */
        ajax_chatlog_url = ApiUrl+"?ctl=Api_Chatlog&met=get&u="+getCookie('user_account')+"&typ=json&to="+user_show+"&day=";
       

        if(obj.find(".chat-log-btn").hasClass('off')){ 
                get_chat_log(7);
                 
        }
        $("#web_chat_dialog").hide();
        $("#dialog_right_clear").hide(); 
        obj.find(".chat-log-btn").removeClass("off");
        obj.find(".chat-log-btn").addClass("on");
       


        
    }
    else
    {
        close_chat_log(user_show);
    }
    check_iframe_history();
}
function show_msg(u_id)
{

    update_user(u_id);
    //显示会员的消息
    var user_info = user_list[u_id];
    var u_name = user_info['u_name'];
    is_online(u_name);
    if (obj.find("div[select_user_msg='" + u_id + "']").size() == 0)
    {
        obj.find("#user_msg_list").prepend('<div class="msg_list" select_user_msg="' + u_id + '"></div>');
    }

    obj.find(".msg_list").hide();
    obj.find("div[select_user_msg='" + u_id + "']").show();
    obj.find("li[select_u_id]").removeClass("select_user");
    obj.find("li[select_u_id='" + u_id + "']").addClass("select_user");


    if (user_show != u_id)
    {
        close_chat_log(user_show);
        var add_html = '';
        if (typeof user_info['s_name'] !== "undefined")
        {
            add_html = '<dd class="store-name">' + user_info['s_name'] + '</dd>';
        }
        if(!user_info['avatar'])
        {
            user_info['avatar'] = './img/avatar.jpg';
        }
        obj.find(".user-info").html('<dt class="user-name">' + u_name + '</dt><dd class="user-avatar avatar-' + user_info['online'] + '"><img src="' +
            user_info['avatar'] + '"></dd>' + add_html);
        obj.find('#msg_list').perfectScrollbar('destroy');
        obj.find('#msg_list').perfectScrollbar();
    }

    user_show = u_id;
    var max_id = 0;
    for (var m_id in msg_list[u_id])
    {
        if (obj.find("div[m_id='" + m_id + "']").size() == 0)
        {
            msg = msg_list[u_id][m_id];
            show_f_msg(msg);
            update_msg(u_id);
            delete msg_list[u_id][m_id];//删除消息
            if (m_id > max_id)
            {
                max_id = m_id;
            }
        }
    }


    var obj_msg = obj.find("div[select_user_msg='" + u_id + "']");
    obj.find("#msg_list").scrollTop(obj_msg.height());
    $("#send_message").focus();

    if (max_id > 0 && connect === 1)
    {
        //socket.emit('del_msg', {'max_id': max_id, 'f_id': u_id});
    }
    show_obj();
    check_iframe_dialog();
    check_iframe_dialog_left();
}

function show_f_msg(type,msg,u_id,avatar,msgid,time)
{

    //显示收到的消息
    var version = msgid;
    var mydate = new Date();
    var strdata = mydate.valueOf();
    var time = new Date(parseInt(version) + parseInt(8) * 60 * 60);
    var ymdhis = "";
    if(version - strdata > 86400)
    {
        ymdhis += (time.getMonth()+1) + "-";
        ymdhis += time.getDate();
    }else{
        ymdhis += '今天';
    }
    if(time.getHours() < 10)
    {
        ymdhis += " " +'0'+ time.getHours() + ":";
    }else{
        ymdhis += " " + time.getHours() + ":";
    }

    if(time.getMinutes() < 10)
    {
        ymdhis += '0'+time.getMinutes() + ":";
    }else{
        ymdhis += time.getMinutes() + ":";
    }

    if(time.getSeconds() < 10)
    {
        ymdhis += '0'+time.getSeconds();
    }else{
        ymdhis += time.getSeconds();
    }

    var text_append = '';
    var obj_msg = obj.find("div[select_user_msg='" + u_id + "']");

    if(!avatar)
    {
        avatar = './img/avatar.jpg';
    }
    if(type == 1)
    {
        text_append += '<div class="from_msg" m_id="' + msgid + '">';
        text_append += '<span class="user-avatar"><img src="' + avatar + '"></span>';
        text_append += '<dl><dt class="from-msg-time">';
        text_append += ymdhis + '</dt>';
        text_append += '<dd class="from-msg-text">';
        text_append += msg + '</dd>';
        text_append += '<dd class="arrow"></dd>';
        text_append += '</dl>';
        text_append += '</div>';
    }

    if(type == 4)
    {
        var windowWid = $(window).width();
        var imgWid = 150;
        var imgHei = 200;

        text_append += '<div class="from_msg" m_id="' + msgid + '">';
        if(!avatar)
        {
            avatar = './img/avatar.jpg';
        }
        text_append += '<span class="user-avatar"><img src="' + avatar + '"></span>';
        text_append += '<dl><dt class="from-msg-time">';
        text_append += ymdhis + '</dt>';
        text_append += '<dd class="from-msg-text">';
        text_append += '<img src="'+ msg + '" style="cursor:pointer;max-width:'
            + imgWid + 'px; max-height:' + imgHei
            + 'px;" onclick="open_img(this)" ></dd>';
        text_append += '<dd class="arrow"></dd>';
        text_append += '</dl>';
        text_append += '</div>';
    }

    obj_msg.append(text_append);
    var n = obj_msg.find("div[m_id]").size();
    /*
     if (n >= msg_max && n % msg_max == 1)
     {
     obj_msg.append('<div clear_id="' + msgid + '" onclick="clear_msg(' + u_id + ',' + msgid +
     ');" class="clear_msg"><a href="Javascript: void(0);">清除已上历史消息</a></div>');
     }
     */
    /*setTimeout('$("#chat_show_img").trigger("click")', 100);
    $('#msg_list').trigger("scroll");
    obj.find("#msg_list").scrollTop(obj_msg.height());*/

//    setTimeout('$("#chat_show_img").trigger("click")', 100);
    $('#msg_list').trigger("scroll");
    $('#msg_list').scrollTop($('#msg_list')[0].scrollHeight);
}


/**
 * 桌面提醒功能
 * @param you_sender 消息发送者账号
 * @param nickName 消息发送者昵称
 * @param you_msgContent 接收到的内容
 * @param msgType 消息类型
 * @param isfrieMsg 是否阅后即焚消息
 * @param isCallMsg 是否音视频呼叫消息
 */
function DO_deskNotice(you_sender,nickName,you_msgContent,msgType,isfrieMsg,isCallMsg){
    var title;
    var body = '';
    if(!!you_sender||!!nickName){
        if('g' == you_sender.substr(0, 1)){
            title = "群消息";
            if(!!nickName){
                body = nickName +":";
            }else{
                body = you_sender +":";
            }
        }else{
            if(!!nickName){
                title = nickName;
            }else{
                title = you_sender;
            }
        }

    }else{
        title = "系统通知";
        body = you_msgContent;
    }

    if(isfrieMsg){
        body += "[阅后即焚消息]";
    }else if(isCallMsg){
        body += you_msgContent;
    }else{
        if(1 == msgType){
            emoji.showText = true;
            you_msgContent = emoji.replace_unified(you_msgContent);
            emoji.showText = false;
            body += you_msgContent;
        }else if(2 == msgType){
            body += "[语音]";
        }else if(3 == msgType){
            body += "[视频]";
        }else if(4 == msgType){
            body += "[图片]";
        }else if(5 == msgType){
            body += "[位置]";
        }else if(6 == msgType){
            body += "[附件]";
        }
    }

    if(!_Notification || !checkWindowHidden()){
        return ;
    }

    var instance = new _Notification(
        title, {
            body: body,
            icon: "http://h5demo.yuntongxun.com/assets/img/logo-blue.png"
        }
    );

    instance.onclick = function () {
        // Something to do
    };
    instance.onerror = function () {
        // Something to do
    };
    instance.onshow = function () {
        // Something to do
        setTimeout(function(){
            //instance.onclose();
            instance.close();
        }, 3000);
    };
    instance.onclose = function () {
        // Something to do
    };

}

function checkWindowHidden()
{
    var prefix = getBrowerPrefix();
    //不支持该属性
    if(!prefix){
        return document['hidden'];
    }
    return document[prefix+'Hidden'];
}
/**
 * 获取hidden属性
 */
function getBrowerPrefix()
{
    return 'hidden' in document ? null : function() {
        var r = null;
        ['webkit', 'moz', 'ms', 'o'].forEach(function(prefix) {
            if((prefix + 'Hidden') in document) {
                return r = prefix;
            }
        });
        return r;
    }();
}


function open_img(obj)
{
    var o = $(obj);
    var w = o.width();
    var h = o.height() + 30;

    var ww = $(document).width();
    var hh = $(document).height();

    w = Math.min(ww/2, w);
    h = Math.min(hh/2, h);

    alertWin('', '', w, h, obj.src);
    //window.open(obj.src);
}
function alertWin(title, msg, w, h,src)
{
    if(document.getElementById("msgObj"))
    {
        return false;
    }

    var s=document.getElementsByTagName("select");
    for(var j=0;j<s.length;j++){s[j].style.display="none";}

    var titleheight = "40px";
    var border_color = "#BED3F0";
    var titlecolor = "#FFFFFF";
    var titlebgcolor = "#1d5798";
    var bgcolor = "#FFFFFF";

    var iWidth = document.documentElement.clientWidth;
    var tHeight = document.documentElement.clientHeight;
    var iHeight = Math.max(document.body.scrollHeight,document.documentElement.scrollHeight);


    var bgObj = document.createElement("div");
    bgObj.style.cssText = "position:absolute;left:0px;top:0px;width:"+iWidth+"px;height:"+Math.max(document.body.clientHeight, iHeight)+"px;filter:Alpha(Opacity=50);opacity:0.5;z-index:1000;";


    bgObj.id='bgObj';
    document.body.appendChild(bgObj);

    var msgObj=document.createElement("div");
    msgObj.style.cssText = "position:fixed;left:"+(iWidth-500) /2+"px;width:500px;height:500px;border:1px solid "+border_color+";background-color:"+bgcolor+";padding:1px;z-index:1999;";
    msgObj.id='msgObj';
    document.body.appendChild(msgObj);

    var table = window.parent.document.createElement("table");
    msgObj.appendChild(table);

    table.style.cssText = "margin:0px;border:0px;padding:0px;width:100%";
    table.cellSpacing = 0;

    var tr = table.insertRow(-1);
    var titleBar = tr.insertCell(-1);
    //  titleBar.style.cssText = "width:*;height:"+titleheight+"px;text-align:left;padding-left:3px;margin:0px;cursor:move;";
    titleBar.style.paddingLeft = "10px";
    titleBar.innerHTML = "<b>"+title+"</b>";
    tr.className = "titleBar";

    var closeBtn = tr.insertCell(-1);
    closeBtn.innerHTML="<span class='closeBtns'>&nbsp;</span>";

    closeBtn.onclick = function()
    {
        for(var j=0;j<s.length;j++){s[j].style.display="";}
        document.body.removeChild(bgObj);
        document.body.removeChild(msgObj);
    }

    var msgBox = table.insertRow(-1).insertCell(-1);
    msgBox.colSpan  = 2;
    msgBox.className = 'imgBar';
    if(src==''&&msg=='')
        src='main.php?m=album&s=admin_album&nohead=true';
    else if(typeof(src)!='undefined')
    {
        msgBox.innerHTML='<iframe class="iframebox" style="height:450px;width:400px;" src="'+src+'" frameborder="0" scrolling="no"></iframe>';
    }
    else
    {
        if(typeof(msg.innerHTML)!='undefined')
        {
            if(temp_con!='')
                msgBox.innerHTML =temp_con;
            else
            {
                msgBox.innerHTML = msg.innerHTML;
                temp_con=msg.innerHTML;
                msg.innerHTML='';
            }
        }
        else
            msgBox.innerHTML=msg;
    }
    function getEvent()
    {
        return window.event || arguments.callee.caller.arguments[0];
    }

}

function show_chat_msg(now_page)
{
    var log_list = chat_log['list'][now_page];
    $('#chat_log_msg').html('');
    for (var k in log_list)
    {
        var class_html = '';
        var text_append = '';
        msg = log_list[k];
        msg['u_name'] = msg['from_name'];
        if (msg['u_name'] == user['u_name'])
        {
            msg['u_name'] = '我';
            class_html = 'chat_user';
        }
        text_append += '<div class="chat_msg ' + class_html + '" m_id="' + msg['id'] + '">';
        text_append += '<p class="user-log"><span class="user-name">' + msg['u_name'] + '</span>';
        text_append += '<span class="user-time">' + msg['date'] + '</span></p>';
        text_append += '<p class="user-msg">' + update_chat_msg(msg['con']) + '</p>';
        text_append += '</div>';
        $('#chat_log_msg').prepend(text_append);
    }
    $('#chat_log_list').perfectScrollbar('destroy');
    $('#chat_log_list').perfectScrollbar();
    $('#chat_log_list').scrollTop($('#chat_log_msg').height());
}

function chat_show_user_list(chat_show)
{
    var obj_chat = $("#chat_user_" + chat_show);
    if (obj_chat.find("dt span").attr("class") == 'hide')
    {
        obj_chat.find("dd[u_id]").show();
        obj_chat.find("dt span").attr("class", "show");
    }
    else
    {
        obj_chat.find("dd[u_id]").hide();
        obj_chat.find("dt span").attr("class", "hide");
    }
}

function chat_show_user_lists(chat_show)
{
    var obj_chat = $("#chat_user_" + chat_show);
    if (obj_chat.find("dt span").attr("class") == 'hide')
    {
        obj_chat.find("dd[u_id]").show();
        obj_chat.find("dt span").attr("class", "show");
    }
    else
    {
        obj_chat.find("dd[u_id]").hide();
        obj_chat.find("dt span").attr("class", "hide");
    }
}



var pa = $('#imbuiler',window.parent.document);
check_iframe_bottom();
function check_iframe_bottom(){
    // pa.css('border','2px solid blue');
    if($('#web_chat_dialog .chat-list').css('display')=='none'){
        pa.css('width','180px').css('height','449px');
    }else{
        pa.css('width','180px').css('height','41px');
    }

}
function check_iframe_history(){
    if($('#new_msg_dialog #dialog_chat_log').css('display')=='block'){
        if($("#new_msg_dialog #user_list .user").length > 1 ) {
            pa.css('width', '812px').css('height', '450px');
        }else{
            pa.css('width', '650px').css('height', '450px');
        }
    }else{
        if($("#new_msg_dialog #user_list .user").length > 1 ) {
            pa.css('width', '770px').css('height', '450px');
        }else{
            pa.css('width', '605px').css('height', '450px');
        }
    }
}
function check_iframe_dialog(){
    // pa.css('border','2px solid blue');
        if($('#new_msg_dialog').css('display')=='block'){
            pa.css('width','605px').css('height','450px');
        }else{
            pa.css('width','180px').css('height','449px');
        }
}


function check_iframe_dialog_left(){
      if($("#new_msg_dialog #user_list .user").length > 1 ){
          if($('#new_msg_dialog').css('display')=='block'){
              pa.css('width','770px').css('height','450px');
          }else{
              pa.css('width','605px').css('height','450px');
          }

      }else{
          if($('#new_msg_dialog').css('display')=='block'){
              if($('#new_msg_dialog #dialog_chat_log').css('display')=='block') {
                  pa.css('width', '650px').css('height', '450px');
              }else{
                  pa.css('width', '605px').css('height', '450px');
              }
          }else{
              pa.css('width','605px').css('height','450px');
          }
      }
}

function chat_show_list()
{
    check_iframe_bottom();
     
    $(".chat-list").slideToggle("fast");
}

 

function del_msg(msg)
{
    //已读消息处理
    var max_id = msg['max_id'];//最大的消息编号
    var u_id = msg['f_id'];//消息发送人

    for (var m_id in msg_list[u_id])
    {
        if (max_id >= m_id)
        {
            delete msg_list[u_id][m_id];
            if (user_list[u_id]['new_msg'] > 0)
            {
                user_list[u_id]['new_msg']--;
            }
            if (new_msg > 0)
            {
                new_msg--;
            }
            alert_user_msg(u_id);
        }
    }

    alert_msg();
}

function alert_user_msg(u_id)
{
    if (user_list[u_id]['new_msg'] > 0)
    {
        obj.find("em[unread_id='" + u_id + "']").addClass("unread");
        obj.find("em[unread_id='" + u_id + "']").html(user_list[u_id]['new_msg']);
        $("#chat_user_recent dd[u_id='" + u_id + "'] a").addClass("msg");
    }
    else
    {
        obj.find("em[unread_id='" + u_id + "']").html("");
        obj.find("em[unread_id='" + u_id + "']").removeClass("unread");
        $("#chat_user_recent dd[u_id='" + u_id + "'] a").removeClass("msg");
    }
}

function alert_msg()
{
    if (new_msg > 0)
    {//消息提醒
        $("#new_msg").attr("class", "ico2");
    }
    else
    {
        new_msg = 0;
        $("#new_msg").attr("class", "ico");
    }
    $("#new_msg").attr("title", '新消息(' + new_msg + ')');
}

function get_chat(u_id, online)
{
    //显示链接地址
    var add_html = '';
    if (u_id != user['u_id'] && u_id > 0)
    {
        var class_html = 'chat_offline';
        var text_html = '离线';
        if (online > 0)
        {
            class_html = 'chat_online';
            text_html = '在线';
        }
        add_html = '<a class="chat ' + class_html + '" title="在线联系" href="JavaScript:chat(' + u_id + ');">' + text_html + '</a>';
    }
    return add_html;
}

function clear_msg(u_id, m_id)
{
    //清除消息处理
    var obj_msg = obj.find("div[select_user_msg='" + u_id + "']");
    obj_msg.find("div[clear_id='" + m_id + "']").prevAll().remove();
    obj_msg.find("div[clear_id='" + m_id + "']").remove();
}

function set_user_list(list)
{
    //初始化会员列表
    for (var k in list)
    {
        var user_info = list[k];
        var u_id = user_info['u_id'];
        var u_name = user_info['u_name'];
        var online = 0;
        if (user_info['online'] > 0)
        {
            online = 1;
        }
        set_user_info(u_id, "u_name", u_name);
        set_user_info(u_id, "s_id", user_info['s_id']);
        set_user_info(u_id, "s_name", user_info['s_name']);
        set_user_info(u_id, "avatar", user_info['avatar']);
        set_user_info(u_id, "online", online);
    }
}

function set_user(u_id, u_name)
{
    //初始化会员信息
    var user_info = {};
    user_info['u_id'] = u_id;
    user_info['u_name'] = u_name;
    user_info['new_msg'] = 0;
    user_info['online'] = 0;
    if (typeof user_list[u_id] === "undefined")
    {
        user_list[u_id] = user_info;
    }
    if (typeof msg_list[u_id] === "undefined")
    {
        msg_list[u_id] = {};
    }
}
function set_user_info(u_id, k, v)
{//设置会员信息
    if (typeof user_list[u_id] === "undefined")
    {
        set_user(u_id, '');
    }
    user_list[u_id][k] = v;
}
function close_chat_log(u_id)
{
    if (user_show == null || chat_log['u_id'] == u_id)
    {
        chat_log = {};
        $("#dialog_chat_log").hide();
        $("#dialog_right_clear").show();
        $('#chat_log_msg').html('');
        obj.find(".chat-log-btn").removeClass("on");
        obj.find(".chat-log-btn").addClass("off");
        if (connect === 1)
        {
            $("#web_chat_dialog").show();
        }
    }
}


function show_emoji_div()
{
    if($('#emoji_div').is(":hidden"))
    {
        $('#emoji_div').show();
    }else{
        $('#emoji_div').hide();
    };
};

function removeshowmag(u_id){
    $("#chat_user_admin").find('dd').each(function(items,index){
            if(u_id == $(index).attr('u_id')){
                $(index).find("a").removeClass('showmsg');
            }
        }
    )
    $("#chat_user_talks").find('dd').each(function(items,index){
            if(u_id == $(index).attr('u_id')){
                $(index).find("a").removeClass('showmsg');
            }
        }
    )
    $("#chat_user_friends").find('dd').each(function(items,index){
            if(u_id == $(index).attr('u_id')){
                $(index).find("a").removeClass('showmsg');
            }
        }
    )
}
function close_dialog(u_id)
{
    obj.find("li[select_u_id='" + u_id + "']").remove();
    obj.find("div[select_user_msg='" + u_id + "']").hide();
    if (obj.find("li[select_u_id]").size() == 0)
    {
        msg_dialog_close('new_msg_dialog');
    }
    else
    {
        if (user_show == u_id)
        {
            obj.find("li[select_u_id]").first().trigger("click");
        }
    }
    if (user_show == u_id)
    {
        user_show = null;
        close_chat_log(u_id);
    }
    if (obj.find("li[select_u_id]").size() < 2)
    {
        obj.find(".user-tab-bar").hide();
    }
    check_iframe_dialog_left();
}
function update_chat_msg(msg)
{
    
    if (typeof smilies_array !== "undefined")
    {
        msg = '' + msg;
        for (i in smilies_array[1])
        {
            var s = smilies_array[1][i];
            var re = new RegExp("" + s[1], "g");
            var smilieimg = '<img width="28" height="28" title="' + s[6] + '" alt="' + s[6] + '" src="' + './script/chat/smilies/images/' + s[2] + '">';
            msg = msg.replace(re, smilieimg);
        }

        msg = msg.replace(/\[em_([0-9]*)\]/g, '<img src="./image/arclist/$1.gif" border="0" />');
    }
    
    $('.chat-list').show();
    $('#imbuiler',window.parent.document).show();

    

    return msg;
}
function update_admin()
{
    var obj_friend = $("#chat_admin");
    
    $.get('././managers.php',function(res) { //此处data是php的返回值
        var data = res.data
        for (u in data)
        {
            var u_id = data[u].u_id
            var avatar = data[u].avatar
            if(getCookie('user_account') !== u_id){
                var html = '<dd u_id="' + u_id + '" onclick="chat(\''+ u_id +'\');"><span class="user-avatar"><img  src="' + avatar + '"></span><h5>' + u_id+ '</h5><a href="javascript:void(0);" onclick="removeshowmag(\''+u_id+'\')"></a></dd>'
            }else{
                var html = '<dd u_id="' + u_id + '"><span class="user-avatar"><img  src="' + avatar + '"></span><h5>' + u_id+ '</h5></dd>'
            }
            obj_friend.before(html);
        }
    },'json');    
}
function update_friends()
{
    var obj_friend = $("#chat_friends");

    for (var u_id in friend_list)
    {
        if (obj_friend.find("dd[u_id='" + u_id + "']").size() == 0)
        {
            if(!user_list[u_id]['avatar'])
            {
                user_list[u_id]['avatar'] = './img/avatar.jpg';
            }
            obj_friend.before('<dd u_id="' + u_id + '" onclick="chat(\''+ u_id +'\');"><span class="user-avatar"><img  src="' + user_list[u_id]['avatar'] + '"></span><h5>' + user_list[u_id]['u_name'] + '</h5><a href="javascript:void(0);" onclick="removeshowmag(\''+u_id+'\')"></a></dd>');
        }
    }
    obj_friend.remove();
    chat_show_user_list('friends');
}
function update_talks()
{
    var obj_talk = $("#chat_talks");

    for (var u_id in talk_list)
    {
        if (obj_talk.find("dd[u_id='" + u_id + "']").size() == 0 && getCookie('user_account') !== u_id)
        {
            if(!user_list[u_id]['avatar'])
            {
                user_list[u_id]['avatar'] = './img/avatar.jpg';
            }
            obj_talk.before('<dd u_id="' + u_id + '" onclick="chat(\''+ u_id +'\');"><span class="user-avatar"><img  src="' + user_list[u_id]['avatar'] + '"></span><h5>' + user_list[u_id]['u_name'] + '</h5><a href="javascript:void(0);" onclick="removeshowmag(\''+u_id+'\')"></a></dd>');
        }
    }
    obj_talk.remove();
    chat_show_user_lists('talks');
}

function update_recent(u_id)
{
    //alert_box(u_id);
    var obj_recent = $("#chat_recent");
    //获取其他用户的信息

    obj_recent.remove();
}
function update_user(u_id, left)
{
    if (obj.find("li[select_u_id='" + u_id + "']").size() == 0)
    {
        var user_info = user_list[u_id];
        var u_name = user_info['u_name'];
        var text_append = '';
        var class_html = 'offline';
        if (user_info['online'] > 0)
        {
            class_html = 'online';
        }
        if(!user_info['avatar'])
        {
            user_info['avatar'] = './img/avatar.jpg';
        }

        text_append += '<li class="user" select_u_id="' + u_id + '" onclick="show_msg(\''+ u_id +'\');">';
        text_append += '<i class="' + class_html + '"></i>';
        text_append += '<span class="user-avatar avatar-' + user_info['online'] + '" title="' + u_name + '"><img  src="' + user_info['avatar'] + '"></span>';
        text_append += '<span class="user-name" title="' + u_name + '">';
        text_append += u_name + '<em></em></span>';
        text_append += '<em unread_id="' + u_id + '" class=""></em>';
        text_append += '<a class="ac-ico"></a>';
        text_append += '</li>';
        obj.find("#user_list").append(text_append);

        //obj.find("#user_list").sortable({items: 'li'});

        obj.find("li[select_u_id='" + u_id + "'] .ac-ico").bind("click", function ()
        {
            close_dialog(u_id);
            return false;
        });

        if (obj.find("li[select_u_id]").size() > 1)
        {
            obj.find(".user-tab-bar").show();
        }
    }

    obj.find(".user-tab-bar").perfectScrollbar();
}

function getconnect()
{
    $.getScript(connect_url + "/resource/socket.io.js", function ()
    {
        clearInterval(interval);
        if (typeof io === "object")
        {
            socket = io.connect(connect_url, {'resource': 'resource', 'reconnect': false});
            socket.on('connect', function ()
            {
                send_state();
                socket.on('get_state', function (u_list)
                {
                    get_state(u_list);
                });
                if (user['u_id'] == '')
                {
                    return false;
                }//未登录时不取消息
                connect = 1;
                $("#web_chat_dialog").show();
                if ($("#new_msg_dialog").size() == 0)
                {
                    $("#web_chat_dialog").after(msg_dialog);
                }
                obj = $("#new_msg_dialog");
                socket.emit('update_user', user);
                socket.on('get_msg', function (msg_list)
                {
                    get_msg(msg_list);
                });
                socket.on('del_msg', function (msg)
                {
                    del_msg(msg);
                });
                socket.on('disconnect', function ()
                {
                    connect = 0;
                    $("#web_chat_dialog").hide();
                    interval = setInterval(getconnect, 60000);//断开1分钟后重新连接服务器
                });
            });
        }
    });
}