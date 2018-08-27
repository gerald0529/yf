<?php
include $this->view->getTplPath() . '/' . 'supplier_join_header.php';
?>
<script type="text/javascript" src="<?= $this->view->js ?>/Touch.js"></script>
<div class="banner">
    <div class="user-box">
        <div class="user-joinin">
            <h3><?= __('亲爱的：') ?><?= $this->user_info['user_name'] ?></h3>
            <dl>
                <dt><?= __('欢迎来') ?><?= Web_ConfigModel::value("site_name") ?></dt>
                <dd> <?= __('若您还没有在支付中心填写实名认证资料') ?> <br>
                    <?= __('请点击') ?>“ <a href="index.php?ctl=Seller_Shop_Settled&met=certification"
                                         target="_blank"> <?= __('实名认证') ?></a><?= __('”进行实名资料填写') ?> </dd>
                <dd> <?= __('若您还没有填写入驻申请资料') ?> <br>
                    <?= __('请点击') ?>“ <a onclick="getStep()"
                                         target="_blank"> <?= __('我要入驻') ?></a><?= __('”进行入驻资料填写') ?> </dd>
                <dd>  <?= __('若您的店铺还未开通') ?> <br>
                    <?= __('请通过“') ?> <a onclick="getStep()"
                                         target="_blank"> <?= __('查看入驻进度') ?></a> <?= __('”了解店铺开通的最新状况') ?> </dd>
            </dl>
            <div class="bottom">
                <?php if (($shop_info && $shop_info['shop_status'] == 3) && $shop_info['shop_type'] == 1) { ?>
                    <a href="javascript:;" class="enter_shop"> <?= __('我要入驻') ?></a>
                <?php } else { ?>
                    <a href="index.php?ctl=Seller_Supplier_Settled&met=index&op=<?php if (Web_ConfigModel::value('supplier_type') == 3) {
                        echo 'step0';
                    } else {
                        echo 'step1';
                    } ?>" target="_blank"> <?= __('我要入驻') ?></a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="swiper-container">
        <ul class="clearfix swiper-wrapper">
            <?php if (Web_ConfigModel::value('supplier_slider_image1')) { ?>
                <a href="<?= Web_ConfigModel::value('supplier_slider_link1') ?>" class="swiper-slide">
                    <li>
                        <img src="<?= Web_ConfigModel::value('supplier_slider_image1') ?>">
                    </li>
                </a>
            <?php } ?>
            <?php if (Web_ConfigModel::value('supplier_slider_image2')) { ?>
                <a href="<?= Web_ConfigModel::value('supplier_slider_link2') ?>" class="swiper-slide">
                    <li>
                        <img src="<?= Web_ConfigModel::value('supplier_slider_image2') ?>">
                    </li>
                </a>
            <?php } ?>
        </ul>

        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <script type="text/javascript">
            $(document).ready(function () {
                var swiper = new Swiper('.swiper-container', {
                    pagination: '.swiper-pagination',
                    paginationClickable: true,
                    autoplayDisableOnInteraction: false,
                    autoplay: 3000,
                    speed: 300,
                    grabCursor: true,
                    paginationClickable: true,
                    lazyLoading: true,
                });
            });

            $('.enter_shop').click(function () {
//                Public.tips.error('您已是入驻商家，不能再入驻供应商了!');
//                return false;
                $.dialog({
                    title: '提示',
                    content: '您已是入驻商家，不能再入驻供应商了!',
                    height: 100,
                    width: 410,
                    lock: true,
                    drag: false,
                    ok: function () {
//                        location.href= SITE_URL +"?ctl=User&met=security";
                    }
                })
            })
        </script>
    </div>
</div>
<div class="indextip">
    <div class="container">
        <span class="title"> <i class="iconfont icon-laba"></i><h3> <?= __('贴心提示') ?></h3></span>
        <span class="content"> <?= Web_ConfigModel::value('supplier_slider_tip') ?></span>
    </div>
</div>

<div class="main mt30">
    <h2 class="index-title"> <?= __('入驻流程') ?></h2>
    <div class="joinin-index-step">
        <span class="step"> <i class="iconfont icon-shangjiaruzhushenqing"></i> <?= __('签署入驻协议') ?> </span>
        <span class="arrow"></span>
        <span class="step"> <i class="iconfont icon-xinxitijiao"></i> <?= __('商家信息提交') ?> </span>
        <span class="arrow"></span>
        <span class="step"> <i class="iconfont icon-pingtaishenhe"></i> <?= __('平台审核资质') ?> </span>
        <span class="arrow"></span>
        <span class="step"> <i class="iconfont icon-jiaonafeiyong"></i> <?= __('商家缴纳费用') ?> </span>
        <span class="arrow"></span>
        <span class="step"> <i class="iconfont icon-dianpu2"></i> <?= __('店铺开通') ?> </span>
    </div>
    <h2 class="index-title"> <?= __('入驻指南') ?></h2>
    <div class="joinin-info">
        <ul class="tabs-nav">
            <?php
            foreach ($shop_help as $key => $value) {
                ?>
                <li class="<?php if ($key == 1) {
                    echo "tabs-selected";
                } ?>">
                    <h3><?= $value['help_title'] ?></h3>
                </li>
            <?php } ?>
        </ul>
        <?php
        foreach ($shop_help as $key => $value) {
            ?>
            <div class="tabs-panel <?php if ($key != 1) { ?>tabs-hide<?php } ?>">
                <?= $value['help_info'] ?>
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    function getStep() {
        var url = "<?= Yf_Registry::get('base_url')?>/index.php?ctl=<?=$ctl?>&met=getstepurl&typ=json";
        $.post(url, {}, function (result) {
            if (result.status == 200) {
                window.location.href = result.data.url;
            }
        }, 'json');
    }


</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
</body>
</html>
