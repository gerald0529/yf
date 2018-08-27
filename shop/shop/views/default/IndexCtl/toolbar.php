<style>
    .cart_list_ordernum .tm-mcMinus, .cart_list_ordernum .tm-mcPlus {
        width: 14px;
        border-radius: 14px;
        border: 1px solid #bfbfbf;
        position: relative;
        cursor: pointer;
        display: inline-block;
        height: 14px;
        visibility: hidden;
    }

    .cart_list_ordernum s {
        width: 8px;
        height: 2px;
        top: 6px;
        left: 3px;

    }

    .cart_list_ordernum b {
        width: 2px;
        height: 8px;
        top: 3px;
        left: 6px;
    }

    .cart_list_ordernum s, .cart_list_ordernum b {
        position: absolute;
        overflow: hidden;
        background: #bfbfbf;
    }

    .cart_list_ordernum .tm-mcMinusOff s {
        background: #e6e6e6;
    }

    .tm-mcOrderActive .tm-mcMinus, .tm-mcOrderActive .tm-mcPlus {
        visibility: visible;
        background: #f3f3f3;
    }

    .tm-mcDel {
        float: right;
        display: none;
    }

    .tm-mcOrderActive .tm-mcDel {
        display: block;
    }
</style>
<script type="text/javascript" src="<?= $this->view->js ?>/nav.js"></script>
<div class="toolbar-wrap J-wrap">
    <div class="toolbar">
        <div class="toolbar-panels J-panel">
            <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-news toolbar-animate-out ">
                <div class="toolbar-panelff">
                    <div class="padd2">
                        <a class="close_p ml10"><?=__('关闭')?><i class="iconfont icon-youshaungjiantou"></i></a>
                        <p class="view_all"><a href="<?= Yf_Registry::get('url') ?>?ctl=Article_Base&amp;met=index"><?=__('全屏查看')?></a></p>
                    </div>
                    <div class="tbar-panel-main tbar-panel-main-sidebar news_contents">
                        <ul>

                            <?php if(!empty($Announcement['items'])){?>
                                <?php
                                        foreach($Announcement['items'] as $k=>$v){ ?>
                                    <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Article_Base&article_id=<?= $v['article_id'] ?>" target="_blank">&bull;&nbsp;<?=$v['article_title']?></a></li>
                                    <?php }?>
                                        <?php }else{?>
                                            <div class="item_cons_no">
                                                <?=__('公告为空')?>
                                            </div>
                                            <?php }?>
                        </ul>
                    </div>
                </div>
            </div>
            <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-cart toolbar-animate-out">
                <div class="padd2">
                    <a class="close_p ml10">
                        <?=__('关闭')?><i class="iconfont icon-youshaungjiantou"></i></a>
                    <p class="view_all">
                        <a href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Cart&met=cart">
                            <?=__('全屏查看')?>
                        </a>
                    </p>
                </div>
                <?php $count = 0;
                                if(isset($cart_list) && $cart_list['count']) :
                            ?>
                    <div class="padd2 Js-toolbar-cart">
                        <p class="select_all clearfix ml10 Js-toolbar-cart">
                            <input type="checkbox" class="checkall checkcart rel_top2"><span><?=__('全选')?></span></p>
                    </div>

                  

                        <div class="tbar-panel-main tbar-panel-main-sidebar cart_con Js-toolbar-cart">
                          <form id="form" action="?ctl=Buyer_Cart&met=confirm" method='post'>
                            <?php
                                    $count = $cart_list['count'];
                                        unset($cart_list['count']);
                                        foreach($cart_list as $cartk => $cartv):
                                    ?>
                                <div class="cart_contents">
                                    <div class="cart_contents_head">
                                        <div class="cart_contents_inp">
                                            <input class="tm-mcElectBundle checkitem checkshop checkcart" type="checkbox" value="<?=($cartk)?>">
                                        </div>
                                        <div class="cart_contents_title">
                                            <a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=Index&id=<?=($cartv['shop_id'])?>"><span title="<?=($cartv['shop_name'])?>"><?=($cartv['shop_name'])?></span></a>
                                            <?php if(isset($cartv['mansong_info']['rule_discount'])){?>
                                                <?=__('（促销）')?>
                                                    <?php }?>
                                        </div>
                                        <div class="cart_contents_cost">
                                            <strong class="shop_sprice_<?=$cartv['shop_id']?>" >
                                                <?=format_money($cartv['sprice'])?>
                                            </strong>
                                        </div>
                                    </div>
                                    <div class="cart_lists">
                                        <?php foreach($cartv['goods'] as $cartgk => $cartgv):?>
                                            <div class="cart_list" data-cart-id="<?= $cartgv['cart_id']; ?>">
                                                <div class="cart_list_order clearfix">
                                                    <div class="cart_list_orderinp cart-checkbox">
                                                        <input type="checkbox" value="<?=($cartgv['cart_id'])?>" data-nums_id='nums_<?=($cartgv['cart_id'])?>' class="checkitem checkcart" name="product_id[]">
                                                    </div>
                                                    <div class="cart_list_orderimg">
                                                        <a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($cartgv['goods_id'])?>" target="_blank">
                                                        <img src="<?=image_thumb($cartgv['goods_base']['goods_image'],50,50)?>">
                                                    </a>
                                                    </div>
                                                    <div class="cart_list_ordersize">
                                                        <a class='hover-cor3a' target="_blank" title="<?=($cartgv['goods_base']['goods_name'])?>" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($cartgv['goods_base']['goods_id'])?>"><?=($cartgv['goods_base']['goods_name'])?></a>
                                                    </div>
                                                    <div class="cart_list_ordernum">
                                                        <a href="javascript:void(0)" data-shop_id='<?=$cartgv['shop_id']?>' class="tm-mcMinus <?= $cartgv['goods_num'] == 1 ? "tm-mcMinusOff" : "" ?>" hidefocus="true"><s></s></a>
                                                        <span class="tm-mcQuantity" id="nums_<?=($cartgv['cart_id'])?>"><?=($cartgv['goods_num'])?></span>
                                                        <a href="javascript:void(0)" data-shop_id='<?=$cartgv['shop_id']?>' class="tm-mcPlus" hidefocus="true"><s></s><b></b></a>
                                                    </div>
                                                    <div class="cart_list_ordercost" style="width: 42px;">
                                                        <a href="javascript:void(0)" class="tm-mcDel" title="删除" data-tmc="del">删除</a>
                                                        <strong class="tm-mcPrice"><?=format_money($cartgv['sumprice'])?></strong>
                                                        <input type="hidden" class="goods_sumprice goods_shop_<?=$cartgv['shop_id']?>" value="<?=($cartgv['sumprice'])?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach;?>
                                    </div>
                                </div>
                                <?php
                                        endforeach;
                                    ?>
                            </form>
                        </div>
                    
                    <div class="cart_pay Js-toolbar-cart">
                        <div class="padd">
                            <div class="cart_foot clearfix"><span class="have_sel"><?=__('已选')?><i>0</i><?=__('件')?></span>
                                <span class="cartall"><?=Web_ConfigModel::value("monetary_unit")?>0.00</span></div>
                            <div class="topay">
                                <a class="submit-btn-disabled submit-btn bbc_bg_col">
                                    <?=__('结算')?><b class="yuan iconfont icon-iconjiantouyou"></b>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="item_cons_no">
                            <?=__('购物车为空')?>
                        </div>
                        <?php endif;?>
            </div>
            <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-assets toolbar-animate-out">
                <div class="padd">
                    <p>
                        <a href="#" class="close_p">
                            <?=__('关闭')?><i class="iconfont icon-youshaungjiantou"></i></a>
                        <a href="<?= Yf_Registry::get('paycenter_api_url') ?>" class="view_all">
                            <?=__('全屏查看')?>
                        </a>
                    </p>
                    <ul class="assets_overview clearfix">
                        <li>
                            <a href="<?= Yf_Registry::get('paycenter_api_url') ?>">
                                <span><?=@$user_list['user_money'];?></span>
                                <h6><?=__('账户余额')?></h6>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points">
                                <span><?=@$user_list['user_points'];?></span>
                                <h6><?=__('积分')?></h6>
                            </a>
                        </li>
                        <li>
                            <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=getUserGrade">
                                <span><?=@$user_list['user_growth'];?></span>
                                <h6><?=__('成长值')?></h6>
                            </a>
                        </li>

                    </ul>
                    <div class="other_voucher"></div>
                </div>
            </div>
            <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-follow toolbar-animate-out">
                <div class="padd">
                    <p>
                        <a href="#" class="close_p">
                            <?=__('关闭')?><i class="iconfont icon-youshaungjiantou"></i></a>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesShop" class="view_all">
                            <?=__('全屏查看')?>
                        </a>
                    </p>
                    <div class="item_cons">
                        <?php if(!empty($shop_list['items'])){?>
                            <?php
                                        foreach($shop_list['items'] as $k=>$v){ ?>
                                <div class="item">
                                    <!-- <img class="brand_logo" src="<?=image_thumb($v['shop_logo'],90,45)?>"> -->
                                    <img src="<?php if ($v['shop_logo']) echo image_thumb($v['shop_logo'],90,45); else echo $this->view->img . '/default_store_image.png'; ?>">
                                    <a class="barnd_shop" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?=$v['shop_id']?>">
                                        <?=__('进入店铺')?>
                                    </a>
                                    <div class="brand_goodsList">
                                        <?php if(!empty($v['detail']['items'])){?>
                                            <?php foreach($v['detail']['items'] as $kk=>$vv){ ?>
                                                <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$vv['goods_id']?>">
                                                <img src="<?=image_thumb($vv['common_image'],100,100)?>">
                                                <p class="brand_name" title="<?=$vv['common_name']?>"><?=$vv['common_name']?></p>
                                                <p class="brand_price" title="<?=format_money($vv['common_price'])?>"><?=format_money($vv['common_price'])?></p>
                                            </a>
                                                <?php }?>
                                                    <?php }?>
                                    </div>
                                </div>
                                <?php }?>
                                    <?php }else{?>
                                        <div class="item_cons_no">
                                            <?=__('店铺收藏为空')?>
                                        </div>
                                        <?php }?>
                    </div>
                </div>
            </div>
            <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-history toolbar-animate-out">
                <div class="padd over">
                    <p>
                        <a href="#" class="close_p">
                            <?=__('关闭')?><i class="iconfont icon-youshaungjiantou"></i></a>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=footprint" class="view_all">
                            <?=__('全屏查看')?>
                        </a>
                    </p>
                    <ul class="history_goods clearfix">
                        <?php if(!empty($footprint_list['items'])){?>
                            <?php
                                            foreach($footprint_list['items'] as $k=>$v){ ?>
                                <?php if(!empty($v['detail'])){?>

                                    <li class="posr">
                                        <?php if($v['detail']['is_del'] == 2){?>
                                    	    <p class="old-Failed  old-Failed-history">此商品<br/>已失效</p>
                                        <?php } ?>
                                    	<a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$v['detail']['goods_id']?>"><img src="<?php if(!empty($v['detail']['common_image'])){?><?=image_thumb($v['detail']['common_image'],116,116)?><?php }else{?><?= image_thumb($this->web['goods_image'],116,116)?><?php }?>"/><h5><?=$v['detail']['common_name']?></h5><h6  class="bbc_color"><?=format_money($v['detail']['common_price'])?></h6></a></li>
                                    <?php }?>

                                        <?php }?>
                                            <?php }else{?>
                                                <div class="item_cons_no">
                                                    <?=__('你没有浏览商品')?>
                                                </div>
                                                <?php }?>
                    </ul>
                </div>
            </div>
            <div id="collectGoods" style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-sav toolbar-animate-out">
                <div class="padd over">
                    <p class="padd2">
                        <a href="#" class="close_p">
                            <?=__('关闭')?><i class="iconfont icon-youshaungjiantou"></i></a>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesGoods" class="view_all">
                            <?=__('全屏查看')?>
                        </a>
                    </p>
                    <ul class="sav_goods clearfix">
                        <?php if(!empty($goods_list['items'])){?>
                            <?php
                                        foreach($goods_list['items'] as $k=>$v){ ?>
                                <?php if(!empty($v['detail'])){?>
                                    <li  class="posr">
                                        <?php if($v['detail']['is_del'] == 2){?>
                                            <p class="old-Failed  old-Failed-history">此商品<br/>已失效</p>
                                        <?php } ?>
                                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$v['goods_id']?>"><img src="<?php if(!empty($v['detail']['goods_image'])){?><?=image_thumb($v['detail']['goods_image'],116,116)?><?php }else{?><?= image_thumb($this->web['goods_image'],116,116)?><?php }?>"/><h5><?=$v['detail']['goods_name']?></h5><h6  class="bbc_color"><?=format_money($v['detail']['goods_price'])?></h6></a>
                                    </li>
                                    <?php }?>
                                        <?php }?>
                                            <?php }else{?>
                                                <div class="item_cons_no">
                                                    <?=__('你没有收藏商品为空')?>
                                                </div>

                                                <?php }?>
                    </ul>
                </div>
            </div>

        </div>

        <div class="toolbar-header"></div>
        <div class="tbar-tab-news">
            <i class="tab-ico iconfont icon-icongonggao nav_icon news_icon"></i>
            <em class="tab-text "><?=__('通知')?></em>
            <span class="tab-sub J-count hide"></span>
        </div>
         <div class="tbar-tab-online-contact">
            <i class="tab-ico iconfont icon-logo_im nav_icon"></i>
            <em class="tab-text"><?=__('在线联系')?></em>
            <span class="tab-sub J-count hide"></span>
        </div>

        <div class="toolbar-tabs J-tab">
            <div class="nav_head">
                <a href="./index.php?ctl=Buyer_Index&met=index">
                              <img src="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=Index&met=img&user_id=<?= @Perm::$userId ?>"/>
                            </a>
            </div>
            <div class=" toolbar-tab  tbar-tab-cart shopcli">
                <i class="tab-ico iconfont icon-gouwuche2 nav_icon top-15"></i>
                <!-- <span class="shopic">购物车</span> -->
                <em class="tab-text"><?=__('我的购物车')?></em>

                <!--购物车数量-->
                <?php if($count > 0){ ?>
                <span class="tab-sub J-count cart_num_toolbar" ><?=($count)?></span>
                <?php } ?>
            </div>
            <div class=" toolbar-tab  tbar-tab-assets">
                <i class="tab-ico iconfont icon-iconyouhuiquan nav_icon top-15"></i>
                <em class="tab-text"><?=__('我的资产')?></em>
            </div>
            <div class=" toolbar-tab tbar-tab-follow ">
                <i class="tab-ico iconfont icon-iconshoucang nav_icon"></i>
                <em class="tab-text"><?=__('我的关注')?></em>
                <span class="tab-sub J-count hide"></span>
            </div>
            <div class=" toolbar-tab tbar-tab-sav ">
                <i class="tab-ico iconfont icon-icoheart nav_icon top-15" id="collect_lable"></i>
                <em class="tab-text"><?=__('我的收藏')?></em>
                <span class="tab-sub J-count hide"></span>
            </div>
            <div class=" toolbar-tab tbar-tab-history ">
                <i class="tab-ico iconfont icon-iconzuji nav_icon"></i>
                <em class="tab-text"><?=__('我的足迹')?></em>
                <span class="tab-sub J-count hide"></span>
            </div>
        </div>
        <div class="toolbar-footer">
            <div class="code_screen">
                <a class="about_code iconfont icon-btnsaoma tab-ico nav_icon" href="#"></a>
                <p class="code_cont">
                    <?php if(Web_ConfigModel::value('mobile_wx')){?>
                        <img src="<?= Web_ConfigModel::value('mobile_wx')?>" style="width:110px;height:110px"/>
                    <?php }else{ ?>
                        <img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?=urlencode(Yf_Registry::get('shop_wap_url'))?>" width="100%" height="100%"/>
                    <?php }?>
                </p>
            </div>
            <div>
                <a class="about_top iconfont icon-top about_top tab-ico nav_icon" href="#"></a>
            </div>
        </div>

    </div>

    <div id="J-toolbar-load-hook"></div>

</div>
<!-- 登录遮罩层 -->
<div id="login_content" style="display:none;"></div>
<script>
    $(function () {
        $("input[type='checkbox'][class='checkcart']").prop("checked", false);
        var _TimeCountDown = $(".fnTimeCountDown");
        _TimeCountDown.fnTimeCountDown();
    })

    //全选
    $('.checkall').click(function () {
        var _self = this;
        $('.checkitem').each(function () {
            if (!this.disabled) {
                $(this).prop('checked', _self.checked);
            }
        });
        $('.checkall').prop('checked', this.checked);
        count();
    });

    function count() {
        var count = 0;
        var num = 0;
        $(".cart-checkbox").find("input[name='product_id[]']:checked").each(function () {
            var value = $(this).val();
            var price = ($(this).parent().parent().find(".cart_list_ordercost .goods_sumprice").val());
            //price = price.replace(/,/g, "");
            price = Number(price);
            count = count + price;
            num++;
        });
        $(".cartall").html('￥' + count.toFixed(2));

        $(".have_sel i").html(num);
        if (num > 0) {
            $(".submit-btn").removeClass("submit-btn-disabled");
        } else {
            $(".submit-btn").addClass("submit-btn-disabled");
        }
    }

    //勾选店铺
    $('.checkshop').click(function () {
        var _self = this;
        if (_self.checked) {
            $(this).parents(".cart_contents").find(".checkitem").prop('checked', true);
        } else {
            $(this).parents(".cart_contents").find(".checkitem").prop('checked', false);
        }

        count();
    });

    //单度选择商品
    $('.checkitem').click(function () {
        var _self = this;
        if (!this.disabled) {
            $(this).prop('checked', _self.checked);

            if (_self.checked) {
                //判断该店铺下的商品是否已全选
                if ($(this).parents('.cart_lists').find(".checkitem").not("input:checked").length == 0) {
                    $(this).parents(".cart_contents").find(".checkshop").prop('checked', true);
                }

                //判断是否所有商品都已选择，如果所有商品都选择了就勾选全选
                if ($(".checkitem").not("input:checked").length == 0) {
                    $('.checkall').prop('checked', true);
                }
            } else {
                //判断该店铺下的商品是否已全选
                if ($(this).parents('.cart_lists').find(".checkitem").not("input:checked").length != 0) {
                    $(this).parents(".cart_contents").find(".checkshop").prop('checked', false);
                }

                //判断全选是否勾选，如果勾选就去除
                if ($(".checkitem").not("input:checked").length != 0) {
                    $('.checkall').prop('checked', false);
                }
            }
        }
        count();
    });

    //结算
    $('.submit-btn').click(function () {

        if (!$(this).is('.submit-btn-disabled')) {
            //获取所有选中的商品id
            var chk_value = []; //定义一个数组

            var nums_value = [];
            $("input[name='product_id[]']:checked").each(function(){
                chk_value.push($(this).val());//将选中的值添加到数组chk_value中
                var nums_id = $(this).data('nums_id');
                nums_value.push($("#"+nums_id).html());
            })
            
            if(chk_value != "")
            {
                $.post(SITE_URL  + '?ctl=Buyer_Cart&met=newconfirm&typ=json',{product_id:chk_value,nums:nums_value},function(data)
                    {
                        if(data && 250 == data.status) {
                            alert_box(data.msg);
                            return false;
                        }else{
                            // $('#form').submit();
                            location.href = SITE_URL + "?ctl=Buyer_Cart&met=confirm&product_id="+chk_value;
                        }
                    }
                );
            }

        }

    });
</script>

<script type="text/javascript">
     $(document).ready(function(){
            var nice_scroll_row = ['.sav_goods', '.cart_con', '.item_cons', '.history_goods', '.news_contents', '.other_voucher', '.contrast_goods'];

            $.each($.unique(nice_scroll_row), function(index, data)
            {
                $scroll_obj= $(data);

                if ($scroll_obj.length > 0)
                {
                    $scroll_obj.niceScroll({
                        cursorcolor: "#666",
                        cursoropacitymax: 1,
                        touchbehavior: false,
                        cursorwidth: "3px",
                        cursorborder: "0",
                        cursorborderradius: "3px",
                        autohidemode: false,
                        nativeparentscrolling:true
                    });
                }
            });
    })
    
    
//im
if(IM_STATU == 1){
    $.get(SITE_URL+'/index.php?ctl=Api_IM_Im&met=index',function(h){
            $('#imbuiler').attr('src',h);
            im_builder_ch();
            iconbtncomment();
    });
    getUserAccount();
}
 
function getUserAccount(){
    $.ajax({
        type: "GET",
        url: SITE_URL + "?ctl=Index&met=getUserLoginInfo&typ=json",
        data: {},
        dataType: "json",
        success: function(data){ 
            if(data.data.status == 200 && typeof data.data.user_account != 'undefined'){
                var user_account_log = data.data.user_account;
                if(getCookie('user_account') == null || getCookie('user_account') != user_account_log){
//                    setCookie('user_account',user_account_log,365); 
                    delCookie('user_account');
                    addCookie('user_account',user_account_log,24*365*10); 
                }
            }

        }
    });
}
function iconbtncomment(){

    $('.icon-btncomment').click(function(){ 
        if(!getCookie('user_account') || getCookie('user_account') == 'undefined'){
            $("#login_content").show();
            load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');

        }
        var ch_u = $('.chat-enter').attr('rel');
        if(ch_u == getCookie('user_account')){ 

             alert_box('不能跟自己聊天'); 
             return ;
        }
        var inner = $('#imbuiler')[0].contentWindow;
        $('#imbuiler').show();
        //查看聊天右侧的用户列表有没有，没有就点一下最下面的就出来了。
        var dis = $('#imbuiler').contents().find('.chat-list').css('display');

        if(dis!='block'){
            $('#imbuiler').contents().find('.bottom-bar a').click();     
        }  
        inner.chat(ch_u);
        $('#imbuiler')[0].contentWindow.bottom_bar();
        return false;
    });
}
function im_builder_ch(){
    var onl = $(".tbar-tab-online-contact"); 
    onl.show();
    onl.click(function(){
        if(!getCookie('user_account') || getCookie('user_account') == 'undefined'){
            return false;
            // getUserAccount();
            // $("#login_content").show();
            // load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');
        } else {
            $(".layui-layim").slideToggle();// 显示
            // $('#imbuiler').show();
            // $('#imbuiler')[0].contentWindow.bottom_bar();
            // $('#imbuiler').contents().find('.bottom-bar a').click();
            // return;
        }
    });
}
</script>



<script type="application/javascript">

    var flag = true; //避免重复点击，幂等性
    $(".toolbar").on("click", ".tm-mcMinus,.tm-mcPlus", function() {

        var $this = $(this);
        if ($this.hasClass("tm-mcMinusOff")) {
            return false;
        }

        if (flag) {
            flag = false
        } else {
            return Public.tips.warning("请等候，正在为您处理！");
        }

        var $cart = $this.parents(".cart_list"),
            $num = $cart.find(".tm-mcQuantity"),
            $price = $cart.find(".tm-mcPrice"),
            $goodsSumPrice = $cart.find(".goods_sumprice"),
            cartId = $cart.data("cart-id"),
            gNum = $num.text(),
            shop_id = $this.data('shop_id'),
            $gMinus;

        $this.hasClass("tm-mcMinus") ? gNum-- && ($gMinus = $this) : gNum++ && ($gMinus = $this.parent(".cart_list_ordernum").children(".tm-mcMinus"));

        Public.ajaxPost(SITE_URL + "?ctl=Buyer_Cart&met=editCartNum&typ=json", {"cart_id": cartId, "num": gNum},
            function(data) {
                if (data.status == 200) {
                    var price = data.data.price.toFixed(2);
                    $num.text(gNum);
                    $price.text("￥" + price);
                    $gMinus.val(price);
                    gNum == 1 ? $gMinus.addClass("tm-mcMinusOff") : $gMinus.removeClass("tm-mcMinusOff");
                    $goodsSumPrice.val(price);
                    count();
                    //修改店铺价格
                    shop_price(shop_id);
                } else {
                    Public.tips.warning(data.msg);
                }

            },
            function() {},
            function() { flag = true; }
        )
    });

    $(".toolbar").on({
        "mouseover": function() {
            triggerMouse(this, "over");
        },
        "mouseout": function() {
            triggerMouse(this, "out");
        }
    }, ".cart_list");

    function shop_price(shop_id){
        var price = 0;
        $(".goods_shop_"+shop_id).each(function(){
            price = Number($(this).val()) + Number(price);
        });
        $('.goods_shop_'+shop_id).val();
        $('.shop_sprice_'+shop_id).html('￥'+Number(price).toFixed(2));
        
    }
    function triggerMouse(_this, type) {
        var $this = $(_this);
        type == "over"
            ? (!$this.hasClass("tm-mcOrderActive") && $this.addClass("tm-mcOrderActive"))
            : ($this.hasClass("tm-mcOrderActive") && $this.removeClass("tm-mcOrderActive"));
    }

    $(".toolbar").on("click", ".tm-mcDel", function() {
        var $cartGoods = $(this).parents(".cart_list");
        Public.ajaxPost(SITE_URL + "?ctl=Buyer_Cart&met=delCartByCid&typ=json", {id: $cartGoods.data("cart-id")},
            function(data) {
                if (data.status == 200) {
                    getCartNum();
                    $cartGoods.parent().children().length == 1
                        ? $cartGoods.parents(".cart_contents").remove()
                        : $cartGoods.remove();

                    $(".cart_list").length == 0
                        ? getCartList()
                        : count();
                } else {
                    Public.tips.warning(data.msg);
                }
            }
        )
    });
</script>
