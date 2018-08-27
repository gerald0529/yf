<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
    <script type="text/javascript" src="<?= $this->view->js ?>/common.js"></script>
    <link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.toastr.min.js"
            charset="utf-8"></script>
    <link href="<?= $this->view->css ?>/login.css" rel="stylesheet">

    <script type="application/javascript">
        $(function () {
            $(".store-privilege").hover(function () {
                $(this).find(".popup-shopinfo").show();
            }, function () {
                $(this).find(".popup-shopinfo").hide();
            });
        })
    </script>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/store.css">
    <div class="wrap">
        <div class="QR-layout">
            <!-- 筛选 -->
            <div class="sort-bar">
                <div class="sort-bar-wrap">
                    <div class="nch-sortbar-array">
                        <ul class="screen">
                            <li class="<?php if (!request_string('or')) echo 'selected'; ?>">
                                <a href="<?= Yf_Registry::get('url') ?>/index.php?ctl=Shop_Index&plat=<?= @request_string('plat') ?>&district=<?= @request_string('district') ?>"
                                   title="<?= __('默认排序') ?>"><?= __('默认排序') ?></a>
                            </li>
                            <li class="<?php if (request_string('or') == 'collect') echo 'selected'; ?>">
                                <a href="<?= Yf_Registry::get('url') ?>/index.php?ctl=Shop_Index&or=collect&district=<?= @request_string('district') ?>&plat=<?= @request_string('plat') ?>"
                                   title="<?= __('点击按成收藏量从高到低排序') ?>"><?= __('收藏量') ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="nch-sortbar-filter">
                        <div class="widget-label">
						<span class="widget-label-txt" style="width:38px; text-align:right">
							<?php if (@request_string('district')) echo request_string('district'); else echo "所在地" ?>
						</span>
                            <i class="widget-label-arrow"></i>
                        </div>
                        <div class="widget-location">
                            <?php if (request_string('district')) { ?>
                                <a class="lacation-sure"
                                   href="<?= Yf_Registry::get('url') ?>/index.php?ctl=Shop_Index&plat=<?= @request_string('plat') ?>"><?= __('取消选择') ?></a>
                            <?php } ?>
                            <div class="section_ul">
                                <ul>
                                    <?php
                                    if ($district_data){
                                    foreach ($district_data['items'] as $ks => $district)
                                    {
                                    ?>
                                    <li>
                                        <a href="<?= Yf_Registry::get('url') ?>/index.php?ctl=Shop_Index&or=<?= @request_string('or') ?>&district=<?= @$district['district_name'] ?>&plat=<?= @request_string('plat') ?>"><?= @$district['district_name'] ?></a>
                                    </li>
                                    <?php if (($ks + 1) % 6 == 0){ ?>
                                </ul>
                            </div>
                            <div class="section_ul">
                                <ul>
                                    <?php } ?>
                                    <?php }
                                    } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="nch-sortbar-filter" style="width:100px;">
                        <div class="widget-label">
                            <?php if (Web_ConfigModel::value($self_shop_show_key) == 1) { ?>
                                <input type="checkbox" class="checkbox rel_top-1 vermiddle"
                                       <?php if (request_string('plat') == '1'){ ?>checked<?php } ?> name="plat"/>
                                <label><?= __('平台自营') ?></label>
                            <?php } ?>

                        </div>
                    </div>
                </div>
                <!--店铺列表-->
                <div class="search-store">
                    <ul>
                        <?php if ($data['items']) {
                            foreach ($data['items'] as $key => $val) {
                                ?>
                                <li class="store-list">
                                    <div class="store-left">
                                        <div class="store-info">
                                            <div class="store-img">
                                                <a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?= ($val['shop_id']) ?>"
                                                   target="_blank" title=""><img
                                                            src="<?php if ($val['shop_logo']) echo $val['shop_logo']; else echo $this->view->img . '/default_store_image.png'; ?>"></a>
                                            </div>
                                            <div class="store-info-o">
                                                <p>
                                                    <a class="store-name m-r-5"
                                                       href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&typ=e&id=<?= ($val['shop_id']) ?>"
                                                       target="_blank">
                                                        <?php if ($val['shop_self_support'] == 'true') { ?><span
                                                                class="goods_self m-r-5"><?= __('自营') ?></span><?php } ?>
                                                        <?= $val['shop_name'] ?>
                                                    </a>
                                                    <a href="javascript:;" data-nc-im="" data-im-seller-id="6"
                                                       data-im-common-id="0"><i class="im_common offline"></i></a>
                                                </p>
                                                <?php if ($val['shop_self_support'] == 'false') { ?>
                                                    <p style="display: none">
                                                        <?= __('所在地') ?>：
                                                        <span><?= @$val['shop_company_address'] ?></span>
                                                    </p>
                                                    <p style="display: none">
                                                        <?= __('店铺等级') ?>：
                                                        <span class="store-major" title=""><?= @$val['shop_grade'] ?></span>
                                                    </p>
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <div class="store-activity"></div>
                                        <div class="store-sever">
                                            <div class="store-volume">
                                                <!---<span>销量<em>39</em></span>-->
                                                <span><?= __('共有') ?><em>&nbsp;<?= @$val['goods_num'] ?>
                                                        &nbsp;</em><?= __('件商品') ?></span>
                                            </div>
                                            <div class="store-privilege">
                                                <em class="pf"></em>
                                                <div class="popup-shopinfo" style="display: none;">
                                                    <div class="popup-shopinfo-arrow"></div>
                                                    <div class="popup-wrap">
                                                        <div class="ncs-detail-rate">
                                                            <dl>
                                                                <dt><?= __('店铺评分') ?> </dt>
                                                                <dd><?= __('商品满意度') ?>
                                                                    ：<?= @$val['shop_detail']['shop_desc_scores'] ?><?= __('分') ?></dd>
                                                                <dd><?= __('服务满意度') ?>
                                                                    ：<?= @$val['shop_detail']['shop_service_scores'] ?><?= __('分') ?></dd>
                                                                <dd><?= __('物流满意度') ?>
                                                                    ：<?= @$val['shop_detail']['shop_send_scores'] ?><?= __('分') ?></dd>
                                                            </dl>
                                                            <dl>
                                                                <dt><?= __('同类对比') ?></dt>
                                                                <dd>
                                                                    <div class="<?php if (@$val['shop_detail']['com_desc_scores'] >= 0) echo 'high'; else echo 'low'; ?>">
                                                                        <span><i></i><?php if (@$val['shop_detail']['com_desc_scores'] >= 0): ?><?= __('高于') ?><?php else: ?><?= __('低于') ?><?php endif; ?></span> <?= number_format(abs(@$val['shop_detail']['com_desc_scores']), 2, '.', '') ?><?= __('%') ?>
                                                                    </div>
                                                                </dd>
                                                                <dd>
                                                                    <div class="<?php if (@$val['shop_detail']['com_service_scores'] >= 0) echo 'high'; else echo 'low'; ?>">
                                                                        <span><i></i><?php if (@$val['shop_detail']['com_service_scores'] >= 0): ?><?= __('高于') ?><?php else: ?><?= __('低于') ?><?php endif; ?></span> <?= number_format(abs(@$val['shop_detail']['com_service_scores']), 2, '.', '') ?><?= __('%') ?>
                                                                    </div>
                                                                </dd>
                                                                <dd>
                                                                    <div class="<?php if (@$val['shop_detail']['com_send_scores'] >= 0) echo 'high'; else echo 'low'; ?>">
                                                                        <span><i></i><?php if (@$val['shop_detail']['com_send_scores'] >= 0): ?><?= __('高于') ?><?php else: ?><?= __('低于') ?><?php endif; ?></span> <?= number_format(abs(@$val['shop_detail']['com_send_scores']), 2, '.', '') ?><?= __('%') ?>
                                                                    </div>
                                                                </dd>
                                                            </dl>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="fav-store">
                                            <a href="javascript:;" nc_type="storeFavoritesBtn" onclick="collectShop(<?= ($val['shop_id']) ?>)">
                                                <i class="icon fa fa-star-o"></i>
                                                <?= __('收藏店铺') ?>
                                                <em class="m-l-5 shop_<?= ($val['shop_id']) ?>" nc_type="storeFavoritesNum" style="display: none">
                                                    <?= @$val['shop_collect'] ?>
                                                </em>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="store-right">
                                        <div class="warp">
                                            <div class="store-goods-container">
                                                <ul>
                                                    <?php
                                                    if ($val['goods_recommended']['items']) {
                                                        foreach ($val['goods_recommended']['items'] as $k => $goods) {
                                                            ?>
                                                            <li class="store-goods">
                                                                <a class="goods"
                                                                   href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?= @$goods['goods_id'] ?>"
                                                                   title="<?= @$goods['common_name'] ?>"><img
                                                                            src="<?= @$goods['common_image'] ?>"></a>
                                                                <div class="goods-info">
                                                                    <p class="goods-name m-t-5"><a
                                                                                href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?= @$goods['goods_id'] ?>"><?= @$goods['common_name'] ?></a>
                                                                    </p>
                                                                    <p class="goods-price m-t-5">
                                                                        <em><?= @format_money($goods['common_price']) ?></em>
                                                                        <span><?= __('售出') ?><em
                                                                                    class="num-color margin2"><?= @$goods['common_salenum'] ?></em><?= __('件') ?></span>
                                                                    </p>
                                                                </div>
                                                            </li>
                                                        <?php }
                                                    } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php }
                        } else { ?>
                            <div class="no_account">
                                <img src="<?= $this->view->img ?>/ico_none.png"/>
                                <p><?= __('暂无符合条件的数据记录') ?></p>
                            </div>
                        <?php } ?>
                    </ul>
<!--                    <div class="page page_front">-->
<!--                        --><?//= @$page_nav ?>
<!--                    </div>-->
                    <?php if($page_nav){ ?>
                        <div style="clear:both"></div>
                        <div class="page page_front">
                            <?=$page_nav?>
                        </div>
                        <div style="clear:both"></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 登录遮罩层 -->
    <div id="login_content" style="display:none;">
    </div>

    <script>
        $(".checkbox").bind("click", function () {
            var _self = this;
            if (_self.checked) {
                if ($(this).attr('name') == 'plat') {
                    checkbox('plat', '1');
                }
            } else {
                if ($(this).attr('name') == 'plat') {
                    checkbox('plat', '');
                }
            }
        });

        //仅显示有货，仅显示促销商品
        function checkbox(a, e) {
            //地址中的参数
            var params = window.location.search;
            params = changeURLPar(params, a, e);
            window.location.href = SITE_URL + params;
        }

        function changeURLPar(destiny, par, par_value) {
            var pattern = par + '=([^&]*)';
            var replaceText = par + '=' + par_value;
            if (destiny.match(pattern)) {
                var tmp = new RegExp(pattern);
                tmp = destiny.replace(tmp, replaceText);
                return (tmp);
            }
            else {
                if (destiny.match('[\?]')) {
                    return destiny + '&' + replaceText;
                }
                else {
                    return destiny + '?' + replaceText;
                }


            }
            return destiny + '\n' + par + '\n' + par_value;
        }

        //收藏店铺
        window.collectShop = function (e) {
            if ($.cookie('key')) {
                $.post(SITE_URL + '?ctl=Shop&met=addCollectShop&typ=json', {shop_id: e}, function (data) {
                    if (data.status == 200) {
                        Public.tips.success(data.data.msg);
                        a = $('.shop_' + e).html();
                        $('.shop_' + e).html(a * 1 + 1);
                    }
                    else {
                        Public.tips.error(data.data.msg);
                    }
                });
            }
            else {
                $("#login_content").show();
                load_goodseval(SITE_URL + '?ctl=Index&met=fastLogin', 'login_content');
            }
        }
    </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
