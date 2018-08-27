<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
    include $this->view->getTplPath() . '/' . 'header.php';
?>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/Group-integral.css" />
    <link href="<?= $this->view->css ?>/tips.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.toastr.min.js"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/common.js"></script>
    <!-- 内容 -->
    <div id="append_parent"></div>
    <div id="ajaxwaitid"></div>
    <div class="bbc-breadcrumb-layout">
        <div class="bbc-breadcrumb wrapper">
            <i class="icon-home"></i>
            <span>
                <a href="<?= Yf_Registry::get('url') ?>">
                    <?= __('首页') ?>
                </a>
            </span>
            <span class="arrow">
                <i class="iconfont icon-iconjiantouyou"></i>
            </span>
            <span>
                <a href="<?= Yf_Registry::get('url') ?>?ctl=RedPacket&met=redPacket">
                    <?= __('红包') ?>
                </a>
            </span>
            <span class="arrow">
                <i class="iconfont icon-iconjiantouyou"></i>
            </span>
            <span>
                <a href="<?= Yf_Registry::get('url') ?>?ctl=RedPacket&met=redPacket">
                    <?= __('红包列表') ?>
                </a>
            </span>
        </div>
    </div>
    <style>
        .bbc-member-top .bbc-member-point dl {
            width: 96px !important;
        }
    </style>
    <div class="bbc-container">
        <?php if (Perm::checkUserPerm()) { ?>
            <div class="bbc-member-top">
                <div class="bbc-member-info">
                    <div class="avatar">
                        <img src="<?= image_thumb($data['user_info']['user_logo'], 80, 80) ?>">
                        <div class="frame"></div>
                    </div>
                    <dl>
                        <dt>Hi, <?= $data['user_info']['user_name'] ?></dt>
                        <dd><?= __('当前等级') ?>：<strong>V<?= $data['user_info']['user_grade'] ?></strong></dd>
                        <dd><?= __('当前经验值') ?>：<strong><?= $data['user_resource']['user_growth'] ?></strong></dd>
                    </dl>
                </div>
                <div class="bbc-member-grade" style="padding:32px 4px;">
                    <div class="progress-bar">
                        <em title="V<?= $data['user_info']['user_grade'] ?><?= __('需经验值') ?><?= $data['growth']['grade_growth_start'] ?>">V<?= $data['user_info']['user_grade'] ?></em><span title="<?= $data['growth']['grade_growth_per'] ?>%"><i class="bbc_bg" style="width:<?= $data['growth']['grade_growth_per'] ?>%;"></i></span><em title="V<?= $data['user_info']['user_grade'] + 1 ?><?= __('需经验值') ?><?= $data['growth']['grade_growth_end'] ?>">V<?= $data['user_info']['user_grade'] + 1 ?></em></div>
                    <div class="progress"><?= __('还差') ?><em class="bbc_color"><?= $data['growth']['next_grade_growth'] ?></em><?= __('经验值即可升级成为V') ?><?= $data['user_info']['user_grade'] + 1 ?><?= __('等级会员') ?></div>
                </div>
                <div class="bbc-member-point">
                    <dl style="border-left: none 0;">
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points" target="_blank">
                            <dt><strong class="bbc_color"><?= $data['user_resource']['user_points'] ?></strong><?= __('分') ?></dt>
                            <dd><?= __('我的积分') ?></dd>
                        </a>
                    </dl>
                    <dl>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Voucher&met=voucher" target="_blank">
                            <dt><strong class="bbc_color"><?= $data['ava_voucher_num'] ?></strong><?= __('张') ?></dt>
                            <dd><?= __('可用代金券') ?></dd>
                        </a>
                    </dl>
                    <dl>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_RedPacket&met=redPacket" target="_blank">
                            <dt><strong class="bbc_color"><?= $data['ava_redpacket_num'] ?></strong><?= __('张') ?></dt>
                            <dd><?= __('平台红包列表') ?></dd>
                        </a>
                    </dl>
                    <dl>
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points&op=getPointsOrder" target="_blank">
                            <dt><strong class="bbc_color"><?= $data['points_order_num'] ?></strong><?= __('个') ?></dt>
                            <dd><?= __('已兑换礼品') ?></dd>
                        </a>
                    </dl>
                </div>
                <div class="bbc-memeber-pointcart">
                    <a href="<?= Yf_Registry::get('url') ?>?ctl=Points&met=pointsCart" class="btn bbc_bg_col">
                        <?= __('礼品兑换购物车') ?>
                        <em><?= $data['points_cart_num'] ?></em>
                    </a>
                </div>
            </div>
        <?php } ?>
        <div>
            <div class="bbc-category">
                <!-- 3.2.0新增领券类型 -->
                <!-- <dl class="bbc-category-class">
                    <dt class="fz16">领券类型：</dt>
                    <dd><ul>
                        <li>店铺代金券</li>
                        <li class="hova">平台红包</li>
                    </ul></dd>
                </dl> -->
                <dl class="searchbox inline">
                    <dt><?= __('排序方式') ?>：</dt>
                    <dd>
                        <ul>
                            <input type="hidden" id="orderby" name="orderby" value="default">
                            <li op_type="search_orderby" onclick='list("default")' class="<?= request_string('orderby') == 'default' || !request_string('orderby') ? 'hova' : '' ?>"><?= __('默认排序') ?></li>
                            <li class="<?= (request_string('orderby') == 'exchangenumdesc' || request_string('orderby') == 'exchangenumasc') ? 'hova' : '' ?>" onclick='list(<?= request_string('orderby') == 'exchangenumdesc' ? '"exchangenumasc"' : '"exchangenumdesc"' ?>)' op_type="search_orderby"><?= __('兑换量') ?>
                                <em class="display_arrow">
                                    <?php if (request_string('orderby') == 'exchangenumdesc' || request_string('orderby') == 'exchangenumasc') { ?>
                                        <i class="iconfont <?= (request_string('orderby') == 'exchangenumdesc') ? 'icon-jiantouxiangxia' : 'icon-jiantouxiangshang' ?>"></i>
                                    <?php } ?>
                                </em>
                            </li>
                            <li class="<?= (request_string('orderby') == 'denominationdesc' || request_string('orderby') == 'denominationasc') ? 'hova' : '' ?>" onclick='list(<?= request_string('orderby') == 'denominationdesc' ? '"denominationasc"' : '"denominationdesc"' ?>)' op_type="search_orderby"><?= __('兑换面额') ?>
                                <em class="display_arrow">
                                    <?php if (request_string('orderby') == 'denominationdesc' || request_string('orderby') == 'denominationasc') { ?>
                                        <i class="iconfont <?= (request_string('orderby') == 'denominationdesc') ? 'icon-jiantouxiangxia' : 'icon-jiantouxiangshang' ?>"></i>
                                    <?php } ?>
                                </em>
                            </li>
                            <?php if (Perm::checkUserPerm()) {
                                if ($data['user_info']['user_grade']) {
                                    ?>
                                    <li>
                                        <label for="isable">
                                            <input class="jifen_input" id="isable" <?php if (request_int('isable')) { ?> checked="checked" <?php } ?> onclick="available(this);" type="checkbox">
                                            <?= __('只看我能兑换') ?>
                                        </label>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </dd>
                </dl>
            </div>
            <?php if ($data['redpacket']['items']) { ?>
                <ul class="voucher-center-li clearfix mt10">
                    <?php foreach ($data['redpacket']['items'] as $key => $value) { ?>
                        <li class="fl" data-id="<?= $value['redpacket_t_id'] ?>"><!--注： li的状态分别添加不同的class：已领取：had-get 已抢光：had-loot -->
                            <div class="fl voucher-center-li-cont">
                                <img src="<?= $value['redpacket_t_img'] ?>" alt="" class="fl">
                                <div class="fl voucher-center-li-text">
                                    <p class="mb10">
                                    <span class="fz20 common-color mr10">
                                        <i class="fz12 common-color top">￥</i>
                                        <?= $value['redpacket_t_price'] ?>
                                    </span>
                                        <em class="fz12 common-color">
                                            <?= __('满') ?><?= $value['redpacket_t_orderlimit'] ?><?= __('可用') ?>
                                        </em>
                                    </p>
                                    <h5 class="fz12 c-333 one-overflow"><?= $value['redpacket_t_title'] ?></h5>
                                    <span class="block c-999 fz12">
                                    <?= __('限') ?><?= $value['redpacket_t_end_date_day'] ?><?= __('前使用') ?>
                                </span>
                                    <b class="block mt10 fz12 c-999 normal">
                                        <?= $value['redpacket_t_giveout'] ?>
                                        <?= __('张已被领取') ?>
                                    </b>
                                </div>
                                <!--注： 只有已领取状态才有icon-get -->
                                <i class="icon-get"></i>
                            </div>
                            <!--注： a 的不同状态的内容：1，立<br>即<br>领<br>取 ,2，已<br>领<br>取 3， 已<br>抢<br>光 -->
                            <!--                            <a href="javascript:void(0);" op_type="exchangebtn" data-param='{"vid":"--><? //= $value['redpacket_t_id'] ?><!--"}' class="fr voucher-get-btn fz20" onclick="ReceiveRedPack()">-->
                            <a href="JavaScript:void(0);" class="fr voucher-get-btn fz20" onclick="ReceiveRedPack(<?= $value['redpacket_t_id']; ?>)">
                                <?= __('立<br>即<br>领<br>取') ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } else { ?>
                <div class="no_account">
                    <img src="<?= $this->view->img ?>/ico_none.png" />
                    <p><?= __('暂无符合条件的数据记录') ?></p>
                </div>
            <?php } ?>
            <div class="flip clearfix">
                <?php if ($page_nav) { ?>
                    <div class="page"><?= $page_nav ?></div>
                <?php } ?>
            </div>
        </div>
    </div>
    <script>
        //综合排序，销量，价格，新品
        function list(e) {
            //地址中的参数
            var params = window.location.search;
            params = changeURLPar(params, 'orderby', e);
            window.location.href = SITE_URL + params;
        }
        
        function available(e) {
            var isable = 0;
            //地址中的参数
            if ($("#isable").is(':checked')) {
                isable = 1;
            }
            var params = window.location.search;
            params = changeURLPar(params, 'isable', isable);
            window.location.href = SITE_URL + params;
        }
        
        function changeURLPar(destiny, par, par_value) {
            var pattern = par + '=([^&]*)';
            var replaceText = par + '=' + par_value;
            if (destiny.match(pattern)) {
                var tmp = new RegExp(pattern);
                tmp = destiny.replace(tmp, replaceText);
                return (tmp);
            } else {
                if (destiny.match('[\?]')) {
                    return destiny + '&' + replaceText;
                } else {
                    return destiny + '?' + replaceText;
                }
            }
            return destiny + '\n' + par + '\n' + par_value;
        }
        
        function ReceiveRedPack(redpacket_t_id) {
            if (!redpacket_t_id) {
                return;
            }
            
            Public.ajaxPost(SITE_URL + "?ctl=RedPacket&typ=json&met=receiveRedPacket", {red_packet_t_id: redpacket_t_id}, function (data) {
                if (data.status == 200) {
                    Public.tips.success(data.msg);
                } else {
                    Public.tips.warning(data.msg);
                }
            });
            
        }
        
        /*以下方法未能使用到*/
        //分类
        function cat(e) {
            //地址中的参数
            var params = window.location.search;
            
            params = changeURLPar(params, 'vc_id', e);
            
            window.location.href = SITE_URL + params;
        }
        
        function price(e) {
            //地址中的参数
            var params = window.location.search;
            e = $(e).val();
            params = changeURLPar(params, 'price', e);
            window.location.href = SITE_URL + params;
        }
        
        function searchvoucher() {
            var params = window.location.search;
            var points_min = $("#points_min").val();
            if (points_min) {
                params = changeURLPar(params, 'points_min', points_min);
            }
            
            var points_max = $("#points_max").val();
            
            if (points_max) {
                params = changeURLPar(params, 'points_max', points_max);
            }
            window.location.href = SITE_URL + params;
        }
    
    </script>
<?php
    include $this->view->getTplPath() . '/' . 'footer.php';
?>