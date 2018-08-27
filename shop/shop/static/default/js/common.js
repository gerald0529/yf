// select插件效果


$(document).ready(function() {
//下拉效果
    function select2Temp (obj) {
      if (!obj.id) {
        return obj.text;
      }
      var style = "";
      if(obj.id == '0'){
        style="style='color:#444;'"
      }else if(obj.disabled==false){
        style="style='color:#fe3a3a'"
      }else if(obj.disabled==true){
        style="style='color:#999'"
      }       
      var v = obj.text;
      var arr = v.split(" ");
      if(arr[1]){
        arr[1] = "<i class='fr'>"+arr[1]+"</i>"
       
          var $obj = $(
            '<span '+style+'> ' + arr[0]+ arr[1] + '</span>'
          );
      
      }else{
        var $obj = $(
            '<span '+style+'> ' + arr[0]+ '</span>'
          );
      }
      
      return $obj;
    };

    //选中效果
    function select2Selection(obj){
            var v = obj.text;
            var arr = v.split(' ');
            var curstyle = "";
            console.log(arr);
          if(obj.id == '0'){
            curstyle="style='color:#444;'"
          }else if(obj.disabled==false){
            curstyle="style='color:#fe3a3a;'"
          }
          if(arr[0]){
            var $curobj=$('<i '+curstyle+'> ' + arr[0]+ '</i>');
          }
             return $curobj;
    }
    
    var order_redpacket_info = $("input[name='redpacket']").val();
    var order_is_discount = $("input[name='redpacket']").data("is_discount");
    var voucher_info = $("input[name='voucher']").val();
    if(voucher_info){
        $(".shop_voucher").select2({
            width:"resolve",
            placeholder: voucher_info,
            templateResult: select2Temp,
            templateSelection: select2Selection,
            minimumResultsForSearch: -1,
            language: {
                noResults: function (params) {
                    $(".shop_voucher").select2("close");
                }
             }
        });
    }
    if(order_redpacket_info){
        $("#redpacket").select2({
            width:"resolve",
            placeholder: order_redpacket_info,
            templateResult: select2Temp,
            templateSelection: select2Selection,
            minimumResultsForSearch: -1,
            language: {
                noResults: function (params) {
                    $("#redpacket").select2("close");
                }
             }
        });
    }
    
    if(order_is_discount == 1) $(".select2").hide();
});
FuckInternetExplorer();    
 

function FuckInternetExplorer() {
    var browser = navigator.appName;
    var b_version = navigator.appVersion;
    var version = b_version.split(";");
    if (version.length > 1) {
        var trim_Version = parseInt(version[1].replace(/[ ]/g, "").replace(/MSIE/g, ""));
        if (trim_Version < 9) {  
           
            document.write('<div class="notifyjs-bootstrap-base notifyjs-bootstrap-error">建议IE8以上版本!</div>'); 
            return false;
        }
    } 
    return true;
}


function lazyload(){ 
 
    $("img.lazy").lazyload({ 
        effect: "fadeIn" 
         
    });
    
    $('img.lazy').on('load',function(){
       $(window).trigger('scroll') 
    });
   

} 




function chat(ch_u){
     if(ch_u == getCookie('user_account')){  
         alert_box('不能跟自己聊天'); 
         return ;
     }

     var inner = $('#imbuiler')[0].contentWindow;
     inner.bottom_bar();
     $('#imbuiler').show();
     //查看聊天右侧的用户列表有没有，没有就点一下最下面的就出来了。
     var dis = $('#imbuiler').contents().find('.chat-list').css('display'); 
     if(dis!='block'){
         $('#imbuiler').contents().find('.bottom-bar a').click();     
     }  
     inner.chat(ch_u);
}
var Public = Public || {};
var Business = Business || {};
Public.isIE6 = !window.XMLHttpRequest;	//ie6

window.im_appId = '';
window.im_appToken = '';

Public.getDefaultPage = function ()
{
    var win = window.self;
    var i = 20;//最多20层，防止无限嵌套
    try
    {
        do {
            if (!(/index.php\?/.test(win.location.href)))
            {
                return win;
            }
            win = win.parent;
            i--;
        } while (i > 0);
    } catch (e)
    {
        return win;
    }
    return win;
};

function getQueryString(name){
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r!=null) return r[2]; return '';
}


/*
 通用post请求，返回json
 url:请求地址， params：传递的参数{...}， callback：请求成功回调
 */
Public.ajaxPost = function(url, params, callback, errCallback, completeCallback){
    var loading;
    var $this = $(this);
    var preventTooFast = 'ui-btn-dis';
    $.ajax({
        type: "POST",
        url: url,
        data: params,
        dataType: "json",
        beforeSend : function(){
            $this.addClass(preventTooFast);
            myTimer = setTimeout(function(){
                $this.removeClass(preventTooFast);
            },2000)
            //loading = $.dialog.tips('请稍候...', 1000, 'loading.gif', true);
        },
        complete : function(){
            //loading.close();
            completeCallback && completeCallback();
        },
        success: function(data, status){
            /*if(data.status != 200){
             var defaultPage = Public.getDefaultPage();
             var msg = data.msg || '出错了=. =||| ,请点击这里拷贝错误信息 :)';
             var errorStr = msg;
             if(data.data.error){
             var errorStr = '<a id="myText" href="javascript:window.clipboardData.setData("Text",data.error);alert("详细信息已经复制到剪切板，请拷贝给管理员！");"'+msg+'</a>'
             }
             defaultPage.Public.tips({type:1, content:errorStr});
             return;
             }*/
            callback(data);
        },
        error: function(err,ms){
            //parent.Public.tips({type: 1, content : '服务端响应错误！'});
            errCallback && errCallback(err);
        }
    });
};

//生成树
Public.zTree = {
    zTree: {},
    opts: {
        showRoot: true,
        defaultClass: '',
        disExpandAll: false,//showRoot为true时无效
        callback: '',
        rootTxt: '全部分类'
    },
    setting: {
        view: {
            dblClickExpand: false,
            showLine: true,
            selectedMulti: false
        },
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: ""
            }
        },
        callback: {
            //beforeClick: function(treeId, treeNode) {}
        }
    },
    _getTemplate: function (opts)
    {
        this.id = 'tree' + parseInt(Math.random() * 10000);
        var _defaultClass = "ztree";
        if (opts)
        {
            if (opts.defaultClass)
            {
                _defaultClass += ' ' + opts.defaultClass;
            }
        }
        return '<ul id="' + this.id + '" class="' + _defaultClass + '"></ul>';
    },
    init: function ($target, opts, setting, callback)
    {
        if ($target.length === 0)
        {
            return;
        }
        var self = this;
        self.opts = $.extend(true, self.opts, opts);
        self.container = $($target);
        self.obj = $(self._getTemplate(opts));
        self.container.append(self.obj);
        setting = $.extend(true, self.setting, setting);


        var defaultPage = Public.getDefaultPage();

        if (defaultPage.SYSTEM.goodsCatInfo)
        {
            if (self.opts.showRoot)
            {
                defaultPage.SYSTEM.goodsCatInfo.shift();
            }
            self._callback(defaultPage.SYSTEM.goodsCatInfo);
        }
        else
        {
            Public.ajaxPost(opts.url || '', {}, function (data)
            {
                if (data.status === 200 && data.data)
                {
                    defaultPage.SYSTEM.goodsCatInfo = data.data.items;
                    //defaultPage.SYSTEM.goodsCatInfo .unshift({name:'全部分类',id:-1});
                    self._callback(data.data.items);
                }
                else
                {
                    Public.tips({
                        type: 2,
                        content: "加载失败！"
                    });
                }
            });
        }
        /*
         Public.ajaxPost(opts.url || '', {}, function(data) {
         if (data.status === 200 && data.data) {
         self._callback(data.data.items);
         } else {
         Public.tips({
         type: 2,
         content: "加载失败！"
         });
         }
         });
         */
        return self;
    },
    _callback: function (data)
    {
        var self = this;
        var callback = self.opts.callback;
        if (self.opts.showRoot)
        {
            data.unshift({name: self.opts.rootTxt, id: -1});
            self.obj.addClass('showRoot');
        }
        if (!data.length)
        {
            return;
        }
        self.zTree = $.fn.zTree.init(self.obj, self.setting, data);
        //self.zTree.selectNode(self.zTree.getNodeByParam("id", 101));
        self.zTree.expandAll(!self.opts.disExpandAll);
        if (callback && typeof callback === 'function')
        {
            callback(self, data);
        }
    }
};

//分类下拉框
Public.categoryTree = function ($obj, opts)
{
    if ($obj.length === 0)
    {
        return;
    }

    opts = opts ? opts : opts = {};
    var opts = $.extend(true, {
        url: SITE_URL + '?ctl=Goods_Cat&met=cat&typ=json&type_number=goods_cat&is_delete=2',
        inputWidth: '145',
        width: '',//'auto' or int
        height: '240',//'auto' or int
        trigger: true,
        defaultClass: 'ztreeCombo',
        disExpandAll: false,//展开闭合
        defaultSelectValue: 0,
        showRoot: true,
        treeSettings: {
            callback: {
                beforeClick: function (treeId, treeNode)
                {
                    var check = (treeNode && !treeNode.isParent);

                    if (!check)
                    {
                        //alert("只能选择最后一级分类...")
                    }
                    else
                    {
                        if (_Combo.obj)
                        {
                            _Combo.obj.val(treeNode.name);
                            _Combo.obj.data('id', treeNode.id);
                            _Combo.hideTree();
                        }
                    }

                    return check;
                },
                onClick: function (treeId, treeNode)
                {
                    _Combo.obj.trigger("change");
                }
            }
        }
    }, opts);
    var _Combo = {
        container: $('<span class="ui-tree-wrap" style="width:' + opts.inputWidth + 'px"></span>'),
        obj: $('<input type="text" class="input-txt" style="width:' + (opts.inputWidth - 26) + 'px" id="' + $obj.attr('id') + '" autocomplete="off" readonly value="' + ($obj.val() || $obj.text()) + '">'),
        trigger: $('<span class="trigger"></span>'),
        data: {},
        init: function ()
        {
            var _parent = $obj.parent();
            var _this = this;
            $obj.remove();
            this.obj.appendTo(this.container);
            if (opts.trigger)
            {
                this.container.append(this.trigger);
            }
            this.container.appendTo(_parent);
            opts.callback = function (publicTree, data)
            {
                _this.zTree = publicTree;
                //_this.data = data;
                if (publicTree)
                {
                    publicTree.obj.css({
                        'max-height': opts.height
                    });
                    for (var i = 0, len = data.length; i < len; i++)
                    {
                        _this.data[data[i].id] = data[i];
                    }
                    ;
                    if (opts.defaultSelectValue !== '')
                    {
                        _this.selectByValue(opts.defaultSelectValue);
                    }
                    ;
                    _this._eventInit();
                }
            };
            this.zTree = Public.zTree.init($('body'), opts, opts.treeSettings);
            return this;
        },
        showTree: function ()
        {
            if (!this.zTree)
            {
                return;
            }
            if (this.zTree)
            {
                var offset = this.obj.offset();
                var topDiff = this.obj.outerHeight();
                var w = opts.width ? opts.width : this.obj.width();
                var _o = this.zTree.obj.hide();
                _o.css({width: w, top: offset.top + topDiff, left: offset.left - 1});
            }
            var _o = this.zTree.obj.show();
            this.isShow = true;
            this.container.addClass('ui-tree-active');
        },
        hideTree: function ()
        {
            if (!this.zTree)
            {
                return;
            }
            var _o = this.zTree.obj.hide();
            this.isShow = false;
            this.container.removeClass('ui-tree-active');
        },
        _eventInit: function ()
        {
            var _this = this;
            if (opts.trigger)
            {
                _this.trigger.on('click', function (e)
                {
                    e.stopPropagation();
                    if (_this.zTree && !_this.isShow)
                    {
                        _this.showTree();
                    }
                    else
                    {
                        _this.hideTree();
                    }
                });
            }
            ;
            _this.obj.on('click', function (e)
            {
                e.stopPropagation();
                if (_this.zTree && !_this.isShow)
                {
                    _this.showTree();
                }
                else
                {
                    _this.hideTree();
                }
            });
            if (_this.zTree)
            {
                _this.zTree.obj.on('click', function (e)
                {
                    e.stopPropagation();
                });
            }
            // $(document).click(function ()
            // {
            //     _this.hideTree();
            // });
        },
        getValue: function ()
        {
            var id = this.obj.data('id') || '';
            if (!id)
            {
                var text = this.obj.val();
                if (this.data)
                {
                    for (var item in this.data)
                    {
                        if (this.data[item].name === text)
                        {
                            id = this.data[item].id;
                        }
                    }
                }
            }
            return id;
        },
        getText: function ()
        {
            if (this.obj.data('id'))
            {
                return this.obj.val();
            }
            return '';
        },
        selectByValue: function (value)
        {
            if (value !== '')
            {
                if (this.data)
                {
                    this.obj.data('id', value);
                    this.obj.val(this.data[value].name);
                    
                }
            }
            return this;
        }
    };

    var combo = _Combo.init();
    var nodeList = [], searchName;

    //搜索事件
    if (opts.searchByName && $(opts.searchByName).get(0) && opts.searchButton && $(opts.searchButton).get(0)) {
        $(opts.searchButton).click(function (){
            combo.showTree();
            searchName = $(opts.searchByName).val();
            var zTree = $.fn.zTree.getZTreeObj(combo.zTree.id);
            zTree.showNodes(zTree.transformToArray(zTree.getNodes()));
            if (searchName) {
                nodeList = zTree.getNodesByFilter(function (node) {
                    return node.name.toString().indexOf(searchName) > -1;
                }); // 查找节点集合
                findNodes(nodeList);
            }
        });
    }

    function findNodes(searchNodeList) {
        var zTree = $.fn.zTree.getZTreeObj(combo.zTree.id),
            allNodes = zTree.getNodes();

        zTree.hideNodes(allNodes); //隐藏所有节点

        if (searchNodeList) {
            var showNodesList = [], parentNodeList = [];
            for( var i=0, l=searchNodeList.length; i<l; i++) {
                parentNodeList = findParents(searchNodeList[i]);
                $.merge(showNodesList, parentNodeList);
            }

            zTree.showNodes(showNodesList);
            //获取ids
            var nodeTIds = $.map(showNodesList, function (n, i) {
                return n.tId;
            });

            //隐藏不需要的子节点
            var hideChildrenNodes = [];
            for( var i=0, l=showNodesList.length, children; i<l; i++) {
                children = findChildren(showNodesList[i]);
                if (children) {
                    for( var m=0, n=children.length; m<n; m++) {
                        if ( $.inArray(children[m].tId, nodeTIds) == -1 ) {
                            hideChildrenNodes.push(children[m]);
                        }
                    }
                }
            }
            zTree.hideNodes(hideChildrenNodes);
        }
    }

    function findParents(node) {
        return node.getPath();
    }

    function findChildren(node) {
        return node.children;
    }

    return combo;
};

(function($) {
    $.fn.yf_show_dialog = function(options) {

        var that = $(this);
        var settings = $.extend({}, {width: 480, title: '', close_callback: function() {}}, options);

        var init_dialog = function(title) {
            var _div = that;
            that.addClass("dialog_wrapper");
            that.wrapInner(function(){
                return '<div class="dialog_content">';
            });
            that.wrapInner(function(){
                return '<div class="dialog_body" style="position: relative;border-radius:3px; ">';
            });
            that.find('.dialog_body').prepend('<h3 class="dialog_head ui_title" style="cursor: move;"><span class="dialog_title"><span class="dialog_title_icon">'+settings.title+'</span></span><span class="dialog_close_button iconfont icon-cuowu"></span></h3>');
            that.append('<div style="clear:both;"></div>');

            $(".dialog_close_button").click(function(){
                settings.close_callback();
                _div.hide();
            });

            that.draggable({handle: ".dialog_head"});
        };

        if(!$(this).hasClass("dialog_wrapper")) {
            init_dialog(settings.title);
        }

        settings.left = $(window).scrollLeft() + ($(window).width() - settings.width) / 2;
        settings.top  = ($(window).height() - $(this).height()) / 2;
        $(this).attr("style","display:none; z-index: 1100;background-color:; position: fixed; width: "+settings.width+"px; left: "+settings.left+"px; top: "+settings.top+"px;");
        $(this).show();

    };
})(jQuery);



Public.tips = function(options){
    var defaults = {
        "type": 0,
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };

    var opts = $.extend({},defaults,options);

    // toastr.clear();

    if (1 == parseInt(opts.type))
    {
        toastr.error(opts.content, null, opts);
    }
    else if (2 == parseInt(opts.type))
    {
        toastr.warning(opts.content, null, opts);
    }
    else if (3 == parseInt(opts.type))
    {
        toastr.success(opts.content, null, opts);
    }
    else
    {
        toastr.info(opts.content, null, opts);
    }
}

Public.tips.info = function(msg)
{
    Public.tips({type: 4, content: msg});
}

Public.tips.error = function(msg)
{
    Public.tips({type: 1, content: msg});
}


Public.tips.success = function(msg)
{
    Public.tips({type: 3, content: msg});
}


Public.tips.warning = function(msg)
{
    Public.tips({type: 2, content: msg});
}


function ucenterLogin(UCENTER_URL, SITE_URL, refresh_flag)
{
    $.ajax({
        type: "get",
        url: UCENTER_URL + "?ctl=Login&met=checkStatus&typ=json",
        dataType: "jsonp",
        jsonp: "jsonp_callback",
        success: function(data){
            if (200 == data.status)
            {
                var key = $.cookie('key');
                var u = $.cookie('id');

                if (u && key && u==data.data.us)
                {
                    getUserInfoNav()
                }
                else
                {
                    //退出
                    $.cookie('id', null);
                    $.cookie('key', null);

                    //本系统登录API
                    $.ajax({
                        type: "get",
                        url: SITE_URL + "?ctl=Login&met=check&typ=json",
                        data:{ks:data.data.ks, us:data.data.us},
                        dataType: "jsonp",
                        jsonp: "jsonp_callback",
                        success: function(data){
                            console.info(data);
                            if (200 == data.status)
                            {
                                //本系统登录API
                                $.cookie('id',data.data.user_id);
                                $.cookie('key',data.data.key);

                                //ajax 调用
                                if (refresh_flag)
                                {
                                    window.location.reload();
                                }
                                else
                                {
                                    getUserInfoNav()
                                }
                                //
                            }
                        },
                        error: function(){
                            //alert('error!');
                        }
                    });
                }
            }
            else
            {
                //退出
                $.cookie('id', null);
                $.cookie('key', null);

                //ajax 调用
                if (refresh_flag)
                {
                    window.location.reload();
                }
                else
                {
                    getUserInfoNav()
                }
            }
        },
        error: function(){
            getUserInfoNav()
        }
    });
}

function getUserInfoNav()
{
    $.ajax({
        type: "GET",
        url: SITE_URL + "?ctl=Index&met=getUserLoginInfo&typ=json",
        data: {},
        dataType: "json",
        success: function(data){
            var html = '';
           
            $.each(data, function(commentIndex, comment){

            });


            $('#login_top').find('.header_select_province').siblings().remove();
            $('#login_top').prepend(data.data[0]);
            $('#login_tright').html(data.data[1]);

            //用户登录 - 加载聊天窗口
            if(typeof(IM_STATU)!=='undefined' && IM_STATU==1 && data.data[3])
            {
                $.ajax({
                    type: "GET",
                    url: "index.php?ctl=Im&met=im&typ=json",
                    data: {},
                    dataType: "json",
                    success: function(data){
                        console.info(data);
                        if(data.status == 200){
                            window.im_appId    = data.data.im_appId;
                            window.im_appToken = data.data.im_appToken;
                            url = 'index.php?ctl=Index&met=chat';
                            $("#chat").load(url, function(){
                            });
                        }
                    }
                });

            }


        }
    });
    $(".set").hover(function(){
        $(this).find(".sub-menu").css("display","block");
        $(this).find("i").css("transform","rotate(-180deg)");

    },function(){
        $(this).find(".sub-menu").css("display","none");
        $(this).find("i").css("transform","rotate(1deg)");
    })
}

function load_goodseval(url,div) {
    $("#" + div).load(url, function(){
    });
}

//console
if ( !window.console ) {
    window.console = {
        info: function () {},
        log: function () {}
    };
}

//加入购物车时，获取最新的购物车列表
function getCartList()
{
    var url = SITE_URL + '?ctl=Index&met=toolbar';
    $(".J-global-toolbar").load(url, function(){
    });
}

//获取购物车商品数量
function getCartNum() {
    Public.ajaxPost(SITE_URL + "?ctl=Buyer_Cart&met=getCartGoodsNum&typ=json", {},
        function(data) {
            if (data.status == 200) {
                if(data.data.cart_count > 0)
                {
                    $('.ci-count').show();
                    $('.cart_num_toolbar').show();
                    $('.ci-count, .cart_num_toolbar').text(data.data.cart_count);
                }
                else
                {
                    $('.ci-count').hide();
                    $('.cart_num_toolbar').hide();
                }
            }
        }
    )
}

//购物车下拉框
$(function() {
    $.cookie("key") && getCartNum();

    $(document).on(
        {
            "mouseenter": function() {
                if (!$.cookie("key") || $(this).hasClass("hover")) {
                    return false;
                }
                $(this).addClass("hover");
                $("#J_cart_body").load(SITE_URL + "?ctl=Index&met=getCart&typ=e");
            },
            "mouseleave": function() {
                $(this).removeClass("hover");
            }
        },
        "#J_settle_up"
    );

    $("#J_settle_up").on("click", ".J_delete",
        function() {
            var cart_id = $(this).data("cart_id");
            Public.ajaxPost(SITE_URL + "?ctl=Buyer_Cart&met=delCartByCid&typ=json", {id: cart_id},
                function(data) {
                    if (data.status == 200) {
                        Public.tips.success(data.msg);
                        getCartNum(), getCartList(); //更新购物车
                        $("#J_cart_body").load(SITE_URL + "?ctl=Index&met=getCart&typ=e");
                    } else {
                        Public.tips.warning(data.msg);
                    }
                }
            )
        }
    )
    $(document).on("click",'.not_shop',function(){
        $.dialog.alert('非商家用户不可进入批发市场！');
    })
    $(document).on("click",'.not_shop_login',function(){
        $.dialog.alert('请先登录系统！');
    })

});
