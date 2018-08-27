/**
 * Created by JKZ on 2015/6/9. 
 */

(function() {

    function alert_box_im(msg){
        if(typeof(msg) == null){
            msg = '未知错误';
        }
        IM.HTML_showAlert('alert-warning',msg);
    }

    
    function goto_top(){
        $('#im_content_list').scrollTop($('#im_content_list')[0].scrollHeight);
    }

    function show_msg_list(u_id){
        var ajax_chatlog_url = ImApiUrl+"?ctl=Api_Chatlog&met=get&u="+getCookie('user_account')+"&to="+u_id+"&day=30&typ=json&is_wap=1";
            $.get(ajax_chatlog_url,function(json){
                 var data = json.data.html;
                 var h = $('#im_content_list');
                 h.html(data);

                 goto_top();
                 $('.chat_header_icon_sender').attr('src',user_logo);
            },'json');
    }

    function send_to_server_bysun(_user_account,content_you,msgid,im_send_content,content_type){
        goto_top();
        /*
        聊天记录发送到远丰数据库

        weichat:sunkangchina

        */

        $.post(ImApiUrl+"?ctl=Api_Chatlog&met=add&typ=json",{u:_user_account,to:content_you,msgid:msgid,content:im_send_content,type:content_type},function(){


        });

    }

    window.IM = window.IM || {
        //_appid : '20150314000000110000000000000010', // 应用ID
        //_appid : '8a48b55152114d54015215890cb907d5',
        _appid : window.appID,
        _onUnitAccount : 'KF10089', // 多渠道客服帐号，目前只支持1个
        _3rdServer : 'http://123.57.230.158:8886/authen/', // 3rdServer，主要用来虚拟用户服务器获取SIG


        /** 以下不要动，不需要改动 */
        _timeoutkey : null,
        _username : null,
        _user_account : null,
        _contact_type_c : 'C', // 代表联系人
        _contact_type_g : 'G', // 代表群组
        _contact_type_m : 'M', // 代表多渠道客服
        _onMsgReceiveListener : null,
        _onDeskMsgReceiveListener : null,
        _noticeReceiveListener : null,
        _onConnectStateChangeLisenter : null,
        _onCallMsgListener :null,
        _isMcm_active : false,
        _local_historyver : 0,
        _msgId : null,// 消息ID，查看图片时有用
        _pre_range : null,// pre的光标监控对象
        _pre_range_num : 0, // 计数，记录pre中当前光标位置，以childNodes为单位
        _fireMessage : 'fireMessage',
        _serverNo : 'XTOZ',
        _baiduMap:null,
        _loginType:1,//登录类型: 1账号登录，3voip账号密码登录
        _Notification:null,


        /**
         * 初始化
         *
         * @private
         */
        init : function() {
            // 初始化SDK
            IM._appid = window.appID;
            var resp = RL_YTX.init(IM._appid);
            if (!resp) {
                alert_box_im('SDK初始化错误');
                return;
            };
            if (200 == resp.code) {// 初始化成功
                $('#navbar_login').show();
                $('#navbar_login_show').hide();

                $.post(UCenterApiUrl + '?ctl=User&met=getImNickname&typ=json',{im_user_name:getQueryString('contact_you'),u:getCookie('id'),k:getCookie('key')},function(resp){
                    if(resp.status == 200 && resp.data.nickname != 'undefined'){
                        $(".content_you").html(decodeURIComponent(resp.data.nickname+'('+getQueryString('contact_you')+')'));
                    } else {
                        $(".content_you").html(decodeURIComponent(getQueryString('contact_you')));
                    }
                });


                // 重置页面高度变化
                IM.HTML_resetHei();

                window.onresize = function() {
                    IM.HTML_resetHei();
                };

                // 初始化表情
                IM.initEmoji();
                // 初始化一些页面需要绑定的事件
                IM.initEvent();
                if($.inArray(174004,resp.unsupport) > -1 || $.inArray(174009,resp.unsupport) > -1){//不支持getUserMedia方法或者url转换
                    IM.Check_usermedie_isDisable();//拍照、录音、音视频呼叫都不支持

                }else if($.inArray(174007,resp.unsupport) > -1){//不支持发送附件
                    IM.SendFile_isDisable();

                }else if($.inArray(174008,resp.unsupport) > -1){//不支持音视频呼叫，音视频不可用
                    IM.SendVoiceAndVideo_isDisable();

                };
            }else if(174001 == resp.code){// 不支持HTML5
                var r = confirm(resp.msg);
                if (r == true || r == false) {
                    window.close();
                }
            }else if(170002 == resp.code){//缺少必须参数
                console.log("错误码：170002,错误码描述"+resp.msg);
            } else {
                console.log('未知状态码');
            };
            IM._Notification = window.Notification || window.mozNotification || window.webkitNotification
                    || window.msNotification || window.webkitNotifications;
	        if(!!IM._Notification){
	            IM._Notification.requestPermission(function (permission) {
	                if (IM._Notification.permission !== "granted") {
	                    IM._Notification.permission = "granted";
	                }
	            });
	        }

        },

        /**
         * 初始化一些页面需要绑定的事件
         */
        initEvent : function() {

            $('#im_send_content').bind('paste', function() {
                                IM.DO_pre_replace_content();
                            });
        },

        /**
         * 初始化表情
         */
        initEmoji : function() {
            var emoji_div = $('#emoji_div');
            for (var i in emoji.show_data) {
                var c = emoji.show_data[i];
                var out = emoji.replace_unified(c[0][0]);

                var content_emoji = '<li ' +
                        'onclick="IM.DO_chooseEmoji(\''+ i + '\', \'' + c[0][0] + '\')" ' +
                        'imtype="content_emoji">' + out + '</li>';
                emoji_div.append(content_emoji);
            }

        },

        /**
         * 监控键盘
         *
         * @param event
         * @constructor
         */
        _keyCode_1 : 0,
        _keyCode_2 : 0,
        EV_keyCode : function(event) {
            IM._keyCode_1 = IM._keyCode_2;
            IM._keyCode_2 = event.keyCode;
            // 17=Ctrl 13=Enter  16=shift 50=@

            if (17 == IM._keyCode_1 && 13 == IM._keyCode_2) {
                if ('none' == $('#navbar_login').css('display')) {
                    IM.DO_sendMsg();
                }
            } else if (17 != IM._keyCode_1 && 13 == IM._keyCode_2) {
                if ('block' == $('#navbar_login').css('display')) {
                    IM.DO_login();
                }
            }else if(16 == IM._keyCode_1 && 50 == IM._keyCode_2){//chrome、火狐、opear 英文输入法
                //判断如果是群组的话才展示成员列表
                $('#im_contact_list').find('li').each(function(){
                    if($(this).attr('class').indexOf("active")> -1){
                        if($(this).attr("contact_type") == IM._contact_type_g){
                            //展示成员列表
                            var groupId = $(this).attr("contact_you");
                            if(document.getElementById("im_send_content") == document.activeElement){
                                //传入startIndex
                                var startIndex = window.getSelection().anchorOffset;
                                IM.EV_getGroupMemberList(groupId,"memberList",startIndex);
                            }
                        }
                    }
                });
            }else if(16 == IM._keyCode_1 && 229 == IM._keyCode_2){//chrome中文输入法时返回229
                setTimeout(function(){
                    var str = $("#im_send_content").text();
                    var startIndex = window.getSelection().anchorOffset;
                    if("@" == str.substring(startIndex-1,startIndex)){
	                    //判断如果是群组的话才展示成员列表
	                    $('#im_contact_list').find('li').each(function(){
	                        if($(this).attr('class').indexOf("active")> -1){
	                            if($(this).attr("contact_type") == IM._contact_type_g){
	                                //展示成员列表
	                                var groupId = $(this).attr("contact_you");
                                    if(document.getElementById("im_send_content") == document.activeElement){
                                        IM.EV_getGroupMemberList(groupId,"memberList",startIndex-1);
                                    }
	                            }
	                        }
	                    });
	                };
                },500)

            }else if(50 == IM._keyCode_2){
                if (!!navigator.userAgent.match(/mobile/i)){//判断是否移动端
                    setTimeout(function(){
                        var str = $("#im_send_content").text();
	                    if("@" == str.substring(str.length-1)){
	                        $('#im_contact_list').find('li').each(function(){
	                            if($(this).attr('class').indexOf("active")> -1){
	                                if($(this).attr("contact_type") == IM._contact_type_g){
	                                    //展示成员列表
	                                    var groupId = $(this).attr("contact_you");
	                                    if(document.getElementById("im_send_content") == document.activeElement){
	                                        IM.EV_getGroupMemberList(groupId,"memberList",'');
                                            $("#groupMemList_div").css("max-width","100%");
	                                    }
	                                }
	                            }
	                        });
	                    }
                    },200);

                }
            }else if(8 == IM._keyCode_2){//退格键
                $("#groupMemList_div").hide();
            }else if(16 == IM._keyCode_2){//火狐中文输入模式
                var userAgent = navigator.userAgent.toLowerCase();
                if(userAgent.indexOf("firefox") > -1){
                    setTimeout(function(){
	                    //传入startIndex
	                    var startIndex = window.getSelection().anchorOffset;
                        var str = $("#im_send_content").text();
	                    if("@" == str.substring(startIndex-1,startIndex)){
	                        //判断如果是群组的话才展示成员列表
			                $('#im_contact_list').find('li').each(function(){
			                    if($(this).attr('class').indexOf("active")> -1){
			                        if($(this).attr("contact_type") == IM._contact_type_g){
			                            //展示成员列表
			                            var groupId = $(this).attr("contact_you");
			                            if(document.getElementById("im_send_content") == document.activeElement){
			                                IM.EV_getGroupMemberList(groupId,"memberList",startIndex-1);
			                            }
			                        }
			                    }
			                });
	                    }
                    },200)
                }
            }

        },

        DO_login_user : function(){

            var login_url = $('.login_url').val();
            window.location.href = login_url;
        },


        DO_login : function(user_account) {
            var resp = RL_YTX.init(IM._appid);
            if (!resp) {
                alert_box_im('SDK初始化错误');
                return;
            };

            console.log("DO_login");


            IM._login(user_account);
        },

        /**
         * 正式处理登录逻辑，此方法可供断线监听回调登录使用 获取时间戳，获取SIG，调用SDK登录方法
         *
         * @param user_account
         * @param pwd 密码
         * @private
         */
        _login : function(user_account) {
            var timestamp = IM._getTimeStamp();

            var flag = false;//是否从第三方服务器获取sig
            if(flag){
            	IM._privateLogin(user_account, timestamp, function(obj) {
                    console.log('obj.sig:' + obj.sig);
                    IM.EV_login(user_account, obj.sig, timestamp);
                }, function(obj) {
                    $('#navbar_user_account').removeAttr("readonly");
                    alert_box_im("错误码_login："+obj.code+"; 错误描述："+obj.msg);
                });
            }else{
            	//仅用于本地测试，官方不推荐这种方式应用在生产环境
            	//没有服务器获取sig值时，可以使用如下代码获取sig
                //var appToken = 'd665f1caf7d4c88653d79a3b31d78f44';//使用是赋值为应用对应的appToken
                var appToken = window.appToken;//使用是赋值为应用对应的appToken
                var sig = hex_md5(IM._appid + user_account + timestamp + appToken);
            	IM.EV_login(user_account, sig, timestamp);
            }
        },

        /**
         * SIG获取 去第三方（客服）服务器获取SIG信息 并将SIG返回，传给SDK中的登录方法做登录使用
         *
         * @param user_account
         * @param timestamp -- 时间戳要与SDK登录方法中使用的时间戳一致
         * @param callback
         * @param onError
         * @private
         */
        _privateLogin : function(user_account, timestamp, callback, onError) {
            console.log("_privateLogin");
            var data = {
                "appid" : IM._appid,
                "username" : user_account,
                "timestamp" : timestamp
            };
            var url = IM._3rdServer+'genSig';
            Public.ajax({
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
        },
            //


        /**
         * 事件，登录 去SDK中请求登录
         *
         * @param user_account
         * @param sig
         * @param timestamp --
         *            时间戳要与生成SIG参数的时间戳保持一致
         * @constructor
         */
       EV_login : function(user_account, sig, timestamp) {
            console.log("EV_login");
            var loginBuilder = new RL_YTX.LoginBuilder();
            loginBuilder.setType(IM._loginType);
            loginBuilder.setUserName(user_account);

            if(1 == IM._loginType){//1是自定义账号，3是voip账号
                loginBuilder.setSig(sig);
            }else{
                loginBuilder.setPwd(pwd);
            }

            loginBuilder.setTimestamp(timestamp);

            RL_YTX.login(loginBuilder, function(obj) {
                console.log("EV_login succ...");
                IM._user_account = user_account;
                IM._username = user_account;

                // 注册PUSH监听
                IM._onMsgReceiveListener = RL_YTX.onMsgReceiveListener(
                    function(obj) {
                        IM.EV_onMsgReceiveListener(obj);
                    });
                // 注册客服消息监听
                IM._onDeskMsgReceiveListener = RL_YTX.onDeskMsgReceiveListener(
                    function(obj) {
                        IM.EV_onMsgReceiveListener(obj);
                    });
                // 注册群组通知事件监听
                IM._noticeReceiveListener = RL_YTX.onNoticeReceiveListener(
                    function(obj) {
                        IM.EV_noticeReceiveListener(obj);
                    });
                // 服务器连接状态变更时的监听
                IM._onConnectStateChangeLisenter = RL_YTX.onConnectStateChangeLisenter(function(obj) {
                    // obj.code;//变更状态 1 断开连接 2 重练中 3 重练成功 4 被踢下线 5
                    // 断线需要人工重连
                    if (1 == obj.code) {
                        console.log('onConnectStateChangeLisenter obj.code:'
                            + obj.msg);
                    } else if (2 == obj.code) {
                        IM.HTML_showAlert('alert-warning',
                            '网络状况不佳，正在试图重连服务器', 10 * 60 * 1000);
                    } else if (3 == obj.code) {
                        IM.HTML_showAlert('alert-success', '连接成功');
                    } else if (4 == obj.code) {
//                        IM.DO_logout();
                        alert_box_im(obj.msg);
                    } else if (5 == obj.code) {
                        IM.HTML_showAlert('alert-warning',
                            '网络状况不佳，正在试图重连服务器');
                        IM._login(IM._user_account);
                    } else {
                        console.log('onConnectStateChangeLisenter obj.code:'
                            + obj.msg);
                    }
                });
                /*音视频呼叫监听
                 obj.callId;//唯一消息标识  必有
                 obj.caller; //主叫号码  必有
                 obj.called; //被叫无值  必有
                 obj.callType;//0 音频 1 视频 2落地电话
                 obj.state;//1 对方振铃 2 呼叫中 3 被叫接受 4 呼叫失败 5 结束通话 6 呼叫到达
                 obj.reason//拒绝或取消的原因
                 obj.code//当前浏览器是否支持音视频功能
                 */
                IM._onCallMsgListener = RL_YTX.onCallMsgListener(
                    function(obj){
                        IM.EV_onCallMsgListener(obj);
                    });

                IM._onMsgNotifyReceiveListener = RL_YTX.onMsgNotifyReceiveListener(function(obj){
                    if(obj.msgType == 21 ){//阅后即焚：接收方已删除阅后即焚消息
                        console.log("接收方已删除阅后即焚消息obj.msgId="+obj.msgId);
                        var id = obj.sender+"_"+obj.msgId;
                        $(document.getElementById(id)).remove();
                    }
                });
                $('#navbar_user_account').removeAttr("readonly");

                $('#navbar_login').hide();
                $('#navbar_login_show').show();
                IM.EV_getMyInfo();
                IM.HTML_LJ_none();

                // 登录后拉取群组列表
                IM.EV_getGroupList();

                // 登录后拉取未读过的消息
                if (IM._local_historyver <= parseInt(obj.historyver)
                    && parseInt(obj.historyver) < parseInt(obj.version)) {
                    IM._local_historyver = parseInt(obj.historyver)
                    IM.EV_syncMsg(parseInt(obj.historyver) + 1, obj.version);
                }

                // 添加客服号到列表中
                IM.HTML_addContactToList(IM._onUnitAccount, IM._onUnitAccount,
                    IM._contact_type_m, false, false, false, null, null,
                    null);
            }, function(obj) {
                $('#navbar_user_account').removeAttr("readonly");
                // alert("错误码： " + obj.code+"; 错误描述："+obj.msg);
                alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
            });




        },
        /**
         * 事件，登出
         *
         * @constructor
         */
        EV_logout : function() {
            console.log("EV_logout");
            IM.DO_logout();
            RL_YTX.logout(function() {
                        console.log("EV_logout succ...");
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 登出
         *
         * @constructor
         */
        DO_logout : function() {
            // 销毁PUSH监听
            IM._onMsgReceiveListener = null;
            // 注册客服消息监听
            IM._onDeskMsgReceiveListener = null;
            // 销毁注册群组通知事件监听
            IM._noticeReceiveListener = null;
            // 服务器连接状态变更时的监听
            IM._onConnectStateChangeLisenter = null;
            //呼叫监听
            IM._onCallMsgListener = null;
            //阅后即焚监听
            IM._onMsgNotifyReceiveListener = null;
            $("#fireMessage").removeClass("active");
            // 清理左侧数据
            $('#im_contact_list').empty();
            // 清理右侧数据
            $('#im_content_list').empty();

            // 隐藏图片层
            IM.HTML_pop_photo_hide();

            // 隐藏拍照层
            IM.HTML_pop_takePicture_hide();

            //隐藏音视频呼叫遮罩层
            $("#pop_videoView").hide();

            //隐藏录音遮罩层，停掉录音流
            $("#pop_recorder").hide();

            // 隐藏群组详情页面
            IM.HTML_pop_hide();

            // 隐藏表情框
            $('#emoji_div').hide();

            // 隐藏提示框
            IM.HTML_closeAlert('all');

            // 联系人列表切换到沟通
            IM.DO_choose_contact_type('C');

            $('#navbar_login').show();
            $('#navbar_login_show').hide();
            IM.HTML_LJ_block('black');
        },

        /**
         * 事件，push消息的监听器，被动接收信息
         *
         * @param obj
         * @constructor
         */
        EV_onMsgReceiveListener : function(obj) {
            console.log('Receive message sender:[' + obj.msgSender
                    + ']...msgId:[' + obj.msgId + ']...content['
                    + obj.msgContent + ']');

            IM.DO_push_createMsgDiv(obj);

            // 播放铃声前，查看是否是群组，如果不是直接播放，如果是查看自定义提醒类型，根据类型判断是否播放声音
            var b_isGroupMsg = ('g' == obj.msgReceiver.substr(0, 1));
            if (b_isGroupMsg) {
                // 1提醒，2不提醒
                var isNotice = $(document.getElementById('im_contact_' + obj.msgReceiver)).attr('im_isnotice');
                if (2 != isNotice) {
                    document.getElementById('im_ring').play();
                }
            } else {
                document.getElementById('im_ring').play();
            }
        },

        /**
         * 事件，notice群组通知消息的监听器，被动接收消息
         *
         * @param obj
         * @constructor
         */
        EV_noticeReceiveListener : function(obj) {
            console.log('notice message groupId:[' + obj.groupId
                    + ']...auditType[' + obj.auditType + ']...msgId:['
                    + obj.msgId + ']...');
            IM.DO_notice_createMsgDiv(obj);
            // 播放铃声
            document.getElementById('im_ring').play();

        },
        EV_onCallMsgListener : function(obj){
            console.log("-------obj.callId = "+obj.callId);
            console.log("-------obj.caller = "+obj.caller);
            console.log("-------obj.called = "+obj.called);
            console.log("-------obj.callType = "+obj.callType);
            console.log("-------obj.state = "+obj.state);
            console.log("-------obj.reason = "+obj.reason);
            console.log("-------obj.code = "+obj.code);
            var noticeMsg = '';//桌面提醒消息
            if(obj.callType == 1){//视频
                if(200 == obj.code){
                    if(obj.state == 1){//收到振铃消息
                        //本地播放振铃
                        document.getElementById("voipCallRing").play();
                    }else if(obj.state == 2){//呼叫中

                    }else if(obj.state == 3){//被叫接受
                        document.getElementById("voipCallRing").pause();
                        $("#cancelVoipCall").text("结束");
                        noticeMsg = "[接收视频通话]";
                    }else if(obj.state == 4){//呼叫失败 对主叫设定：自动取消，对方拒绝或者忙
                        $("#pop_videoView").hide();
                        document.getElementById("voipCallRing").pause();
                        noticeMsg = "[视频通话失败]";
                    }else if(obj.state == 5){//结束通话  或者主叫取消（对被叫而言）
                        document.getElementById("voipCallRing").pause();
                        $("#pop_videoView").hide();
                        noticeMsg = "[视频通话结束]";
                    }else if(obj.state == 6){//呼叫到达
                        //添加左侧联系人
                        var im_contact = $('#im_contact_list').find('li[contact_type="'
                            + IM._contact_type_c + '"][contact_you="' + obj.caller
                            + '"]');
                        if (im_contact.length <= 0) {
                            IM.HTML_clean_im_contact_list();

                            IM.HTML_addContactToList(obj.caller, obj.caller,
                                            IM._contact_type_c, true, true, false, null,
                                            null, null);

                            IM.HTML_clean_im_content_list(obj.caller);
                        };
                        $("#videoView").show();
                        $("#voiceCallDiv_audio").hide();
                        IM.HTML_videoView(obj.callId,obj.caller,obj.called,1);
                        $("#cancelVoipCall").hide();
                        $("#acceptVoipCall").show();
                        $("#refuseVoipCall").show();
                        //本地播放振铃
                        document.getElementById("voipCallRing").play();
                        noticeMsg = "[视频呼叫]";
                    }
                }else{
                    var str = '<pre>请求视频通话，请使用其他终端处理</pre>';
                    IM.HTML_pushCall_addHTML( obj.caller, obj.callId, str);
                }
            }else if(obj.callType == 0){//音频
                if(200 == obj.code){
                    if(obj.state == 1){//收到振铃消息
                        //本地播放振铃
                        document.getElementById("voipCallRing").play();
                    }else if(obj.state == 2){//呼叫中

                    }else if(obj.state == 3){//被叫接受
                        document.getElementById("voipCallRing").pause();
                        $("#cancelVoiceCall").text("结束");
                        noticeMsg = "[接收语音通话]";
                    }else if(obj.state == 4){//呼叫失败 是对主叫设定：主动取消，对方拒绝或者忙
                        $("#pop_videoView").hide();
                        document.getElementById("voipCallRing").pause();
                        noticeMsg = "[语音通话失败]";
                    }else if(obj.state == 5){//结束通话  或者主叫取消（对被叫而言）
                        document.getElementById("voipCallRing").pause();
                        $("#pop_videoView").hide();
                        noticeMsg = "[语音通话结束]";
                    }else if(obj.state == 6){//呼叫到达
                        //添加左侧联系人
                        var im_contact = $('#im_contact_list').find('li[contact_type="'
                            + IM._contact_type_c + '"][contact_you="' + obj.caller
                            + '"]');
                        if (im_contact.length <= 0) {
                            IM.HTML_clean_im_contact_list();

                            IM.HTML_addContactToList(obj.caller, obj.caller,
                                            IM._contact_type_c, true, true, false, null,
                                            null, null);

                            IM.HTML_clean_im_content_list(obj.caller);
                        };
                        $("#videoView").hide();
                        $("#voiceCallDiv_audio").show();
                        IM.HTML_videoView(obj.callId,obj.caller,obj.called,0);
                        $("#cancelVoiceCall").hide();
                        $("#acceptVoiceCall").show();
                        $("#refuseVoiceCall").show();
                        //本地播放振铃
                        document.getElementById("voipCallRing").play();
                        noticeMsg = "[语音呼叫]";
                    };
                }else{
                    var str = '<pre>请求语音通话，请使用其他终端处理</pre>';
                    IM.HTML_pushCall_addHTML( obj.caller, obj.callId, str);
                }
            }

            //桌面提醒通知
            if(!!noticeMsg){
                IM.DO_deskNotice(obj.caller,'',noticeMsg,'',false,true);
            }
        },

        /**
         * 事件，发送消息
         *
         * @param msgid
         * @param text
         * @param receiver
         * @param isresend
         * @constructor
         */
        EV_sendTextMsg : function(oldMsgid, text, receiver,isresend) {
            console.log('write history log');
            console.log('send Text message: receiver:[' + receiver
                    + ']...connent[' + text + ']...');

            var obj = new RL_YTX.MsgBuilder();
            obj.setText(text);
            obj.setType(1);
            obj.setReceiver(receiver);
            if($("#fireMessage").attr("class").indexOf("active")>-1){//domain
                obj.setDomain("fireMessage");
            };

            send_to_server_bysun(IM._user_account,receiver,oldMsgid,text ,1001);


            var msgId = RL_YTX.sendMsg(obj, function(obj) {
                        setTimeout(function() {
                                    $(document.getElementById(receiver + '_' + obj.msgClientNo)).find('span[imtype="resend"]').css('display', 'none');
                                    console.log('send Text message succ');
                                    if(isresend){
                                        var msg = $(document.getElementById(receiver + '_' + obj.msgClientNo));
                                        $('#im_content_list').append(msg.prop("outerHTML"));
                                        msg.remove();// 删掉原来的展示
                                    };
                                }, 300)
                    }, function(obj) {
                        setTimeout(function() {
                            var msgf = $(document.getElementById(receiver + '_' + obj.msgClientNo));
                            if(msgf.find('pre [msgtype="resendMsg"]').length == 0){
                                var resendStr = '<pre msgtype="resendMsg" style="display:none;">'+text+'</pre>'
                                msgf.append(resendStr);
                            }

                            msgf.find('span[imtype="resend"]').css('display', 'block');
                            alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                            }, 300)
                    });
            $(document.getElementById(receiver + '_' + oldMsgid)).attr("id",receiver + "_" + msgId);
        },

        /**
         * 事件，重发消息
         *
         * @param id
         *            右侧展示模块元素的id
         *
         * @constructor
         */
        EV_resendMsg : function(obj) {
            var msg = $(obj.parentElement);
            // 消息类型1:文本消息 2：语音消息 3：视频消息 4：图片消息 5：位置消息 6：文件
            var msgtype = msg.attr('im_msgtype');
            var receiver = msg.attr('content_you');
            var oldMsgid = msg.attr('id').substring(msg.attr('id').indexOf("_")+1);

            if (1 == msgtype) {// 文本消息
                msg.find('span[imtype="resend"]').css('display', 'none');
                var text = msg.find('pre[msgtype="resendMsg"]').html();
                console.log('resend message: text[' + text + ']...receiver:['
                        + receiver + ']');

                if (IM._contact_type_m == contact_type) {
                    IM.EV_sendMcmMsg(oldMsgid, text, content_you,true);
                }else{
                    IM.EV_sendTextMsg(oldMsgid, text, receiver,true);
                }

            } else if (4 == msgtype || 6 == msgtype) {
                // 查找当前选中的contact_type值 1、IM上传 2、MCM上传
                var contact_type = msg.attr('content_type');
                var oFile = msg.find('input[imtype="msg_attach_resend"]')[0];
                if (!!oFile) {
                    oFile = oFile.files[0];
                    console.log('resend Attach message: msgtype[' + msgtype
                            + ']...receiver:[' + receiver + ']');
                    if (IM._contact_type_m == contact_type) {
                        IM.EV_sendToDeskAttachMsg(oldMsgid, oFile, msgtype,
                                receiver,true);
                    } else {
                        IM.EV_sendAttachMsg(oldMsgid, oFile, msgtype, receiver,true);
                    }
                } else {
                    oFile = msg.find("object").val();
                    console.log('resend Attach message: msgtype['+ msgtype + ']...receiver:[' + receiver+ ']');
                    if (IM._contact_type_m == contact_type) {
                        IM.EV_sendToDeskAttachMsg(oldMsgid, oFile,
                                msgtype, receiver,true);
                    } else {
                        IM.EV_sendAttachMsg(oldMsgid, oFile,
                                msgtype, receiver,true);
                    };
                };

            }else if(2 == msgtype){//语音

                var oFile = msg.find("object").val();
                if (IM._contact_type_m == contact_type) {
                    IM.EV_sendToDeskAttachMsg(oldMsgid, oFile,
                            msgtype, receiver,true);
                } else {
                    IM.EV_sendAttachMsg(oldMsgid, oFile,
                            msgtype, receiver,true);
                };
            } else {
                console.log('暂时不支持附件类型消息重发');
            }
        },

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
        EV_sendAttachMsg : function(oldMsgid, file, type, receiver,isresend) {
            console.log('send Attach message: type[' + type + ']...receiver:['+ receiver + ']'+'fileName:['+file.fileName+']');
            var obj = new RL_YTX.MsgBuilder();
            console.info(obj);
            console.info(file);
            console.info(type);
            console.info(receiver);
            obj.setFile(file);
            obj.setType(type);
            obj.setReceiver(receiver);
            if($("#fireMessage").attr("class").indexOf("active")>-1){//domain
                obj.setDomain("fireMessage");
            };
            var oldMsg = $(document.getElementById(receiver + '_' + oldMsgid));
            oldMsg.attr('msg', 'msg');
            oldMsg.css('display', 'block');
            if(4 == type){
                oldMsg.attr('im_carousel', 'real');
                oldMsg.attr('im_msgtype', '4');
            }

            $('#im_content_list').scrollTop($('#im_content_list')[0].scrollHeight);

            var msgid = RL_YTX.sendMsg(obj, function(obj) {
                        setTimeout(function() {
                                    var id = receiver + "_" + obj.msgClientNo;
                                    var msg = $(document.getElementById(id));
                                    msg.find('span[imtype="resend"]').css(
                                            'display', 'none');
                                    msg.find('div[class="bar"]').parent().css(
                                            'display', 'none');
                                    msg.find('p[imtype="msg_attach"]').css(
                                            'display', 'block');
                                    console.log('send Attach message succ');
                                    if(isresend){
                                        $('#im_content_list').append(msg.prop("outerHTML"));
                                        msg.remove();// 删掉原来的展示
                                    }
                                    var d =  '<img imtype="msg_attach_src" src="'+obj.fileUrl+'" style="max-width:130px; max-height:200px;" onclick="open_img(this)">';

                                    send_to_server_bysun(IM._user_account,receiver,oldMsgid,d ,type);


                                }, 100)
                    }, function(obj) {// 失败
                        setTimeout(function() {
                                    var msg = $(document.getElementById(receiver + "_" + obj.msgClientNo));
                                    msg.find('span[imtype="resend"]').css(
                                            'display', '');
                                    msg.find('div[class="bar"]').parent().css(
                                            'display', 'none');
                                    msg.find('p[imtype="msg_attach"]').css(
                                            'display', '');
                                    alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                                }, 100)
                    }, function(sended, total, msgId) {// 进度条
                        setTimeout(function() {
                                    var msg = $(document.getElementById(receiver + "_" + msgId));
                                    console.log('send Attach message progress:'
                                            + (sended / total * 100 + '%'));
                                    // sended;//已发送字节数
                                    // total;//总字节数
                                    if (sended < total) {
                                        msg.find('div[class="bar"]').css(
                                                'width',
                                                (sended / total * 100 + '%'));
                                    } else {
                                        msg.find('div[class="bar"]').parent()
                                                .css('display', 'none');
                                        msg.find('p[imtype="msg_attach"]')
                                                .css('display', 'block');
                                    };
                                }, 100)
                    });
            oldMsg.attr("id", receiver + '_' + msgid);
            if(file instanceof Blob){
	            oldMsg.find("object").val(file);
            }
        },

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
        EV_sendToDeskAttachMsg : function(oldMsgid, file, type, receiver,isresend) {
            console.log('send Attach message: type[' + type + ']...receiver:['
                    + receiver + ']');
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
            $('#im_content_list').scrollTop($('#im_content_list')[0].scrollHeight);
            var msgid = RL_YTX.sendToDeskMessage(obj, function(obj) {// 成功
                        setTimeout(function() {
                                    var msg = $(document.getElementById(receiver + "_" + obj.msgClientNo));
                                    msg.find('span[imtype="resend"]').css(
                                            'display', 'none');
                                    msg.find('div[class="bar"]').parent().css(
                                            'display', 'none');
                                    msg.find('p[imtype="msg_attach"]').css(
                                            'display', 'block');
                                    msg.attr('msg', 'msg');
                                    console.log('send Attach message succ');
                                    if(isresend){
                                        $('#im_content_list').append(msg.prop("outerHTML"));
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
                                    msg.find('p[imtype="msg_attach"]').css(
                                            'display', 'block');
                                    alert_box_im("错误码：" + obj.code+"; 错误描述："+obj.msg);
                                }, 100);
                    }, function(sended, total, msgId) {// 进度条
                        setTimeout(function() {
                                    var msg = $(document.getElementById(receiver + "_" + msgId));
                                    console.log('send Attach message progress:'
                                            + (sended / total * 100 + '%'));
                                    // sended;//已发送字节数
                                    // total;//总字节数
                                    if (sended < total) {
                                        msg.find('div[class="bar"]').css(
                                                'width',
                                                (sended / total * 100 + '%'));
                                    } else {
                                        msg.find('div[class="bar"]').parent()
                                                .css('display', 'none');
                                        msg.find('p[imtype="msg_attach"]')
                                                .css('display', 'block');
                                    }
                                }, 100);
                    });
            oldMsg.attr("id", receiver + '_' + msgid);
        },

        /**
         * 事件，客服开始咨询
         *
         * @param receiver --
         *            客服号
         * @constructor
         */
        EV_startMcmMsg : function(receiver) {
            console.log('start MCM message...');
            var obj = new RL_YTX.DeskMessageStartBuilder();
            obj.setOsUnityAccount(receiver);
            obj.setUserData('');

            RL_YTX.startConsultationWithAgent(obj, function() {
                        console.log('start MCM message succ...');
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 事件，客服停止咨询
         *
         * @param receiver --
         *            客服号
         * @constructor
         */
        EV_stopMcmMsg : function(receiver) {
            console.log('stop MCM message...');
            var obj = new RL_YTX.DeskMessageStopBuilder();
            obj.setOsUnityAccount(receiver);
            obj.setUserData('');

            RL_YTX.finishConsultationWithAgent(obj, function() {
                        console.log('stop MCM message succ...');
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 事件，客服发送消息
         *
         * @param msgid
         * @param text
         * @param receiver --
         *            客服号
         * @constructor
         */
        EV_sendMcmMsg : function(oldMsgid, text, receiver,isresend) {
            console.log('send MCM message...');
            var obj = new RL_YTX.DeskMessageBuilder();
            obj.setContent(text);
            obj.setUserData();
            obj.setType(1);
            obj.setOsUnityAccount(receiver);
            if($("#fireMessage").attr("class").indexOf("active")>-1){//domain
                obj.setDomain("fireMessage");
            };

            var msgid = RL_YTX.sendToDeskMessage(obj, function(obj) {
                        var msg = $(document.getElementById(receiver + "_" + obj.msgClientNo));
                        msg.find('span[imtype="resend"]').css('display','none');
                        console.log('send MCM message succ...');
                        if(isresend){
                            $('#im_content_list').append(msg.prop("outerHTML"));
                            msg.remove();// 删掉原来的展示
                        };

                    }, function(obj) {
                        var msgf = $(document.getElementById(receiver + '_' + obj.msgClientNo));
                        if(msgf.find('pre [msgtype="resendMsg"]').length == 0){
                            var resendStr = '<pre msgtype="resendMsg" style="display:none;">'+text+'</pre>'
                            msgf.append(resendStr);
                        }
                        msgf.find('span[imtype="resend"]').css('display','block');
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
            $(document.getElementById(receiver + "_" + oldMsgid)).attr("id",receiver + "_" + msgid);
        },

        /**
         * 事件，主动拉取消息
         *
         * @param sv
         * @param ev
         * @constructor
         */
        EV_syncMsg : function(sv, ev) {
            var obj = new RL_YTX.SyncMsgBuilder();
            obj.setSVersion(sv);
            obj.setEVersion(ev);

            RL_YTX.syncMsg(obj, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 事件，获取登录者个人信息
         *
         * @constructor
         */
        EV_getMyInfo : function() {
            RL_YTX.getMyInfo(function(obj) {
                if (!!obj && !!obj.nickName) {
                    IM._username = obj.nickName;
                };
            user_logo = '';
            receiver_logo = '';
            //content_you = $('.contact_you').html();
            content_you = decodeURIComponent(getQueryString('contact_you'));console.log(content_you);
            $.post(ImApiUrl+"?ctl=UserApi&met=getUserInfo&typ=json",{"k":k,"u":u} ,function(data) {
                  if(data.status == 200)
                  {
                    console.info(data);
                    user_logo = data.data.user_avatar;
                  }
            });
            show_msg_list(content_you);
            $.post(ImApiUrl+"?ctl=UserApi&met=getGuestInfo&typ=json",{"user_name":content_you,"k":k,"u":u} ,function(data) {
                if(data.status == 200)
                {
                    console.info(data);
                    receiver_logo = data.data.user_avatar;
                    receiver_name = data.data.nickname ? data.data.nickname : content_you;
                     $('#navbar_login_show')
                        .html('<span style="float: left;display: block;font-size: 20px;font-weight: 200;padding-top: 10px;padding-bottom: 10px;text-shadow: 0px 0px 0px;color:#eee" >联系人:</span>'
                                + '<a onclick="IM.DO_userMenu()" style="text-decoration: none;cursor:pointer;float: left;font-size: 20px;font-weight: 200;max-width:130px;'
                                + 'padding-top: 10px;padding-right: 20px;padding-bottom: 10px;padding-left: 20px;text-shadow: 0px 0px 0px;color:#eee;word-break:keep-all;text-overflow:ellipsis;overflow: hidden;" >'
                                + receiver_name
                                + '</a>'
                                /*+ '<span onclick="IM.EV_logout()" style="cursor:pointer;float: right;font-size: 20px;font-weight: 200;'
                                + 'padding-top: 10px;padding-bottom: 10px;text-shadow: 0px 0px 0px;color:#eeeeee">退出</span>'*/);
                }
                else
                {
                    alert_box_im(data.msg);
                    //history.go(-1);
                }
            });
               /*
               $('#navbar_login_show')
                        .html('<span style="float: left;display: block;font-size: 20px;font-weight: 200;padding-top: 10px;padding-bottom: 10px;text-shadow: 0px 0px 0px;color:#eee" >您好:</span>'
                                + '<a onclick="IM.DO_userMenu()" style="text-decoration: none;cursor:pointer;float: left;font-size: 20px;font-weight: 200;max-width:130px;'
                                + 'padding-top: 10px;padding-right: 20px;padding-bottom: 10px;padding-left: 20px;text-shadow: 0px 0px 0px;color:#eee;word-break:keep-all;text-overflow:ellipsis;overflow: hidden;" >'
                                + receiver_name
                                + '</a>'
                                + '<span onclick="IM.EV_logout()" style="cursor:pointer;float: right;font-size: 20px;font-weight: 200;'
                                + 'padding-top: 10px;padding-bottom: 10px;text-shadow: 0px 0px 0px;color:#eeeeee">退出</span>');
                        */

            }, function(obj) {
                if (520015 != obj.code) {
                    alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                }
            });
        },

        /**
         * 事件，创建群组
         *
         * @param groupName
         * @param permission
         * @constructor
         */
        EV_createGroup : function(groupName, permission) {
            $("#createGroup_bt").attr("disabled","disabled");
            //如果有类型则传值
            var declare = $("#createDeclare").val();
            if(!!declare){
                var regx1 = /^[\\x00-\\x7F\a-zA-Z\u4e00-\u9fa5、，。！；《》【】”“’‘：:,.!;_\s-]{0,128}$/;//只含有汉字、数字、字母、下划线，下划线位置不限
                if(regx1.exec(declare) == null){
                    alert_box_im("群组说明只允许中英文数字和中文符号、，。！；《》【】”“’‘：及英文符号:,.!;和@_-，长度不超过128");
                    $("#createGroup_bt").removeAttr("disabled");
                    return;
                };

                if(declare.substring(0,1) == "g" || declare.substring(0,1) == "G"){
                    alert_box_im("群组说明不能以g或G开头");
                    $("#createGroup_bt").removeAttr("disabled");
                    return;
                }
            }
            //如果群组说明不是空，校验说明的合法性

            var groupType = $(document).find('input[name="groupType"]:checked').val();

            //如果有地区信息则传值
            var province = $("#province").find("option:selected").text();
            var city = $("#city").find("option:selected").text()

            console.log('create group...groupName[' + groupName
                    + '] permission[' + permission + ']');

            var obj = new RL_YTX.CreateGroupBuilder();
            obj.setGroupName(groupName);
            obj.setPermission(permission);
            if(!!groupType){
                obj.setGroupType(groupType);
            }
            if(!!province&&(province != "--")){
                obj.setProvince(province);
            }
            if(!!city){
                obj.setCity(city);
            }
            if(!!declare){
                obj.setDeclared(declare);
            }
            // target参数 1讨论组 2 群组
            if (permission == 4) {
                obj.setTarget(1);
                // 校验邀请参数是否符合要求
                var memberSts = $("#pop_invite_area").val();
                var memberArr = memberSts.split(",");
                if (memberArr.length > 50) {
                    alert_box_im("邀请用户过多！");
                    $("#createGroup_bt").removeAttr("disabled");
                    return;
                }
                for (var i in memberArr) {
                    if (i == IM._user_account) {
                        $("#createGroup_bt").removeAttr("disabled");
                        return;
                    };
                    if (!IM.DO_checkContact(memberArr[i])) {
                        $("#createGroup_bt").removeAttr("disabled");
                        return;
                    }
                };
            } else {
                obj.setTarget(2);
            };

            RL_YTX.createGroup(obj, function(obj) {
                        var groupId = obj.data;

                        console.log('create group succ... groupId[' + groupId
                                + ']');

                        if (permission == 4) {// 如果是讨论组，需要在讨论组创建成功之后随即添加账号
                            // 左侧名称列表
                            IM.HTML_addContactToList(groupId, groupName,
                                    IM._contact_type_g, true, true, false,
                                    IM._user_account, 1, 1);
                            IM.EV_inviteGroupMember(groupId, permission, true);
                            $('#im_add').find('input[imtype="im_add_group"]').val('');
                            IM.HTML_pop_hide();
                        } else {
                            // 左侧名称列表
                            IM.HTML_addContactToList(groupId, groupName,
                                    IM._contact_type_g, true, true, false,
                                    IM._user_account, 1, 2);
                            IM.HTML_pop_hide();
                        }
                    }, function(obj) {
                        $("#createGroup_bt").removeAttr("disabled");
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });

        },

        /**
         * 解散群组
         *
         * @param groupId
         * @constructor
         */
        EV_dismissGroup : function(groupId) {
            console.log('dismiss Group...');
            var obj = new RL_YTX.DismissGroupBuilder();
            obj.setGroupId(groupId);

            RL_YTX.dismissGroup(obj, function() {
                        console.log('dismiss Group SUCC...');
                        // 将群组从列表中移除
                        IM.HTML_remove_contact(groupId);
                        // 隐藏群组详情页面
                        IM.HTML_pop_hide();
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 退出群组
         *
         * @param groupId
         * @constructor
         */
        EV_quitGroup : function(groupId) {
            console.log('quit Group...');
            var obj = new RL_YTX.QuitGroupBuilder();
            obj.setGroupId(groupId);

            RL_YTX.quitGroup(obj, function() {
                        console.log('quit Group SUCC...');
                        // 将群组从列表中移除
                        IM.HTML_remove_contact(groupId);
                        // 隐藏群组详情页面
                        IM.HTML_pop_hide();
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 事件，获取群组详情
         *
         * @param groupId
         * @param target
         * @constructor
         */
        EV_getGroupDetail : function(groupId, isowner, target) {
            console.log('get Group Detail...');
            var objBuilder = new RL_YTX.GetGroupDetailBuilder();
            objBuilder.setGroupId(groupId);

            RL_YTX.getGroupDetail(objBuilder, function(obj) {
                        console.log('get Group Detail SUCC...');
                        var getTarget = obj.target;
                        if (target == null) {// 推送的target参数为空
                            // 构建页面
                            IM.DO_pop_show(groupId, isowner, getTarget);
                            // 调用SDK方法获取数据
                            // 获取群组详情
                            IM.EV_getGroupDetail(groupId, isowner, getTarget);
                        } else {
                            // 更新pop弹出框属性
                            var im_target = $('#pop').find('div[im_isowner]');
                            im_target.attr('im_target', getTarget);
                            // 展示群组的详细信息
                            IM.DO_pop_show_help_GroupDetail(obj, groupId,
                                    getTarget);
                            // 获取成员列表并展示
                            IM.EV_getGroupMemberList(groupId,getTarget,null);
                        }

                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 事件，获取群组列表
         *
         * @constructor
         */
        EV_getGroupList : function() {
            var obj = new RL_YTX.GetGroupListBuilder();
            obj.setPageSize(-1);
            obj.setTarget(125);// target传125是一起查， 1是讨论组 2是群组
            RL_YTX.getGroupList(obj, function(obj) {
                        for (var i in obj) {
                            var groupId = obj[i].groupId;
                            var groupName = obj[i].name;
                            var owner = obj[i].owner;
                            var isNotice = obj[i].isNotice;
                            var target = obj[i].target;
                            IM.HTML_addContactToList(groupId, groupName,
                                    IM._contact_type_g, false, false, true,
                                    owner, isNotice, target);
                        }
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 事件，获取群组成员列表
         *
         * @param groupId
         * @param isowner
         * @param target
         *            1 讨论组 2 群组
         * @constructor
         */
        EV_getGroupMemberList : function(groupId,target,startIndex) {
            console.log('get Group Member List...');
            var obj = new RL_YTX.GetGroupMemberListBuilder();
            obj.setGroupId(groupId);
            obj.setPageSize(-1);

            RL_YTX.getGroupMemberList(obj, function(obj) {
                        console.log('get Group Member List SUCC...');
                        if("memberList" == target){
                            console.log("展示群组成员列表");
                            IM.HTML_memberList(obj,startIndex);
                        }else{
                            IM.DO_pop_show_help_GroupMemberList(obj, groupId,target);
                        }
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 更新群组信息
         *
         * @param groupId
         * @constructor
         */
        EV_updateGroupInfo : function(groupId) {
            console.log("update groupInfo,groupId:[" + groupId + "]");
            var obj = $('#pop').find('span[imtype="im_pop_group_declared"]');
            var declaredObj = obj.children();
            var declared = declaredObj.val();

            obj = $('#pop').find('div[imtype="im_pop_group_name"]');
            var nameObj = obj.children();
            var groupName = nameObj.val();

            var builder = new RL_YTX.ModifyGroupBuilder(groupId, groupName,
                    null, null, null, null, declared);
            RL_YTX.modifyGroup(builder, function() {
                        console.log("update group info suc");
                        IM.HTML_addContactToList(groupId, groupName,
                                IM._contact_type_g, false, true, true, null,
                                null, null);
                        IM.HTML_pop_hide();
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });

        },

        /**
         * 更新群组个性化设置
         *
         * @param groupId
         * @param isNotice
         * @constructor
         */
        EV_groupPersonalization : function(groupId, isNotice) {
            console.log("set group notice,groupId:[" + groupId + "],isNotice["
                    + isNotice + "]");
            var builder = new RL_YTX.SetGroupMessageRuleBuilder(groupId,
                    isNotice);
            RL_YTX.setGroupMessageRule(builder, function() {
                console.log("set groupNotice suc");
                // 切换btn样式
                if (isNotice == 2) {
                    str = '<a href="javascript:void(0);" class="btn btn-primary" style="margin-left:10px;" >开启</a>'
                            + '<a href="javascript:void(0);" class="btn" style="margin-left:10px;" onclick="IM.EV_groupPersonalization(\''
                            + groupId + '\',1)">关闭</a>';
                } else {
                    str = '<a href="javascript:void(0);" class="btn" style="margin-right:10px;" style="margin-left:10px;" onclick="IM.EV_groupPersonalization(\''
                            + groupId
                            + '\',2)">开启</a>'
                            + '<a href="javascript:void(0);" class="btn btn-primary" >关闭</a>';
                }
                $('#pop').find('span[imtype="im_pop_group_notice"]').html(str);

                // 修改左侧联系人列表attr值
                $(document.getElementById('im_contact_' + groupId)).attr('im_isnotice', isNotice);
            }, function(obj) {
                alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
            });
        },

        /**
         * 邀请成员加入群组
         *
         * @param groupId
         * @param permission
         * @constructor
         */
        EV_inviteGroupMember : function(groupId, permission, isowner) {
            var memberSts = $("#pop_invite_area").val();
            var memberArr = memberSts.split(",");
            if (permission != 4) {

                if (memberArr.length > 50) {
                    alert_box_im("邀请用户过多！");
                    return;
                }
                for (var i in memberArr) {
                    if (!IM.DO_checkContact(memberArr[i])) {
                        return;
                    }
                }
            };
            var confirm = '';
            var target = '';
            if (permission == 1) {// 是否需要邀请者确认 可选 1 不需要 2 需要 默认为2
                confirm = 1;
            } else {
                confirm = 2;
            };
            if (permission == 4) {
                confirm = 1;
                target = 1;
            } else {
                target = 2;
            };
            var builder = new RL_YTX.InviteJoinGroupBuilder(groupId, null,
                    memberArr, confirm);
            RL_YTX.inviteJoinGroup(builder, function() {
                        IM.HTML_hideInviteArea();
                        $("#pop_invite_area").val("");
                        if (confirm == 1) {
                            for (var i in memberArr) {
                                IM.HTML_popAddMember(groupId, memberArr[i],
                                        memberArr[i], isowner, target);
                            }
                        }
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    })
        },

        /**
         * 事件，用户申请加入确认操作
         *
         * @param groupId
         *            群组id 必选
         * @param memberId
         *            成员id 必选
         * @param confirm
         *            是否同意 必选 1 不同意 2同意
         * @constructor
         */
        EV_confirmJoinGroup : function(you_sender, version, groupId, memberId,
                confirm) {
            console.log('confirm join group...groupId[' + groupId
                    + '] memberId[' + memberId + '] confirm[' + confirm + ']');
            var obj = new RL_YTX.ConfirmJoinGroupBuilder();
            obj.setGroupId(groupId);
            obj.setMemberId(memberId);
            obj.setConfirm(confirm);

            RL_YTX.confirmJoinGroup(obj, function() {
                        var str = '';
                        if (1 == confirm)
                            str = '{已拒绝}';
                        if (2 == confirm)
                            str = '{已同意}';
                        $(document.getElementById(you_sender + '_' + version)).find('span[imtype="notice"]').html(str);
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 事件，管理员是否同意加入群组
         *
         * @param invitor
         *            邀请者 必选
         * @param groupId
         *            群组id 可选
         * @param confirm
         *            是否同意 1 不同意 2同意
         * @constructor
         */
        EV_confirmInviteJoinGroup : function(you_sender, groupName, version,
                invitor, groupId, confirm) {
            console.log('confirm invite join group...invitor[' + invitor
                    + '] groupId[' + groupId + '] confirm[' + confirm + ']');
            var obj = new RL_YTX.ConfirmInviteJoinGroupBuilder();
            obj.setInvitor(invitor);
            obj.setGroupId(groupId);
            obj.setConfirm(confirm);

            RL_YTX.confirmInviteJoinGroup(obj, function() {
                        var str = '';
                        if (1 == confirm)
                            str = '{已拒绝}';
                        if (2 == confirm)
                            str = '{已同意}';
                        $(document.getElementById(you_sender + '_' + version)).find('span[imtype="notice"]').html(str);

                        if (2 == confirm) {
                            // 在群组列表中添加群组项
                            var current_contact_type = IM.HTML_find_contact_type();
                            var isShow = false;
                            if (IM._contact_type_g == current_contact_type) {
                                isShow = true;
                            }
                            IM.HTML_addContactToList(groupId, groupName,
                                    IM._contact_type_g, false, isShow, false,
                                    null, null, null);
                        }
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 更新群组成员禁言状态
         *
         * @param groupId
         * @param memberId
         * @param status
         * @constructor
         */
        EV_forbidMemberSpeak : function(groupId, memberId, status) {
            console.log('forbid member speakstatus groupId:[' + groupId
                    + '],memberId:[' + memberId + '],status[' + status + ']');
            var builder = new RL_YTX.ForbidMemberSpeakBuilder(groupId,
                    memberId, status);
            RL_YTX.forbidMemberSpeak(builder, function() {
                var trobj = $('#pop').find('tr[contact_you="' + memberId + '"]');
                var tdobj = trobj.children();
                var spanobj = tdobj.children();
                var deleobj = spanobj[1];
                var speakobj = spanobj[2];
                $(speakobj).remove();
                console.log("修改成员禁言状态成功");
                var str = '';
                if (status == 2) {
                    str += '<span class="pull-right label label-success" imtype="im_pop_memberspeak'
                            + memberId
                            + '" onclick="IM.EV_forbidMemberSpeak(\''
                            + groupId
                            + '\',\'' + memberId + '\',1)"> 恢复 </span>'
                } else {
                    str += '<span class="pull-right label label-important" imtype="im_pop_memberspeak'
                            + memberId
                            + '" onclick="IM.EV_forbidMemberSpeak(\''
                            + groupId
                            + '\',\'' + memberId + '\',2)"> 禁言 </span>'
                }
                tdobj.append(str);
            }, function(obj) {
                alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
            })
        },

        /**
         * 提出群组成语
         *
         * @param groupId
         * @param memberId
         * @constructor
         */
        EV_deleteGroupMember : function(groupId, memberId) {
            console.log("delete group member groupId:[" + groupId
                    + "],memberId:[" + memberId + "]");
            var builder = new RL_YTX.DeleteGroupMemberBuilder(groupId, memberId);
            RL_YTX.deleteGroupMember(builder, function() {
                        console.log("delete group member suc");
                        IM.HTML_popDeleteMember(memberId);
                    }, function(obj) {
                        alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
                    });
        },

        /**
         * 事件，获取群组成员列表
         *
         * @param obj
         *            obj.creator; //创建者 obj.groupName; //群组名称 obj.type; //群组类型
         *            obj.province; //省份 obj.city; //城市 obj.scope; //群组大小
         *            obj.declared; //群组公告 obj.dateCreated; //创建时间 obj.numbers;
         *            //群组人数 obj.isNotice; //是否免打扰 obj.permission; //群组权限
         *            1：默认可直接加入 2：需要身份验证 3:私有群组（不能主动加入，仅能管理员邀请） obj.groupDomain;
         *            //扩展信息 obj.isApplePush; //是否苹果离线推送 obj.target;//群组模式 1 讨论组
         *            2 普通群组
         * @param groupId
         * @param target
         * @constructor
         */
        DO_pop_show_help_GroupDetail : function(obj, groupId, target) {
            var isowner = false;
            if (IM._user_account == obj.creator) {
                isowner = true;
            }
            var str = '';
            if (isowner || target == 1) {
                str = '<input type="text" class="pull-right" style="width:95%;" value="'
                        + obj.groupName + '"/>';
                $('#pop').find('div[imtype="im_pop_group_name"]').html(str);

                if (!obj.declared) {// 群组公告
                    obj.declared = '';
                }
                str = '<textarea class="pull-right" rows="5" style="width:95%;">'
                        + obj.declared + '</textarea>';
                $('#pop').find('span[imtype="im_pop_group_declared"]')
                        .html(str);

                var str_add = '<tr>'
                        + '<td style="padding:0 0 0 0;"></td>'
                        + '</tr>'
                        + '<tr>'
                        + '<td>'
                        + '<span class="pull-left" style="width: 25%;"><a href="javascript:void(0);" class="btn" style="font-size: 20px;" onclick="IM.HTML_showInviteArea(this)" >+</a></span>'
                        + '<span class="pull-left" style="width: 25%; display: none;">'
                        + '<a href="javascript:void(0);" class="btn" onclick="IM.EV_inviteGroupMember(\''
                        + groupId
                        + '\','
                        + obj.permission
                        + ',\''
                        + isowner
                        + '\')" >邀请</a>'
                        + '</span>'
                        + '<span class="pull-right" style="width: 75%; display: none;">'
                        + '<textarea class="pull-left" id="pop_invite_area" style="width:95%;" rows=3 placeholder="请输入邀请用户账号，中间使用英文逗号\“,”\分隔，'
                        + '最多邀请50个"></textarea>' + '</span>' + '</td>'
                        + '</tr>';
                $('#pop').find('table[imtype="im_pop_members_add"]')
                        .html(str_add);
            } else {
                str = '<span class="pull-left" maxlength="128">'
                        + obj.groupName + '</span>';
                $('#pop').find('div[imtype="im_pop_group_name"]').html(str);

                if (!obj.declared) {
                    obj.declared = '';
                }
                str = '<span class="pull-left" maxlength="128">'
                        + obj.declared + '</span>';
                $('#pop').find('span[imtype="im_pop_group_declared"]')
                        .html(str);
            }
            if (obj.isNotice == 1) {
                str = '<a href="javascript:void(0);" class="btn" style="margin-left:10px;" onclick="IM.EV_groupPersonalization(\''
                        + groupId
                        + '\',2)" >开启</a>'
                        + '<a href="javascript:void(0);" class="btn btn-primary" style="margin-left:10px;">关闭</a>';
            } else {
                str = '<a href="javascript:void(0);" class="btn btn-primary" style="margin-left:10px;">开启</a>'
                        + '<a href="javascript:void(0);" class="btn" style="margin-left:10px;" onclick="IM.EV_groupPersonalization(\''
                        + groupId + '\',1)">关闭</a>';
            }
            $('#pop').find('span[imtype="im_pop_group_notice"]').html(str);
        },

        getUnicodeCharacter : function(cp) {
            if (cp >= 0 && cp <= 0xD7FF || cp >= 0xE000 && cp <= 0xFFFF) {
                return String.fromCharCode(cp);
            } else if (cp >= 0x10000 && cp <= 0x10FFFF) {

                // we substract 0x10000 from cp to get a 20-bits number
                // in the range 0..0xFFFF
                cp -= 0x10000;

                // we add 0xD800 to the number formed by the first 10 bits
                // to give the first byte
                var first = ((0xffc00 & cp) >> 10) + 0xD800

                // we add 0xDC00 to the number formed by the low 10 bits
                // to give the second byte
                var second = (0x3ff & cp) + 0xDC00;

                return String.fromCharCode(first) + String.fromCharCode(second);
            }
        },

        /**
         * 添加PUSH消息，只做页面操作 供push和拉取消息后使用
         *
         * @param obj
         * @constructor
         */
        DO_push_createMsgDiv : function(obj) {
            //判断是否是阅后即焚消息
            var isFireMsg = false;
            if(IM._fireMessage == obj.msgDomain){
                isFireMsg = true;
            }

            var b_isGroupMsg = ('g' == obj.msgReceiver.substr(0, 1));
            var you_sender = (b_isGroupMsg) ? obj.msgReceiver : obj.msgSender;
            var you_senderNickName = obj.senderNickName;
            var name = obj.msgSender;
            if (!!you_senderNickName) {
                name = you_senderNickName;
            }
            // push消息的联系人，是否是当前展示的联系人
            var b_current_contact_you = IM.DO_createMsgDiv_Help(you_sender,
                    name, b_isGroupMsg);  //false:不是当前联系人  true:当前联系人


            // 是否为mcm消息 0普通im消息 1 start消息 2 end消息 3发送mcm消息
            var you_msgContent = obj.msgContent;
            var content_type = null;
            //var version = obj.version;//改版
            var version = obj.msgId;
            var time = obj.msgDateCreated;
            if(!b_current_contact_you && you_msgContent)
            {
                //chatwap(you_sender,IM._user_account,config_weburl);
                
            }

            if (0 == obj.mcmEvent) {// 0普通im消息
                // 点对点消息，或群组消息
                content_type = (b_isGroupMsg)
                        ? IM._contact_type_g
                        : IM._contact_type_c;
                var msgType = obj.msgType;
                var str = '';

                //消息类型1:文本消息 2：语音消息 3：视频消息 4：图片消息 5：位置消息 6：文件
                if (1 == msgType) {

                    str = emoji.replace_unified(you_msgContent);

                    if(isFireMsg){
                        str = '<p fireMsg="yes" >' + str + '</p>';
                    }else{
                        str = '<p>' + str + '</p>';
                    }

                } else if (2 == msgType) {
                	//判断是否支持支持audio标签
                	str = '<p>您有一条语音消息,请用其他设备接收</p>';
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
                        str = '<p style="display:inline"><img fireMsg="yes" onclick="IM.DO_pop_phone(\''+you_sender+'\', \''
                            + version + '\')" videourl="'+urlReal+'" src="'+urlShow+'" style="max-width:'
                            + imgWid + 'px;max-height:' + imgHei + 'px;display:none;cursor:pointer" />'
                            + '<span style="font-size: small;margin-left:15px;">'+size+'</span></p>';
                    }else{
                        str = '<p style="display:inline"><img onclick="IM.DO_pop_phone(\''+you_sender+'\', \''
                            + version + '\')" videourl="'+urlReal+'" src="'+urlShow+'" style="cursor:pointer;max-width:'
                            + imgWid + 'px;max-height:' + imgHei + 'px;" />'
                            + '<span style="font-size: small;margin-left:15px;">'+size+'</span></p>';
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
                        var str = '<img fireMsg="yes" src="' + url + '" style="cursor:pointer;max-width:'
                            + imgWid + 'px; max-height:' + imgHei
                            + 'px;display:none" onclick="IM.DO_pop_phone(\'' + you_sender
                            + '\', \'' + version + '\')"/>';
                    }else{
                        var str = '<p><img src="' + url + '" id="img" style="cursor:pointer;max-width:'
                            + imgWid + 'px; max-height:' + imgHei
                            + 'px;" onclick="IM.DO_pop_phone(\'' + you_sender
                            + '\', \'' + version + '\')"/></p>';
                    }

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
                        str = '<p style="display:inline"><a fireMsg="yes" href="' + url + '" target="_blank">'
                            + '<span>'
                            + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="../css/img/im/attachment_icon.png" />'
                            + '</span>' + '<span>' + fileName + '</span>' //+ '<span style="font-size: small;margin-left:15px;">'+size+'</span>'
                            + '</a>'+ '<span style="font-size: small;margin-left:15px;">'+size+'</span></p>';
                    }else{
                        str = '<p style="display:inline"><a href="' + url + '" target="_blank">'
                            + '<span>'
                            + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="../css/img/im/attachment_icon.png" />'
                            + '</span>' + '<span>' + fileName + '</span>' //+ '<span style="font-size: small;margin-left:15px;">'+size+'</span>'
                            + '</a>'+ '<span style="font-size: small;margin-left:15px;">'+size+'</span></p>';
                    }
                }

                IM.HTML_pushMsg_addHTML(msgType, you_sender, version,
                        content_type, b_current_contact_you, name, str);
	            //桌面提醒通知
                IM.DO_deskNotice(you_sender,name,you_msgContent,msgType,isFireMsg,false);

            } else if (1 == obj.mcmEvent) {// 1 start消息
                IM.HTML_pushMsg_addHTML(obj.msgType, you_sender, version,
                        IM._contact_type_m, b_current_contact_you, name,
                        you_msgContent);
            } else if (2 == obj.mcmEvent) {// 2 end消息
                IM.HTML_pushMsg_addHTML(obj.msgType, you_sender, version,
                                IM._contact_type_m, b_current_contact_you,
                                name, "结束咨询");
            } else if (3 == obj.mcmEvent) {// 3发送mcm消息
                IM.HTML_pushMsg_addHTML(obj.msgType, you_sender, version,
                        IM._contact_type_m, b_current_contact_you, name,
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
                            + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="../css/img/im/attachment_icon.png" />'
                            + '</span>' + '<span>' + fileName + '</span>' + '<span style="font-size: small;margin-left:15px;">'+size+'</span>'
                            + '</a>';
                    }else{
                        str = '<a href="' + url + '" target="_blank">'
                            + '<span>'
                            + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="../css/img/im/attachment_icon.png" />'
                            + '</span>' + '<span>' + fileName + '</span>' + '<span style="font-size: small;margin-left:15px;">'+size+'</span>'
                            + '</a>';
                    }
                }

                IM.HTML_pushMsg_addHTML(msgType, you_sender, version,
                        content_type, b_current_contact_you, name, str);
            }
        },

        /**
         * 展示阅后即焚消息
         */
        DO_showFireMsg : function(id,msgtype){
        	var play_time = 10;
        	if(msgtype == 2){//录音
        		var dom = document.getElementById(id).getElementsByTagName("audio")[0];
        		dom.play();
        		/*避免因为卡顿引起的异常：例如，本来需要5秒播放完成，
        		但是由于卡顿播放了7秒，这时会导致语音文件还没播放完成就销毁的情况
        		解决方式：播放长度 + 3 */
        		play_time = Math.ceil(dom.duration) + 1;
        	}
            $($(document.getElementById(id)).children()[2]).hide();
            $($(document.getElementById(id)).children()[1]).show();
            var timerStr = $($(document.getElementById(id)).children()[0]).prop("outerHTML");
            $($(document.getElementById(id)).children()[0]).remove();
            timerStr += '<span class="pull-right">倒计时<code id="fireMsgtimer'+id+'">'+play_time+'</code>秒</span>';
            $(document.getElementById(id)).prepend(timerStr);
            var timerTab = document.getElementById("fireMsgtimer"+id);
            function fireMsgTimer(){
                if(((timerTab.innerHTML * 1 - 1)+"").length>1){
                    timerTab.innerHTML = ""+timerTab.innerHTML * 1 - 1;
                }else{
                    timerTab.innerHTML = "0"+(timerTab.innerHTML * 1 - 1);
                }
                if(timerTab.innerHTML == "00") {
                    $(document.getElementById(id)).remove();
                    window.clearInterval(num);
                    return false;
                }
            };
            var num=window.setInterval(fireMsgTimer,1000);
            var msgid = id.substring(id.indexOf("_")+1);
            var deleteReadMsgBuilder = new RL_YTX.DeleteReadMsgBuilder();
            deleteReadMsgBuilder.setMsgid(msgid);
            RL_YTX.deleteReadMsg(deleteReadMsgBuilder,function(obj){
                console.log("阅后即焚消息通知主叫侧成功");
            },function(obj){
                alert_box_im("错误码： " + obj.code+"; 错误描述："+obj.msg);
            });
        },
        DO_pop_phone : function(you_sender, version,obj) {
            var msgId ='';
            if(obj){
                msgId = $(obj).parent().parent()[0].id;
            }else{
	            msgId = you_sender + '_' + version;
            }
            IM._msgId = msgId;

            var msg = $(document.getElementById(msgId));

            var videoUrl = msg.find('img').attr("videourl");
            var str = '';
            var showHei = $("#lvjing").height() - 50;//客户端竖屏视频需要拖动滚动条才能露出控制按钮，所以减去50px
            if(!!videoUrl){

      	    	var type = videoUrl.substring(videoUrl.lastIndexOf(".")+1);

                str= '<video controls="controls" preload="auto" height="'+showHei+'px" style="position:relative;top:-20px;left:0px;">'+
                             '<source src="'+videoUrl+'" type="video/'+type+'" /></video>';

            }else{
                var url = msg.find('#img').attr('src');
                str = '<img src="'+ url +'" />';
            };
            $("#carousels").empty();
            $("#carousels").append(str);

            IM.HTML_pop_photo_show();
        },

        DO_spop_phone : function(url) {
            var str = '';
            var showHei = $("#lvjing").height() - 50;//客户端竖屏视频需要拖动滚动条才能露出控制按钮，所以减去50px

                str = '<img src="'+ url +'" />';

            $("#carousels").empty();
            $("#carousels").append(str);

            IM.HTML_pop_photo_show();
        },

        /**
         * lat 纬度
         * lon 经度
         * title 位置信息描述
         */
        DO_show_map : function(lat,lon,title ){
        	$("#im_body").append("<div id='baiduMap' style='z-index:888899; margin-left: 10%;margin-right:10%; height: 550px; width: 80%;'></div>");
        	$("#carousels").empty();
    		var map = new BMap.Map("baiduMap"); // 创建地图实例
        	var point = new BMap.Point(lon,lat); // 创建点坐标
        	var marker = new BMap.Marker(point);        // 创建标注
        	map.addOverlay(marker);
        	map.centerAndZoom(point, 15);
        	var opts = {width : 200,
        			 height: 100,
        			 enableMessage:true//设置允许信息窗发送短息
        			};
			var infoWindow = new BMap.InfoWindow(title,opts);  // 创建信息窗口对象
			marker.addEventListener("click", function(){
				map.openInfoWindow(infoWindow,point); //开启信息窗口
			});

    		IM._baiduMap = $("#baiduMap");
        	$("#carousels").append(IM._baiduMap);
        	$("#baiduMap").show();
            IM.HTML_pop_photo_show();
        },

        /**
         * 向上选择图片，同一个对话框内
         *
         * @constructor
         */
        DO_pop_photo_up : function() {

            var msg = $(document.getElementById(IM._msgId));
            if (msg.length < 1) {
                return;
            };

            var index = -1;
            msg.parent().find('div[msg="msg"][im_carousel="real"]:visible').each(
            function() {
                index++;
                if (IM._msgId == $(this).attr('id')) {
                    index--;
                    return false;
                };
            });
            if (index < 0) {
                return;
            };
            var prevMsg = msg.parent().children('div[msg="msg"][im_carousel="real"]:visible').eq(index);
            if (prevMsg.length < 1) {
                return;
            };
            var str ='';
            var showHei = $("#lvjing").height() - 50;//客户端竖屏视频需要拖动滚动条才能露出控制按钮，所以减去50px
            if(prevMsg.attr("im_msgtype") == 4){

                var src = prevMsg.find('img').attr('src');
                str = '<img src="'+ src +'" />';
            }else{
                var videoUrl = prevMsg.find('img').attr("videourl");
                var type = videoUrl.substring(videoUrl.lastIndexOf(".")+1);

                str= '<video controls="controls" preload="auto" height="'+showHei+'px" style="position:relative;top:-20px;left:0px;">'+
                     '<source src="'+videoUrl+'" type="video/'+type+'" /></video>';
            };
            IM._msgId = prevMsg.attr('id');
            $("#carousels").empty();
            $("#carousels").append(str);
            if($("#carousels").find("img")){
                $("#carousels").find("img").css('max-height', (showHei - 30)+"px").css(
                    'max-width', ($(window).width() - 50)+"px");
            };
            var q=1;

        },

        /**
         * 向下选择图片,同一个对话框内
         *
         * @constructor
         */
        DO_pop_photo_down : function() {

            var msg = $(document.getElementById(IM._msgId));
            if (msg.length < 1) {
                return;
            }

            var index = -1;
            msg.parent().find('div[msg="msg"][im_carousel="real"]:visible').each(
                    function() {
                        index++;
                        if (IM._msgId == $(this).attr('id')) {
                            index++;
                            return false;
                        }
                    });
            if (index < 0) {
                return;
            }
            var nextMsg = msg.parent().children('div[msg="msg"][im_carousel="real"]:visible').eq(index);
            if (nextMsg.length < 1) {
                return;
            }
            var showHei = $("#lvjing").height() - 50;//客户端竖屏视频需要拖动滚动条才能露出控制按钮，所以减去50px
            if(nextMsg.attr("im_msgtype") == 4){
                var src = nextMsg.find('img').attr('src');
                 str = '<img src="'+ src +'" />';
            }else{
                var videoUrl = nextMsg.find('img').attr("videourl");
                var type = videoUrl.substring(videoUrl.lastIndexOf(".")+1);

                str= '<video controls="controls" preload="auto" height="'+showHei+'px" style="position:relative;top:-20px;left:0px;">'+
                     '<source src="'+videoUrl+'" type="video/'+type+'" /></video>';
            };
            IM._msgId = nextMsg.attr('id');
            $("#carousels").empty();
            $("#carousels").append(str);
            if($("#carousels").find("img")){
                $("#carousels").find("img").css('max-height', (showHei - 30)+"px").css(
                    'max-width', ($(window).width() - 50)+"px");
            }

        },

        /**
         * 添加群组事件消息，只处理页面
         *
         * @param obj
         * @constructor
         */
        DO_notice_createMsgDiv : function(obj) {
        	var you_sender = IM._serverNo;
            var groupId = obj.groupId;
            var name = '系统通知';
            var groupName = obj.groupName;
            var version = obj.msgId;

            var peopleId = obj.member;
            var people = (!!obj.memberName) ? obj.memberName : obj.member;
            var you_msgContent = '';
            var noticeContent = '';
            // 1,(1申请加入群组，2邀请加入群组，3直接加入群组，4解散群组，5退出群组，6踢出群组，7确认申请加入，8确认邀请加入，9邀请加入群组的用户因本身群组个数超限加入失败(只发送给邀请者)10管理员修改群组信息，11用户修改群组成员名片12新增管理员变更通知)
            var auditType = obj.auditType;
            var groupTarget = (obj.target==2)?"群组":"讨论组";
            if (1 == auditType) {
                you_msgContent = '['
                        + people
                        + ']申请加入'+groupTarget+'['
                        + groupName
                        + '] <span imtype="notice">{<a style="color: red; cursor: pointer;" onclick="IM.EV_confirmJoinGroup(\''
                        + you_sender
                        + '\', \''
                        + version
                        + '\', \''
                        + groupId
                        + '\', \''
                        + peopleId
                        + '\', 2)">同意</a>}{<a style="color: red; cursor: pointer;" onclick="IM.EV_confirmJoinGroup(\''
                        + you_sender + '\', \'' + version + '\', \'' + groupId
                        + '\', \'' + peopleId + '\', 1)">拒绝</a>}</span>';
                noticeContent = '['
                        + people
                        + ']申请加入'+groupTarget+'['
                        + groupName
                        + '] ';
            } else if (2 == auditType) {
                if (1 == obj.confirm) {
                    you_msgContent = '[' + groupName + ']管理员邀请您加入'+groupTarget;
                    noticeContent = you_msgContent;
                    // 在群组列表中添加群组项
                    var current_contact_type = IM.HTML_find_contact_type();
                    var isShow = false;
                    if (IM._contact_type_g == current_contact_type) {
                        isShow = true;
                    }
                    IM.HTML_addContactToList(groupId, groupName,
                            IM._contact_type_g, false, isShow, false, null,
                            null, null);
                } else {
                    you_msgContent = '['
                            + groupName
                            + ']管理员邀请您加入群组 <span imtype="notice">{<a style="color: red; cursor: pointer;" onclick="IM.EV_confirmInviteJoinGroup(\''
                            + you_sender
                            + '\', \''
                            + groupName
                            + '\', \''
                            + version
                            + '\', \''
                            + obj.admin
                            + '\', \''
                            + groupId
                            + '\', 2)">同意</a>}{<a style="color: red; cursor: pointer;" onclick="IM.EV_confirmInviteJoinGroup(\''
                            + you_sender + '\', \'' + groupName + '\', \''
                            + version + '\', \'' + obj.admin + '\', \''
                            + groupId + '\', 1)">拒绝</a>}</span>';
                    noticeContent = '['
                            + groupName
                            + ']管理员邀请您加入群组;';
                }
            } else if (3 == auditType) {
                you_msgContent = '[' + people + ']直接加入群组[' + groupName + ']';
                noticeContent = you_msgContent;
                IM.DO_procesGroupNotice(auditType, groupId, peopleId, people);
            } else if (4 == auditType) {
                you_msgContent = '管理员解散了群组[' + groupName + ']';
                noticeContent = you_msgContent;
                // 将群组从列表中移除
                IM.HTML_remove_contact(groupId);
                IM.DO_procesGroupNotice(auditType, groupId, peopleId, people);
            } else if (5 == auditType) {
                you_msgContent = '[' + people + ']退出了'+groupTarget+'[' + groupName + ']';
                noticeContent = you_msgContent;
                IM.DO_procesGroupNotice(auditType, groupId, peopleId, people);
            } else if (6 == auditType) {
                you_msgContent = '群[' + groupName + ']管理员将[' + people + ']踢出'+groupTarget;
                noticeContent = you_msgContent;
                // 将群组从列表中移除
                if (IM._user_account == people) {
                    IM.HTML_remove_contact(groupId);
                }
                IM.DO_procesGroupNotice(auditType, groupId, peopleId, people);
            } else if (7 == auditType) {
                you_msgContent = '管理员同意[' + people + ']加入群组[' + groupName
                        + ']的申请';
                noticeContent = you_msgContent;
                IM.DO_procesGroupNotice(auditType, groupId, peopleId, people);
            } else if (8 == auditType) {
                if (2 != obj.confirm) {
                    you_msgContent = '[' + people + ']拒绝了群组[' + groupName
                            + ']的邀请';
                    noticeContent = you_msgContent;
                } else {
                    you_msgContent = '[' + people + ']同意了管理员的邀请，加入群组['
                            + groupName + ']';
                    noticeContent = you_msgContent;
                    IM.DO_procesGroupNotice(auditType, groupId, peopleId,
                            people);
                }
            } else if (10 == auditType) {
                you_msgContent = '管理员修改'+groupTarget+'[' + groupName + ']信息';
                noticeContent = you_msgContent;
                if (!!obj.groupName) {
                    IM.HTML_addContactToList(groupId, obj.groupName,
                            IM._contact_type_g, false, isShow, true, null,
                            null, null);
                }
                IM.DO_procesGroupNotice(auditType, groupId, peopleId, people,
                        obj.groupName, obj.ext);
            } else if (11 == auditType) {
                you_msgContent = '用户[' + people + ']修改群组成员名片';
                noticeContent = you_msgContent;
                // TODO obj.memberName有值，意味着要修改展示的名字
                IM.DO_procesGroupNotice(auditType, groupId, peopleId,
                        obj.memberName, obj.groupName, obj.ext);
            } else if(12 == auditType){
                you_msgContent = '用户[' + people + ']成为'+groupTarget+'[' + groupName + ']管理员';
                noticeContent = you_msgContent;
                IM.DO_procesGroupNotice(auditType, groupId, peopleId,
                    obj.memberName, obj.groupName, obj.ext);
            } else {
                you_msgContent = '未知type[' + auditType + ']';
                noticeContent = you_msgContent;
            }

            // 添加左侧消息
            // 监听消息的联系人，是否是当前展示的联系人
            var b_current_contact_you = IM.DO_createMsgDiv_Help(you_sender,
                    name, true);

            // 添加右侧消息
            IM.HTML_pushMsg_addHTML(1, you_sender, version, IM._contact_type_g,
                    b_current_contact_you, groupName, you_msgContent);
            //桌面提醒通知
            IM.DO_deskNotice('','',noticeContent,'',false,false);
        },

        /**
         * 处理群组成员变更通知,只处理pop页面
         *
         * @param type
         *            通知类型 4解散群组，5退出群组，6踢出群组，7确认申请加入，8确认邀请加入
         *            10管理员修改群组信息，11用户修改群组成员名片)
         * @param groupId
         *            群组id
         * @param memberId
         *            用户id
         * @param memberName
         *            用户名称
         * @param groupName
         *            群组名称
         * @param ext
         *            扩展字段
         * @constructor
         */
        DO_procesGroupNotice : function(type, groupId, memberId, memberName,
                groupName, ext) {
            if (!IM.DO_checkPopShow(groupId)) {
                return;
            }
            if (type == 4) {
                alert_box_im("管理员解散了该群组！");
                IM.HTML_pop_hide();
            } else if (type == 5 || type == 6) {
                if (memberId == IM._user_account) {
                    alert_box_im("您被管理员移出该群组！");
                    IM.HTML_pop_hide();
                } else {
                    IM.HTML_popDeleteMember(memberId);
                }
            } else if (type == 7 || type == 8) {
                var obj = $('#pop').find('div[im_isowner]');
                var isowner = obj.attr('im_isowner');
                var target = obj.attr('im_target');
                IM.HTML_popAddMember(groupId, memberId, memberName, isowner,
                        target);
            } else if (type == 10) {
                var obj = $('#pop').find('div[im_isowner]');
                var isowner = obj.attr('im_isowner');
                if (!!groupName) {
                    IM.HTML_showGroupName(isowner, groupName);
                }
                if (!!ext) {
                    var json = eval("(" + ext + ")");
                    if (!!json["groupDeclared"]) {
                        IM.HTML_showGroupDeclared(isowner,
                                json["groupDeclared"]);
                    }
                }
            } else if (type == 11) {
                IM.HTML_showMemberName(memberId, memberName);
            }
        },

        DO_checkPopShow : function(groupId) {
            if ($('#pop_group_' + groupId).length <= 0) {
                return false;
            }
            var display = $('#pop').css("display");
            if (display != 'block') {
                return false;
            }
            return true;
        },

        /**
         * 删除联系人，包括左侧和右侧
         *
         * @param id
         * @constructor
         */
        HTML_remove_contact : function(id) {
            // 删除左侧联系人列表
            $(document.getElementById('im_contact_' + id)).remove();
            // 删除右侧相应消息
            $('#im_content_list').find('div[content_you="' + id + '"]').each(
                    function() {
                        $(this).remove();
                    });
        },

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
        DO_createMsgDiv_Help : function(you_sender, name, b_isGroupMsg) {
            // 处理联系人列表，如果新联系人添加一条新的到im_contact_list，如果已经存在给出数字提示
            var b_current_contact_you = false; // push消息的联系人(you_sender)，是否是当前展示的联系人
            /*$('#im_contact_list').find('li').each(function() {
                        if (you_sender == $(this).attr('contact_you')) {
                            if ($(this).hasClass('active')) {
                                b_current_contact_you = true;
                            }
                        }
                    });*/
            if(you_sender == decodeURIComponent(getQueryString('contact_you')))
            {
                b_current_contact_you = true;
            }

            // 新建时判断选中的contact_type是那个然后看是否需要显示
            var current_contact_type = IM.HTML_find_contact_type();

            var isShow = false;
            if (IM._contact_type_g == current_contact_type && b_isGroupMsg) {
                isShow = true;
            }
            if (IM._contact_type_c == current_contact_type && !b_isGroupMsg) {
                isShow = true;
            }

            IM.HTML_addContactToList(you_sender, name, (b_isGroupMsg)
                            ? IM._contact_type_g
                            : IM._contact_type_c, false, isShow, false, null,
                    null, null);

            return b_current_contact_you;
        },

        /**
         * 查找当前选中的contact_type值
         *
         * @returns {*}
         * @constructor
         */
        HTML_find_contact_type : function() {
            // 在群组列表中添加群组项
            var current_contact_type = null;
            $('#im_contact_type').find('li').each(function() {
                        if ($(this).hasClass('active')) {
                            current_contact_type = $(this).attr('contact_type');
                        }
                    });
            return current_contact_type;
        },

        /**
         * 样式，push监听到消息时添加右侧页面样式
         *
         * @param msgtype --
         *            消息类型1:文本消息 2：语音消息 3：视频消息 4：图片消息 5：位置消息 6：文件
         * @param you_sender --
         *            对方帐号：发出消息时对方帐号，接收消息时发送者帐号
         * @param version --
         *            消息版本号，本地发出时为long时间戳
         * @param content_type --
         *            C G or M
         * @param b --
         *            是否需要展示 true显示，false隐藏
         * @param name --
         *            显示对话框中消息发送者名字
         * @param you_msgContent --
         *            消息内容
         * @constructor
         */

        HTML_pushMsg_addHTML : function(msgtype, you_sender, version,
                content_type, b, name, you_msgContent) {
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


            var carou = '';

            if(msgtype==4||msgtype==3){
                carou="real";
            };

            if(you_msgContent)
            {
                //receiver_logo = "";
                $.post(ImApiUrl+"?ctl=UserApi&met=getUserInfosByName&typ=json",{"user_name":decodeURIComponent(getQueryString('contact_you')),"k":k,"u":u} ,function(data) {
                    if(data.status == 200)
                    {

                        console.info(data);
                        receiver_logo = data.data.user_avatar;

                        if(data.data.user_avatar)
                        {
                            receiver_logo_str = 'src="'+data.data.user_avatar+'"';
                            var str = '<div  msg="msg" im_carousel="'+carou+'" im_msgtype="' + msgtype + '" id="'
                                + you_sender + '_' + version + '" content_type="'
                                + content_type + '" content_you="' + you_sender
                                + '" class="chatinterfacelist-left clearfix alert-left" style="display:'
                                + ((b) ? 'block' : 'none') + '">'

                                + '<div class="hd-portrait"><img  '+ receiver_logo_str+ '></div><div class="chatcontent"><div class="triangle"></div>' + you_msgContent + '</div></div>';
                            $('#im_content_list').append(str);
                        }
                        else
                        {
                            receiver_logo_str = '';
                            var str = '<div msg="msg" im_carousel="'+carou+'" im_msgtype="' + msgtype + '" id="'
                                + you_sender + '_' + version + '" content_type="'
                                + content_type + '" content_you="' + you_sender
                                + '" class="alert-left" style="display:'
                                + ((b) ? 'block' : 'none') + '">'

                                + '<div class="hd-portrait"><img  '+ receiver_logo_str+ '></div><div class="chatcontent"><div class="triangle"></div>' + you_msgContent + '</div></div>';
                            $('#im_content_list').append(str);
                        }
                    }
                    else
                    {
                        receiver_logo_str = '';
                        var str = '<div msg="msg" im_carousel="'+carou+'" im_msgtype="' + msgtype + '" id="'
                            + you_sender + '_' + version + '" content_type="'
                            + content_type + '" content_you="' + you_sender
                            + '" class="alert-left " style="display:'
                            + ((b) ? 'block' : 'none') + '">'

                            + '<div class="hd-portrait"><img  '+ receiver_logo_str+ '></div><div class="chatcontent"><div class="triangle"></div>' + you_msgContent + '</div></div>';
                        $('#im_content_list').append(str);
                    }


                    $('#im_content_list').trigger("scroll");
                    $('#im_content_list').scrollTop($('#im_content_list')[0].scrollHeight);
                    setTimeout(function() {
                        $('#im_content_list').scrollTop( );
                    }, 1000);
                });
                //alert_box_im(content_you);

                if(you_msgContent.indexOf("fireMsg") > -1){//fireMsg="yes"
                    var id = you_sender + "_" + version;
                    $(document.getElementById(id)).find("code").next().hide();
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
                    var fireMsgStr = '<img style="cursor:pointer;max-width:'+imgWid+'px; max-height:'+imgHei+'px; margin-right:5px; margin-left:5px;" ' +
                        'src="assets/img/fireMessageImg.png" onclick="IM.DO_showFireMsg(\''+ id +'\',\''+msgtype+'\')" />';
                    $(document.getElementById(id)).append(fireMsgStr);
                }


                // 右侧列表添加数字提醒
                // TODO 后期要添加提醒数字时，记得要先拿到旧值，再+1后写入新建的列表中
                var current_contact = $(document.getElementById('im_contact_' + you_sender));
                if (!current_contact.hasClass('active')) {
                    var warn = current_contact.find('span[contact_style_type="warn"]');
                    if ('99+' == warn.html()) {
                        return;
                    }
                    var warnNum = parseInt((!!warn.html()) ? warn.html() : 0) + 1;
                    if (warnNum > 99) {
                        warn.html('99+');
                    } else {
                        warn.html(warnNum);
                    }
                    warn.show();
                }
            }

        },
        HTML_pushCall_addHTML : function( you_sender, callId, you_msgContent) {
            // push消息的联系人，是否是当前展示的联系人
            var b = IM.DO_createMsgDiv_Help(you_sender,you_sender, false);
            var str = '<div msg="msg" id="' + you_sender + '_' + callId
                    + '" content_you="' + you_sender
                    + '" class="alert-left " style="display:'
                    + ((b) ? 'block' : 'none') + '">'
                    + '<code style="max-width:70%;word-break:keep-all;text-overflow:ellipsis;overflow: hidden;">' + you_sender
                    + ':</code>&nbsp;<p class="name"> '+ name +'</p>' + you_msgContent + '</div>';
            $('#im_content_list').append(str);

            setTimeout(function() {
                        $('#im_content_list').scrollTop($('#im_content_list')[0].scrollHeight);
                    }, 100);

            // 右侧列表添加数字提醒
            // TODO 后期要添加提醒数字时，记得要先拿到旧值，再+1后写入新建的列表中
            var current_contact = $(document.getElementById('im_contact_' + you_sender));
            if (!current_contact.hasClass('active')) {
                var warn = current_contact.find('span[contact_style_type="warn"]');
                if ('99+' == warn.html()) {
                    return;
                }
                var warnNum = parseInt((!!warn.html()) ? warn.html() : 0) + 1;
                if (warnNum > 99) {
                    warn.html('99+');
                } else {
                    warn.html(warnNum);
                }
                warn.show();
            }
        },
        HTML_pushMsg_addPreHTML : function(msgtype, you_sender, version,
                content_type, b, name, you_msgContent) {
            var carou = '';
            if(msgtype==4||msgtype==3){
                carou="real";
            };
            var str = '<div msg="msg" im_carousel="'+carou+'" im_msgtype="' + msgtype + '" id="'
                    + you_sender + '_' + version + '" content_type="'
                    + content_type + '" content_you="' + you_sender
                    + '" class=" alert-left " style="display:'
                    + ((b) ? 'block' : 'none') + '">' + '<code style="max-width:70%;word-break:keep-all;text-overflow:ellipsis;overflow: hidden;">' + name
                    + ':</code>&nbsp;<pre style="font-size:12px;"> '+ name +'</pre>' + you_msgContent + '</div>';
            $('#im_content_list').prepend(str);

            setTimeout(function() {
                        $('#im_content_list').scrollTop($('#im_content_list')[0].scrollHeight);
                    }, 100);

            // 右侧列表添加数字提醒
            // TODO 后期要添加提醒数字时，记得要先拿到旧值，再+1后写入新建的列表中
            var current_contact = $(document.getElementById('im_contact_' + you_sender));
            if (!current_contact.hasClass('active')) {
                var warn = current_contact.find('span[contact_style_type="warn"]');
                if ('99+' == warn.html()) {
                    return;
                }
                var warnNum = parseInt((!!warn.html()) ? warn.html() : 0) + 1;
                if (warnNum > 99) {
                    warn.html('99+');
                } else {
                    warn.html(warnNum);
                }
                warn.show();
            }
        },

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
        HTML_sendMsg_addHTML : function(msg, msgtype, msgid, content_type,
                content_you, im_send_content) {
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

            im_send_content = emoji.replace_unified(im_send_content);
            var display = ('temp_msg' == msg) ? 'none' : 'block';
            var carou = '';

            if(msgtype==4||msgtype==3){
                carou="real";
            };
            // var user_logo_str = 'http://img4q.duitang.com/uploads/item/201505/23/20150523212026_fACJU.thumb.224_0.jpeg';
            if(user_logo)
            {
                var user_logo_str = 'src="'+user_logo+'" ';
            }else{
                var user_logo_str = '';
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
                    + '" class="chatinterfacelist-right clearfix alert-right" style="display:'
                    + display
                    + '">'
                    + '<span imtype="resend" class="add-on" onclick="IM.EV_resendMsg(this)"'
                    + ' style="display:none; cursor:pointer; position: relative; left: -40px; top: 0px;"><i class="icon-repeat"></i></span>'
                    + '<div class="hd-portrait"><img '+ user_logo_str +'></div><div class="chatcontent"><div class="triangle"></div>' + im_send_content + '</div></div>';
            $('#im_content_list').append(str);
            $('#im_send_content').html('');
            $('#im_content_list')
                    .scrollTop($('#im_content_list')[0].scrollHeight);

            return content_you + '_' + msgid;
        },
        HTML_sendMsg_addPreHTML : function(msg, msgtype, msgid, content_type,content_you, im_send_content) {
            if(!!im_send_content){
                im_send_content = emoji.replace_unified(im_send_content);
            };

            var display = ('temp_msg' == msg) ? 'none' : 'block';
            var carou = '';
            if(msgtype==4||msgtype==3){
                carou="real";
            };
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
                    + '" class="alert-right" style="display:'
                    + display
                    + '">'
                    + '<span imtype="resend" class="add-on" onclick="IM.EV_resendMsg(this)"'
                    + ' style="display:none; cursor:pointer; position: relative; left: -40px; top: 0px;"><i class="icon-repeat"></i></span>'
                    + '<code class="pull-right" style="max-width:70%;word-break:keep-all;text-overflow:ellipsis;overflow: hidden;">&nbsp;:' + IM._username
                    + '</code>' + im_send_content + '</div>';

			$('#im_content_list').prepend(str);
            var hisStr = $("#getHistoryMsgDiv").prop('outerHTML');
            $("#getHistoryMsgDiv").remove();
            $('#im_content_list').prepend(hisStr);
            $('#im_send_content').html('');
            $('#im_content_list')
                    .scrollTop($('#im_content_list')[0].scrollHeight);

            return content_you + '_' + msgid;
        },

        /**
         * 选择联系人列表，并切换消息列表
         *
         * @param contact_type
         * @param contact_you
         */
        DO_chooseContactList : function(contact_type, contact_you) {
            IM.HTML_clean_im_contact_list();
            $("#fireMessage").removeClass("active");
            var current_contact = document.getElementById("im_contact_" + contact_you);
            $(current_contact).addClass('active');
            var warn = $(current_contact).find('span[contact_style_type="warn"]');
            warn.hide();
            warn.html(0);
            /*暂时屏蔽历史消息功能
            $("#getHistoryMsgDiv").html('<a href="#" onclick="IM.DO_getHistoryMessage();" style="font-size: small;position: relative;top: -30px;">查看更多消息</a>');
            $("#getHistoryMsgDiv").show();
          */
            IM.HTML_clean_im_content_list(contact_you);

            //显示用户的状态
            if(IM._contact_type_c == contact_type){
                $("#fireMessage").show();
                IM.EV_getUserState(contact_you);
            } else if(IM._contact_type_g == contact_type){
                $("#fireMessage").hide();
            }
            // 如果当前选择的是客服列表直接发起咨询
            if (IM._contact_type_m == contact_type) {
                IM.EV_startMcmMsg(contact_you);
                IM._isMcm_active = true;
            } else {
                if (IM._isMcm_active) {
                    IM.EV_stopMcmMsg(contact_you);
                }
            }
        },
        EV_getUserState : function(contact_you){
            var current_contact = document.getElementById("im_contact_" + contact_you);
            var getUserStateBuilder = new RL_YTX.GetUserStateBuilder();
            getUserStateBuilder.setUseracc(contact_you);
            var onlineState = $(current_contact).find('span[contact_style_type="onlineState"]');

            RL_YTX.getUserState(getUserStateBuilder,function(obj){
                if(obj.state == 1){//1在线 2离线
                    onlineState.html("在线");
                    onlineState.show();
                    onlineState.css("background-color","blue");
                }else if(obj.state == 2){
                    onlineState.html("离线");
                    onlineState.show();
                }else{
                    alert_box_im("错误码："+obj.state+"; 错误描述：获得用户状态结果不合法")
                }
            },function(obj){
                if(174006 != obj.code){
                	alert_box_im("错误码："+obj.code+"; 错误描述："+obj.msg)

                }
            });
        },

        /**
         * 清理右侧消息列表
         *
         * @param contact_you --
         *            左侧联系人列表中的
         */
        HTML_clean_im_content_list : function(contact_you) {

            $('#im_content_list').find('div[msg="msg"]').each(function() {
                        if ($(this).attr('content_you') == contact_you) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });

            $('#im_content_list').scrollTop($('#im_content_list')[0].scrollHeight);
        },

        /**
         * 清理联系人列表样式
         */
        HTML_clean_im_contact_list : function() {
            // 清除选中状态class
            $('#im_contact_list').find('li').each(function() {
                        $(this).removeClass('active');
                    });
        },

        /**
         * 添加联系人到列表中
         */
        DO_addContactToList : function() {
            var contactVal = $('#im_add').find('input[imtype="im_add_contact"]').val();

            if (!IM.DO_checkContact(contactVal)) {//校验联系人格式
                return;
            }

            var im_contact = $('#im_contact_list').find('li[contact_type="'
                    + IM._contact_type_c + '"][contact_you="' + contactVal
                    + '"]');
            if (im_contact.length <= 0) {
                IM.HTML_clean_im_contact_list();//清除原来选中的li

                IM.HTML_addContactToList(contactVal, contactVal,
                                IM._contact_type_c, true, true, false, null,
                                null, null);

            }

            $('#im_add').find('input[imtype="im_add_contact"]').val('');

        },

        /**
         * 检查联系名称规则是否合法
         *
         * @param contactVal
         * @returns {boolean}
         * @constructor
         */
        DO_checkContact : function(contactVal) {
            if (!contactVal) {
                IM.HTML_showAlert('alert-warning', '请填写联系人');
                return false;
            }
            if (contactVal.indexOf("#") > -1 && contactVal.length > 161) {
                IM.HTML_showAlert('alert-error', '跨应用联系人长度不能超过161');
                return false;
            } else if (contactVal.length > 128) {
                IM.HTML_showAlert('alert-error', '联系人长度不能超过128');
                return false;
            }
            if ('g' == contactVal.substr(0, 1)) {
                IM.HTML_showAlert('alert-error', '联系人不能以"g"开始');
                return false;
            }

            if (contactVal.indexOf("@") > -1) {
                var regx2 = /^([a-zA-Z0-9]{32}#)?[a-zA-Z0-9_-]{1,}@(([a-zA-z0-9]-*){1,}.){1,3}[a-zA-z-]{1,}$/;
                if (regx2.exec(contactVal) == null) {
                    IM.HTML_showAlert('alert-error',
                            '检查邮箱格式、如果是跨应用再检查应用Id长度是否为32且由数字或字母组成）');
                    return false;
                }
            } else {
                var regx1 = /^([a-zA-Z0-9]{32}#)?[A-Za-z0-9_-]+$/;
                if (regx1.exec(contactVal) == null) {
                    IM.HTML_showAlert('alert-error',
                                    '联系人只能使用数字、_、-、大小写英文组成; 如果是跨应用则应用id长度为32位由数字或大小写英文组成');
                    return false;
                }
            }
            return true;
        },

        /**
         * 添加群组
         *
         * @param permission
         * @constructor
         */
        DO_addGroupToList : function(permission) {
            var groupName = $('#im_add').find('input[imtype="im_add_group"]').val();
            if (!groupName) {
                IM.HTML_showAlert('alert-error', '请填写群组名称，用来创建群组');
                return;
            }else if (groupName.trim() == "") {
                IM.HTML_showAlert('alert-error', '请填写正确的群组名称');
                return;
            }else{//校验群组名称的合法性
                var regx1 = /^[\\x00-\\x7F\a-zA-Z\u4e00-\u9fa5_-]{0,10}$/;
                if(regx1.exec(groupName) == null){
                    alert_box_im("群组名只允许中英文数字@_-,长度不超过10")
                    return;
                }
                if(groupName.substring(0,1) =="g" || groupName.substring(0,1) =="G"){
                    alert_box_im("群组名不能以g或G开头")
                    return;
                }
                if(groupName.indexOf("@") > -1){
                    alert_box_im("群组名不能含有@符号")
                    return;
                }

            }

            if (permission == 4) {
                IM.DO_html_create(groupName,permission);
            } else {
                IM.DO_html_create(groupName,permission);
            }

            $('#im_add').find('input[imtype="im_add_group"]').val('');
        },

        /**
         * 样式，添加左侧联系人列表项
         *
         * @param contactVal
         * @param contact_type
         * @param b
         *            true--class:active false--class:null
         * @param bb
         *            true--display:block false--display:none
         * @param bbb
         *            true--需要改名字 false--不需要改名字
         * @param owner --
         *            当前群组创建者（只有content_type==G时才有值）
         * @param isNotice --
         *            是否提醒 1：提醒；2：不提醒(只有content_type==G时才有值)
         * @param target --
         *            1表示讨论组 2表示群组
         * @constructor
         */
        HTML_addContactToList : function(contactVal, contactName, content_type,
                b, bb, bbb, owner, isNotice, target) {

            var old = $(document.getElementById('im_contact_' + contactVal));
            // 已存在，置顶，并更改数字
            if (!!old && old.length > 0) {
                // 如果名字不同，修改名字
                if (bbb) {
                    old.find('span[contact_style_type="name"]').html(contactName);
                }
                var str = old.prop('outerHTML');
                old.remove();
                $('#im_contact_list').prepend(str);

                return;
            }

            // 不存在创建个新的
            if (IM._contact_type_m == content_type) {
                var onUnitAccount = $(document.getElementById('im_contact_' + IM._onUnitAccount));
                if (IM._onUnitAccount == onUnitAccount.attr('contact_you')) {
                    return;
                }
            }
            var active = '';
            if (b)
                active = 'active';
            var dis = 'none';
            if (bb)
                dis = 'block';

            var str = '<li onclick="IM.DO_chooseContactList(\'' + content_type
                    + '\', \'' + contactVal + '\')" id="im_contact_'
                    + contactVal + '" im_isnotice="' + isNotice
                    + '" contact_type="' + content_type + '" contact_you="'
                    + contactVal + '" class="' + active + '"  style="display:'
                    + dis + '">' + '<a href="javascript:void(0);">'
                    + '<span contact_style_type="name">' + contactName
                    + '</span>';
            if (IM._contact_type_g == content_type) {
                if (contactName != "系统通知") {
                    str += '<span class="pull-right" onclick="IM.DO_groupMenu(\''
                            + contactVal
                            + '\', \''
                            + owner
                            + '\', '
                            + target
                            + ');"><i class="icon-wrench"></i></span>';
                }

            }

            str += '<span contact_style_type="warn" class="badge badge-warning pull-right" style="margin-top:3px; margin-right:10px; display:none;">0</span>'
                    +'<span contact_style_type="onlineState" class="badge pull-right" style="margin-top:6px; margin-right:10px; display:none;"></span>'
                    + '</a>' + '</li>';
            $('#im_contact_list').prepend(str);

            var ulWidth = $('#im_contact_list').width();
            /*
            var conListWidth = document.getElementById("im_contact").scrollWidth;

            if(conListWidth-ulWidth>30){
                $('#im_contact_list').find("li")[0].style.width=conListWidth+45+"px";//防止用户名过长时用户状态或消息条数换行
            }
            */
            if (b)
                IM.DO_chooseContactList(content_type, contactVal);
        },

        /**
         * 选择群组管理事件，群组列表后面的扳手
         *
         * @param groupId
         * @param owner
         * @param target
         *            1 讨论组 2 群组
         * @constructor
         */
        DO_groupMenu : function(groupId, owner, target) {// 依据推送生成的左侧列表只有groupId参数
            var isowner = false;
            if (IM._user_account == owner) {// 自己创建的群组
                isowner = true;
            };
            if (target == null) {// 推送创建的页面参数没有target
                IM.EV_getGroupDetail(groupId, isowner, target);
            } else {
                // 构建页面
                IM.DO_pop_show(groupId, isowner, target);
                // 调用SDK方法获取数据
                // 获取群组详情
                IM.EV_getGroupDetail(groupId, isowner, target);
            }

        },
        /**
         * 初始化讨论组弹窗
         */
        DO_html_create : function(groupName,permission) {
            var str = '<div class="modal" style="position: relative; top: auto; left: auto; right: auto; margin: 0 auto 20px; z-index: 1; max-width: 100%;">'
                    + '<div class="modal-header">'
                    + '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="IM.HTML_pop_hide();">×</button>';
            if(permission == 4){
                str += '<h3>讨论组：';
            }else{
                str += '<h3>群组：';
            }

            str += groupName + '</h3></div>'
            + '<div class="modal-body">'
            + '<table class="table table-bordered">'
            + '<tr>'
            + '<td>'
            + '<div style="height:auto; padding-bottom: 0px;">'
            + '<table imtype="im_pop_members_add" class="table table-striped">';

            if(permission == 4){
                str += '<tr>'
                    + '<td>'
                    + '<span class="pull-left" style="width: 25%;"><a href="javascript:void(0);" class="btn" style="font-size: 20px;" onclick="IM.HTML_showInviteArea(this)" >+</a></span>'
                    + '<span class="pull-left" style="width: 25%; display: none;">'
                    + '<span>邀请 :</span>'
                    + '</span>'
                    + '<span class="pull-right" style="width: 75%; display: none;">'
                    + '<textarea class="pull-left" id="pop_invite_area" style="width:95%;" rows=3 placeholder="请输入邀请用户账号，中间使用英文逗号\“,”\分隔，'
                    + '最多邀请50个"></textarea>'
                    + '</span>'
                    + '</td>'
                    + '</tr>';
            }else{
                str += '<tr>'
                    + '<td>'
                    + '<div style="height:auto">'
                    + '<div style="height:auto;padding-bottom:30px">'
                    +'<span style="float:left">分类 ：</span>'
                    + '<div style="margin-left:90px;height:auto">'
                    + '<div style="height:auto;min-width: 122px;float:left">'
                    + '<input type="radio" name="groupType" value="1">&nbsp;&nbsp;&nbsp;同学'
                    + '</div>'
                    + '<div style="height:auto;min-width: 122px;float:left">'
                    + '<input type="radio" name="groupType" value="2">&nbsp;&nbsp;&nbsp;朋友'
                    + '</div>'
                    + '<div style="height:auto;min-width: 122px;float:left">'
                    + '<input type="radio" name="groupType" value="3">&nbsp;&nbsp;&nbsp;同事'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '<div style="height:auto;padding-bottom:5px;padding-top:10px;clear:both">'
                    + '<span style="float:left;margin-top: 10px;">地区 ：</span>'
                    + '<div style="margin-left:90px;height:auto;padding:0px">'
                    + '<div style="height:auto;padding-top:5px;min-width:122px;float:left">'
                    + '<span style="position:relative;top:-5px">省：</span><select id="province" size=1 onchange="IM.DO_getCity()" style="width:auto">'
                        + '<option value=-1>--</option>'
                        + '<option value=0>北京</option>'
                        + '<option value=1>上海</option>'
                        + '<option value=2>天津</option>'
                        + '<option value=3>重庆</option>'
                        + '<option value=4>河北</option>'
                        + '<option value=5>山西</option>'
                        + '<option value=6>内蒙古</option>'
                        + '<option value=7>辽宁</option>'
                        + '<option value=8>吉林</option>'
                        + '<option value=9>黑龙江</option>'
                        + '<option value=10>江苏</option>'
                        + '<option value=11>浙江</option>'
                        + '<option value=12>安徽</option>'
                        + '<option value=13>福建</option>'
                        + '<option value=14>江西</option>'
                        + '<option value=15>山东</option>'
                        + '<option value=16>河南</option>'
                        + '<option value=17>湖北</option>'
                        + '<option value=18>湖南</option>'
                        + '<option value=19>广东</option>'
                        + '<option value=20>广西</option>'
                        + '<option value=21>海南</option>'
                        + '<option value=22>四川</option>'
                        + '<option value=23>贵州</option>'
                        + '<option value=24>云南</option>'
                        + '<option value=25>西藏</option>'
                        + '<option value=26>陕西</option>'
                        + '<option value=27>甘肃</option>'
                        + '<option value=28>宁夏</option>'
                        + '<option value=29>青海</option>'
                        + '<option value=30>新疆</option>'
                        + '<option value=31>香港</option>'
                        + '<option value=32>澳门</option>'
                        + '<option value=33>台湾</option>'
                    + '</select>'
                    + '</div>'
                    + '<div style="height:auto;padding-top:5px;min-width:122px;float:left">'//如果屏幕过小的时候就分成两列
                    + '<span style="position:relative;top:-5px">市：</span><select id="city" style="width:auto">'
                    + '<option value=-1>--</option>'
                    + '</select>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '<div style="height:auto;padding-top:10px;clear:both">'
                    + '<span style="float:left;margin-top: 10px;">群组描述 ：</span>'
                    + '<div style="height:auto;margin-left:90px;padding-top:10px">'
                    + '<textarea id="createDeclare" class="pull-left" style="width:95%"></textarea>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '</td></tr>';

            }

            str += '</table></div></td></tr></table></div>'
                + '<div class="modal-footer">'
                + '<button href="#" id= "createGroup_bt" class="btn btn-primary" onclick="IM.EV_createGroup(\''
                + groupName + '\',' + permission + ');" > 确定 </button>'
                + '<a href="javascript:void(0);" class="btn" onclick="IM.HTML_pop_hide()">取消</a>'
                + '</div>'
                + '</div>';

            $('#pop').find('div[class="row"]').html(str);
            IM.HTML_pop_show();
            if (!!navigator.userAgent.match(/mobile/i) || $(window).width() <600){//浏览器兼容
                $("#city").parent().css("float","none");
            }

        },
        DO_getCity : function(){
            var arr = new  Array();
			arr[0]="东城,西城,崇文,宣武,朝阳,丰台,石景山,海淀,门头沟,房山,通州,顺义,昌平,大兴,平谷,怀柔,密云,延庆"
			arr[1]="黄浦,卢湾,徐汇,长宁,静安,普陀,闸北,虹口,杨浦,闵行,宝山,嘉定,浦东,金山,松江,青浦,南汇,奉贤,崇明"
			arr[2]="和平,东丽,河东,西青,河西,津南,南开,北辰,河北,武清,红挢,塘沽,汉沽,大港,宁河,静海,宝坻,蓟县"
			arr[3]="万州,涪陵,渝中,大渡口,江北,沙坪坝,九龙坡,南岸,北碚,万盛,双挢,渝北,巴南,黔江,长寿,綦江,潼南,铜梁,大足,荣昌,壁山,梁平,城口,丰都,垫江,武隆,忠县,开县,云阳,奉节,巫山,巫溪,石柱,秀山,酉阳,彭水,江津,合川,永川,南川"
			arr[4]="石家庄,邯郸,邢台,保定,张家口,承德,廊坊,唐山,秦皇岛,沧州,衡水"
			arr[5]="太原,大同,阳泉,长治,晋城,朔州,吕梁,忻州,晋中,临汾,运城"
			arr[6]="呼和浩特,包头,乌海,赤峰,呼伦贝尔盟,阿拉善盟,哲里木盟,兴安盟,乌兰察布盟,锡林郭勒盟,巴彦淖尔盟,伊克昭盟"
			arr[7]="沈阳,大连,鞍山,抚顺,本溪,丹东,锦州,营口,阜新,辽阳,盘锦,铁岭,朝阳,葫芦岛"
			arr[8]="长春,吉林,四平,辽源,通化,白山,松原,白城,延边"
			arr[9]="哈尔滨,齐齐哈尔,牡丹江,佳木斯,大庆,绥化,鹤岗,鸡西,黑河,双鸭山,伊春,七台河,大兴安岭"
			arr[10]="南京,镇江,苏州,南通,扬州,盐城,徐州,连云港,常州,无锡,宿迁,泰州,淮安"
			arr[11]="杭州,宁波,温州,嘉兴,湖州,绍兴,金华,衢州,舟山,台州,丽水"
			arr[12]="合肥,芜湖,蚌埠,马鞍山,淮北,铜陵,安庆,黄山,滁州,宿州,池州,淮南,巢湖,阜阳,六安,宣城,亳州"
			arr[13]="福州,厦门,莆田,三明,泉州,漳州,南平,龙岩,宁德"
			arr[14]="南昌市,景德镇,九江,鹰潭,萍乡,新馀,赣州,吉安,宜春,抚州,上饶"
			arr[15]="济南,青岛,淄博,枣庄,东营,烟台,潍坊,济宁,泰安,威海,日照,莱芜,临沂,德州,聊城,滨州,菏泽"
			arr[16]="郑州,开封,洛阳,平顶山,安阳,鹤壁,新乡,焦作,濮阳,许昌,漯河,三门峡,南阳,商丘,信阳,周口,驻马店,济源"
			arr[17]="武汉,宜昌,荆州,襄樊,黄石,荆门,黄冈,十堰,恩施,潜江,天门,仙桃,随州,咸宁,孝感,鄂州"
			arr[18]="长沙,常德,株洲,湘潭,衡阳,岳阳,邵阳,益阳,娄底,怀化,郴州,永州,湘西,张家界"
			arr[19]="广州,深圳,珠海,汕头,东莞,中山,佛山,韶关,江门,湛江,茂名,肇庆,惠州,梅州,汕尾,河源,阳江,清远,潮州,揭阳,云浮"
			arr[20]="南宁,柳州,桂林,梧州,北海,防城港,钦州,贵港,玉林,南宁地区,柳州地区,贺州,百色,河池"
			arr[21]="海口,三亚"
			arr[22]="成都,绵阳,德阳,自贡,攀枝花,广元,内江,乐山,南充,宜宾,广安,达川,雅安,眉山,甘孜,凉山,泸州"
			arr[23]="贵阳,六盘水,遵义,安顺,铜仁,黔西南,毕节,黔东南,黔南"
			arr[24]="昆明,大理,曲靖,玉溪,昭通,楚雄,红河,文山,思茅,西双版纳,保山,德宏,丽江,怒江,迪庆,临沧"
			arr[25]="拉萨,日喀则,山南,林芝,昌都,阿里,那曲"
			arr[26]="西安,宝鸡,咸阳,铜川,渭南,延安,榆林,汉中,安康,商洛"
			arr[27]="兰州,嘉峪关,金昌,白银,天水,酒泉,张掖,武威,定西,陇南,平凉,庆阳,临夏,甘南"
			arr[28]="银川,石嘴山,吴忠,固原"
			arr[29]="西宁,海东,海南,海北,黄南,玉树,果洛,海西"
			arr[30]="乌鲁木齐,石河子,克拉玛依,伊犁,巴音郭勒,昌吉,克孜勒苏柯尔克孜,博 尔塔拉,吐鲁番,哈密,喀什,和田,阿克苏"
			arr[31]="香港"
			arr[32]="澳门"
			arr[33]="台北,高雄,台中,台南,屏东,南投,云林,新竹,彰化,苗栗,嘉义,花莲,桃园,宜兰,基隆,台东,金门,马祖,澎湖"

            var pro = document.getElementById("province");
		    var city = document.getElementById("city");
		    var index = pro.selectedIndex -1;
		    var cityArr = arr[index].split(",");

		    city.length = 0;
		    //将城市数组中的值填充到城市下拉框中
		    for(var i=0;i<cityArr.length;i++){
		             city[i]=new Option(cityArr[i],cityArr[i]);
		    }
        },
        /**
         * 展现群组名称
         *
         * @param isowner
         * @param groupName
         * @constructor
         */
        HTML_showGroupName : function(isowner, groupName) {
            var str = '';
            if (isowner && isowner == 'true') {
                str = '<input type="text" class="pull-right" style="width:95%;" value="'
                        + groupName + '"/>';
            } else {
                str = '<span class="pull-right" maxlength="128">' + groupName
                        + '</span>';
            }
            $('#pop').find('div[imtype="im_pop_group_name"]').html(str);
        },

        /**
         * 展现群组公告
         *
         * @param isowner
         * @param groupDeclared
         * @constructor
         */
        HTML_showGroupDeclared : function(isowner, groupDeclared) {
            var str = '';
            if (isowner && isowner == 'true') {
                str = '<textarea class="pull-right" rows="5" style="width:95%;">'
                        + groupDeclared + '</textarea>';
            } else {
                str = '<span class="pull-right" maxlength="128">'
                        + groupDeclared + '</span>';
            }
            $('#pop').find('span[imtype="im_pop_group_declared"]').html(str);
        },

        /**
         * 样式，展现邀请域
         *
         * @constructor
         */
        HTML_showInviteArea : function(obj) {

            $(obj).parent().next().show();
            $(obj).parent().next().next().show();
            $(obj).parent().hide();
        },

        /**
         * 样式，隐藏邀请域
         *
         * @constructor
         */
        HTML_hideInviteArea : function() {
            var tab = $('#pop').find('table[imtype="im_pop_members_add"]');
            var tdObj = tab.children().children().next().children();
            tdObj.children().show();
            tdObj.children().next().hide();
            tdObj.children().next().next().hide();
        },

        /**
         * 处理群组成员列表展现
         *
         * @param obj
         *            member.member;//成员id member.nickName;//昵称
         *            member.speakState;//禁言状态 1:不禁言 2:禁言 member.role;//角色 1:创建者
         *            2:管理员 3：成员 member.sex;//性别 1:男 2：女
         * @param isowner
         * @param groupId
         * @param target
         *            1 讨论组 2 群组
         * @constructor
         */
        DO_pop_show_help_GroupMemberList : function(obj, groupId, target) {
            var str = '<tr><td style="padding:0 0 0 0;"></td></tr>';
            var isowner = false;
            for (var i in obj) {
                var member = obj[i];
                if (!member.member) {
                    continue;
                }
                if (!member.nickName) {
                    member.nickName = member.member;
                }
                if (member.role == 1 || member.role == 2) {
                    if (member.member == IM._user_account) {
                        // 判断是否管理员
                        isowner = true;
                    };
                    str += '<tr contact_you="'
                            + member.member
                            + '">'
                            + '<td><span class="pull-left"><span style="color: #b94a48">[管理员]</span>&nbsp;&nbsp;'
                            + '<a href="javascript:void(0);" onclick="IM.DO_editNickName(this)" '
                            + ' style="max-width:70%;word-break:keep-all;text-overflow:ellipsis;overflow: hidden;text-decoration:none">'
                            + member.nickName + '</a><input style="display:none;padding:0px;margin-bottom:0px" type="text" onblur="IM.DO_checkNick(this);" /></span></td>' + '</tr>';

                } else {
                    str += '<tr contact_you="'
                            + member.member
                            + '">'
                            + '<td>'
                            + '<span class="pull-left"><span style="color: #006dcc">[成员]</span>&nbsp;&nbsp;'
                            + '<a href="javascript:void(0);"  onclick="IM.DO_editNickName(this)" '
                            + 'style="max-width:50%;word-break:keep-all;text-overflow:ellipsis;overflow: hidden;text-decoration:none">'
                            + member.nickName + '</a><input style="display:none;padding:0px;margin-bottom:0px" type="text" onblur="IM.DO_checkNick(this);" /></span>';
                    if (isowner) {
                        str += '<span class="pull-right label label-warning" onclick="IM.EV_deleteGroupMember(\''
                                + groupId
                                + '\',\''
                                + member.member
                                + '\')"> 踢出 </span>';

                        // 禁言状态 1:不禁言 2:禁言
                        if (target == 2) {
                            if (member.speakState == 2) {
                                str += '<span class="pull-right label label-success" onclick="IM.EV_forbidMemberSpeak(\''
                                        + groupId
                                        + '\',\''
                                        + member.member
                                        + '\',1)"> 恢复 </span>';
                            } else {
                                str += '<span class="pull-right label label-important" onclick="IM.EV_forbidMemberSpeak(\''
                                        + groupId
                                        + '\',\''
                                        + member.member
                                        + '\',2)"> 禁言 </span>'
                            }
                        }
                    } else {
                        // 禁言状态 1:不禁言 2:禁言
                        if (member.speakState == 2) {
                            str += '<span class="pull-right label label-inverse" style="cursor: default;"> 已禁言 </span>'
                        }
                    }
                    str += '</td>' + '</tr>';
                }
            }
            // 更新pop弹出框属性
            var obj = $('#pop').find('div[im_isowner]');
            obj.attr('im_isowner', isowner);
            if (isowner || target == 1) {
                $('#pop').find('table[imtype="im_pop_members_add"]').show();
            } else {
                $('#pop').find('table[imtype="im_pop_members_add"]').hide();
            }

            $('#pop').find('table[imtype="im_pop_members"]').html(str);

        },

        HTML_showMemberName : function(memberId, memberName) {
            var trobj = $('#pop').find('tr[contact_you="' + memberId + '"]');
            var nameSpan = trobj.children().children().children().next();
            nameSpan.html(memberName);
        },

        /**
         * 样式，删除群组成员
         *
         * @param memberId
         * @constructor
         */
        HTML_popDeleteMember : function(memberId) {
            var trobj = $('#pop').find('tr[contact_you="' + memberId + '"]');
            trobj.remove();
        },

        /**
         * 样式，新增群组成员
         *
         * @param groupId
         * @param memberId
         * @param memberName
         * @param isowner
         * @param permission
         * @constructor
         */
        HTML_popAddMember : function(groupId, memberId, memberName, isowner,
                target) {
            if ($('#pop').find('tr[contact_you=' + memberId + ']').length > 0) {
                return;
            }
            var str = '<tr contact_you="'
                    + memberId
                    + '">'
                    + '<td>'
                    + '<span class="pull-left"><span style="color: #006dcc">[成员]</span>&nbsp;&nbsp;'
                    + '<a href="javascript:void(0);"  onclick="IM.DO_editNickName(this)" '
                    + 'style="max-width:50%;word-break:keep-all;text-overflow:ellipsis;overflow: hidden;text-decoration:none">'
                    + memberName + '</a><input style="display:none;padding:0px;margin-bottom:0px" type="text" onblur="IM.DO_checkNick(this);" /></span>';
            if (isowner && isowner == 'true') {
                str += '<span class="pull-right label label-warning" onclick="IM.EV_deleteGroupMember(\''
                        + groupId + '\',\'' + memberId + '\')"> 踢出 </span>';
                if (target == 2) {
                    str += '<span class="pull-right label label-important" onclick="IM.EV_forbidMemberSpeak(\''
                            + groupId
                            + '\',\''
                            + memberId
                            + '\',2)"> 禁言 </span>';
                };

            };
            str += '</td>' + '</tr>';
            $('#pop').find('table[imtype="im_pop_members"]').append(str);

        },
        DO_editNickName : function(obj){
            $(obj).hide();
            $(obj).next().show();
            $(obj).next().focus();
        },
        _modifyName : function(obj){
            var memberId = $(obj).parent().parent().parent().attr("contact_you");
            var nick = $(obj).prev().text();
            var belong = $("#pop").find("h3").text();
            var belongIndex = belong.indexOf("：")+1;
            belong = belong.substring(belongIndex);
            var modifyMemberCardBuilder = new RL_YTX.ModifyMemberCardBuilder();
            modifyMemberCardBuilder.setMember(memberId);
            modifyMemberCardBuilder.setBelong(belong);
            modifyMemberCardBuilder.setDisplay(nick);
            RL_YTX.modifyMemberCard(modifyMemberCardBuilder, function(obj){//member belong
                console.log("修改群组成员名片成功！");
            }, function(obj){
                console.log("修改群组成员名片失败！");
            })
        },
        DO_checkNick : function(obj){
            var nick = $(obj).val();
            if('' == nick.trim()){
                $(obj).prev().show();
                $(obj).hide();
                return;
            }else{
	            var regx = /^[\\x00-\\x7F\a-zA-Z\u4e00-\u9fa5_-]{0,6}$/;
	            if(regx.exec(nick) == null){
	                alert_box_im("含有中英文和@_-=\以外的非法字符或昵称长度超过6");
                    return;
	            }else{
                    $(obj).prev().text(nick);
                }
                $(obj).prev().show();
                $(obj).hide();
                $(obj).val("");
            };
            IM._modifyName(obj);
        },
        /**
         * 群组详情页面数据处理
         *
         * @param groupId
         * @param owner
         * @target 1 讨论组 2 群组
         * @constructor
         */
        DO_pop_show : function(groupId, isowner, target) {
            // 弹窗展示
            var str = '<div class="modal" id="pop_group_'
                    + groupId
                    + '" style="position: relative; top: auto; left: auto; right: auto; margin: 0 auto 20px; z-index: 1; max-width: 100%;">'
                    + '<div class="modal-header" im_isowner="'
                    + isowner
                    + '" im_target="'
                    + target
                    + '">'
                    + '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="IM.HTML_pop_hide();">×</button>';
            if (target == 1) {
                str += '<h3>讨论组Id：' + groupId + '</h3>';
            }
            if (target == 2) {
                str += '<h3>群组Id：' + groupId + '</h3>';
            }
            str += '</div>'
                    + '<div class="modal-body">'
                    + '<table class="table table-bordered">'
                    + '<tr>'
                    + '<td>'
                    + '<div style="height:auto;">'
                    + '<table imtype="im_pop_members" class="table table-striped">'
                    + '</table>'
                    + '</div>'
                    + '<div style="height:auto; padding-bottom: 0px;">'
                    + '<table imtype="im_pop_members_add" style="display:none;" class="table table-striped">'
                    + '</table>'
                    + '</div>'
                    + '<a href="javascript:void(0);" class="btn pull-right" style="margin-left:10px;" onclick="IM.DO_cleanChatHis(\''+groupId+'\')">清除聊天记录</a>'
                    + '</td>'
                    + '</tr>'
                    + '<tr>'
                    + '<td>'
                    + '<span class="pull-left">消息免打扰：</span>'
                    + '<span class="pull-right" imtype="im_pop_group_notice">'
                    + '<a href="javascript:void(0);" class="btn btn-primary" style="margin-left:10px;">开启</a>'
                    + '<a href="javascript:void(0);" class="btn" style="margin-left:10px;">关闭</a>'
                    + '</span>' + '</td>' + '</tr>';

            str += '<tr><td>';

            if (target == 2) {
                str += '<div class="pull-left" style="width: 25%;">群组名：</div>';
            }
            if (target == 1) {
                str += '<div class="pull-left" style="width: 25%;">讨论组名：</div>';
            }

            str += '<div class="pull-right" style="width: 75%;" imtype="im_pop_group_name">'
                    + '<input class="pull-right" type="text" style="width:95%;" />'
                    + '</div>'
                    + '</td>'
                    + '</tr>'
                    + '<tr>'
                    + '<td>'
                    + '<span class="pull-left" style="width: 25%;">公告：</span>'
                    + '<span class="pull-right" style="width: 75%;" imtype="im_pop_group_declared">'
                    + '<textarea class="pull-left" style="width:95%;"></textarea>'
                    + '</span>' + '</td>' + '</tr>' + '</table>';

            if (target == 2) {
                if (!isowner) {
                    str += '<div style="text-align: center;">'
                            + '<button class="btn btn-primary" type="button" onclick="IM.EV_quitGroup(\''
                            + groupId + '\')"> 退出群组 </button>' + '</div>';
                }
                str += '</div>';
                if (isowner) {
                    str += '<div class="modal-footer">'
                            + '<a href="javascript:void(0);" class="btn" onclick="IM.EV_dismissGroup(\''
                            + groupId
                            + '\')"> 解散群组 </a>'
                            + '<a href="javascript:void(0);" class="btn btn-primary" onclick="IM.EV_updateGroupInfo(\''
                            + groupId + '\')">保存修改</a>' + '</div>';
                }
            }
            if (target == 1) {

                str += '</div><div class="modal-footer">'
                        + '<a href="javascript:void(0);" class="btn" onclick="IM.EV_quitGroup(\''
                        + groupId + '\')">退出讨论组</a>'
                        + '<a href="javascript:void(0);" class="btn btn-primary" onclick="IM.EV_updateGroupInfo(\''
                        + groupId + '\')">保存修改</a>' + '</div>';
            }
            str += '</div>';

            $('#pop').find('div[class="row"]').html(str);

            IM.HTML_pop_show();
        },

        /**
         * 群组pop层展示
         *
         * @constructor
         */
        HTML_pop_photo_show : function() {
            IM.HTML_LJ_block('photo');

            var navbarHei = $('#im_content_list').height();
            var lvjingHei = $(window).height();

            var pop_photo = $('#pop_photo');

            pop_photo.find('img').css('max-height', lvjingHei - 30).css(
                    'max-width', $(window).width() - 50);
            pop_photo.css('top', navbarHei);

            var d = $(window).scrollTop();
            // a+b=c
            var a = parseInt(pop_photo.find('div[imtype="pop_photo_top"]')
                    .css('margin-top'));
            var b = parseInt(pop_photo.find('div[imtype="pop_photo_top"]')
                    .css('height'));
            var c = $(window).height();

            if (a + b >= c) {
                d = 0;
            } else if (d + b >= c) {
                d = c - b - 20;
            }
            pop_photo.find('div[imtype="pop_photo_top"]').css('margin-top', d);
            $(window).scrollTop(d);

            pop_photo.show();
        },
        HTML_pop_takePicture_show : function() {
            IM.HTML_LJ_block('photo');

            var navbarHei = $('#navbar').height();
            var lvjingHei = $('#lvjing').height();
            var pop_photo = $('#pop_takePicture');

            pop_photo.find('img').css('max-height', lvjingHei - 30).css(
                    'max-width', $(window).width() - 50);
            pop_photo.css('top', navbarHei);

            var d = $(window).scrollTop();
            // a+b=c
            var a = parseInt(pop_photo
                    .find('div[imtype="pop_takePicture_top"]')
                    .css('margin-top'));
            var b = parseInt(pop_photo
                    .find('div[imtype="pop_takePicture_top"]').css('height'));
            var c = $(window).height();

            if (a + b >= c) {
                d = 0;
            } else if (d + b >= c) {
                d = c - b - 20;
            }
            pop_photo.find('div[imtype="pop_takePicture_top"]').css(
                    'margin-top', d);
            $(window).scrollTop(d);

            pop_photo.show();
        },

        /**
         * 图片pop层隐藏
         *
         * @constructor
         */
        HTML_pop_photo_hide : function() {
            IM._msgId = null;
            $('#pop_photo').hide();
            if($('#pop_photo').find("video").length >0){
                if(!document.getElementById("pop_photo").querySelector('video').paused){
                    document.getElementById("pop_photo").querySelector('video').pause();
                }
            };
            IM.HTML_LJ_none();
        },
        /**
         * 拍照pop层隐藏
         *
         * @constructor
         */
        HTML_pop_takePicture_hide : function() {

            $('#pop_takePicture').hide();
            $("#video").attr("src",'');
            IM.HTML_LJ_none();
        },

        /**
         * 样式，群组详情页面显示
         *
         * @constructor
         */
        HTML_pop_show : function() {
            IM.HTML_LJ_block('white');

            var navbarHei = $('#navbar').height();
            var contentHei = $(".scrollspy-content-example").height();
            var pop = $('#pop');
            pop.css('top', navbarHei + 20);
            pop.css('height', contentHei);
            pop.show();
        },

        /**
         * 样式，群组详情页面隐藏
         *
         * @constructor
         */
        HTML_pop_hide : function() {
            $('#pop').hide();
            IM.HTML_LJ_none();
        },

        /**
         * 隐藏提示框
         *
         * @param id
         */
        HTML_closeAlert : function(id) {
            if ('all' == id) {
                IM.HTML_closeAlert('alert-error');
                IM.HTML_closeAlert('alert-info');
                IM.HTML_closeAlert('alert-warning');
                IM.HTML_closeAlert('alert-success');
            } else {
                //$('#hero-unit').css('padding-top', '60px');
                $(document.getElementById(id)).parent().css('top', '0px');
                $(document.getElementById(id)).hide();
                $(document.getElementById(id)).parent().hide();
            }
        },

        /**
         * 显示提示框
         *
         * @param id
         * @param str
         */
        HTML_showAlert : function(id, str, time) {
            var t = 3 * 1000;
            if (!!time) {
                t = time;
            }
            clearTimeout(IM._timeoutkey);
            $('#alert-info').hide();
            $('#alert-warning').hide();
            $('#alert-error').hide();
            $('#alert-success').hide();

            $(document.getElementById(id + '_content')).html(str);
            $('#hero-unit').css('padding-top', '0px');
            $(document.getElementById(id)).parent().css('top', '-5px');
            $(document.getElementById(id)).show();
            $(document.getElementById(id)).parent().show();
            IM._timeoutkey = setTimeout(function() {
                        IM.HTML_closeAlert(id);
                    }, t);
        },

        /**
         * 样式，因高度变化而重置页面布局
         *
         * @constructor
         */
        HTML_resetHei : function() {
            var windowHei = $(window).height();
            /*if (windowHei < 666) {
                windowHei = 666;
            }*/
            if (windowHei < 500) {
                windowHei = 500;
            }
            var windowWid = $(window).width();

            var width =  Math.round(windowWid*0.3);
     	    $("#videoView").css("width",width);
     	   var leftWidth = (windowWid-width)/2;
           $("#videoView").css("left",leftWidth);
            /*if(windowWid < 666){
                //移动端兼容发送菜单栏
                $("#contentEditDiv").css("top","0px");
                $("#sendMenu").css("top","0px");
            }else{
                $("#contentEditDiv").css("top","-10px");
            }*/
            $("#sendMenu").width(windowWid-30);

            var navbarHei = $('#navbar').height();
            var contactTypeHei = $('#im_contact_type').height() + 20 + 6;
            var addContactHei = $('#im_add_contact').height() + 10 + 10;

            var hei = windowHei - navbarHei - 20;
            $(".scrollspy-contact-example").height(hei);
            $(".scrollspy-content-example").height(hei - contactTypeHei - 60);
            $(".scrollspy-content-example").width(windowWid);
            $("#im_send_content").width(windowWid-24-70-5);
            $("#contentEditDiv").width(windowWid-5);



            $('#im_content_list').scrollTop($('#im_content_list')[0].scrollHeight);

            // 绘制滤镜
            /*if ('block' == $('#pop_photo').css('display')) {
                IM.HTML_pop_photo_show();
            } else if ('block' == $('#pop').css('display')) {
                IM.HTML_pop_show();
            } else if ('block' == $('#lvjing').find('img').css('display')) {
                IM.HTML_LJ('black');
            } else if('block' == $('#pop_takePicture').css('display')){
            }else{
                IM.HTML_LJ('black');
            }*/
        },

        /**
         * canvas绘制滤镜层（HTML5）
         *
         * @param style
         *            white, black
         * @constructor
         */
        HTML_LJ : function(style) {
            var lvjing = $('#lvjing');

            var windowWid = $(window).width();
            if (windowWid < 666) {
                //$('#hero-unit').css('padding-left', 20);
                //$('#hero-unit').css('padding-right', 20);
            } else {
                //$('#hero-unit').css('padding-left', 60);
                //$('#hero-unit').css('padding-right', 60);
            }
            $('#hero-unit').css('padding-bottom', 10);
            var navbarHei = $('#navbar').height();
            var concentHei = ($('#hero-unit').height() + 20 + 60 + 30);
            var concentwid = ($('#hero-unit').width()
                    + parseInt($('#hero-unit').css('padding-left')) + parseInt($('#hero-unit')
                    .css('padding-right')));

            var lvjingImgHei = lvjing.find('img').height();
            if (0 == lvjingImgHei)
                lvjingImgHei = 198;

            lvjing.css('top', navbarHei);
            lvjing.css('left', 0);
            lvjing.css('width', '100%');
            lvjing.height(concentHei + 15);

            var canvas = document.getElementById("lvjing_canvas");
            canvas.clear;
            canvas.height = (concentHei + 15);
            canvas.width = concentwid;
            if (!canvas.getContext) {
                console.log("Canvas not supported. Please install a HTML5 compatible browser.");
                return;
            }

            var context = canvas.getContext("2d");
            context.clear;
            context.beginPath();
            context.moveTo(0, 0);
            context.lineTo(concentwid, 0);
            context.lineTo(concentwid, concentHei + 15);
            context.lineTo(0, concentHei + 15);
            context.closePath();
            context.globalAlpha = 0.4;
            if ('white' == style) {
                context.fillStyle = "rgb(200,200,200)";
                lvjing.find('img').hide();
            } else if ('photo' == style) {
                context.fillStyle = "rgb(20,20,20)";
                lvjing.find('img').hide();
            } else if ('black' == style) {
                context.fillStyle = "rgb(0,0,0)";
                var qr = lvjing.find('img');
                qr.css('top', concentHei / 2 - lvjingImgHei / 2);
                qr.css('left', concentwid / 2 - lvjingImgHei / 2);
                qr.show();
            }
            context.fill();
            context.stroke();

            var cha = navbarHei + 4;
            if (navbarHei > 45)
                cha = 0;
            $('#im_body').height(navbarHei + concentHei - 25);
            $('body').height(navbarHei + concentHei - 25);

            setTimeout(function() {
                        $('#ClCache').parent().remove();
                    }, 20);

        },

        /**
         * 样式，滤镜隐藏
         *
         * @constructor
         */
        HTML_LJ_none : function() {
            $('#lvjing').hide();
        },

        /**
         * 样式，滤镜显示
         *
         * @constructor
         */
        HTML_LJ_block : function(style) {
            IM.HTML_LJ(style);
            $('#lvjing').show();
        },

        /**
         * 聊天模式选择
         *
         * @param contact_type --
         *            'C':代表联系人; 'G':代表群组; 'M':代表多渠道客服
         * @constructor
         */
        DO_choose_contact_type : function(contact_type) {
            $('#im_contact_type').find('li').each(function() {
                        $(this).removeClass('active');
                        if (contact_type == $(this).attr('contact_type')) {
                            $(this).addClass('active');
                        }
                    });

            // 选择列表下内容

            $('#im_contact_list').find('li').each(function() {
                        if (contact_type == $(this).attr('contact_type')) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
            /*
            content_typel = $(".contact_type").val();
            if(contact_type == content_typel)
            {
                $(this).show();
            }else
            {
                $(this).hide();
            }
            */

            // 切换样式
            var current_contact_type = IM.HTML_find_contact_type();
            var im_add = $('#im_add');
            if (IM._contact_type_c == current_contact_type) {// 点对点
                im_add.find('i').attr('class', '').addClass('icon-user');
                im_add.find('input[imtype="im_add_contact"]').show();
                im_add.find('input[imtype="im_add_group"]').hide();
                im_add.find('input[imtype="im_add_mcm"]').hide();
                im_add.find('button[imtype="im_add_btn_contact"]').show();
                im_add.find('div[imtype="im_add_btn_group"]').hide();
                $("#voipInvite").show();
                $("#voiceInvite").show();
            } else if (IM._contact_type_g == current_contact_type) {// 群组
                im_add.find('i').attr('class', '').addClass('icon-th-list');
                im_add.find('input[imtype="im_add_contact"]').hide();
                im_add.find('input[imtype="im_add_group"]').show();
                im_add.find('input[imtype="im_add_mcm"]').hide();
                im_add.find('button[imtype="im_add_btn_contact"]').hide();
                im_add.find('div[imtype="im_add_btn_group"]').show();
                $("#voipInvite").hide();
                $("#voiceInvite").hide();
            } else if (IM._contact_type_m == current_contact_type) {// 客服
                im_add.find('i').attr('class', '').addClass('icon-home');
                im_add.find('input[imtype="im_add_contact"]').hide();
                im_add.find('input[imtype="im_add_group"]').hide();
                im_add.find('input[imtype="im_add_mcm"]').show();
                im_add.find('button[imtype="im_add_btn_contact"]').hide();
                im_add.find('div[imtype="im_add_btn_group"]').hide();
            } else {

            }
        },
        /**
         * 群组聊天的时候监控到@符号则展示群组成员列表
         */
        HTML_memberList : function(obj,startIndex){
            var popoverContent = $("#groupMemList_div").find('div[class="popover-content"]');
            popoverContent.empty();
            var num = obj.length;
            $("#groupMemList_div").css("top", (20-(num-1)*30)+"px");

            for(var i=0;i<obj.length;i++){
                var member =obj[i].member;//账号  nickName 昵称
                console.log("member = "+member+";nickName="+obj[i].nickName);
                $("#groupMemList_div").show();
                var str ='';
                if('' !=obj[i].nickName && IM._user_account != member){
                    str +='<div id="'+ member +'" onclick="IM._selectGroupMem(this,\''+startIndex+'\')" '
                        + 'onmousemove="IM._mouseoverStyle(this)" onmouseout="IM._mouseoutStyle(this)">'
                        + obj[i].nickName +'</div>';
                }else if(IM._user_account != member){
                    str +='<div id="'+ member +'" onclick="IM._selectGroupMem(this,\''+startIndex+'\')" '
                        + 'onmousemove="IM._mouseoverStyle(this)" onmouseout="IM._mouseoutStyle(this)" >'
                        + member +'</div>';
                };
                popoverContent.append(str);
            };
           $("#groupMemList_div").show();
           $(window).click(function(){
                $("#groupMemList_div").hide();
           });
        },
        _mouseoverStyle :function(obj){
            $(obj).css("background-color","#E9E9E4");
        },
        _mouseoutStyle :function(obj){
            $(obj).css("background-color","");
        },
        _selectGroupMem :function(obj,startIndex){
             var member = $(obj).attr("id");
             var nickName = $(obj).text();
             if(startIndex == ''){
                $("#im_send_content").append(nickName);
             }else{
	            var currentTab = document.getElementById("im_send_content");
	            IM.insertText(currentTab,nickName,startIndex);
             }
        },
        insertText : function (obj,nickName,startIndex) {

            var startPos = parseInt(startIndex)+1;
	        var endPos = startPos;
	        var cursorPos = startPos;
            var tmpStr = obj.childNodes[0].data;
	        obj.childNodes[0].data = tmpStr.substring(0, startPos) + nickName + tmpStr.substring(endPos, tmpStr.length);
	        cursorPos += nickName.length;
        },

        /**
         * 样式，发送消息
         */
        DO_sendMsg : function() {
            var str = IM.DO_pre_replace_content_to_db();
            $('#im_send_content_copy').html(str);
            $('#im_send_content_copy').find('img[imtype="content_emoji"]').each(function() {
                        var emoji_value_unicode = $(this).attr('emoji_value_unicode');
                        $(this).replaceWith(emoji_value_unicode);
                    });
            var im_send_content = $('#im_send_content_copy').html();

            // 清空pre中的内容
            $('#im_send_content_copy').html('');
            // 隐藏表情框
            $(".chatinterface-ft").addClass("dynamic");
            $(".More").removeClass("show");
            $(".More").removeClass("hide");
            $(".manyemoticon").removeClass("hide");
            $(".bqimg").removeClass("hide");
            $(".bqimg").addClass("show");
            $(".text .kbimg ").addClass("hide");
            $(".text .kbimg ").removeClass("show");
            $(".textms").focus();

            var msgid = new Date().getTime();
            var content_type = '';
            var content_you = '';
            var b = false;

            /*$('#im_contact_list').find('li').each(function() {
                        if ($(this).hasClass('active')) {
                            content_type = $(this).attr('contact_type');
                            content_you = $(this).attr('contact_you');
                        }
                    });*/
            content_type = content_type ? content_type : getQueryString('contact_type');
            content_you  = content_you  ? content_you  : decodeURIComponent(getQueryString('contact_you'));
            if(content_you && content_type)
            {
                b = true;
            }
            if (!b) {
                alert_box_im("请选择要对话的联系人或群组");
                return;
            };

            /*
            b = true;
            */
            // alert(IM._serverNo);xtoz
            // alert(content_you);songzhiq
            // alert(im_send_content);gei
            if(IM._serverNo == content_you){
            	alert_box_im("系统消息禁止回复");
            	return;
            }

            if (im_send_content == undefined || im_send_content == null
                    || im_send_content == '')
            {
                return;
            }
            im_send_content = im_send_content.replace(/&lt;/g, '<').replace(
                    /&gt;/g, '>').replace(/&quot;/g, '"')
                    .replace(/&amp;/g, '&').replace(/&nbsp;/g, ' ');

            console.log('msgid[' + msgid + '] content_type[' + content_type
                    + '] content_you[' + content_you + '] im_send_content['
                    + im_send_content + ']');
            var str = '<p>' + im_send_content + '</p>';
            IM.HTML_sendMsg_addHTML('msg', 1, msgid, content_type, content_you,str);
            // 发送消息至服务器
            if (IM._contact_type_c == content_type) {
                IM.EV_sendTextMsg(msgid, im_send_content, content_you,false);
            } else if (IM._contact_type_g == content_type) {
                IM.EV_sendTextMsg(msgid, im_send_content, content_you,false);
            } else {
                IM.EV_sendMcmMsg(msgid, im_send_content, content_you,false);
            };

        },

        DO_im_image_file : function() {
            var msgid = new Date().getTime();
            var content_type = '';
            var content_you = '';
            var b = false;

            $('#im_contact_list').find('li').each(function() {
                        if ($(this).hasClass('active')) {
                            content_type = $(this).attr('contact_type');
                            content_you = $(this).attr('contact_you');
                            //b = true;
                        }
                    });

            content_type = content_type ? content_type : $(".contact_type").val();
            content_you  = content_you  ? content_you  : $('.contact_you').val();

            if(content_you && content_type)
            {
                b = true;
            }

            if (!b) {
                alert_box_im("请选择要对话的联系人或群组");
                return;
            }
/*
            content_type = $(".contact_type").val();
            content_you = $('.contact_you').val();
            b=true;
*/
            if(IM._serverNo == content_you){
                alert_box_im("系统消息禁止回复");
                return;
            }

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
            var str = '<div class="progress progress-striped active">'
                    + '<div class="bar" style="width: 20%;"></div>'
                    + '</div>'
                    + '<p imtype="msg_attach">'
                    + '<img imtype="msg_attach_src" src="#" style="max-width:'
                    + imgWid
                    + 'px; max-height:'
                    + imgHei
                    + 'px;" onclick="IM.DO_pop_phone(\''+content_you+'\',\''+''+'\',this)" />'
                    /*+ 'px;"  />'*/
                    + '<input imtype="msg_attach_resend" type="file" accept="image/*" style="display:none;margin: 0 auto;" onchange="IM.DO_im_image_file_up(\''
                    + content_you + '_' + msgid + '\', \'' + msgid
                    + '\',null)">' + '</p>';

            // 添加右侧消息
            var id = IM.HTML_sendMsg_addHTML('temp_msg', 4, msgid,
                    content_type, content_you, str);

            $(document.getElementById(id)).find('input[imtype="msg_attach_resend"]').click();

        },

        /**
         * 发送图片，页面选择完图片后出发
         *
         * @param id --
         *            dom元素消息体的id
         * @param msgid
         * @constructor
         */
        DO_im_image_file_up : function(id, oldMsgid, img_blob) {
            var msg = $(document.getElementById(id));
            var oFile = msg.find('input[imtype="msg_attach_resend"]')[0];

            if (!!oFile) {
                oFile = oFile.files[0];
                console.log(oFile.name + ':' + oFile.type);
            } else {
                oFile = img_blob;
            }
            //如果是附件则本地显示
            window.URL = window.URL || window.webkitURL || window.mozURL || window.msURL;
            var url = window.URL.createObjectURL(oFile);
            msg.find('img[imtype="msg_attach_src"]').attr('src', url);

            var receiver = msg.attr('content_you');
            // 查找当前选中的contact_type值 1、IM上传 2、MCM上传
            var current_contact_type = IM.HTML_find_contact_type();
            if (IM._contact_type_m == current_contact_type) {
                IM.EV_sendToDeskAttachMsg(oldMsgid, oFile, 4, receiver);
            } else {
                IM.EV_sendAttachMsg(oldMsgid, oFile, 4, receiver);
            }
        },
        /**
         * 发送本地附件
         */
        DO_im_attachment_file : function() {
            var msgid = new Date().getTime();
            var content_type = '';
            var content_you = '';
            var b = false;

            /*$('#im_contact_list').find('li').each(function() {
                        if ($(this).hasClass('active')) {
                            content_type = getQueryString('contact_type');
                            content_you = decodeURIComponent(getQueryString('contact_you'));
                            //b = true;
                        }
                    });*/

            content_type = getQueryString('contact_type');
            content_you  = decodeURIComponent(getQueryString('contact_you'));

            if(content_you && content_type)
            {
                b = true;
            }
            if (!b) {
                alert_box_im("请选择要对话的联系人或群组");
                $('#im_attachment_file').val('');
                return;
            };

            if(IM._serverNo == content_you){
                alert_box_im("系统消息禁止回复");
                return;
            }

            var str = '<div class="progress progress-striped active">'
                    + '<div class="bar" style="width: 40%;"></div>'
                    + '</div>'
                    + '<p imtype="msg_attach">'
                    + '<a imtype="msg_attach_href" href="javascript:void(0);" target="_blank">'
                    + '<span>'
                    + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="../css/img/im/attachment_icon.png" />'
                    + '</span>'
                    + '<span imtype="msg_attach_name"></span>'
                    + '</a>'
                    + '<span style="font-size: small;margin-left:15px;"></span>'
                    + '<input imtype="msg_attach_resend" type="file" accept="" style="display:none;margin: 0 auto;" onchange="IM.DO_im_attachment_file_up(\''
                    + content_you + '_' + msgid + '\', \'' + msgid + '\')">'
                    + '</p>';
            // 添加右侧消息
            var id = IM.HTML_sendMsg_addHTML('temp_msg', 6, msgid,
                    content_type, content_you, str);

            $(document.getElementById(id)).find('input[imtype="msg_attach_resend"]').click();

        },

        /**
         * 打开本地文件时触发本方法
         *
         * @param id --
         *            dom元素消息体的id
         * @param msgid
         * @constructor
         */
        DO_im_attachment_file_up : function(id, oldMsgid) {
            var msg = $(document.getElementById(id));
            var oFile = msg.find('input[imtype="msg_attach_resend"]')[0].files[0];
            var msgType = 0;
            console.log(oFile.name + ':' + oFile.type);

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
                            + '  onclick="IM.DO_spop_phone(\''+url+'\',this)"/>';
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
            var current_contact_type = IM.HTML_find_contact_type();
            if (IM._contact_type_m == current_contact_type) {
                IM.EV_sendToDeskAttachMsg(oldMsgid, oFile, msgType, receiver);
            } else {
                IM.EV_sendAttachMsg(oldMsgid, oFile, msgType, receiver);
            }

        },

        /**
         * 选择表情
         *
         * @param unified
         * @param unicode
         * @constructor
         */
        DO_chooseEmoji : function(unified, unicode) {

            var content_emoji = '<img imtype="content_emoji" emoji_value_unicode="'
                    + unicode
                    + '" style="width:18px; height:18px; margin:0 1px 0 1px;" src="img/img-apple-64/'
                    + unified + '.png"/>';
            if ($('#im_send_content').children().length <= 1) {

                $('#im_send_content').find('p').detach();
                $('#im_send_content').find('br').detach();
                $('#im_send_content').find('div').detach();
            }

            var brlen = $('#im_send_content').find('br').length - 1;
            $('#im_send_content').find('br').each(function(i) {
                        if (i == brlen) {
                            $(this).replaceWith('');
                        }
                    });

            var plen = $('#im_send_content').find('p').length - 1;
            $('#im_send_content').find('p').each(function(i) {
                        if (i < plen) {
                            $(this).replaceWith($(this).html() + '<br>');
                        } else {
                            $(this).replaceWith($(this).html());
                        }
                    });

            $('#im_send_content').find('div').each(function(i) {
                        if ('<br>' == $(this).html()) {
                            $(this).replaceWith('<br>');
                        } else {
                            $(this).replaceWith('<br>' + $(this).html());
                        }
                    });

            var im_send_content = $('#im_send_content').html();

            if ('<br>' == im_send_content) {
                im_send_content = '';
            } else {
                im_send_content = im_send_content.replace(/(<(br)[/]?>)+/g,
                        '\u000A');
            }

            $('#im_send_content').html(im_send_content + content_emoji);
        },

        DO_pre_replace_content : function() {
            console.log('pre replace content...');
            setTimeout(function() {
                        var str = IM.DO_pre_replace_content_to_db();
                        $('#im_send_content').html(str);
                    }, 20);
        },

        DO_pre_replace_content_to_db : function() {
            var str = $('#im_send_content').html();
            str = str.replace(/<(div|br|p)[/]?>/g, '\u000A');
            str = str.replace(/\u000A+/g, '\u000D');
            str = str.replace(/<[^img][^>]+>/g, '');// 去掉所有的html标记
            str = str.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(
                    /&quot;/g, '"').replace(/&amp;/g, '&').replace(/&nbsp;/g,
                    ' ');
            if ('\u000D' == str) {
                str = '';
            }
            var RegUrl = new RegExp(); 
            RegUrl.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");//jihua.cnblogs.com 
            if (RegUrl.test(str)) { 
                str = "<a href='"+str+"'>"+str+"</a>"; 
            } 
            return str;
        },

        /**
         * 隐藏或显示表情框
         *
         * @constructor
         */
        HTML_showOrHideEmojiDiv : function() {
            if ('none' == $('#emoji_div').css('display')) {
                $('#emoji_div').show();
            } else {
                $('#emoji_div').hide();
            }
        },

        /**
         * 获取当前时间戳 YYYYMMddHHmmss
         *
         * @returns {*}
         */
        _getTimeStamp : function() {
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
        },

        /**
         * 修改用户信息
         */
        DO_userMenu : function() {
            // 构建用户信息页面
            IM.DO_userpop_show();
            // 调用SDK方法获取user信息
            IM.EV_getMyMenu();

        },

        /**
         * 构建用户信息页面
         */
        DO_userpop_show : function() {
            var str = '<div class="modal" id="pop_MyInfo" style="position: relative; top: auto; left: auto; right: auto; margin: 0 auto 20px; z-index: 1; max-width: 100%;">'
                    + '<div class="modal-header" >'
                    + '<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="IM.HTML_pop_hide();">×</button>'
                    + '<h3>个人信息 </h3>'
                    + '</div>'
                    + '<div class="modal-body">'
                    + '<table class="table table-bordered">'
                    + '<tr>'
                    + '<td>'
                    + '<div class="pull-left" style="width: 25%;">昵称：</div>'
                    + '<div class="pull-right" style="width: 75%;" imtype="im_pop_MyInfo_nick">'
                    + '<input id="nickName" class="pull-right" type="text" style="width:95%;" value="" />'
                    + '</div>'
                    + '</td>'
                    + '</tr>'
                    + '<tr>'
                    + '<td>'
                    + '<div class="pull-left" style="width: 25%;">性别：</div>'
                    + '<div class="pull-right" style="width: 75%;" imtype="im_pop_MyInfo_sex">'
                    + '<input name="sex" type="radio" value="1" />男'
                    + '<input name="sex" style="margin-left:20%;" type="radio" value="2" />女'
                    + '</div>'
                    + '</td>'
                    + '</tr>'
                    + '<tr>'
                    + '<td>'
                    + '<div class="pull-left" style="width: 25%;">出生日期：</div>'
                    + '<div class="pull-right" style="width: 75%;" >'
                    + '<input id="birth" size="16" type="text" value="" class="form_date" readonly="readonly">'
                    + '</div></td>'
                    + '</tr>'
                    + '<tr>'
                    + '<td>'
                    + '<span class="pull-left" style="width: 25%;">个性签名：</span>'
                    + '<span class="pull-right" style="width: 75%;" imtype="im_pop_MyInfo_sign">'
                    + '<textarea id="sign" class="pull-left" style="width:95%;"></textarea>'
                    + '</span>'
                    + '</td>'
                    + '</tr>'
                    + '</table>'
                    + '<div class="modal-footer">'
                    + '<a href="javascript:void(0);" class="btn btn-primary" onclick="IM.EV_updateMyInfo()"> 保存修改 </a>'
                    + '<a href="javascript:void(0);" class="btn" onclick="IM.HTML_pop_hide();">取消</a>'
                    + '</div></div>';

            $('#pop').find('div[class="row"]').html(str);
            // 时间控件
            $('.form_date').datetimepicker({
                        language : 'zh-CN',
                        pickTime : true,
                        todayBtn : true,
                        autoclose : true,
                        minView : '2',
                        forceParse : false,
                        format : "yyyy-mm-dd"
                    });
            IM.HTML_pop_show();
        },
        /**
         * 获取当前用户个人信息
         */
        EV_getMyMenu : function() {
            RL_YTX.getMyInfo(function(obj) {
                        $("#nickName").val(obj.nickName);
                        $("[name=sex]").each(function() {
                                    if ($(this).val() == obj.sex) {
                                        $(this).prop("checked", true);
                                    }
                                });
                        $("#birth").val(obj.birth);

                        if (!!obj.sign) {
                            $("#sign").text(obj.sign);
                        }

                    }, function(obj) {
                        if (520015 != obj.code) {
                            alert_box_im("错误码："+obj.code+"; 错误描述："+obj.msg)
                        }
                    });
        },

        /**
         * 整合用户信息传给服务器
         */
        EV_updateMyInfo : function() {

            var nickName = $("#nickName").val();
            if(nickName!=IM._user_account){
                if(nickName.length>6){
                    alert_box_im("昵称长度不能超过6");
                    return ;
                };
            }

            var sex = '';
            $("[name=sex]").each(function() {
                        if (!!$(this).prop("checked")) {
                            sex = $(this).val();
                        }
                    });
            var birth = $("#birth").val();
            var sign = $("#sign").val();
            if(sign.length>100){
                alert_box_im("签名长度不能超过100");
                return;
            }
            var uploadPersonInfoBuilder = new RL_YTX.UploadPersonInfoBuilder(
                    nickName, sex, birth, sign);

            RL_YTX.uploadPerfonInfo(uploadPersonInfoBuilder, function(obj) {
                        IM.HTML_pop_hide();
                        $('#navbar_login_show').find('span')[1].innerHTML = nickName;
                        $('#navbar_login_show').html('<span style="float: left;display: block;font-size: 20px;font-weight: 200;padding-top: 10px;padding-bottom: 10px;text-shadow: 0px 0px 0px;color:#eee" >您好:</span>'
                                + '<a onclick="IM.DO_userMenu()" style="text-decoration: none;cursor:pointer;float: left;font-size: 20px;font-weight: 200;max-width:130px;'
                                + 'padding-top: 10px;padding-right: 20px;padding-bottom: 10px;padding-left: 20px;text-shadow: 0px 0px 0px;color:#eee;word-break:keep-all;text-overflow:ellipsis;overflow: hidden;" >'
                                + nickName
                                + '</a>'
                                /*+ '<span onclick="IM.EV_logout()" style="cursor:pointer;float: right;font-size: 20px;font-weight: 200;'
                                + 'padding-top: 10px;padding-bottom: 10px;text-shadow: 0px 0px 0px;color:#eeeeee">退出</span>'*/);
                         IM._username = nickName;
                    }, function(obj) {
                        alert_box_im("错误码："+obj.code+"; 错误描述："+obj.msg)
                    });
        },

        _cancelTakePic : function() {
            IM.HTML_pop_takePicture_hide();
            var onErr = function(obj){
                console.log("错误码："+obj.code+"; 错误码描述："+obj.msg);
            };
            RL_YTX.photo.cancel();
        },
        DO_takePicture : function() {

            var b = false;
            var content_you = '';

            $('#im_contact_list').find('li').each(function() {
                        if (!!$(this).hasClass('active')) {
                            content_you = $(this).attr('contact_you');
                            //b = true;
                        }
                    });
            content_you  = content_you  ? content_you  : $('.contact_you').val();

            if(content_you)
            {
                b = true;
            }
            if (!b) {
                alert_box_im("请选择要对话的联系人或群组");
                return;
            };
            /*
            content_you = $('.contact_you').val();
            b= true;
            */

            if(IM._serverNo == content_you){
                alert_box_im("系统消息禁止回复");
                return;
            }
            // 拍照按钮的浏览器兼容
            var windowWid = $(window).width();
            if (windowWid < 666) {
                $('#takePhoto').find('div').css("height", "35px");
                $('#takePhoto').find('img').css("height", "30px");
                $('#takePhoto').find('img').css("width", "30px");
            } else {
                $('#takePhoto').find('div').css("height", "50px");
                $('#takePhoto').find('img').css("height", "45px");
                $('#takePhoto').find('img').css("width", "45px");
            };

            var objTag = {};
            var video = document.getElementById("video");
            objTag.tag = video;
            var onCanPlay = function(){
                IM.HTML_pop_takePicture_show();
            };
            var onErr = function(errObj){
                console.log("错误码："+errObj.code+"; 错误描述："+errObj.msg);
                //IM.HTML_pop_takePicture_hide();
                return;
            };
            RL_YTX.photo.apply(objTag,onCanPlay,onErr);

        },

        /**
         * 拍照
         */
        _snapshot : function() {
            var content_type = '';
            var content_you = '';

            $('#im_contact_list').find('li').each(function() {
                        if (!!$(this).hasClass('active')) {
                            content_type = $(this).attr('contact_type');
                            content_you = $(this).attr('contact_you');
                        }
                    });
            /*
            content_type = $(".contact_type").val();
            content_you = $('.contact_you').val();
            */

            var resultObj = RL_YTX.photo.make();
            IM.HTML_pop_takePicture_hide();
            if("174010" == resultObj.code){//没有调用applay方法
                console.log("错误描述："+resultObj.msg);
            }else{
                var windowWid = $(window).width();
                var msgid = new Date().getTime();
                var imgWid = 0;
                var imgHei = 0;
                if (windowWid < 666) {
                    imgWid = 100;
                    imgHei = 150;
                } else {
                    imgWid = 150;
                    imgHei = 200;
                };

                var url = resultObj.blob.url;
                // 初始化右侧对话框消息
                var str1 = '<div class="progress progress-striped active">'
                        + '<div class="bar" style="width: 20%;"></div>'
                        + '</div>'
                        + '<p imtype="msg_attach">'
                        + '<img imtype="msg_attach_src" src="'+url+'" onclick="IM.DO_pop_phone(\''+content_you+'\', \''+''+'\',this)" style="cursor:pointer;max-width:'+ imgWid + 'px;max-height:' + imgHei + 'px;" />'
                        + '<object style="display:none"></object>'
                        + '</p>';

                var id = IM.HTML_sendMsg_addHTML('msg', 4, msgid, content_type,content_you, str1);
                IM.DO_im_image_file_up(id, msgid, resultObj.blob);
            };
        },

        DO_getHistoryMessage : function() {
            var content_list = $('#im_content_list');

            var scrollTop = content_list.scrollTop();
            if (scrollTop == 0) {
                // 获取参数
                var firstMsg = null;
                for (var i = 0; i < content_list.children().length; i++) {
                    var child = content_list.children()[i];
                    if (child.nodeName == "DIV" && child.id != "getHistoryMsgDiv") {// 判断标签是不是div
                        if (child.style.display != "none") {
                            firstMsg = child;
                            break;
                        }
                    }
                }
                IM.EV_getHistoryMessage(firstMsg);
            }
        },
            /**
         * 获取历史消息GetHistoryMessageBuilder
         *
         * @param talker
         *            消息交互者或群组id
         * @param pageSize
         *            获取消息数目 默认为10 最大为50
         * @param version
         *            接收消息的消息版本号 分页条件
         * @param msgId
         *            发送消息的msgId 分页条件
         * @param order
         *            排序方式 1升序 2降序 默认为1
         * @param callback --
         *            function(obj){ var msg = obj[i]; //obj 为数组 msg.version;
         *            //消息版本号 msg.msgType; //消息类型 msg.msgContent; //文本消息内容
         *            msg.msgSender; //发送者 msg.msgReceiver; //接收者 msg.msgDomain;
         *            //扩展字段 msg.msgFileName; //消息文件名 msg.msgFileUrl; //消息下载地址
         *            msg.msgDateCreated; //服务器接收时间 msg.mcmEvent; //是否为mcm消息
         *            0普通im消息 1 start消息 2 end消息 53发送mcm消息 }
         * @param onError --
         *            function(obj){ obj.code; //错误码; }
         * @constructor
         */
        EV_getHistoryMessage : function(firstMsg){
            var getHistoryMessageBuilder = null;
            var pageSize = 20;
            var order = 2;
            var talker = null;
            if(!!firstMsg){
                talker = $(firstMsg).attr("content_you");// 接受者
                console.log("talker" + talker + "," + IM._user_account);
                var msgId = $(firstMsg).attr("id").substring(talker.length+ 1);// 当前条为发送消息则提供参数msgId
                console.log(msgId);


                var sender = $(firstMsg).attr("contactor");
                if (sender != "sender") {
                    getHistoryMessageBuilder = new RL_YTX.GetHistoryMessageBuilder(
                            talker, pageSize,1, msgId, order);
                } else {
                    getHistoryMessageBuilder = new RL_YTX.GetHistoryMessageBuilder(
                            talker, pageSize, 2, msgId, order);
                }
                console.log("talker="+talker+";pageSize="+pageSize+";msgId="+msgId+";(1升序2降序)order="+order);
            }else{

                $('#im_contact_list').find('li').each(function() {
                    if ($(this).hasClass('active')) {
                        talker = $(this).attr('contact_you');
                    }
                });
                /*
                talker = $('.contact_you').val();
                */
                getHistoryMessageBuilder = new RL_YTX.GetHistoryMessageBuilder(
                            talker, pageSize, "", "", order);
                console.log("talker="+talker+";pageSize="+pageSize+";msgId="+msgId+";(1升序2降序)order="+order);
            }

            // 调用接口

            RL_YTX.getHistoryMessage(getHistoryMessageBuilder,
                    function(obj) {
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

                        for (var i = 0; i < obj.length; i++) {
                            var msg = obj[i];
                            var content_you ='';
                            var version = msg.version;
                            if(msg.msgSender == IM._user_account){
                                content_you = msg.msgReceiver;
                            }else{
                                content_you = msg.msgSender;
                            };
                            var str='';
                            if(msg.msgType == 1){//1:文本消息 2：语音消息 3：视频消息 4：图片消息 5：位置消息 6：文件   msg.msgFileName; //消息文件名
                                str = '<pre msgtype="content">' + msg.msgContent + '</pre>';
                            };
                            if(msg.msgType == 3){
                                str = '<img onclick="IM.DO_pop_phone(\''+content_you+'\', \'' + version + '\')" ' +
                                       'videourl="'+msg.msgFileUrl+'" src="'+ msg.msgFileUrlThum +'" ' +
                                       'style="max-width:'+ imgWid + 'px;max-height:' + imgHei + 'px;" />';
                            };
                            if(msg.msgType == 4){
                               str = '<p imtype="msg_attach">'
                                        + '<img imtype="msg_attach_src" src="'+ msg.msgFileUrl +'" style="max-width:'+ imgWid + 'px;max-height:' + imgHei + 'px;" />'
                                        + '</p>';
                            };
                            if(msg.msgType == 6){
                               str = '<p imtype="msg_attach">'
                                    + '<a imtype="msg_attach_href" href="'+ msg.msgFileUrl +'" target="_blank">'
                                    + '<span>'
                                    + '<img style="width:32px; height:32px; margin-right:5px; margin-left:5px;" src="../css/img/im/attachment_icon.png" />'
                                    + '</span>'
                                    + '<span imtype="msg_attach_name">'+ msg.msgFileName +'</span>'
                                    + '</a>'
                                    + '</p>';
                            };
                            if (!!msg && msg.msgSender == IM._user_account) {
                                // 追加自己聊天记录
                                IM.HTML_sendMsg_addPreHTML("msg",msg.msgType, null,null, msg.msgReceiver,str);
                            } else {
                                // 追加对方聊天记录
                                IM.HTML_pushMsg_addPreHTML(msg.msgType,msg.msgReceiver, msg.version,null, true, msg.msgSender,str);
                            }
                        }
                    }, function(obj) {
                        if (obj.code == "540016") {
                            $("#getHistoryMsgDiv").html('<a href="javascript:void(0);" style="font-size: small;position: relative;top: -30px;">没有更多历史消息</a>');
                        } else {
                            alert_box_im("错误码："+obj.code+"; 错误描述："+obj.msg)
                        }

                    });
        },
        /**
         *
         */
        DO_inviteCall : function(callType){

            //发起呼叫
            var receiver = '';
            var b = false;

            $('#im_contact_list').find('li').each(function() {
                        if ($(this).hasClass('active')) {
                            receiver = $(this).attr('contact_you');
                            //b = true;
                        }
                    });
            receiver  = receiver  ? receiver  : $('.contact_you').val();

            if(receiver)
            {
                b = true;
            }

            if (!b) {
                alert_box_im("请选择要对话的联系人或群组");
                return;
            };


            if(IM._serverNo == receiver){
                alert_box_im("系统消息禁止回复");
                return;
            };
            if(callType == 1){
                var view = document.getElementById("receivedVideo");
                var localView = document.getElementById("localVideo");
                localView.muted=true;
                RL_YTX.setCallView(view,localView);
            }else if(callType == 0){
                var view = document.getElementById("voiceCallAudio");
                RL_YTX.setCallView(view,null);
            }

            var makeCallBuilder = new RL_YTX.MakeCallBuilder();
            makeCallBuilder.setCalled(receiver);//John的号码
            makeCallBuilder.setCallType(callType);//呼叫的类型 0 音频 1视频 2 落地电话
            console.log("called = "+receiver+"; "+"callType = "+callType);

            var callId = RL_YTX.makeCall (makeCallBuilder,
                function(){

                }, function callback(obj){
                    alert_box_im("错误码："+obj.code+"; 错误描述："+obj.msg)
                  $("#videoViewCanvas").hide();
                  $("#videoView").hide();
                  $("#cancelVoipCall").hide();
                  $("#voiceCallDiv_audio").hide();
                  $("#cancelVoiceCall").hide();
                  $("#pop_videoView").hide();
                });

        	IM.HTML_videoView(callId,IM._user_account,receiver,callType);
            if(callType == 1){
                $("#videoView").show();
                $("#voiceCallDiv_audio").hide();
                $("#cancelVoipCall").show();
                $("#cancelVoipCall").text("取消");
                $("#acceptVoipCall").hide();
                $("#refuseVoipCall").hide();
            }else if(callType == 0){
                $("#videoView").hide();
                $("#voiceCallDiv_audio").show();
                $("#cancelVoiceCall").show();
                $("#cancelVoiceCall").text("取消");
                $("#acceptVoiceCall").hide();
                $("#refuseVoiceCall").hide();
            }

        },
        /**
         * 展示视频通话窗口
         */
        HTML_videoView : function(callId,caller,called,type){
                var windowWidth = document.body.clientWidth;
                if(windowWidth<666){
                    $("#receivedVideo").removeAttr("width");
                    $("#receivedVideo").css("height","100%");
                    $("#localVideo").removeAttr("width");
                    $("#localVideo").css("height","25%");
                }else{
                    var windowHeight = document.body.clientHeight;
                    $("#pop_videoView").css("display","block");
                    $("#pop_videoView").css("height",windowHeight);

                   //设置视频框长
                   var width =  Math.round(windowWidth*0.3);
            	   $("#videoView").css("width",width);

                    var viewCanva = document.getElementById("videoViewCanvas");
                    var context = viewCanva.getContext("2d");
                    context.clearRect(0,0,windowWidth,windowHeight+200);
                    context.globalAlpha = 0.4;
                    context.fillRect(0,0,windowWidth,windowHeight+200);
                    if(type == 1){
                        var leftWidth = (windowWidth-$("#videoView").width())/2;
                        $("#videoView").css("left",leftWidth);
                        if(!document.getElementById("cancelVoipCall")){
                            var str = '<div style="width:100%;height:30px;background-color:black;margin-top:-7px;' +
                                    'padding-top:5px;padding-bottom:5px;"><a href="javascript:void(0);" id="cancelVoipCall" ' +
                                    'class="btn" >取消</a><a href="javascript:void(0);" id="acceptVoipCall" ' +
                                    'class="btn btn-primary" >接受视频</a><a href="javascript:void(0);" id="refuseVoipCall" ' +
                                    'class="btn" >拒绝视频</a></div>';
                            $("#videoView").append(str);

                            $("#cancelVoipCall").click(function(){
                                IM.DO_cancelVoipCall(callId,caller,called);
                            });
                            $("#acceptVoipCall").click(function(){
                                IM.DO_answerVoipCall(callId,caller,called,type);
                            });
                            $("#refuseVoipCall").click(function(){
                                IM.DO_refuseVoipCall(callId,caller,called);
                            })
                        }else{
                            $("#cancelVoipCall").unbind('click');
                            $("#cancelVoipCall").click(function(){
                                IM.DO_cancelVoipCall(callId,caller,called);
                            });
                            $("#cancelVoipCall").text("取消");
                            $("#acceptVoipCall").unbind('click');
                            $("#acceptVoipCall").click(function(){
                                IM.DO_answerVoipCall(callId,caller,called,type);
                            });
                            $("#refuseVoipCall").unbind('click');
                            $("#refuseVoipCall").click(function(){
                                IM.DO_refuseVoipCall(callId,caller,called);
                            })
                        };
                    }else if(type == 0){
                        var leftWidth = (windowWidth-$("#voiceCallDiv_audio").width())/2;
                        var topHight = (windowHeight-$("#voiceCallDiv_audio").height())/2;
                        $("#voiceCallDiv_audio").css("left",leftWidth);
                        $("#voiceCallDiv_audio").css("top",topHight);
                        if(!document.getElementById("cancelVoiceCall")){
                            var audiostr = '<div style="width:100%;height:30px;background-color:black;margin-top:-7px;' +
                                    'padding-top:10px;background:transparent;"><a href="javascript:void(0);" id="cancelVoiceCall" ' +
                                    'class="btn " >取消</a><a href="javascript:void(0);" id="acceptVoiceCall" ' +
                                    'class="btn btn-primary" >接受音频</a><a href="javascript:void(0);" id="refuseVoiceCall" ' +
                                    'class="btn " >拒绝音频</a></div>';

                            $("#voiceCallDiv_audio").append(audiostr);
                            $("#cancelVoiceCall").click(function(){
                                IM.DO_cancelVoipCall(callId,caller,called);
                            });
                            $("#acceptVoiceCall").click(function(){
                                IM.DO_answerVoipCall(callId,caller,called,type);
                            });
                            $("#refuseVoiceCall").click(function(){
                                IM.DO_refuseVoipCall(callId,caller,called);
                            })
                        }else{
                            $("#cancelVoiceCall").unbind('click');
                            $("#cancelVoiceCall").click(function(){
                                IM.DO_cancelVoipCall(callId,caller,called);
                            });
                            $("#cancelVoiceCall").text("取消");
                            $("#acceptVoiceCall").unbind('click');
                            $("#acceptVoiceCall").click(function(){
                                IM.DO_answerVoipCall(callId,caller,called,type);
                            });
                            $("#refuseVoiceCall").unbind('click');
                            $("#refuseVoiceCall").click(function(){
                                IM.DO_refuseVoipCall(callId,caller,called);
                            })
                        };
                    }



                }
        },

        /**
         * 取消呼叫
         * @param callId 消息的唯一标识
         * @param called 被叫号码
         * @param caller 主叫号码
         */
        DO_cancelVoipCall : function(callId,caller,called){
            document.getElementById("voipCallRing").pause();
            var releaseCallBuilder = new RL_YTX.ReleaseCallBuilder();
            releaseCallBuilder.setCallId(callId);//请求的callId
            releaseCallBuilder.setCaller(caller);//请求的主叫号码，即Tony的号码
            releaseCallBuilder.setCalled(called);// 请求的被叫号码，即John的号码

            RL_YTX.releaseCall(releaseCallBuilder,function(){

            }, function(obj){
                alert_box_im("错误码："+obj.code+"; 错误描述："+obj.msg)
            });
            $("#pop_videoView").hide();

        },
        /**
         * 接受voipCall
         * @param callId 唯一消息标识
         * @param caller 主叫
         */
       DO_answerVoipCall : function(callId,caller,called,acceptType){
            if(acceptType == 1){
                document.getElementById("voipCallRing").pause();
                //设置页面view句柄
                var view = document.getElementById("receivedVideo");
                var localView = document.getElementById("localVideo");
                localView.muted=true;
                RL_YTX.setCallView(view,localView);

                $("#cancelVoipCall").text("结束");
                $("#cancelVoipCall").show();
                $("#acceptVoipCall").hide();
                $("#refuseVoipCall").hide();
            }else if(acceptType == 0){
                document.getElementById("voipCallRing").pause();
                 //设置页面view句柄
                var view = document.getElementById("voiceCallAudio");
                RL_YTX.setCallView(view,null);

                $("#cancelVoiceCall").text("结束");
                $("#cancelVoiceCall").show();
                $("#acceptVoiceCall").hide();
                $("#refuseVoiceCall").hide();
            }
            var acceptCallBuilder = new RL_YTX.AcceptCallBuilder ();
            acceptCallBuilder.setCallId (callId);//请求的callId，
            acceptCallBuilder.setCaller(caller);//请求的主叫号码，即Tony的号码
            console.log("callId="+callId+"; caller="+caller);
            RL_YTX.accetpCall (acceptCallBuilder,
              function(){

              }, function callback(obj){
                 alert_box_im("错误码："+obj.code+"; 错误描述："+obj.msg)
              });
       },
       /**
        * 拒绝voipCall
        * @param callId 唯一消息标识
        * @param caller 主叫
        * @param reason 拒绝原因
        */
       DO_refuseVoipCall : function(callId,caller,refuseType){
            document.getElementById("voipCallRing").pause();
            var rejectCallBuilder = new RL_YTX.RejectCallBuilder();
            rejectCallBuilder.setCallId(callId);//请求的callId
            rejectCallBuilder.setCaller(caller);//请求的主叫号码，即Tony的号码

            RL_YTX.rejectCall (rejectCallBuilder,function(){

            }, function(obj){
                alert_box_im("错误码："+obj.code+"; 错误描述："+obj.msg)
            });
            $("#pop_videoView").hide();
       },
       DO_fireMsg : function(obj){

            if($(obj).attr("class").indexOf("active") > -1){
	            $(obj).removeClass("active");
            }else{
                $(obj).addClass("active");
            }
       },
       /**
        * 录音发送
        */
       DO_startRecorder : function(){
            var b = false;
            var content_you = '';

            $('#im_contact_list').find('li').each(function() {
                        if ($(this).hasClass('active')) {
                            content_you = $(this).attr('contact_you');
                            //b = true;
                        }
                    });

            content_you  = content_you  ? content_you  : $('.contact_you').val();

            if(content_you)
            {
                b = true;
            }

            if (!b) {
                alert_box_im("请选择要对话的联系人或群组");
                return;
            };
            if(IM._serverNo == content_you){
                alert_box_im("系统消息禁止回复");
                return;
            }
            var windowWidth = document.body.clientWidth;
            var windowHeight = document.body.clientHeight;
            var recorderCanva = document.getElementById("recorderCanvas");
            var context = recorderCanva.getContext("2d");
            context.clearRect(0,0,windowWidth,windowHeight+200);
            context.globalAlpha = 0.4;
            context.fillRect(0,0,windowWidth,windowHeight+200);
            $("#pop_recorder").show();
            $("#recorderAudio").parent().css("top",windowHeight/2);
            var width = $("#recorderAudio").parent().width();
            $("#recorderAudio").parent().css("left",(windowWidth - width)/2+"px");
            var obj = new Object;
            obj.tag = document.getElementById("recorderAudio");
            RL_YTX.audio.apply(obj,function(){
            	//初始化录音成功回调
            },function(resp){
                alert_box_im("错误码："+resp.code+"; 错误描述："+resp.msg)
                $("#recorderCanvas").hide();
                $("#pop_recorder").hide();
            });
       },
       DO_endRecorder : function(){
    	var dataBlob = RL_YTX.audio.make();
    	var code = dataBlob.code;
    	if(code == 200){
    		 IM._sendRecorder(dataBlob.blob);
    	}else{
    		 $("#pop_recorder").hide();
             $("#recorderAudio").attr("src","");
    	}
       },
       _sendRecorder : function (blob) {
            // 初始化右侧对话框消息
            var str1 = '<div class="progress progress-striped active">'
                    + '<div class="bar" style="width: 20%;"></div>'
                    + '</div>'
                    + '<p imtype="msg_attach">'
                    + '<audio controls="controls" src="'+blob.url+'" ></audio>'
                    + '<object style="display:none"></object>'
                    + '</p>';
	        var msgid = new Date().getTime();
            var content_type = '';
            var content_you = '';

            $('#im_contact_list').find('li').each(function() {
                        if ($(this).hasClass('active')) {
                            content_type = $(this).attr('contact_type');
                            content_you = $(this).attr('contact_you');
                            b = true;
                        }
                    });
            /*
            content_type = $(".contact_type").val();
            content_you = $('.contact_you').val();
            b = true;
            */

            IM.HTML_sendMsg_addHTML('msg', 2, msgid, content_type,content_you, str1);

            $("#pop_recorder").hide();
            $("#recorderAudio").attr("src","");
            var current_contact_type = IM.HTML_find_contact_type();
            if (IM._contact_type_m == current_contact_type) {
                IM.EV_sendToDeskAttachMsg(msgid, blob, 2, content_you);
            } else {
                IM.EV_sendAttachMsg(msgid, blob, 2, content_you);
            };

  },
  EV_cancel:function(){
	  RL_YTX.audio.cancel();
	  $("#pop_recorder").hide();
      $("#recorderAudio").attr("src","");
  },
  /**
   * 禁止发送附件
   */
  SendFile_isDisable:function(){
	  $("#file_button").append('<span style="color:#FF0000; font-size: 16px; font-weight:bold;margin-left:-15px;">X</span>');
	  $("#file_button").removeAttr("onclick");
  },

  /**
   * 禁止发送音视频
   */
  SendVoiceAndVideo_isDisable:function(){
	  $("#voipInvite").append('<span style="color:#FF0000; font-size: 16px; font-weight:bold;margin-left:-15px;">X</span>');
	  $("#voipInvite").removeAttr("onclick");

	  $("#voiceInvite").append('<span style="color:#FF0000; font-size: 16px; font-weight:bold;margin-left:-15px;">X</span>');
	  $("#voiceInvite").removeAttr("onclick");
  },
  /**
   * 不支持usermedie
   */
  Check_usermedie_isDisable:function(){

    $("#camera_button").removeAttr("onclick");
    $("#camera_button").html('<i class="icon-picture"></i>');
    $("#camera_button").click(function(){
        IM.DO_im_image_file();
    });

	$("#startRecorder").append('<span style="color:#FF0000; font-size: 16px; font-weight:bold;margin-left:-15px;">X</span>');
	$("#startRecorder").removeAttr("onclick");

	IM.SendVoiceAndVideo_isDisable();
  },

  /**
   * 切换按钮
   */
  Check_login : function(){
	  $("div[name = 'loginType']").each(function(){
			var display = $(this).css("display");
			if(display == 'none'){
				IM._loginType = $(this).attr("id");
				$(this).show();
			}else{
				$(this).hide();
			}
		  });
  },
  isNull:function(value){
	  if(value == '' || value == undefined
              || value == null){
		  return true;
	  }
  },
  DO_cleanChatHis : function(groupId){
      $('#im_content_list > div[content_you="'+groupId+'"]').each(function(){
          $(this).remove();
      });
  },
    /**
    * 桌面提醒功能
    * @param you_sender 消息发送者账号
    * @param nickName 消息发送者昵称
    * @param you_msgContent 接收到的内容
    * @param msgType 消息类型
    * @param isfrieMsg 是否阅后即焚消息
    * @param isCallMsg 是否音视频呼叫消息
    */
    DO_deskNotice:function(you_sender,nickName,you_msgContent,msgType,isfrieMsg,isCallMsg){
        console.log("you_msgContent="+you_msgContent+"；msgType="+msgType+"；isCallMsg="+isCallMsg);
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
        if(!IM._Notification || !IM.checkWindowHidden()){
            return ;
        }

        var instance = new IM._Notification(
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

    },
    /**
     * 获取hidden属性
     */
    getBrowerPrefix : function() {
        return 'hidden' in document ? null : function() {
            var r = null;
            ['webkit', 'moz', 'ms', 'o'].forEach(function(prefix) {
                if((prefix + 'Hidden') in document) {
                    return r = prefix;
                }
            });
            return r;
        }();
    },

    checkWindowHidden : function(){
        var prefix = IM.getBrowerPrefix();
        //不支持该属性
        if(!prefix){
            return document['hidden'];
        }
        return document[prefix+'Hidden'];
    }

};
})();
