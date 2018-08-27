
document.write('<div class="tip" style="display: none;"><div class="relative"><div class="tip-area"><h5><span class="icon"></span>提示</h5><div class="tip-cont"></div><div class="clearfix"><a href="javascript:;" class="btn-sure">确定</a> </div></div><a href="javascript:;" class="btn-close"></a></div></div><style>.tip-area{width:300px;background:#fff;padding:50px 0 20px;position:relative;margin:auto;overflow:hidden;}.tip-area h5{font-size:14px;position:absolute;left:-25px;top:-24px;background:#ED5564;color:#fff;padding: 30px 20px 6px 40px;font-weight:normal;border-radius:26px;letter-spacing:3px;}.tip-area h5 span{width:16px;height:16px;display:inline-block;background:url(./analytics/static/default/images/icon-tip.png) no-repeat;vertical-align:middle;margin-right:6px;}.tip-area .tip-cont{padding:30px 0;text-align:center;color:#333;}.tip-area .btn-sure{ text-decoration:none; float:right;line-height:26px;border:1px solid #999;padding:0 10px;margin-right:30px;color:#333;border-radius:2px;font-size:14px;}.tip-area .btn-sure:hover{color:#00a3ee;border-color:#00a3ee;} .tip .btn-close{text-decoration: none;line-height: 26px;border: 0;padding: 5px 10px;margin-right: 0px;color: #333;border-radius: 2px;font-size: 14px;margin-top: 5px;}a.btn-close{ cursor: pointer;display: inline-block;height: 30px;position: absolute;right: -9px;top: -7px;width: 30px;background:url(./analytics/static/default/images/icon-close.png) no-repeat;background-size:cover;}a.btn-close:hover{background:url(./analytics/static/default/images/icon-close-hov.png) no-repeat;background-size:cover;transition:0.3s;}.relative{position:relative;display: inline-block;}.tip{width:100%;text-align:center;z-index:999;font-size:16px;padding:50px 0;position:fixed; left:50%; top:50%; width: 300px;height: 200px;margin-left:-150px;margin-top:-100px;}</style>');
function alert_box(msg){
                    $('.btn-sure').attr('href','javascript:;');
                    $(".tip-cont").html(msg);
                    $('.tip').show();
}

function alert_box_link(msg,link){

                alert_box(msg);
                $('.btn-sure').attr('href',link);
}
if(typeof(template)!='undefined'){
    template.helper('image_cdn', function(str) {
        if(!cdn_domain){
            return str
        }
        return str.replace(SiteUrl,cdn_domain)
    });
}




$('.btn-close').click(function(){ $(".tip").hide(); });
$('.btn-sure').click(function(){ $(".tip").hide(); });



if (typeof(window.title) == 'undefined')
{
    window.title = '商城触屏版';
}

if (typeof(window.paySiteName) == 'undefined')
{
    window.paySiteName = '网付宝';
}


//扩展函数,需要放入lib
function _($str)
{
    return $str;
}


function sprintf () {
    var regex = /%%|%(\d+\$)?([\-+'#0 ]*)(\*\d+\$|\*|\d+)?(?:\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g
    var a = arguments
    var i = 0
    var format = a[i++]

    var _pad = function (str, len, chr, leftJustify) {
        if (!chr) {
            chr = ' '
        }
        var padding = (str.length >= len) ? '' : new Array(1 + len - str.length >>> 0).join(chr)
        return leftJustify ? str + padding : padding + str
    }

    var justify = function (value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
        var diff = minWidth - value.length
        if (diff > 0) {
            if (leftJustify || !zeroPad) {
                value = _pad(value, minWidth, customPadChar, leftJustify)
            } else {
                value = [
                    value.slice(0, prefix.length),
                    _pad('', diff, '0', true),
                    value.slice(prefix.length)
                ].join('')
            }
        }
        return value
    }

    var _formatBaseX = function (value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
        // Note: casts negative numbers to positive ones
        var number = value >>> 0
        prefix = (prefix && number && {
                '2': '0b',
                '8': '0',
                '16': '0x'
            }[base]) || ''
        value = prefix + _pad(number.toString(base), precision || 0, '0', false)
        return justify(value, prefix, leftJustify, minWidth, zeroPad)
    }

    // _formatString()
    var _formatString = function (value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
        if (precision !== null && precision !== undefined) {
            value = value.slice(0, precision)
        }
        return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar)
    }

    // doFormat()
    var doFormat = function (substring, valueIndex, flags, minWidth, precision, type) {
        var number, prefix, method, textTransform, value

        if (substring === '%%') {
            return '%'
        }

        // parse flags
        var leftJustify = false
        var positivePrefix = ''
        var zeroPad = false
        var prefixBaseX = false
        var customPadChar = ' '
        var flagsl = flags.length
        var j
        for (j = 0; j < flagsl; j++) {
            switch (flags.charAt(j)) {
                case ' ':
                    positivePrefix = ' '
                    break
                case '+':
                    positivePrefix = '+'
                    break
                case '-':
                    leftJustify = true
                    break
                case "'":
                    customPadChar = flags.charAt(j + 1)
                    break
                case '0':
                    zeroPad = true
                    customPadChar = '0'
                    break
                case '#':
                    prefixBaseX = true
                    break
            }
        }

        // parameters may be null, undefined, empty-string or real valued
        // we want to ignore null, undefined and empty-string values
        if (!minWidth) {
            minWidth = 0
        } else if (minWidth === '*') {
            minWidth = +a[i++]
        } else if (minWidth.charAt(0) === '*') {
            minWidth = +a[minWidth.slice(1, -1)]
        } else {
            minWidth = +minWidth
        }

        // Note: undocumented perl feature:
        if (minWidth < 0) {
            minWidth = -minWidth
            leftJustify = true
        }

        if (!isFinite(minWidth)) {
            throw new Error('sprintf: (minimum-)width must be finite')
        }

        if (!precision) {
            precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type === 'd') ? 0 : undefined
        } else if (precision === '*') {
            precision = +a[i++]
        } else if (precision.charAt(0) === '*') {
            precision = +a[precision.slice(1, -1)]
        } else {
            precision = +precision
        }

        // grab value using valueIndex if required?
        value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++]

        switch (type) {
            case 's':
                return _formatString(value + '', leftJustify, minWidth, precision, zeroPad, customPadChar)
            case 'c':
                return _formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad)
            case 'b':
                return _formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
            case 'o':
                return _formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
            case 'x':
                return _formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
            case 'X':
                return _formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
                    .toUpperCase()
            case 'u':
                return _formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad)
            case 'i':
            case 'd':
                number = +value || 0
                // Plain Math.round doesn't just truncate
                number = Math.round(number - number % 1)
                prefix = number < 0 ? '-' : positivePrefix
                value = prefix + _pad(String(Math.abs(number)), precision, '0', false)
                return justify(value, prefix, leftJustify, minWidth, zeroPad)
            case 'e':
            case 'E':
            case 'f': // @todo: Should handle locales (as per setlocale)
            case 'F':
            case 'g':
            case 'G':
                number = +value
                prefix = number < 0 ? '-' : positivePrefix
                method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())]
                textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2]
                value = prefix + Math.abs(number)[method](precision)
                return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]()
            default:
                return substring
        }
    }

    return format.replace(regex, doFormat)
}

function get_ext(filename){
    var index1=filename.lastIndexOf(".");

    var index2=filename.length;
    var postf=filename.substring(index1,index2);//后缀名

    return postf;
}
var Public = Public || {};
function getQueryString(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if (r!=null) return r[2]; return '';
}

function addCookie(name,value,expireHours){
	var cookieString=name+"="+escape(value)+"; path=/";
	//判断是否设置过期时间
	if(expireHours>0){
		var date=new Date();
		date.setTime(date.getTime()+expireHours*3600*1000);
		cookieString=cookieString+";expires="+date.toGMTString();
	}
	document.cookie=cookieString;
}

function getCookie(name){
	var strcookie=document.cookie;
	var arrcookie=strcookie.split("; ");
	for(var i=0;i<arrcookie.length;i++){
	var arr=arrcookie[i].split("=");
	if(arr[0]==name)return unescape(arr[1]);
	}
	return null;
}

function delCookie(name){//删除cookie
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null) document.cookie= name + "="+cval+"; path=/;expires="+exp.toGMTString();
}

function checkLogin(state){
	if(state == 0){
		location.href = WapSiteUrl+'/tmpl/member/login.html';
		return false;
	}else {
		return true;
	}
}

function contains(arr, str) {
    var i = arr.length;
    while (i--) {
           if (arr[i] === str) {
           return true;
           }
    }
    return false;
}

function buildUrl(type, data) {
    switch (type) {
        case 'keyword':
            return WapSiteUrl + '/tmpl/product_list.html?keyword=' + encodeURIComponent(data);
        case 'shop':
            return WapSiteUrl + '/tmpl/store-list.html?keywords=' + encodeURIComponent(data);
        case 'special':
            return WapSiteUrl + '/special.html?special_id=' + data;
        case 'goods':
            return WapSiteUrl + '/tmpl/product_detail.html?goods_id=' + data;
        case 'groupbuy_keyword1':
            return WapSiteUrl + '/tmpl/group_buy_list.html?groupbuy_type=1&groupbuy_keyword=' + encodeURIComponent(data);
        case 'groupbuy_keyword2':
            return WapSiteUrl + '/tmpl/group_buy_list.html?groupbuy_type=2&groupbuy_keyword=' + encodeURIComponent(data);
        case 'url':
            return data;
    }
    return WapSiteUrl;
}

function errorTipsShow(html) {
    $(".error-tips").html(html).show();
    setTimeout(function(){
        errorTipsHide();
    }, 3000);
}

function errorTipsHide() {
    $(".error-tips").html("").hide();
}

function writeClear(o) {
    if (o.val().length > 0) {
        o.parent().addClass('write');
    } else {
        o.parent().removeClass('write');
    }
    btnCheck(o.parents('form'));
}

function btnCheck(form) {
    var btn = true;
    form.find('input').each(function(){
        if ($(this).hasClass('no-follow')) {
            return;
        }
        if ($(this).val().length == 0) {
            btn = false;
        }
    });
    if (btn) {
        form.find('.btn').parent().addClass('ok');
    } else {
        form.find('.btn').parent().removeClass('ok');
    }
}

/**
 * 取得默认系统搜索关键词
 * @param cmd
 */
function getSearchName() {
	var keyword = decodeURIComponent(getQueryString('keyword'));
	if (keyword == '') {
	    if(getCookie('deft_key_value') == null) {
	        $.getJSON(ApiUrl + '/index.php?ctl=Index&met=getSearchWords&typ=json', function(result) {
	        	var data = result.data.hot_info;
	        	if(typeof data.name != 'undefined') {
//	            	$('#keyword').attr('placeholder',data.name);
	            	$('#keyword').val(data.name);
	            	addCookie('deft_key_name',data.name,1);
	            	addCookie('deft_key_value',data.value,1);
	        	} else {
	            	addCookie('deft_key_name','',1);
	            	addCookie('deft_key_value','',1);
	        	}
	        })
	    } else {
//	    	$('#keyword').attr('placeholder',getCookie('deft_key_name'));
//	    	$('#keyword').val(getCookie('deft_key_name'));
	    }
	}
}
// 免费领代金券
function getFreeVoucher(tid) {
    var key = getCookie('key');
    if (!key) { checkLogin(0); return; }
    $.ajax({
        type:'post',
        url:ApiUrl+"/index.php?ctl=Voucher&met=receiveVoucher&typ=json",
        data:{vid:tid,k:key,u:getCookie('id')},
        dataType:'json',
        success:function(result){
            checkLogin(result.login);
            var msg = '领取成功';
            var skin = 'green';
            if(result.status==250){
                msg = '领取失败：' + result.msg;
                skin = 'red';
            }
            $.sDialog({
                skin:skin,
                content: msg,
                okBtn:false,
                cancelBtn:false
            });
        }
    });
}

// 登陆后更新购物车
function updateCookieCart(key) {
    var cartlist = decodeURIComponent(getCookie('goods_cart'));
    if (cartlist && cartlist !== 'null') {
        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?ctl=Buyer_Cart&met=addCartRow',
            data:{k:key,u:getCookie('id'),cartlist:cartlist},
            dataType:'json',
            async:false
        });
        delCookie('goods_cart');
    }
}
/**
 * 查询购物车中商品数量
 * @param key
 * @param expireHours
 */
function getCartCount(key, expireHours) {
    var cart_count = 0;
    if (getCookie('key') !== null && getCookie('cart_count') === null) {
        var key = getCookie('key');
        $.ajax({
            type:'post',
            url:ApiUrl+'/index.php?ctl=Buyer_Cart&met=getCartGoodsNum&typ=json',
            data:{k:key,u:getCookie('id')},
            dataType:'json',
            async:false,
            success:function (result) {
                console.log(result);
                if (typeof(result.data.cart_count) != 'undefined') {
                    addCookie('cart_count',result.data.cart_count,expireHours);
                    cart_count = result.data.cart_count;
                }
            }
        });
    } else {
        cart_count = getCookie('cart_count');
    }
    if (cart_count > 0 && $('.nctouch-nav-menu').has('.cart').length > 0) {
        $('.nctouch-nav-menu').has('.cart').find('.cart').parents('li').find('sup').show();
        $('#header-nav').find('sup').show();
    }
}
/**
 * 查询是否有新消息
 */
function getChatCount() {
    if ($('#header').find('.message').length > 0) {
        var key = getCookie('key');
        if (key !== null) {
            $.getJSON(ApiUrl+'/index.php?ctl=Buyer_Message&met=getNewMessageNum&typ=json', {k:key,u:getCookie('id')}, function(result){
                if (result.data.count > 0) {
                    $('#header').find('.message').parent().find('sup').show();
                    $('#header-nav').find('sup').show();
                }
            });
        }
        $('#header').find('.message').parent().click(function(){
            window.location.href = WapSiteUrl+'/tmpl/member/chat_list.html';
        });
    }
}

$(function() {

    $('.input-del').click(function(){
        $(this).parent().removeClass('write').find('input').val('');
        btnCheck($(this).parents('form'));
    });

	// radio样式
	$('body').on('click', 'label', function(){
	    if ($(this).has('input[type="radio"]').length > 0) {
	        $(this).addClass('checked').siblings().removeAttr('class').find('input[type="radio"]').removeAttr('checked');
	    } else if ($(this).has('[type="checkbox"]')) {
	        if ($(this).find('input[type="checkbox"]').prop('checked')) {
	            $(this).addClass('checked');
	        } else {
	            $(this).removeClass('checked');
	        }
	    }
  	});
    // 滚动条通用js
    if ($('body').hasClass('scroller-body')) {
        new IScroll('.scroller-body', { mouseWheel: true, click: true });
    }

    // 右上侧小导航控件
    $('#header').on('click', '#header-nav', function(){
        if ($('.nctouch-nav-layout').hasClass('show')) {
            $('.nctouch-nav-layout').removeClass('show');
        } else {
            $('.nctouch-nav-layout').addClass('show');
        }
    });
    $('#header').on('click', '.nctouch-nav-layout',function(){
        $('.nctouch-nav-layout').removeClass('show');
    });
    $(document).scroll(function(){
        $('.nctouch-nav-layout').removeClass('show');
    });
    getSearchName();
    getCartCount();
    getChatCount();// 导航右侧消息


    //回到顶部
    $(document).scroll(function(){
        set();
    });
     $('.fix-block-r,#footer').on('click', ".gotop",function (){
         btn = $(this)[0];
         this.timer=setInterval(function(){
             $(window).scrollTop(Math.floor($(window).scrollTop()*0.8));
             if($(window).scrollTop()==0) clearInterval(btn.timer,set);
         },10);
     });
      /*$('.fix-block-r').on('click', ".gotop",function (){
        btn = $(this)[0];
        this.timer=setInterval(function(){
            $(window).scrollTop(Math.floor($(window).scrollTop()*0.8));
            if($(window).scrollTop()==0) clearInterval(btn.timer,set);
        },10);
    });*/
    function set(){$(window).scrollTop()==0 ? $('#goTopBtn').addClass('hide') : $('#goTopBtn').removeClass('hide');}
});
(function($) {
    $.extend($, {
        /**
         * 滚动header固定到顶部
         */
        scrollTransparent: function(options) {
            var defaults = {
                    valve : '#header',          // 动作触发
                    scrollHeight : 50
            }
            var options = $.extend({}, defaults, options);
            function _init() {
                $(window).scroll(function(){
                    if ($(window).scrollTop() <= options.scrollHeight) {
                        $(options.valve).addClass('transparent').removeClass('posf');
                    } else {
                        $(options.valve).addClass('posf').removeClass('transparent');
                    }
                });

            }
            return this.each(function() {
                _init();
            })();
        },

    /**
     * 选择地区
     *
     * @param $
     */
        areaSelected: function(options) {
            var defaults = {
                    success : function(data){},
                    hideThirdLevel: false
                }
            var options = $.extend({}, defaults, options);
            var ASID = 0;
            var ASID_1 = 0;
            var ASID_2 = 0;
            var ASID_3 = 0;
            var ASNAME = '';
            var ASINFO = '';
            var ASDEEP = 1;
            var ASINIT = true;
            function _init() {
                if ($('#areaSelected').length > 0) {
                    $('#areaSelected').remove();
                }
                var thirdLevelHtml = options.hideThirdLevel ? "" : '<li><a href="javascript:void(0);" >三级地区</a></li>';
                var html = '<div id="areaSelected">'
                    + '<div class="nctouch-full-mask left">'
                    + '<div class="nctouch-full-mask-bg"></div>'
                    + '<div class="nctouch-full-mask-block">'
                    + '<div class="header absolute">'
                    + '<div class="header-wrap">'
                    + '<div class="header-l"><a href="javascript:void(0);"><i class="back"></i></a></div>'
                    + '<div class="header-title">'
                    + '<h1>选择地区</h1>'
                    + '</div>'
                    + '<div class="header-r"><a href="javascript:void(0);"><i class="close"></i></a></div>'
                    + '</div>'
                    + '</div>'
                    + '<div class="nctouch-main-layout">'
                    + '<div class="nctouch-single-nav">'
                    + '<ul id="filtrate_ul" class="area">'
                    + '<li class="selected"><a href="javascript:void(0);">一级地区</a></li>'
                    + '<li><a href="javascript:void(0);" >二级地区</a></li>'
                    + thirdLevelHtml
                    + '</ul>'
                    + '</div>'
                    + '<div class="nctouch-main-layout-a"><ul class="nctouch-default-list"></ul></div>'
                    + '</div>'
                    + '</div>'
                    + '</div>'
                    + '</div>';
                $('body').append(html);
                _getAreaList();
                _bindEvent();
                _close();
            }

            function _getAreaList() {
                $.ajax({//获取区域列表
                    type:'get',
                    url:ApiUrl+'/index.php?ctl=Base_District&met=district&typ=json',
                    data:{pid:ASID},
                    dataType:'json',
                    async:false,
                    success:function(result){
                        if (result.data.items.length == 0) {
                            _finish();
                            return false;
                        }
                        if (ASINIT) {
                            ASINIT = false
                        } else {
                            ASDEEP++;
                        }
                        $('#areaSelected').find('#filtrate_ul').find('li').eq(ASDEEP-1).addClass('selected').siblings().removeClass('selected');
                        checkLogin(result.login);
                        var data = result.data;
                        var area_li = '';
                        for(var i=0;i<data.items.length;i++){
                            area_li += '<li><a href="javascript:void(0);" data-id="' + data.items[i].district_id + '" data-name="' + data.items[i].district_name + '"><h4>' + data.items[i].district_name + '</h4><span class="arrow-r"></span> </a></li>';
                        }
                        $('#areaSelected').find(".nctouch-default-list").html(area_li);
                        if (typeof(myScrollArea) == 'undefined') {
                            if (typeof(IScroll) == 'undefined') {
                                $.ajax({
                                    url: WapSiteUrl+'/js/iscroll.js',
                                    dataType: "script",
                                    async: false
                                  });
                            }
                            myScrollArea = new IScroll('#areaSelected .nctouch-main-layout-a', { mouseWheel: true, click: true });
                        } else {
                            myScrollArea.destroy();
                            myScrollArea = new IScroll('#areaSelected .nctouch-main-layout-a', { mouseWheel: true, click: true });
                        }
                    }
                });
                return false;
            }

            function _bindEvent() {
                $('#areaSelected').find('.nctouch-default-list').off('click', 'li > a');
                var onceClick = true;
                $('#areaSelected').find('.nctouch-default-list').on('click', 'li > a', function(){

                    if (onceClick === false) {
                        return false;
                    }

                    ASID = $(this).attr('data-id');
                    eval("ASID_"+ASDEEP+"=$(this).attr('data-id')");
                    ASNAME = $(this).attr('data-name');
                    ASINFO += ASNAME + ' ';
                    var _li = $('#areaSelected').find('#filtrate_ul').find('li').eq(ASDEEP);
                    _li.prev().find('a').attr({'data-id':ASID, 'data-name':ASNAME}).html(ASNAME);
                    if (options.hideThirdLevel && ASDEEP == 2) {
                        _finish();
                        onceClick = false;
                        return false;
                    }
                    if (ASDEEP == 3) {
                        _finish();
                        onceClick = false;
                        return false;
                    }
                    _getAreaList();
                });
                $('#areaSelected').find('#filtrate_ul').off('click', 'li > a');
                $('#areaSelected').find('#filtrate_ul').on('click', 'li > a', function(){
                    if ($(this).parent().index() >= $('#areaSelected').find('#filtrate_ul').find('.selected').index()) {
                        return false;
                    }
                    ASID = $(this).parent().prev().find('a').attr('data-id');
                    ASNAME = $(this).parent().prev().find('a').attr('data-name');
                    ASDEEP = $(this).parent().index();
                    ASINFO = '';
                    for (var i=0; i<$('#areaSelected').find('#filtrate_ul').find('a').length; i++) {
                        if (i < ASDEEP) {
                            ASINFO += $('#areaSelected').find('#filtrate_ul').find('a').eq(i).attr('data-name') + ' ';
                        } else {
                            var text = '';
                            switch (i) {
                            case 0:
                                text = '一级地区'
                                break;
                            case 1:
                                text = '二级地区'
                                break;
                            case 2:
                                text = '三级地区';
                                break;
                            }
                            $('#areaSelected').find('#filtrate_ul').find('a').eq(i).html(text);
                        }
                    }
                    _getAreaList();
                });
            }

            function _finish() {
                var data = {area_id:ASID,area_id_1:ASID_1,area_id_2:ASID_2,area_id_3:ASID_3,area_name:ASNAME,area_info:ASINFO};
                options.success.call('success', data);
                if (!ASINIT) {
                    $('#areaSelected').find('.nctouch-full-mask').addClass('right').removeClass('left');
                }
                return false;
            }

            function _close() {
                $('#areaSelected').find('.header-l').off('click', 'a');
                $('#areaSelected').find('.header-l').on('click', 'a',function(){
                    $('#areaSelected').find('.nctouch-full-mask').addClass('right').removeClass('left');
                });
                return false;
            }

            return this.each(function() {
                return _init();
            })();
        },



        /**
         * 从右到左动态显示隐藏内容
         *
         */
        animationLeft: function(options) {
            var defaults = {
                    valve : '.animation-left',          // 动作触发
                    wrapper : '.nctouch-full-mask',    // 动作块
                    scroll : '',     // 滚动块，为空不触发滚动
                    openCallback : "" //显示内容触发事件
            }
            var options = $.extend({}, defaults, options);
            function _init() {
                $(document).on('click', options.valve, function(){
                    options.openCallback && options.openCallback();
                    $(options.wrapper).removeClass('hide').removeClass('right').addClass('left');

                    if (options.scroll != '') {
                        if (typeof(myScrollAnimationLeft) == 'undefined') {
                            if (typeof(IScroll) == 'undefined') {
                                $.ajax({
                                    url: WapSiteUrl+'/js/iscroll.js',
                                    dataType: "script",
                                    async: false
                                });
                            }
                            myScrollAnimationLeft = new IScroll(options.scroll, { mouseWheel: true, click: true });
                        } else {
                            myScrollAnimationLeft.refresh();
                        }
                    }
                });
                $(options.wrapper).on('click', '.header-l > a', function(){
                    $(options.wrapper).addClass('right').removeClass('left');
                });

                $(document).on("click", "#ldg_lockmask", function() {
                    $(options.wrapper).addClass('right').removeClass('left');
                });
            }
            return this.each(function() {
                _init();
            })();
        },

        /**
         * 从下到上动态显示隐藏内容
         *
         */
        animationUp: function(options) {
            var defaults = {
                    valve : '.animation-up',                    // 动作触发，为空直接触发
                    wrapper : '.nctouch-bottom-mask',           // 动作块
                    scroll : '.nctouch-bottom-mask-rolling',    // 滚动块，为空不触发滚动
                    start : function(){},       // 开始动作触发事件
                    close : function(){}        // 关闭动作触发事件
            }
            var options = $.extend({}, defaults, options);
            function _animationUpRun() {
                // options.start.call('start');
                $(options.wrapper).removeClass('down').addClass('up');

                if (options.scroll != '') {
                    if (typeof(myScrollAnimationUp) == 'undefined') {
                        if (typeof(IScroll) == 'undefined') {
                            $.ajax({
                                url: WapSiteUrl+'/js/iscroll.js',
                                dataType: "script",
                                async: false
                              });
                        }
                        myScrollAnimationUp = new IScroll(options.scroll, { mouseWheel: true, click: true });
                    } else {
                        myScrollAnimationUp.refresh();
                    }
                }
            }
            return this.each(function() {
                var trigger_element; //触发元素
                if (options.valve != '') {
                    // $(options.valve).on('click', function(){
                    $(document).on('click',options.valve, function(){
                        trigger_element = this;
                        options.start.call(this);
                        _animationUpRun();
                    });
                } else {
                    _animationUpRun();
                }
                $(options.wrapper).on('click', '.nctouch-bottom-mask-bg,.nctouch-bottom-mask-close,.JS_close', function(){
                    $(options.wrapper).addClass('down').removeClass('up');
                    options.close.call(this, trigger_element);
                });
            })();
        }
    });
})(Zepto);

/**
 * 异步上传图片
 */
$.fn.ajaxUploadImage = function(options) {
    var defaults = {
        url : '',
        data : {},
        start : function(){},     // 开始上传触发事件
        success : function(){}
    }
    var options = $.extend({}, defaults, options);
    var _uploadFile;
    function _checkFile() {
          //文件为空判断
          if (_uploadFile === null || _uploadFile === undefined) {
              alert("请选择您要上传的文件！");
              return false;
          }
//           
//          //检测文件类型
//          if(_uploadFile.type.indexOf('image') === -1) {
//              alert("请选择图片文件！");
//              return false;
//          }
//           
//          //计算文件大小
//          var size = Math.floor(_uploadFile.size/1024);
//          if (size > 5000) {
//              alert("上传文件不得超过5M!");
//              return false;
//          };
          return true;
    };
    return this.each(function() {
        $(this).on('change', function(){
            var _element = $(this);
            options.start.call('start', _element);
            _uploadFile = _element.prop('files')[0];
            if (!_checkFile) return false;
            try {
                //执行上传操作
                var xhr = new XMLHttpRequest();
                xhr.open("post",options.url+'&typ=json', true);
                xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        returnDate = $.parseJSON(xhr.responseText);
                        options.success.call('success', _element, returnDate);
                    };
                };
                //表单数据
                var fd = new FormData();
                for (k in options.data) {
                    fd.append(k, options.data[k]);
                }
                fd.append(_element.attr('name'), _uploadFile);
                //执行发送
                result = xhr.send(fd);
            } catch (e) {
                console.log(e);
                alert(e);
            }
        });
    });
}

function loadSeccode(){
    /*
    $("#codekey").val('');
    //加载验证码
    $.ajax({
        type:'get',
        url:ApiUrl+"/index.php?act=seccode&op=makecodekey",
        async : false,
        dataType: 'json',
        success:function(result){
            $("#codekey").val(result.data.codekey);
        }
    });
    $("#codeimage").attr('src',ApiUrl+'/index.php?act=seccode&op=makecode&k='+$("#codekey").val()+'&t=' + Math.random());
    */
}
/**
 * 收藏店铺
 */
function favoriteStore(shop_id){
    var key = getCookie('key');
    if (!key) {
        checkLogin(0);
        return;
    }
    if (shop_id <= 0) {
        $.sDialog({skin: "green", content: '参数错误', okBtn: false, cancelBtn: false});
        return false;
    }
    var return_val = false;
    $.ajax({
        type: 'post',
        url: ApiUrl + '/index.php?ctl=Shop&met=addCollectShop&typ=json',
        data: {k: key,u:getCookie('id'), shop_id: shop_id},
        dataType: 'json',
        async: false,
        success: function(result) {
            if (result.status == 200) {
                $.sDialog({skin: "green", content: "收藏成功！", okBtn: false, cancelBtn: false});
                return_val = true;
            } else {
                $.sDialog({skin: "red", content: result.data.msg, okBtn: false, cancelBtn: false});
            }
        }
    });
    return return_val;
}
/**
 * 取消收藏店铺
 */
function dropFavoriteStore(shop_id){
    var key = getCookie('key');
    if (!key) {
        checkLogin(0);
        return;
    }
    if (shop_id <= 0) {
        $.sDialog({skin: "green", content: '参数错误', okBtn: false, cancelBtn: false});
        return false;
    }
    var return_val = false;
    $.ajax({
        type: 'post',
        url: ApiUrl + '/index.php?ctl=Buyer_Favorites&met=delFavoritesShop&typ=json',
        data: {k: key,u:getCookie('id'), id: shop_id},
        dataType: 'json',
        async: false,
        success: function(result) {
            if (result.status == 200) {
                $.sDialog({skin: "green", content: "成功取消收藏！", okBtn: false, cancelBtn: false});
                return_val = true;
            } else {
                $.sDialog({skin: "red", content: result.data.error, okBtn: false, cancelBtn: false});
            }
        }
    });
    return return_val;
}
/**
 * 收藏商品
 */
function favoriteGoods(goods_id){
    var key = getCookie('key');
    if (!key) {
        checkLogin(0);
        return;
    }
    if (goods_id <= 0) {
        $.sDialog({skin: "green", content: '参数错误', okBtn: false, cancelBtn: false});
        return false;
    }
    var return_val = false;
    $.ajax({
        type: 'post',
        url: ApiUrl + '/index.php?ctl=Goods_Goods&met=collectGoods&typ=json',
        data:{k:key,u:getCookie('id'),goods_id:goods_id},
        dataType: 'json',
        async: false,
        success: function(result) {
            if (result.status == 200) {
                $.sDialog({skin: "green", content: "收藏成功", okBtn: false, cancelBtn: false});
                return_val = true;
            } else {
                $.sDialog({skin: "red", content: "收藏失败", okBtn: false, cancelBtn: false});
            }
        }
    });
    return return_val;
}
/**
 * 取消收藏商品
 */
function dropFavoriteGoods(goods_id){
    var key = getCookie('key');
    if (!key) { checkLogin(0); return; }
    if (goods_id <= 0) {
        $.sDialog({skin: "green", content: '参数错误', okBtn: false, cancelBtn: false}); return false;
    }
    var return_val = false;
    $.ajax({
        type: 'post',
        url: ApiUrl + '/index.php?ctl=Buyer_Favorites&met=delFavoritesGoods&typ=json',
        data: {k: key,u:getCookie('id'), id: goods_id},
        dataType: 'json',
        async: false,
        success: function(result) {
            if (result.status == 200) {
                $.sDialog({skin: "green", content: "已取消收藏", okBtn: false, cancelBtn: false});
                return_val = true;
            } else {
                $.sDialog({skin: "red", content: "取消失败", okBtn: false, cancelBtn: false});
            }
        }
    });
    return return_val;
}
/**
 * 动态加载css文件
 * @param css_filename css文件路径
 */
function loadCss(css_filename) {
    var link = document.createElement('link');
    link.setAttribute('type', 'text/css');
    link.setAttribute('href', css_filename);
    link.setAttribute('href', css_filename);
    link.setAttribute('rel', 'stylesheet');
    css_id = document.getElementById('auto_css_id');
    if (css_id) {
        document.getElementsByTagName('head')[0].removeChild(css_id);
    }
    document.getElementsByTagName('head')[0].appendChild(link);
}
/**
 * 动态加载js文件
 * @param script_filename js文件路径
 */
function loadJs(script_filename) {
    var script = document.createElement('script');
    script.setAttribute('type', 'text/javascript');
    script.setAttribute('src', script_filename);
    script.setAttribute('id', 'auto_script_id');
    script_id = document.getElementById('auto_script_id');
    if (script_id) {
        document.getElementsByTagName('head')[0].removeChild(script_id);
    }
    document.getElementsByTagName('head')[0].appendChild(script);
}


function ucenterRegist()
{
    callback = WapSiteUrl + '/tmpl/member/login.html';

    login_url   = UCenterApiUrl + '?ctl=Login&met=regist&typ=e';


    callback = ApiUrl + '?ctl=Login&met=check&typ=e&redirect=' + encodeURIComponent(callback);


    login_url = login_url + '&from=wap&callback=' + encodeURIComponent(callback);

    window.location.href = login_url;
}

function checkUserMobile()
{
    var userMobile = false;
    $.ajax({
        type: "post",
        xhrFields: {
            withCredentials: true
        },
        crossDomain: true,
        dataType: 'json',
        async: false,
        url: UCenterApiUrl + "?ctl=Login&met=checkUserMobile&typ=json&user_id=" + getCookie('id'),
        success: function(data){

            console.info(data);
            //已经登录
            if (200 == data.status)
            {
                userMobile = true;
            }
            else
            {
                userMobile = false;
            }

        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            console.log('error!');
        }
    });

    return userMobile;
}

function ucenterLogin()
{
    $.ajax({
        type: "post",
        xhrFields: {
              withCredentials: true
        },
        data: {
            k: getQueryString('k'),
            u: getQueryString('u'),
        },
        crossDomain: true,
        url: UCenterApiUrl + "?ctl=Login&met=checkStatus&typ=json",
        //dataType: "jsonp",
        //jsonp: "jsonp_callback",
        success: function(data){

            console.info(data);
            //已经登录
            if (200 == data.status)
            {
                var key = getCookie('key');
                var u = getCookie('id');
                //判断cookie中是否存在recserialize,存在的话调用分销
                var rec = getCookie('recserialize');

                if (u && key && u==data.data.us)
                {

                }
                else
                {
                    //退出
                    delCookie('username');
                    delCookie('user_account');
                    delCookie('id');
                    delCookie('key');

                    var k = data.data.ks;
                    var u = data.data.id;
                    //本系统登录
                    $.ajax({
                        type: "get",
                        url: ApiUrl + "/index.php?ctl=Login&met=check&typ=json",
                        data:{ks:data.data.ks, us:data.data.id, rec:rec},
                        dataType: "json",
                        success: function(result){
                            if (200 == result.status)
                            {
                                //本系统登录API
                                var expireHours = 0;
                                if ($('#checkbox').prop('checked')) {
                                    expireHours = 188;
                                }
                                console.info(result);

                                addCookie('id',result.data.user_id, expireHours);
                                addCookie('user_account',result.data.user_account, expireHours);
                                addCookie('key',result.data.key, expireHours);
                                delCookie('recserialize');
                                // 更新cookie购物车
                                updateCookieCart(result.data.key);

                                location.reload();
                                //window.location.href = WapSiteUrl+'/tmpl/member/member.html';

                            }
                        },
                        error: function(){
                            errorTipsShow('<p>' + result.msg + '</p>');
                        }
                    });

                }

            }
            else  //未登录
            {
                var key = getCookie('key');
                var u = getCookie('id');

                if (u && key)
                {
                    delCookie('username');
                    delCookie('user_account');
                    delCookie('id');
                    delCookie('key');

                    location.reload();
                }
            }

        },
        error: function(XMLHttpRequest, textStatus, errorThrown){
            console.log('error login!');
        }
    });
}



//修正title
$(function() {

    if (typeof(window.title) == 'undefined')
    {
    }
    else
    {
        $('title').html(title + ' - ' + $('title').html())
    }

});


//百度定位,需要使用默认位置时从cookie获取
function baidu_lbs_geo() {
    // 百度地图API功能
    var geolocation = new BMap.Geolocation();
    var geoc = new BMap.Geocoder();
    geolocation.getCurrentPosition(function(r){
        if(this.getStatus() == BMAP_STATUS_SUCCESS){
            var mk = new BMap.Marker(r.point);
            window.coordinate = {'lng':r.point.lng, lat:r.point.lat};
            geoc.getLocation(r.point, function(rs){
                var addComp = rs.addressComponents;

                if(addComp.province != null && addComp.province != 'undefined' && addComp.province != ''){
                    //获取分站信息
                    var addressStr = "province:"+ addComp.province + ",city:" + addComp.city + ",district:" + addComp.district + ",street:" + addComp.street + ",streetnumber:" + addComp.streetNumber;
                    addCookie('lbs_geo',addressStr);
                }
            });
        } else {
            alert('failed'+this.getStatus());
        }
    },{enableHighAccuracy: true})
}

function loadScriptBaiduLbs() {
    var script = document.createElement("script");
    script.src = "//api.map.baidu.com/api?v=2.0&ak=5At3anZe83x8oOpFap42Gt8eHYpy3wm9&callback=baidu_lbs_geo";//此为v2.0版本的引用方式
    document.body.appendChild(script);

}


$(function() {
    //获取分站参数
    sub_request = GetRequest();
    if(typeof(sub_request['sub_site_id']) != 'undefined' && sub_request['sub_site_id']!=''){
        addCookie('sub_site_id',sub_request['sub_site_id'],0);
    }
    if(getCookie('sub_site_id') == '' || getCookie('sub_site_id') == 'undefined' || getCookie('sub_site_id') == null){
        getSubsiteHost();
    }
});


function getSubsiteHost(){
    var WapSiteHost =WapSiteUrl.split( "/" );
    if(location.hostname != WapSiteHost[2]){
        $.post(ApiUrl + "/index.php?ctl=Base_District&met=getSubsiteHost&typ=json",{host:location.hostname},function(result){
            if(typeof(result.data.sub_site_id) == 'undefined' || result.data.sub_site_id == 0){
                window.location.href = WapSiteUrl;
            }else{
                addCookie('sub_site_id',result.data.sub_site_id,0);
                window.location.reload();
            }
        },'json');

    }else{
        if(getCookie('lbs_geo') == '' || getCookie('lbs_geo') == 'undefined' || getCookie('lbs_geo') == null){
            loadScriptBaiduLbs();
        }
    }

}
function GetRequest() {
  var url = location.search; //获取url中"?"符后的字串
   var theRequest = new Object();
   if (url.indexOf("?") != -1) {
      var str = url.substr(1);
      strs = str.split("&");
      for(var i = 0; i < strs.length; i ++) {
         theRequest[strs[i].split("=")[0]]=(strs[i].split("=")[1]);
      }
   }
   return theRequest;
}

function isLogin() {
    var key = getCookie('key'),
        callback = window.location.href,
        login_url = UCenterApiUrl + '?ctl=Login&met=index&typ=e';
        callback = ApiUrl + '?ctl=Login&met=check&typ=e&redirect=' + encodeURIComponent(callback);
        login_url = login_url + '&from=wap&callback=' + encodeURIComponent(callback);

    window.location.href = login_url;
};

Public.ajax = function(ajaxOpts){
    var opts = {
        type: "POST",
        dataType: "json",
        timeout:900000 //15mins
    };
    $.extend(true, opts, ajaxOpts);
    var success = ajaxOpts.success;
    var error = ajaxOpts.error;
    opts.success = function(data, status){
        success && success(data, status);
    }
    opts.error = function(err,ms){
        var content = '服务端响应错误！';
        if(ms === 'timeout'){
            content = '请求超时！';
        }
        error && error(err);
    }
    return $.ajax(opts);
};
/*
 通用post请求，返回json
 url:请求地址， params：传递的参数{...}， callback：请求成功回调
 */
Public.ajaxPost = function(url, params, callback, errCallback){
    return Public.ajax({
        url: url,
        data: $.extend(params, this.getCookie()),
        success: callback,
        error: errCallback
    });
};

Public.ajaxGet = function(url, params, callback, errCallback){
    return Public.ajax({
        type: "GET",
        url: url,
        data: $.extend(params, this.getCookie()),
        success: callback,
        error: errCallback
    });
};

Public.getCookie = function () {
    return {
        k: getCookie('key'),
        u: getCookie('id')
    };
};

