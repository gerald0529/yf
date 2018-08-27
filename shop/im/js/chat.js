/**
 * Created by xinze on 15/5/26.
 */
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}


//setInterval("getMsgUnread()", 2000);
function getViewSizeWithoutScrollbar(){//不包含滚动条
    return {
        width : document.documentElement.clientWidth,
        height: document.documentElement.clientHeight
    }
}

function getViewSizeWithScrollbar(){//包含滚动条
    if(window.innerWidth){
        return {
            width : window.innerWidth,
            height: window.innerHeight
        }
    }else if(document.documentElement.offsetWidth == document.documentElement.clientWidth){
        return {
            width : document.documentElement.offsetWidth,
            height: document.documentElement.offsetHeight
        }
    }else{
        return {
            width : document.documentElement.clientWidth + getScrollWith(),
            height: document.documentElement.clientHeight + getScrollWith()
        }
    }
}


//上传图片

var imfocus=1;var retry_num=0;var lnkover=0;var m_hasreg=-1;var worker_online=0;var m_regName="";
var m_success=true;var m_busy=false;

var http_pro = (document.location.protocol == 'https:')?'https://':'http://';//区分HTTP和HTTPS
var infos = new Array();
infos.push("网络故障，无法连接服务器");
infos.push("连接失败");
infos.push("连接已断开，无法发送消息");
infos.push("连接已断开");
infos.push("的客服助手");
infos.push("客服不在线，请留言");
infos.push("占线请稍候");
infos.push("对不起！线路忙，请稍候。您也可以给我们留言。");
infos.push("说");
infos.push("与...交谈中");
infos.push("客服已离线，请留言<br>为便于我们与您联系，请注明您的姓名和联系方式。谢谢！");
infos.push("客服不在线");
infos.push("对话结束");
infos.push("我");
infos.push("链接地址");
infos.push("链接文字");
infos.push("请输入图片地址:");
infos.push("公告建议");
infos.push("连接中请稍候");
infos.push("连接中，无法发送消息");
infos.push("按Enter键发送");
infos.push("按Ctrl+Enter键发送");
infos.push("您已经打开了一个窗口咨询，不允许同时开两个窗口");
infos.push("不在线，以下是客服助手的自动应答");
infos.push("无此客服");
infos.push("不在线");
infos.push("正在输入消息");
infos.push("如果离开，您将无法继续接收客服的消息！");
infos.push("如果没有留下联系方式，客服将无法联系您！");
infos.push("系统提示");
infos.push("等待您选择客服人员");
infos.push("在线");
infos.push("留言");
infos.push("无法连接客服，您已被阻止");
infos.push("网页对话");
infos.push("请输入HTML代码:");
infos.push("感谢您的咨询！请点击“取消”给客服评分");
infos.push("评分");
infos.push("感谢您的评分");
infos.push("您已经评过分！");
infos.push("没有建立对话，不能发送文件");
infos.push("文件正在传送中");
infos.push("正在传送文件");
infos.push("传送成功");
infos.push("发送文件给您");
infos.push("点击接收保存");
infos.push("您指定的客服不在线！<br>您可以给客服<span class='span-link' onclick='to_rec();' title='请在信息输入框中发送留言'> 留言 </span>或者选择<span class='span-link' onclick='showauto()'> 客服助手 </span>的帮助！<br>如果您要选择其他客服人员进行对话，请<span class='span-link' onclick='to_main_kf()'> 返回 </span>。");
infos.push("您也可以选择<span class='span-link' onclick='showauto()'> 客服助手 </span>的帮助。");
infos.push("请在此发送留言！");
infos.push("感谢您的评分，我将继续为您服务！");
infos.push("尚未与客服建立对话，不能评分!");
infos.push("请填写你的姓名！");
infos.push("请填写你的电子信箱！");
infos.push("请填写你的电话号码或手机号码！（固定电话必须填写区号，中间不可有“－”如：0571－87858665 应该填写为 057187858665）");
infos.push("请填写你的电话号码或手机号码！");
infos.push("请填写你的qq！");
infos.push("请填写留言内容！");
infos.push("自助答疑助手");
infos.push("系统自动回复");
infos.push("秒后自动为您分配客服接待...");
infos.push("空闲");
infos.push("正常");
infos.push("繁忙");
infos.push("占线");
infos.push("未分配部门");
infos.push("您选择的客服不在线！\r\n点“确定”进入留言，“取消”重新选择其他客服");
infos.push("您正排在队列的第");
infos.push("位，请稍候...");
infos.push("如果您不想继续等待，也可以<span class='span-link' onclick='talkWithOthers()'><b>咨询其他客服</b></span>或<span class='span-link' onclick='getWlist(1)'><b>给客服人员留言</b></span>");
infos.push("<img src='style/chat/crystal_blue/img/machine.gif' style='float:left;'><span style='position:relative; left:2px; top:8px;'>您好，我是智能机器人小Q<br/><br/>如果不是太复杂的问题，我也可以为您<span class='span-link' onclick='showzsk()'><b>立刻解答</b></span></span><br/>");
infos.push("文件传送成功！");
infos.push("给客服评分");
infos.push("<img src='style/chat/crystal_blue/img/machine.gif' style='float:left;'><span style='position:relative; left:2px; top:8px;'>您好，我是智能机器人小Q<br/><br/>管理中心设置问候语</span><br/><br/>");
infos.push("&nbsp;&nbsp;&nbsp;请选择您想咨询的问题分类");
infos.push("有如下热门问题:");
infos.push("小Q 说:");
infos.push("所有分类");
infos.push("&nbsp;&nbsp;&nbsp;您是不是想咨询这些问题:");
infos.push("<tr><td align='left'>&nbsp;&nbsp;&nbsp;其他分类中还有");
infos.push("个类似问题 >> <span class='span-link' onclick='lookOver()'>点击查看</span></td></tr>");
infos.push("如果您没有得到满意的解答，可以 <span class='span-link' onclick='getWlist(1)'>给客服人员留言</span> 或 <span class='span-link' onclick='history.go(0)'>在线咨询</span>");
infos.push("<div style='float:left; margin-top:5px; margin-left:3px;'>>> 您正在咨询有关");
infos.push("的问题</div><span class='span-link' onclick='sendtext(\"GetTypeList\")' style='float:right; margin-top:5px; margin-right:10px; text-decoration:none'>选择问题分类</span>");
infos.push("所有分类");
infos.push("&nbsp;&nbsp;&nbsp;&nbsp;抱歉，该类没有找到与您输入相关的问题");
infos.push("请填写你的msn！");
infos.push("请填写你的单位！");
infos.push("• 客服人员将您转接至自助答疑<br>");
infos.push("未建立对话，不能发送消息。");
infos.push("对话已结束，不能发送消息。");
infos.push("发送内容不能为空，请重新输入。");
infos.push("智能机器人");
infos.push("自助答疑");
infos.push("您好，我是");
infos.push("提交留言");
infos.push("继续留言");
infos.push("结束对话");
infos.push("留言成功！");
infos.push("是否继续留言");
infos.push("链接不能为空！");
infos.push("建立对话失败，请检查网络环境后重试");
infos.push("您的消息");
infos.push("发送失败，请检查网络环境后重试");
infos.push("文件发送失败，请检查网络环境后重试");
infos.push("网络连接已修复，您可以继续发送对话");
infos.push("由于网络原因，提交评分失败");
infos.push("");
infos.push("取消");
infos.push("您已经很长时间没有发送信息了，再过3分钟，系统将会自动断开对话");
infos.push("请正确填写你的电子信箱！");
infos.push("请正确填写你的msn！");
infos.push("您太久没有操作，页面已过期，请在刷新后重新操作！");
infos.push("请填写你的联系地址！");
infos.push("对话已接通，");
infos.push("正在为您服务!");
infos.push("*如果链接文字为空，则直接显示链接");
infos.push("确定");
infos.push("您有新消息..");
infos.push("系统提示");
infos.push("建议");
infos.push("*字数控制在150字以内");
infos.push("输入字数超过150字，请重新输入！");
infos.push("无热门问题");
infos.push("回复方式");
infos.push("短信");
infos.push("邮件");
infos.push("网站");
infos.push("留言对象");
infos.push("公司");
infos.push("部门(人员)");
infos.push("不能选择未分组");
infos.push("回复");
infos.push("客服咨询");
infos.push("留言回复");
infos.push("访客防火墙");
infos.push("验证码");
infos.push("跳过");
infos.push("请输入验证码");
infos.push("验证码错误");
infos.push("是否接受");
infos.push("的评分请求？");
infos.push("如果您没有得到满意的解答，可以 <span class='span-link' onclick='history.go(0)'>在线咨询</span>");
infos.push("来自手机客服端：<a href='http://www.im-builder.com' target='_blank'>http://www.im-builder.com</a>");
infos.push("请输入相关信息");
infos.push("如果您没有得到满意的解答，可以 <span class='span-link' onclick='getWlist(1,1)'>给客服人员留言</span> 或 <span class='span-link' onclick='history.go(0)'>在线咨询</span>");
infos.push("很抱歉，由于您长时间未咨询，系统结束了本次服务，请对本次服务评价。若您仍需其他服务，请再次联系客服。");
infos.push("感谢您的咨询，请您主动评价，您将获得再次咨询时的优先接待服务。");






function chat_show_img()
{
    if(typeof($('#dialogUpImg').val()) != "undefined") {
        $('#dialogUpImg').remove();
        return;
    }

    var body = $('#msg_list');

    if (body.length == 0)
    {
        body = $('#content_box');
    }

    if(arguments[0] == 'dz8') {//定制版
        $('<div id="dialogUpImg" class="dialogs" style="top: 57px; left: 15px; height: 180px; width: 290px; border: 1px solid rgb(51, 153, 204); position: absolute; font-size: 14px; z-index: 9999; background: url(http://talk.53rj.com/img/pf_bg.gif) repeat-x;"><div style="float:right;margin-top:8px;margin-right:8px;width:12px;height:12px;cursor:pointer;background:url(../style/chat/minichat2/img/minchat_ns_dz8.png) -18px 0 no-repeat;" onclick="$(\'#dialogUpImg\').remove();"></div><div style=""><p style="margin: 0;background: #139ea1;color: #fff;font-size: 14px;line-height: 30px;text-align: left;padding-left: 10px;">图片上传</p><table cellpadding="0" cellspacing="8" border="0" width="100%" align="center" style="padding-top: 5px;"><tbody><tr><td style="text-align: left; padding-right:5px;position: relative;"><input id="src-input" type="text" class="comm-tbox" placeholder="请上传图片或输入图片地址" style="width: 200px; line-height: 24px; height: 24px;float: left;border: 1px solid #ddd;padding: 1px 5px;" onkeypress="if(event.keyCode==13){getLinkContent();try{window.focus();}catch(e){}return false;}"><input id="img_pic" name="userimg" type="file" value="浏览" onchange="to_insert_img_obj.ajaxuploadimg();" style="width: 66px;  height: 28px;-moz-opacity: 0.0;  filter: alpha(opacity=0);  opacity: 0;position: absolute;z-index: 5;"><span style="position: absolute;display: block;width: 51px;height: 26px;line-height: 26px;right: 5px;border: 1px solid #ddd;background: #fff;padding-left: 5px;z-index: 4;">浏览..</span></td></tr><tr><td style="text-align: left; font-size:12px;padding-bottom: 5px; color:red;">*仅支持JPG、JPEG、GIF、PNG，大小2M以内</td></tr><tr><td style=" text-align:right;padding-top: 36px;"><span style="padding-right:5px;"><input type="button" value="确定" onclick="if(to_insert_img_obj.sendimg($(\'#src-input\').val()))$(\'#dialogUpImg\').remove();" style="width: 78px;height: 31px;background:url(../style/chat/minichat2/img/dialog_send.png) no-repeat; border:none; text-indent:100em;"></span></td></tr></tbody></table></div></div>').appendTo(body);
        return;
    }

    //fix wap position
    if (window.temp == 'default' || window.temp == '' || typeof window.temp == 'undefined')
    {
        var h = $('#user_msg_list').height();
        if (h < 300)
        {
            h = 300 -110;
        }

        var dialog_top  = (h - 162) +  'px';
        var dialog_left = (($('#msg_list').width() - 300) / 2) + 'px';
    }
    else
    {
        //wap
        var dialog_top  = $('#page').height() - 250 + 'px';
        var dialog_left = ($('#page').width() - 300)/2 + 'px';
    }

    if(window.location.href.indexOf("tpl=minichat2")!=-1){
        $('<div id="dialogUpImg" class="dialogs" style="display:block;position:absolute;top:' + dialog_top + ';left:' + dialog_left + ';background:#fafafa;z-index:200000000;width:300px;"><style>\
 .img_load_lh .head {\
  height: 30px; line-height: 30px; background: #ddd; box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2); text-align: left;\
}\
.img_load_lh .head strong.title {\
  padding-left: 10px; color: #333;\
}\
 .img_load_lh .head .close {\
  display: block; width: 20px; height: 20px; font-size: 17px; line-height: 20px; text-align: center; position: absolute; top: 3px; right: 5px;\
}\
  .img_load_lh .uploadImg{\
  display: block;border: 1px solid #999;width: 55px;height: 25px;position: absolute;right: 25px;top: 55px;line-height: 25px;text-align: center;background-color: #eee;\
}.img_load_lh .head .close:hover {\
  text-decoration: none; color: #c00; \
}.img_load_lh .btns {\
  position: relative; margin-top:10px; left: 0; width: 100%; text-align: left;\
}\
 .img_load_lh .btn-complete, .img_load_lh .btn-cancel {\
 margin-bottom: 10px; display:inline-block; font-size: 11px; font-weight:bold; padding: 2px 15px; border-radius:2px;\
}\
 .img_load_lh .btns .btn-complete {\
  margin-right: 30px; float: right; color:#fff; background:#f7af1f; border:1px solid #e7840f;\
}\
 .img_load_lh .btns .btn-cancel {\
  margin-left: 30px; color:#555; background:#eee; border:1px solid #b3b3b3;\
}\
.img_load_lh .btn-cancel:hover{\
 text-decoration: none; text-decoration: none; background:#f3f3f3; border:1px solid #a5a5a5; box-shadow:0 1px 1px rgba(0, 0, 0, 0.1);\
}\
.img_load_lh .btn-complete:hover {\
 text-decoration: none; background:#f7ba3f; border:1px solid #d5780b; box-shadow:0 1px 1px rgba(250, 138, 0, 0.3);\
}\
 .img_load_lh .comm-tbox {\
  position:absolute; left:10px;top:0;width: 276px;padding-left:5px; height: 25px; line-height: 25px; border: 1px solid #666;margin-top:9px; box-shadow: inset 1px 2px 0 rgba(0, 0, 0, 0.15);color:#999999;\
}\
 .img_load_lh p {\
  margin: 15px 10px 0; *margin-left: 5px;\
}\
 .img_load_lh p.uComment {\
  color: #999; font-size:12px; text-align:left; font-family:arial;\
}\
 .img_load_lh .choice{\
  width:300px;position:relative;border-left:1px solid #aaa;border-top:1px solid #aaa;border-right:1px solid #bbb;border-bottom:1px solid #bbb;background-color:#FFF;margin:10px;\
}\
.img_load_lh{\
background-color: #fafafa; border: 1px solid #aaa; position:relative;z-index: 200000000;box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);border-radius: 3px;\
}\
.img_load_lh .choice span{display:block;}\
#inputUploadImg {\
  height: 30px; *height: 28px; position: absolute; right: 23px; *right: 5px; top: 53px; *top: 55px; width: 61px; *width: 58px; opacity: 0; filter:alpha(opacity=0); cursor: pointer;\
}</style><div class="img_load_lh"><div class="head"><strong class="title">图片设置</strong><a class="close" href="javascript:void(0);" onclick="$(\'#dialogUpImg\').remove();">×</a></div>\
    <div style="position:relative;height:35px;"><input id="src-input" class="comm-tbox" type="text" target="blank" value="  请输入网络地址或点击浏览上传" onFocus="if(this.value==\'  请输入网络地址或点击浏览上传\') this.value=\'\';" onblur="if(this.value==\'\')this.value=\'  请输入网络地址或点击浏览上传\'">\
       <input type="file" id="img_pic" name="userimg" onchange="to_insert_img_obj.ajaxuploadimg();" style="cursor: pointer;font-size: 9px;position:absolute;width:43px;height:25px;z-index:4;top:8px;right:7px;-moz-opacity: 0.0;filter: alpha(opacity=0);opacity:0;"/><span style="background-color:#F6F6F6;border-left:1px solid #666666;border-top:1px solid #666666;border-right:1px solid #AAAAAA;border-bottom:1px solid #AAAAAA;display:block;height:25px;line-height:25px;position:absolute;right:8px;text-align:center;top:9px;width:46px;z-index:3;">浏 览</span></div>\
    <p class="uComment">*仅支持JPG、JPEG、GIF、PNG，大小2M以内</p>\
    <div class="btns"><a href="#" class="btn-complete" onclick="if(to_insert_img_obj.sendimg($(\'#src-input\').val())) $(\'#dialogUpImg\').remove();">发&nbsp;送</a><a href="#" class="btn-cancel" onclick="$(\'#dialogUpImg\').remove();">取&nbsp;消</a></div></div></div>').appendTo(body);
    }else{
        $('<div id="dialogUpImg" class="dialogs" style="display:block;position:absolute;top:' + dialog_top + ';left:' + dialog_left + ';background:#fafafa;z-index:200000000;width:300px;"><style>\
 .img_load_lh .head {\
  height: 30px; line-height: 30px; background: #ddd; box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);\
}\
.img_load_lh .head strong.title {\
  padding-left: 10px; color: #333;\
}\
 .img_load_lh .head .close {\
  display: block; width: 20px; height: 20px; font-size: 17px; line-height: 20px; text-align: center; position: absolute; top: 3px; right: 5px;\
}\
  .img_load_lh .uploadImg{\
  display: block;border: 1px solid #999;width: 55px;height: 25px;position: absolute; right: 25px;top: 55px;line-height: 25px;text-align: center;background-color: #eee;\
}.img_load_lh .head .close:hover {\
  text-decoration: none; color: #c00;\
}.img_load_lh .btns {\
  position: relative; margin-top:10px; left: 0; width: 100%;\
}\
 .img_load_lh .btn-complete, .img_load_lh .btn-cancel {\
 margin-bottom: 10px; display:inline-block; font-size: 11px; font-weight:bold; padding: 2px 15px; border-radius:2px;\
}\
 .img_load_lh .btns .btn-complete {\
  margin-right: 10px; float: right; color:#fff; background:#f7af1f; border:1px solid #e7840f;\
}\
 .img_load_lh .btns .btn-cancel {\
  margin-left: 10px; color:#555; background:#eee; border:1px solid #b3b3b3;\
}\
.img_load_lh .btn-cancel:hover{\
 text-decoration: none; text-decoration: none; background:#f3f3f3; border:1px solid #a5a5a5; box-shadow:0 1px 1px rgba(0, 0, 0, 0.1);\
}\
.img_load_lh .btn-complete:hover {\
 text-decoration: none; background:#f7ba3f; border:1px solid #d5780b; box-shadow:0 1px 1px rgba(250, 138, 0, 0.3);\
}\
 .img_load_lh .comm-tbox {\
  width: 291px; height: 25px; line-height: 25px; border: 1px solid #666;margin-top:9px; box-shadow: inset 1px 2px 0 rgba(0, 0, 0, 0.15);\
}\
 .img_load_lh p {\
  margin: 15px 10px 0; *margin-left: 5px;\
}\
 .img_load_lh p.uComment {\
  color: #999; font-size:12px;\
}\
 .img_load_lh .choice{\
  width:300px;position:relative;border-left:1px solid #aaa;border-top:1px solid #aaa;border-right:1px solid #bbb;border-bottom:1px solid #bbb;background-color:#FFF;margin:10px;\
}\
.img_load_lh{\
display: block; width: 300px; background: #fafafa; border: 1px solid #aaa; position:absolute;z-index: 200000000;box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);border-radius: 3px;\
}\
.img_load_lh .choice span{display:block;}\
#inputUploadImg {\
  height: 30px; *height: 28px; position: absolute; right: 23px; *right: 5px; top: 53px; *top: 55px; width: 61px; *width: 58px; opacity: 0; filter:alpha(opacity=0); cursor: pointer;\
}</style><div class="img_load_lh" style="position:relative;"><div class="head"><strong class="title">图片设置</strong><a class="close" href="javascript:void(0);" onclick="$(\'#dialogUpImg\').remove();" hidefocus="">×</a></div>\
    <p><table border="0" cellpadding="0" cellspacing="0"><tr><td><input id="src-input" class="comm-tbox" type="text" target="blank" value="  请输入网络地址或点击浏览上传" onFocus="if(this.value==\'  请输入网络地址或点击浏览上传\')this.value=\'\';" onblur="if(this.value==\'\')this.value=\'  请输入网络地址或点击浏览上传\'">\
        </td><td><input type="file" id="img_pic" name="userimg" onchange="to_insert_img_obj.ajaxuploadimg();" style="cursor: pointer;font-size: 9px;position:absolute;width:43px;height:25px;z-index:4;top:55px;right:14px;-moz-opacity: 0.0;filter: alpha(opacity=0);opacity:0;"/><span style="background-color:#F6F6F6;border-left:1px solid #666666;border-top:1px solid #666666;border-right:1px solid #AAAAAA;border-bottom:1px solid #AAAAAA;display:block;height:25px;line-height:25px;position:absolute;right:7px;text-align:center;top:54px;*top:54px;width:46px;z-index:3;">浏 览</span></td></tr></table></p>\
    <p class="uComment">*仅支持JPG、JPEG、GIF、PNG，大小2M以内</p>\
    <div class="btns"><a href="javascript:void(0);" class="btn-complete" onclick="if(to_insert_img_obj.sendimg($(\'#src-input\').val()))$(\'#dialogUpImg\').remove();">发送</a><a href="javascript:void(0);" class="btn-cancel" onclick="$(\'#dialogUpImg\').remove();">取消</a></div></div></div>').appendTo(body);
    }

}


//插入图片对象操作
var to_insert_img_obj = {
    sendimg : function(urlstr){
        if(urlstr==""||urlstr=="  请输入网络地址或点击浏览上传"){
            alert('图片路径不能为空');
            return false;
        }
        var urlcode='[IMG]'+urlstr+'[/IMG]';
        to_insert_img_obj.sendimgmsg(urlcode);
        return true;
    },
    ajaxuploadimg:function(){

        //fix wap position
        if (window.temp == 'default' || window.temp == '' || typeof window.temp == 'undefined')
        {
            var url = './module/chat/chat_media.php';
        }
        else
        {
            var url = '../../module/chat/chat_media.php';
        }

        $.ajaxFileUpload({
            url:url,//处理图片脚本
            secureuri :false,
            fileElementId :'img_pic',//file控件id
            dataType : 'json',
            success : function (data, status){

                if(data.upload=='success'){
                    $("#src-input").val(data.url);
                }else if(data.upload=='fail'){
                    alert('上传文件失败');
                }

                if(data.filetype=='error'){
                    alert('仅支持jpg、jpeg、gif、png格式');
                }
                if(data.maxsize=='true'){
                    alert('仅支持文件小于2M');
                }

            },
            error: function(data, status, e){
                console.info(data);
                console.info(status);
                console.info(e);
                alert(e);
            }
        });
    },
    sendimgmsg:function(msg){
        msg = msg.trim();
        try{
            msg = msgFilter(msg);
        }catch(e){}
        var show_msg = msg;

        if(m_success==false && lnkover!=200)
        {
            /*
             if(lnkover==2)
             {
             document.getElementById("send_tips").innerHTML = "<p>"+infos[88]+"</p>";
             }
             else
             {
             document.getElementById("send_tips").innerHTML = "<p>"+infos[89]+"</p>";
             }
             document.getElementById("send_tips").style.display = "block";
             setTimeout("hide_sendTips()", 1000);
             */
            return;
        }
        else if(msg=="")
        {
            /*
             document.getElementById("send_tips").innerHTML = "<p>"+infos[90]+"</p>";
             document.getElementById("send_tips").style.display = "block";
             setTimeout("hide_sendTips()", 1000);
             */
            return;
        }

        show_msg = UBBEncode(show_msg);
        show_msg = HtmlEncode(show_msg);
        show_msg = UBBCode(show_msg);
        show_msg = show_msg.replace(/<br>/g, "<br>");

        console.info(show_msg);
        $("#send_message").val(show_msg);
        $("#ent").val(show_msg);
        send_msg();
        return true;
        //display_msg2("<div class='send-msg-name'>"+infos[13]+"&nbsp;"+getTime2()+"</div><p class='send-msg-content'>"+show_msg+"</p>");

    }
};
// 插入图片
function HtmlEncode(text)
{var msg=text.replace(/&/g,'&amp;').replace(/\"/g,'&quot;').replace(/\'/g,'&#039;').replace(/</g,'&lt;').replace(/>/g,'&gt;');msg=msg.replace(/\r\n/g,"<br/>");msg=msg.replace(/\r|\n/g,"<br/>");return msg;}

function HtmlDecode(text)
{return text.replace(/&amp;/g,'&').replace(/&amp;/g,'&').replace(/&quot;/g,'\"').replace(/&#039;/g,'\'').replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&mdash;/g,'—');}

function UBBEncode(str)
{var replaceURL=false;var replaceIMG=false;str=str.replace(/<img[^>]*smile=\"(\d+)\"[^>]*>/ig,'[s:$1]');str=str.replace(/<img[^>]*src=[\'\"\s]*([^\s\'\"]+)[^>]*>/ig,'[img]'+'$1'+'[/img]');var apattern=new RegExp(/<a[^>]*href=[\'\"\s]*([^\s\'\"]*)[^>]*>(.+?)<\/a>/i);if(apattern.test(str)==true){str=str.replace(/<a[^>]*href=[\'\"\s]*([^\s\'\"]*)[^>]*>(.+?)<\/a>/ig,'[url='+'$1'+']'+'$2'+'[/url]');return UBBEncode(str);}
    if(str.indexOf("[URL")!=-1||str.indexOf("[url")!=-1)
    {var pattern=/\[URL\=([^\]]*)\]([^\[]*)\[\/URL\]/gim;str=str.replace(pattern,"{#URL={$1{$2}}}");replaceURL=true;}
    if(str.indexOf("[IMG]")!=-1||str.indexOf("[img]")!=-1)
    {var imgpattern=/\[IMG\](http:\/\/)?([^[]*)\[\/IMG\]/gim;str=str.replace(imgpattern,"{#IMG={http://$2}}");replaceIMG=true;}
    str="!"+str;var pattern=/([^{\/])((ftp:\/\/|https:\/\/|http:\/\/|www\d{0,4}\.)[\w\-]*\.[\w!~;*'()&=\+\$%\-\/\#\?:\.,\|\^]*)/gim;str=str.replace(pattern,"$1[URL=$2]$2[/URL]");str=str.replace('!','');if(replaceIMG==true)
{var backimgpattern=/{#IMG={([^}]*)}}/gim;str=str.replace(backimgpattern,"[IMG]$1[/IMG]");}
    if(replaceURL==true)
    {var backpattern=/{#URL={([^{]*){([^}]*)}}}/gim;str=str.replace(backpattern,"[URL=$1]$2[/URL]");}
    str=str.replace(/\[URL=www/gi,"[URL=http://www");return str;}

function UBBCode(strContent)
{if((navigator.appName=="Microsoft Internet Explorer")&&(navigator.appVersion.match(/MSIE \d\.\d/)=="MSIE 5.0"))
{if(strContent.indexOf("[IMG]")>=0)
{var con=strContent.substr(5,strContent.indexOf("[/IMG]")-5);strContent="<IMG SRC=\""+con+"\">";}
    if(strContent.indexOf("[URL=")>=0)
    {var tlink=strContent.substr(5,strContent.indexOf("]")-5);var text=strContent.substr(strContent.indexOf("]")+1,strContent.length-6-strContent.indexOf("]")-1);strContent="<A HREF=\""+tlink+"\" TARGET=_blank>"+text+"</A>";}}
else
{
    var r2=new RegExp("(\\[URL=(.+?)\])(.+?)(\\[\\/URL\\])","gim");var r3=new RegExp("(\\[IMG\])(\\S+?)(\\[\\/IMG\\])","gim");var r4=new RegExp("(\\[QQ\])(\\d+?)(\\[\\/QQ\\])","gim");var r5=new RegExp("&amp","gim");strContent=strContent.replace(r2,'<A HREF="$2" TARGET="_blank" style="text-decoration:underline; color:#8A2BE2;">$3</A>');strContent=strContent.replace(r3,'<IMG border="0" class="chat_img" onclick="open_img(this)"  SRC="$2">');strContent=strContent.replace(r4,'<img border="0" title="点击跟我QQ[$2]聊" src="http://www.im-builder.com/img/qq.gif" onclick="addQQ(\'$2\')" style="cursor:pointer"/>[$2]');}
    strContent=strContent.replace(/{(.[^#.-\/]*)#(.[^#.-\/]*)#}/gi,"<img src=\"http://"+domain_root+"/img/face/$1/$1_$2.gif\" border=\"0\">");return strContent;

}



function textCounter(obj,maxlength)
{if(obj.value.length>maxlength)
{obj.value=obj.value.substr(0,maxlength);}}

function getpara(strname)
{var hrefstr,pos,parastr,para,tempstr;hrefstr=window.location.href;pos=hrefstr.indexOf("#");pos=(pos==-1?hrefstr.length:pos);hrefstr=hrefstr.substring(0,pos);pos=hrefstr.indexOf("?");parastr=hrefstr.substring(pos+1);para=parastr.split("&");tempstr="";for(i=0;i<para.length;i++)
{tempstr=para[i];pos=tempstr.indexOf("=");if(tempstr.substring(0,pos)==strname)
{return tempstr.substring(pos+1);}}
    return"";}

function UBBEncode2(str){str=str.replace(/<br[^>]*>/ig,'\n');str=str.replace(/<p[^>\/]*\/>/ig,'\n');str=str.replace(/\son[\w]{3,16}\s?=\s*([\'\"]).+?\1/ig,'');str=str.replace(/<hr[^>]*>/ig,'[hr]');str=str.replace(/<(sub|sup|u|strike|b|i|pre)>/ig,'[$1]');str=str.replace(/<\/(sub|sup|u|strike|b|i|pre)>/ig,'[/$1]');str=str.replace(/<(\/)?strong>/ig,'[$1b]');str=str.replace(/<(\/)?em>/ig,'[$1i]');str=str.replace(/<(\/)?blockquote([^>]*)>/ig,'[$1blockquote]');str=str.replace(/<img[^>]*smile=\"(\d+)\"[^>]*>/ig,'[s:$1]');str=str.replace(/<img[^>]*src=[\'\"\s]*([^\s\'\"]+)[^>]*>/ig,'[img]'+'$1'+'[/img]');str=str.replace(/<a[^>]*href=[\'\"\s]*([^\s\'\"]*)[^>]*>(.+?)<\/a>/ig,'[url=$1]'+'$2'+'[/url]');str=str.replace(/<[^>]*?>/ig,'');str=str.replace(/&amp;/ig,'&');str=str.replace(/&lt;/ig,'<');str=str.replace(/&gt;/ig,'>');return str;}

function UBBDecode2(str){str=str.replace(/</ig,'&lt;');str=str.replace(/>/ig,'&gt;');str=str.replace(/\n/ig,'<br />');str=str.replace(/\[code\](.+?)\[\/code\]/ig,function($1,$2){return phpcode($2);});str=str.replace(/\[hr\]/ig,'<hr />');str=str.replace(/\[\/(size|color|font|backcolor)\]/ig,'</font>');str=str.replace(/\[(sub|sup|u|i|strike|b|blockquote|li)\]/ig,'<$1>');str=str.replace(/\[\/(sub|sup|u|i|strike|b|blockquote|li)\]/ig,'</$1>');str=str.replace(/\[\/align\]/ig,'</p>');str=str.replace(/\[(\/)?h([1-6])\]/ig,'<$1h$2>');str=str.replace(/\[align=(left|center|right|justify)\]/ig,'<p align="$1">');str=str.replace(/\[size=(\d+?)\]/ig,'<font size="$1">');str=str.replace(/\[color=([^\[\<]+?)\]/ig,'<font color="$1">');str=str.replace(/\[backcolor=([^\[\<]+?)\]/ig,'<font style="background-color:$1">');str=str.replace(/\[font=([^\[\<]+?)\]/ig,'<font face="$1">');str=str.replace(/\[list=(a|A|1)\](.+?)\[\/list\]/ig,'<ol type="$1">$2</ol>');str=str.replace(/\[(\/)?list\]/ig,'<$1ul>');str=str.replace(/\[s:(\d+)\]/ig,function($1,$2){return smilepath($2);});str=str.replace(/\[img\]([^\[]*)\[\/img\]/ig,'<img src="$1" border="0" />');str=str.replace(/\[url=([^\]]+)\]([^\[]+)\[\/url\]/ig,'<a href="$1">'+'$2'+'</a>');str=str.replace(/\[url\]([^\[]+)\[\/url\]/ig,'<a href="$1">'+'$1'+'</a>');return str;}

function msgFilter(msg)
{while(/onerror|onclick|onload|onmouse|onkey|unescape|decodeuri|eval|expression|\\/igm.test(msg)){msg=msg.replace(/onerror|onclick|onload|onmouse|onkey|unescape|decodeuri|eval|expression|\\/igm,'');}
    return msg;}

// 获取图片内容
function getImageContent()
{
    var imagePath = document.getElementById("insert_image_url").value.trim();
    if(imagePath=="")
    {
        alert(infos[99]);
        document.getElementById("insert_image_url").focus();
    }
    else
    {
        var html = "[IMG]"+imagePath+"[/IMG]";
        document.getElementById("send_message").value += html;
        destryInsertDiv("image");
        document.getElementById("send_message").focus();
    }
}





//end 上传图片

var locStorage;

//本地存储容器
if (typeof domain_root == 'undefined')
{
    var domain_root = 'http://www.im-builder.com/';
}

var index_url = domain_root + 'official_app/api/index.php';
//index_url = 'http://www.jin.com/index.php';

var member_url = domain_root + "official_app/member.php";
var friend_url = domain_root + "official_app/friends.php";
var news_url = domain_root + "official_app/news.php";
var sns_url = domain_root + "official_app/sns.php";

var login_url = domain_root + "official_app/login.php";

if(window.localStorage)
{
    locStorage = window.localStorage;
}
else
{
    alert('This browser does NOT support localStorage');
}

function init_chat_ui(user_id, user_name, user_logo)
{
    locStorage['member:user_id'] = user_id;
    locStorage['member:user_name'] = user_name;

    if (user_logo.indexOf("http://") >= 0 )
    {
        locStorage['member:user_logo'] = user_logo;
    }
    else
    {
        locStorage['member:user_logo'] = "../../" + user_logo;
    }


    if (!$('#fileUploaderEmptyHole').length)
    {
        var iframe;

        try {
            iframe = document.createElement('<iframe name="fileUploaderEmptyHole">');
        }
        catch (ex){
            iframe = document.createElement('iframe');
        }

        //var size_no_scrll = getViewSizeWithoutScrollbar();
        //var size = getViewSizeWithScrollbar();

        //var t_h = $('#header').height();
        var t_h = 33;

        if ('product' == getUrlParam('m'))
        {
            t_h = 33;
        }

        /*
         var w = $(document).width();
         //var h = $(document).height();
         var h = $(document).height();
         var url = 'templates/wap/chat_content.html';
        */
        if (window.temp == 'default' || window.temp == '' || typeof window.temp == 'undefined')
        {
            var w = 400;
            var h = 600;
            var url = 'templates/default/chat_content.html?user_id=' + user_id;
        }
        else
        {
            var w = $(document).width();
            //var h = $(document).height();
            var h = $(document).height();
            var url = 'templates/wap/chat_content.html?user_id=' + user_id;
        }

        iframe.id = 'fileUploaderEmptyHole';
        iframe.name = 'fileUploaderEmptyHole';
        iframe.width = w + "px";
        iframe.height = h - t_h + "px";
        iframe.marginHeight = 0;
        iframe.marginWidth = 0;

        var border = 0;
        iframe.src = url;
        iframe.style.width = w - border*2 + "px";
        iframe.style.height = h - t_h - border*2  + "px";
        iframe.style.position = "absolute";
        iframe.style.top = 0;
        iframe.style.border = border;
        iframe.style.borderColor = "#FAFAFA";

        if (window.temp == 'default' || window.temp == '' || typeof window.temp == 'undefined')
        {
            $('#page_0').css({
                "bottom": "0px",
                "right": "0px",
                "width": w + "px",
                "height": h + "px",
                "min-height": h + "px",
                "max-height": h + "px",
                "position": "fixed"
                //"border": "0px"
            });
        }
        else
        {
            $('#page_0').css({
                "top": "0px",
                "left": "0px",
                "width": w + "px",
                "height": h + "px",
                "min-height": h + "px",
                "max-height": h + "px",
                "position": "absolute"
                //"border": "0px"
            });
        }



        $('#chat_content').append(iframe);


        //session iframe

        var iframe_session;

        try {
            iframe_session = document.createElement('<iframe name="fileUploaderEmptyHoleSession">');
        }
        catch (ex){
            iframe_session = document.createElement('iframe');
        }

        if (window.temp == 'default' || window.temp == '' || typeof window.temp == 'undefined')
        {
            var url_session = 'templates/default/chat_session.html?user_id=' + user_id;
        }
        else
        {
            var url_session = 'templates/wap/chat_session.html?user_id=' + user_id;
        }


        iframe_session.id = 'fileUploaderEmptyHoleSession';
        iframe_session.name = 'fileUploaderEmptyHoleSession';
        iframe_session.width = w + "px";
        iframe_session.height = h - t_h + "px";
        iframe_session.marginHeight = 0;
        iframe_session.marginWidth = 0;

        var border = 0;
        iframe_session.src = url_session;
        iframe_session.style.width = w - border*2 + "px";
        iframe_session.style.height = h - t_h - border*2  + "px";
        iframe_session.style.position = "absolute";
        iframe_session.style.top = 0;
        iframe_session.style.border = border;
        iframe_session.style.borderColor = "#FAFAFA";


        $('#chat_content').append(iframe_session);
        $('#fileUploaderEmptyHoleSession').hide();

    }
    else
    {
        console.info("$('#page_0').show();");
        $('#page_0').show();
    }
}

function init()
{
    /*
    var h = document.getElementById("content").offsetHeight;
    var f = document.getElementById("footer").offsetHeight; //显示隐藏，高度未修正，所以...
    var nh = h + f + 8;
    document.getElementById("content").style.height = nh + 'px';
    window.scroll(0, 100000);
    */
    /*
    appcan.button(".show_member_btn", "ani-act", function(e)
    {
        //console.info($(this).attr('show_id'));
        goMember($(this).attr('show_id'), $(this).attr('show_name'))
    });
    */

    //修正wap端输入框位置信息

}

function init_input()
{
    /*
    var h = document.getElementById("content").offsetHeight;
    var f = document.getElementById("footer").offsetHeight; //显示隐藏，高度未修正，所以...
    var nh = h + f + 8;
    //document.getElementById("content").style.height = nh + 'px';
    window.scroll(0, 100000);
    */
}


function haveWrite()
{
    /*
    if ($("#ent").val())
    {
        $("#add").addClass("hidden");
        $("#send").removeClass("hidden");
    }
    else
    {
        $("#add").removeClass("hidden");
        $("#send").addClass("hidden");
    }
    */
}


function send()
{
    var touserid   = locStorage['member:chat:user_id'];
    var fromuserid = locStorage['member:user_id'];
    var show_name  = locStorage['member:user_name'];

    var content = document.getElementById("ent").value;

    if ('' == content)
    {
        $("#ent").focus();

        return ;
    }

    //var logo = "../../image/default/user_admin/default_user_portrait.gif";
    var logo = locStorage['member:user_logo'];

    html = "<dt class='show_member_btn' show_id='" + fromuserid + "' show_name='" + show_name + "'><img src='" + logo + "' /></dt><dd>" + replace_em(content) + "</dd>";
    document.getElementById("ent").value = "";

    var dl = document.createElement("dl");
    dl.className = "rchat";
    dl.innerHTML = html;
    document.getElementById('content').appendChild(dl);

    init();

    var op =
    {
        touserid : touserid,
        fromuserid : fromuserid,
        fromInfo : content,
        msgtype : 1
    };

    if (touserid != fromuserid)
    {
        $.post(index_url + "?ctl=Message&met=add&data_type=json", op,function(){
            //setTimeout(closefunc1,200);
            console.info(111);
        });
    }
    else
    {
        alert('不能和自己聊天');
    }
}

function send_msg()
{
    send();
}

function receive(json_str)
{
    console.info(json_str);

    //var chat_data = JSON.parse(json_str);
    var chat_data = json_str;
    var uid = locStorage['member:user_id'];
    var show_name = chat_data['from_name'];
    var show_id = chat_data['fromuserid'];

    chat_data['con'] = replace_em(chat_data['con']);

    if (chat_data['from_logo'].indexOf("http://") >= 0 )
    {
        var logo = chat_data['from_logo'];
    }
    else
    {
        var logo = "../../" + chat_data['from_logo'];
    }

    var dl = document.createElement("dl");

    if (chat_data['touserid'] == uid)
    {
        dl.className = "lchat";
    }

    if (chat_data['fromuserid'] == uid)
    {
        dl.className = "rchat";
        show_id     = locStorage['member:user_id']
        show_name = locStorage['member:user_name'];
        logo = locStorage['member:user_logo'];
    }



    var html = "<dt class='show_member_btn' show_id='" + show_id + "'  show_name='" + show_name + "'><img src='" + logo + "' /></dt><dd>";
    html =  html + chat_data['con'];
    html =  html + "</dd>";

    dl.innerHTML = html;

    $('#content').append(dl);

    init();
};

var interval_id = null;
//{'id':43, 'name':'huangxz'}

function chat_with()
{
    showMsgUnread();
    interval_id =  setInterval("showMsgUnread()", 6000);
}


function end_chat_session()
{
    clearInterval(interval_id);
}

function chat_win_init(data)
{
    if (!$.cookie('USER'))
    {
        alert('请先登录！');
        return false;
    }

    var uid = locStorage['member:chat:user_id'] = data['id'];
    var show_name = locStorage['member:chat:user_name'] = data['name'];
    var show_logo = locStorage['member:chat:user_logo'] = data['logo'];

    $('#page_0').show();
    $('#fname').html(data['name']);

    window.frames["fileUploaderEmptyHole"].window.chat_with();

    window.scrollTo(0,10009999);

    return false;
}

function clean_chat_content()
{
    $('#content').html('');
}

function change_chat_with(data)
{
    window.frames["fileUploaderEmptyHole"].window.clean_chat_content();

    $('#fileUploaderEmptyHoleSession').hide();
    $('#fileUploaderEmptyHole').show();

    chat_win_init(data);

    return false;
}


function showMsgUnread()
{
    var user_id     = locStorage['member:user_id'];
    var fromuserid  = locStorage['member:chat:user_id'];

    var op_data =
    {
        iflook : 2,
        user_id:user_id,
        fromuserid:fromuserid,
        flag:1
    };

    $.post(index_url + "?ctl=Message&met=get&data_type=json", op_data, function(rs){
        //显示处理
        data = JSON.parse(rs);
        console.info(data[0]["b"]);

        $.each(data[0]["b"], function(i, d){
            console.info('****');
            console.info(i);

            //receive('{"friend_id_send":12, "friend_id_receive":41, "msg_text":"测试玩家"}');
            receive(d);
        });
        //[{"id":"47","uid":"41","touserid":"12","fromuserid":"41","fromInfo":"afdafd","msgtype":"2","sub":"afdafd","con":"afdafd","iflook":"2","date":"2015-05-26 19:08:18","contype":null,"tid":null,"receive_type":null,"reply_by":null,"attachments":null,"is_save":"0"}]}]});
    });
}

function getMsgUnread()
{
    var user_id     = locStorage['member:user_id'];

    var op_data =
    {
        iflook : 2,
        user_id:user_id,
        flag:0
    };

    $.post(index_url + "?ctl=Message&met=get&data_type=json", op_data,function(rs){
        console.info(rs);
        data = JSON.parse(rs);

        var user_row = {};


        $.each(data[0]["b"], function(i, d){
            console.info('---:');
            console.info(i);
            console.info(d);

            if (d['from_logo'].indexOf("http://") >= 0 )
            {
                var logo = d['from_logo'];
            }
            else
            {
                var logo = d['from_logo'];
            }

            if (!user_row.hasOwnProperty(d['fromuserid']))
            {
                user_row[d['fromuserid']] = {};
                user_row[d['fromuserid']]['num'] = 1;
                user_row[d['fromuserid']]['name'] = d['from_name'];
                user_row[d['fromuserid']]['logo'] = logo;
                user_row[d['fromuserid']]['msg'] = d['con'];
            }
            else
            {
                user_row[d['fromuserid']]['num'] = user_row[d['fromuserid']]['num'] + 1;
                user_row[d['fromuserid']]['msg'] = d['con'];
            }
        });

        console.info(user_row);

        /*
        $.each(locStorage['member:session'], function(id, d){
            //locStorage['member:session'][id]['num'] = 0;
        });
        */

        $.each(user_row, function(id, d){
            var html = '<dt id="user_' + id + '" class="show_member_btn" show_id="' + id + '" show_name="' + d['name'] + '"> <span class="badge"> ' + d['num'] + '</span><img src="' + d['logo'] + '" /></dt>';


            //locStorage['member:session'][id]['msg'] = d['msg'];
            //locStorage['member:session'][id]['num'] = d['num'];

            if ($('#user_' + id))
            {
                $('#user_' + id).unbind("click");

                $('#user_' + id).remove();

                $('#chat_icon').append(html);

                $('#user_' + id).bind("click", function ()
                {
                    $(this).remove();
                    $("#chat_icon").hide();
                    $("#page_0").show();

                    chat_win_init({'id':id, 'name':d['name'], 'logo':d['logo']});

                });
            }

        });

        if (data[0]["b"].length)
        {
            $('#badge').html(data[0]["b"].length);
            $('#badge').show();
        };



    });
}

function fixChatContainer()
{
    /*
    var top = $(window).scrollTop() + 60;
    var left= $(window).width() - $("#chat_container").width();

    $("#chat_container").css({ left:left + "px", top: top + "px" });
    console.info($(window).width());
    console.info({ left:left + "px", top: top + "px" });

    //var t= window.scrollY - 10 + 1 +  "px";
    //$("#chat_icon").css({ right:0 + "px", bottom: 0 + "px", top: t});
    */
}

//--------------module:qqFace-------------

jQuery.browser = {};
(function ()
{
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./))
    {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1
    }
})();

jQuery.extend({
    unselectContents: function ()
    {
        if (window.getSelection)
        {
            window.getSelection().removeAllRanges()
        }
        else
        {
            if (document.selection)
            {
                document.selection.empty()
            }
        }
    }
});

jQuery.fn.extend({
    selectContents: function ()
    {
        $(this).each(function (b)
        {
            var d = this;
            var c, a, f, e;
            if ((f = d.ownerDocument) && (e = f.defaultView) && typeof e.getSelection != "undefined" && typeof f.createRange != "undefined" && (c = window.getSelection()) && typeof c.removeAllRanges != "undefined")
            {
                a = f.createRange();
                a.selectNode(d);
                if (b == 0)
                {
                    c.removeAllRanges()
                }
                c.addRange(a)
            }
            else
            {
                if (document.body && typeof document.body.createTextRange != "undefined" && (a = document.body.createTextRange()))
                {
                    a.moveToElementText(d);
                    a.select()
                }
            }
        })
    }, setCaret: function ()
    {
        if (!$.browser.msie)
        {
            return
        }
        var a = function ()
        {
            var b = $(this).get(0);
            b.caretPos = document.selection.createRange().duplicate()
        };
        $(this).click(a).select(a).keyup(a)
    }, insertAtCaret: function (c)
    {
        var b = $(this).get(0);
        if (document.all && b.createTextRange && b.caretPos)
        {
            var d = b.caretPos;
            d.text = d.text.charAt(d.text.length - 1) == "" ? c + "" : c
        }
        else
        {
            if (b.setSelectionRange)
            {
                var g = b.selectionStart;
                var f = b.selectionEnd;
                var h = b.value.substring(0, g);
                var e = b.value.substring(f);
                b.value = h + c + e;
                b.focus();
                var a = c.length;
                b.setSelectionRange(g + a, g + a);
                b.blur()
            }
            else
            {
                b.value += c
            }
        }
    }
});



function replace_em(a)
{
    //a = a.replace(/\</g, "&lt;");
    //a = a.replace(/\>/g, "&gt;");
    a = a.replace(/\n/g, "<br/>");

    if (typeof smilies_array !== "undefined")
    {
        a = '' + a;
        for (i in smilies_array[1])
        {
            var s = smilies_array[1][i];
            var re = new RegExp("" + s[1], "g");
            var smilieimg = '<img width="28" height="28" title="' + s[6] + '" alt="' + s[6] + '" src="' + '../../script/chat/smilies/images/' + s[2] + '">';
            a = a.replace(re, smilieimg);
        }
    }

    a = a.replace(/\[em_([0-9]*)\]/g, '<img src="../../image/arclist/$1.gif" border="0" />');
    return a
};


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

(function (a)
{
    a.fn.qqFace = function (c)
    {
        var g = {id: "facebox", path: "face/", assign: "content", tip: "em_"};
        var d = a.extend(g, c);
        var b = a("#" + d.assign);
        var h = d.id;
        var f = d.path;
        var e = d.tip;
        if (b.length <= 0)
        {
            alert("缺少表情赋值对象。");
            return false
        }
        a(this).click(function (l)
        {
            var o, n;
            if (a("#" + h).length <= 0)
            {
                o = '<div id="' + h + '" style="position:absolute;display:none;z-index:1000;" class="qqFace"><table border="0" cellspacing="0" cellpadding="0"><tr>';
                for (var j = 1; j <= 75; j++)
                {
                    n = "[" + e + j + "]";
                    o += '<td><img src="' + f + j + '.gif" onclick="$(\'#' + d.assign + "').setCaret();$('#" + d.assign + "').insertAtCaret('" + n + "');\" /></td>";
                    if (j % 15 == 0)
                    {
                        o += "</tr><tr>"
                    }
                }
                o += "</tr></table></div>"
            }
            a(this).parent().append(o);
            var m = a(this).position();
            var k = m.top + a(this).outerHeight() - 207;
            a("#" + h).css("top", k);

            if (window.temp == 'default' || window.temp == '' || typeof window.temp == 'undefined')
            {
                a("#" + h).css("left", -2);
            }
            else
            {
                a("#" + h).css("left", m.left - 2);  //放在右边， -193
            }

            //a("#" + h).css("left", m.left - 9);
            a("#" + h).show();
            l.stopPropagation()
        });
        a(document).click(function ()
        {
            a("#" + h).hide();
            a("#" + h).remove()
        })
    }
})(jQuery);


$(function() {
    $(window).scroll(function() {
        fixChatContainer();
    });

    $("#chat_icon").hide();

    $('#nav-left').click(function(){
        window.frames["fileUploaderEmptyHole"].window.end_chat_session();

        $("#chat_icon").show();
        $('#badge').html('');

        $("#page_0").hide();
    });

    /*
    $("#chat_icon").click(function ()
    {
        $("#chat_icon").hide();
        $('#badge').html('');

        $('#page_0').show();
    });
    */

    $("#chat_close").click(function ()
    {
        $("#chat_icon").show();
        $('#badge').html('');

        $("#page_0").hide();
    });


    $('#ent').keydown(function(e){
        if(e.keyCode==13){
            send();
        }
    });

    if ($("#add_face_btn").length>0)
    {
        $("#add_face_btn").qqFace({id: "facebox", assign: "ent", path: "../../image/arclist/"});
    }

    if ($("#add_image").length>0)
    {
        $("#add_image").click(function ()
        {
            chat_show_img();
        });
    }

    /*--------------拖曳效果----------------
     *原理：标记拖曳状态dragging ,坐标位置iX, iY
     *         mousedown:fn(){dragging = true, 记录起始坐标位置，设置鼠标捕获}
     *         mouseover:fn(){判断如果dragging = true, 则当前坐标位置 - 记录起始坐标位置，绝对定位的元素获得差值}
     *         mouseup:fn(){dragging = false, 释放鼠标捕获，防止冒泡}
     */
    /*
    var dragging = false;
    var iX, iY;
    $("#page_0").mousedown(function(e) {
        dragging = true;
        iX = e.clientX - this.offsetLeft;
        iY = e.clientY - this.offsetTop;
        this.setCapture && this.setCapture();
        return false;
    });
    document.onmousemove = function(e) {
        if (dragging) {
            var e = e || window.event;
            var oX = e.clientX - iX;
            var oY = e.clientY - iY;
            $("#page_0").css({"left":oX + "px", "top":oY + "px"});
            return false;
        }
    };
    $(document).mouseup(function(e) {
        dragging = false;
        $("#page_0")[0].releaseCapture();
        e.cancelBubble = true;
    })
    */
});
