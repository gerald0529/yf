<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
    include $this->view->getTplPath() . '/' . 'header.php';
?>
<!-- author：309558639 | team：http://www.yuanfengerp.com/ -->
<script>
    var CONFIG = {
        DEFAULT_PAGE: true,
        //SERVICE_URL: './<?=Yf_Registry::get('url')?>?ctl=Service'
    };
    //系统参数控制
    var SYSTEM = {
        version: 1,
        skin: "default",
        curDate: '1423619990432',  //系统当前日期
        DBID: '88887785', //账套ID
        serviceType: '15', //账套类型，13：表示收费服务，12：表示免费服务
        userName: 'Demo', //用户名
        companyName: '商城管理中心',	//公司名称
        companyAddr: '',	//公司地址
        phone: '',	//公司电话
        fax: '',	//公司传真
        postcode: '',	//公司邮编
        startDate: '2015-11-18', //启用日期
        invEntryCount: '',//试用版单据分录数
        rights: {},//权限列表
        taxRequiredCheck: 1,
        taxRequiredInput: 13,
        isAdmin: true, //是否管理员
        siExpired: false,//是否过期
        siType: 2, //服务版本，1表示基础版，2表示标准版
        siVersion: 4, //1表示试用、2表示免费（百度版）、3表示收费，4表示体验版
        shortName: ""//shortName
        
        
    };
    
    SYSTEM.categoryInfo = {};
    //区分服务支持
    SYSTEM.servicePro = SYSTEM.siType === 2 ? 'forbscm3' : 'forscm3';
    var cacheList = {};	//缓存列表查询
    //全局基础数据
    
    
    //缓存登陆用户
    function getUser() {
        Public.ajaxGet('', {}, function (data) {
            if (data.status === 200) {
                SYSTEM.realName = (data.data[0].user_account);
            }
            else if (data.status === 250) {
                SYSTEM.realName = '';
            }
            else {
                Public.tips({type: 1, content: data.msg});
            }
        });
    }
    //缓存时间
    function initDate() {
        var a = new Date,
            b = a.getFullYear(),
            c = ("0" + (a.getMonth() + 1)).slice(-2),
            d = ("0" + a.getDate()).slice(-2);
        SYSTEM.beginDate = b + "-" + c + "-01", SYSTEM.endDate = b + "-" + c + "-" + d
    }
    
    //左上侧版本标识控制
    function markupVension() {
        var imgSrcList = {
            base: '/css/default/img/icon_v_b.png',	//基础版正式版
            baseExp: '/css/default/img/icon_v_b_e.png',	//基础版体验版
            baseTrial: '/css/default/img/icon_v_b_t.png',	//基础版试用版
            standard: '/css/default/img/icon_v_s.png', //标准版正式版
            standardExp: './shop_admin/static/default/css/img/icon_v_s_e.png', //标准版体验版
            standardTrial: '/css/default/img/icon_v_s_t.png' //标准版试用版
        };
        var imgModel = $("<img id='icon-vension' src='' alt=''/>");
        if (SYSTEM.siType === 1) {
            switch (SYSTEM.siVersion) {
                case 1:
                    imgModel.attr('src', imgSrcList.baseTrial).attr('alt', '基础版试用版');
                    break;
                case 2:
                    imgModel.attr('src', imgSrcList.baseExp).attr('alt', '免费版（百度版）');
                    break;
                case 3:
                    imgModel.attr('src', imgSrcList.base).attr('alt', '基础版');//标准版
                    break;
                case 4:
                    imgModel.attr('src', imgSrcList.baseExp).attr('alt', '基础版体验版');//标准版
                    break;
            }
        }
        else {
            switch (SYSTEM.siVersion) {
                case 1:
                    imgModel.attr('src', imgSrcList.standardTrial).attr('alt', '标准版试用版');
                    break;
                case 3:
                    imgModel.attr('src', imgSrcList.standard).attr('alt', '标准版');//标准版
                    break;
                case 4:
                    imgModel.attr('src', imgSrcList.standardExp).attr('alt', '标准版体验版');//标准版
                    break;
            }
        }
        
    }
    
    //全局基础数据
    (function () {
        /*
         * 判断IE6，提示使用高级版本
         */
        /*
         if(Public.isIE6) {
         var Oldbrowser = {
         init: function(){
         this.addDom();
         },
         addDom: function() {
         var html = $('<div id="browser">您使用的浏览器版本过低，影响网页性能，建议您换用<a href="http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html" target="_blank">谷歌</a>、<a href="http://download.microsoft.com/download/4/C/A/4CA9248C-C09D-43D3-B627-76B0F6EBCD5E/IE9-Windows7-x86-chs.exe" target="_blank">IE9</a>、或<a href=http://firefox.com.cn/" target="_blank">火狐浏览器</a>，以便更好的使用！<a id="bClose" title="关闭">x</a></div>').insertBefore('#container').slideDown(500);
         this._colse();
         },
         _colse: function() {
         $('#bClose').click(function(){
         $('#browser').remove();
         });
         }
         };
         Oldbrowser.init();
         };	*/
        getUserInfo();
        getGoodsState();
        //getUser();
        initDate();
        
    })();
    
    $(function () {
        getGoodsCatTree();
    });
    
    //缓存商品分类
    function getGoodsCatTree() {
        Public.ajaxPost(SITE_URL + '?ctl=Category&met=lists&typ=json&type_number=goods_cat&is_delete=2', {}, function (data) {
            if (data.status === 200 && data.data) {
                SYSTEM.goodsCatInfo = data.data.items;
                SYSTEM.goodsCatInfo.unshift({name: '全部分类', id: -1});
            } else {
            }
        });
    }
    
    
    //缓存客户信息
    function getBrand() {
        if (true) {
            Public.ajaxGet('./c.php', {rows: 5000}, function (data) {
                if (data.status === 200) {
                    SYSTEM.brandInfo = data.data.rows;
                } else if (data.status === 250) {
                    SYSTEM.brandInfo = [];
                } else {
                    Public.tips({type: 1, content: data.msg});
                }
            });
        } else {
            SYSTEM.brandInfo = [];
        }
    }
    //缓存管理员
    function getUserInfo() {
        if (true) {
            Public.ajaxGet(SITE_URL + '?ctl=Category&met=listUser&typ=json&type_number=user', {}, function (data) {
                if (data.status === 200) {
                    SYSTEM.categoryInfo['user'] = data.data.items;
                }
                else if (data.status === 250) {
                    SYSTEM.categoryInfo['user'] = {};
                }
                else {
                    Public.tips({type: 1, content: data.msg});
                }
            });
        }
        else {
            SYSTEM.categoryInfo['user'] = {};
        }
    }
    //state verify
    function getGoodsState() {
        Public.ajaxGet(SITE_URL + '?ctl=Category&met=lists&typ=json&type_number=goods_state', {}, function (data) {
            if (data.status === 200) {
                for (var key in  data.data) {
                    SYSTEM.categoryInfo[key] = data.data[key];
                }
            }
            else {
                SYSTEM.categoryInfo['state'] = {};
                SYSTEM.categoryInfo['verify'] = {};
                SYSTEM.categoryInfo['type'] = {};
            }
        });
    }
</script>
<link href="<?= $this->view->css ?>/base.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css ?>/default.css" rel="stylesheet" type="text/css" id="defaultFile">
<script src="<?= $this->view->js_com ?>/tabs.js?ver=20140430"></script>
</head>
<body>
<div id="container" class="cf">
    
    <div class="col-hd cf">
        <div class="left"><a class="company" id="companyName" href="javascript:" title=""></a></div>
        
        <div class="right cf">
            <!--
            <ul class="nav">
                <li class="cur" id="fast">平台</li>
                <li>商城</li>
            </ul>
            -->
            <?php
                $ucenter_api_url_row = parse_url(Yf_Registry::get('ucenter_api_url'));
                $ucenter_admin_url = Yf_Registry::get('ucenter_admin_api_url');
                
                $paycenter_api_url_row = parse_url(Yf_Registry::get('paycenter_api_url'));
                $paycenter_admin_api_url = Yf_Registry::get('paycenter_admin_api_url');
                
                $analytics_app_id = Yf_Registry::get('analytics_app_id');
                $analytics_jump_url = Yf_Registry::get('analytics_api_url') . '?plat_id=' . $analytics_app_id;
            ?>
            <ol>
                <li><a href="<?= $ucenter_admin_url ?>" target="_blank"><i class="nav_href">UCenter</i></a></li>
                <li><a href="<?= $paycenter_admin_api_url ?>" target="_blank"><i class="nav_href">PayCenter</i></a></li>
                <!--<li><a href="<? /*= Yf_Registry::get('url') */ ?>?ctl=Login&met=loginout"><i class="nav_href">广告系统</i></a></li>
				<li><a href="<? /*= Yf_Registry::get('url') */ ?>?ctl=Login&met=loginout"><i class="nav_href">大数据</i></a></li>
				<li><a href="<? /*= Yf_Registry::get('url') */ ?>?ctl=Login&met=loginout"><i class="nav_href">备份系统</i></a></li>-->
                <?php
                    $im_statu = Yf_Registry::get('im_statu');
                    if ($im_statu == 1) {
                        ?>
                        <li><a href="<?= Yf_Registry::get('im_admin_api_url') ?>" target="_blank"><i class="nav_href"><?php echo _('ImBuilder') ?></i></a></li>
                    <?php } ?>
                <li><img src="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=Index&met=img&user_id=<?= Perm::$userId ?>">
                    <div><span><?= Perm::$row['user_account'] ?></span>
                        <div>
                </li>
                <li><a href="#"><i class="iconfont icon-top01"></i></a></li>
                <li><a href="<?= Yf_Registry::get('shop_api_url') ?>" target="_blank"><i class="iconfont icon-top02"></i></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Login&met=loginout"><i class="iconfont icon-top03"></i></a></li>
            </ol>
        </div>
    </div>
    <div class="col-bd">
        <div id="col-side">
            <div class="nav-wrap hidden cf"><!--商品-->
                <ul id="nav" class="cf">
                    <?php foreach ($menus as $key => $val) { ?>
                        <li class="item item-setting">
                            <a href="javascript:void(0);" class="setting main-nav"><i class="iconfont <?= ($val['menu_icon']) ?>"></i>
                                <p><?= ($val['menu_name']) ?></p><s></s></a>
                        </li>
                    
                    <?php } ?>
                </ul>
                
                <div id="sub-nav"><!--商城设置-->
                    <?php foreach ($menus as $key => $val) { ?>
                        <ul>
                            <?php foreach ($val['next_menus'] as $k => $v) { ?>
                                <li>
                                    <i class="iconfont icon-point"></i>
                                    <a data-right="BU_QUERY" href="<?= Yf_Registry::get('url') ?>?ctl=<?= ($v['menu_url_ctl']) ?>&met=<?= ($v['menu_url_met']) ?><?php if ($v['menu_url_parem']) { ?>&<?= ($v['menu_url_parem']) ?><?php } ?>" rel="pageTab" tabid="<?= ($v['menu_id']) ?>" tabtxt="<?= ($v['menu_name']) ?>">
                                        <?= ($v['menu_name']) ?>
                                    </a>
                                </li>
                            <?php } ?>
                        
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div id="col-main">
            <div id="main-bd">
                <div class="page-tab" id="page-tab"></div>
            </div>
        </div>
    </div>
</div>
<div id="selectSkin" class="shadow dn">
    <ul class="cf">
        <li><a id="skin-default"><span></span>
                <small>经典</small>
            </a></li>
        <li><a id="skin-blue"><span></span>
                <small>丰收</small>
            </a></li>
        <li><a id="skin-green"><span></span>
                <small>小清新</small>
            </a></li>
    </ul>
</div>
<!--暂时屏蔽未开发菜单-->
<script>
    $('.soon').click(function () {
        parent.Public.tips({type: 2, content: '为防止测试人员乱改数据，演示站功能受限，暂时屏蔽。'});
    });
    
    // 菜单初始样式
    $('#nav li:first').addClass('cur');
    $('#sub-nav ul:first').attr('class', 'cur cf');
    $('#sub-nav ul:first').attr('id', 'setting-base');

</script>
<script src="<?= $this->view->js ?>/controllers/default.js"></script>
<?php
    include $this->view->getTplPath() . '/' . 'footer.php';
?>



