<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<style>
    .w30 {
        width: 30px;
    }

    .w180 {
        width: 180px;
    }

    .w100 {
        width: 100px !important;
    }

    .w150 {
        width: 150px !important;
    }

    .nscs-table-handle span a {
        color: #777;
        background-color: #FFF;
        display: block;
        padding: 3px 7px;
        margin: 1px;
    }

    .waybill-img-thumb {
        background-color: #fff;
        border: 1px solid #e6e6e6;
        display: inline-block;
        height: 45px;
        padding: 1px;
        vertical-align: top;
        width: 70px;
    }

    .waybill-img-thumb a {
        display: table-cell;
        height: 45px;
        line-height: 0;
        overflow: hidden;
        text-align: center;
        vertical-align: middle;
        width: 70px;
    }

    .waybill-img-thumb a img {
        max-height: 45px;
        max-width: 70px;
    }

    .waybill-img-size {
        color: #777;
        display: inline-block;
        line-height: 20px;
        margin-left: 10px;
        vertical-align: top;
    }

    .table-list-style th {
        padding: 8px 0;
    }

    .table-list-style tbody td {
        color: #999;
        background-color: #FFF;
        text-align: center;
        padding: 10px 0;
    }
</style>
<div class="deliverSetting">
    <div class="alert">
        <h4><?=__('操作提示')?>：</h4>
        <ul>
            <li>1、<?=__('未绑定的物流公司后边会出现“选择模板”按钮，在选择模板页面可以绑定可用的打印模板')?>。</li>
            <li>2、<?=__('点击“设置”按钮可以设置自定义的内容，包括偏移量和需要显示的项目')?>。</li>
            <li>3、<?=__('点击“默认”按钮可以设置当前模板为默认打印模板')?>。</li>
            <li>4、<?=__('点击“解绑”按钮可以解除当前绑定，重新选择其它模板')?>。</li>
        </ul>
    </div>
    <form method="post" id="form">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
            <tbody>

            <tr>
                <th class="w30"></th>
                <th class="w180 tl"><?=__('物流公司')?></th>
                <th class="w180 tl"><?=__('运单模板')?></th>
                <th class="tl"><?=__('运单图例')?></th>
                <th class="w100 tl"><?=__('默认')?></th>
                <th class="w150"><?=__('操作')?></th>
            </tr>
            <?php if ( !empty($default_shop_express) ) { ?>
            <?php foreach ( $default_shop_express as $key => $val ) { ?>
            <tr class="bd-line">
                <td></td>
                <td class="tl"><?php echo $val['express_name']; ?></td>
                <?php if ( empty($val['way_bill']) ) { ?>
                <td class="tl"><?=__('未绑定')?></td>
                <td class="tl">
                </td>
                <td class="tl"></td>
                <td class="nscs-table-handle">
                    <span data-user_express_id = "<?php echo $val['user_express_id']; ?>" data-action="choose_tpl">
                        <a href="<?php echo Yf_Registry::get('url') . '?ctl=Seller_Trade_Waybill&met=waybillBind&typ=e&user_express_id=' .  $val['user_express_id'] . '&express_id=' . $val['express_id']; ?>"class="btn-bluejeans">
                            <i class="iconfont icon-btnsearch"></i><p><?=__('选择模板')?></p></a></span>
                    <span>
                    <!--<span>
                        <a href="javascript:void(0)" class="btn-bluejeans">
                        <i class="iconfont icon-btnsetting"></i><p>设置</p></a>
                    </span>
                    <span>
                        <a href="javascript:void(0)" class="btn-mint">
                        <i class="iconfont icon-icoselectturn"></i><p>默认</p></a>
                    </span>
                    <span data-action="">
                        <a href="javascript:void(0)" class="btn-grapefruit">
                        <i class="iconfont icon-lajitong"></i><p>解绑</p></a>
                    </span>-->
                </td>
                <?php } else { ?>
                <td class="tl"><?php echo $val['way_bill']['waybill_tpl_name']; ?></td>
                    <td class="tl">
                    <div class="waybill-img-thumb">
                    <a class="nyroModal" rel="gal" href="<?php echo $val['way_bill']['waybill_tpl_image']; ?>">
                <img src="<?php echo $val['way_bill']['waybill_tpl_image']; ?>"></a></div>
                <div class="waybill-img-size">
                    <p><?=__('宽度')?>：<?php echo $val['way_bill']['waybill_tpl_width']; ?>(mm)</p>
                    <p><?=__('高度')?>：<?php echo $val['way_bill']['waybill_tpl_height']; ?>(mm)</p>
                </div>
                </td>
                <td class="tl"><?php echo $val['user_is_default'] == 1 ? "<?=__('是')?>" : "<?=__('否')?>"; ?></td>
                <td class="nscs-table-handle" data-waybill_tpl_id = <?php echo $val['way_bill']['waybill_tpl_id']; ?> data-user_express_id = "<?php echo $val['user_express_id']; ?>" >
                <span data-action="set_tpl" >
                    <a href="<?php echo Yf_Registry::get('url') . '?ctl=Seller_Trade_Waybill&met=waybillSetting&typ=e&waybill_tpl_id=' .  $val['way_bill']['waybill_tpl_id'] . '&user_express_id=' . $val['user_express_id']; ?>"class="btn-bluejeans">
                        <i class="iconfont icon-btnsetting"></i><p><?=__('设置')?></p></a></span>
                <span data-action="set_default">
                    <a href="javascript:void(0)" class="btn-mint">
                        <i class="iconfont icon-icoselectturn"></i><p><?=__('默认')?></p></a></span>
                <span data-action="unbind_tpl">
                    <a href="javascript:void(0)" class="btn-grapefruit">
                        <i class="iconfont icon-lajitong"></i><p><?=__('我的订单')?>解绑</p></a></span>
                    </td>
                <?php } ?>
            </tr>
            <?php } ?>
            <?php } ?>
            </tbody>
        </table>
        <?php if ( empty($default_shop_express) ) { ?>
            <div class="no_account">
                <img src="<?= $this->view->img ?>/ico_none.png">
                <p><?= __('暂无符合条件的数据记录') ?></p>
            </div>
        <?php } ?>
        <div class="flip page page_front clearfix">
            <?=$page_nav?>
        </div>
    </form>
</div>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>
    $('.tabmenu').children().children('li:gt(1)').hide();
    $(function () {

        function initEvent () {

            $('span[data-action]').on('click', function () {
                var user_express_id , tpl_id , param = {} ,
                    action = $(this).data('action') ,
                    url = SITE_URL + '?ctl=Seller_Trade_Waybill&met=operateByManage&typ=json&action=' ;

                if (action == 'set_default' || action == 'unbind_tpl') {

                    param = {
                        user_express_id: $(this).parent().data('user_express_id'),
                        waybill_tpl_id: $(this).parent().data('waybill_tpl_id')
                    };

                    url += action;

                    $.post(url, param, function (data) {
                        if ( data.status == 200 ) {
                            Public.tips({ content: data.msg, type: 3 });
                            setTimeout('window.location.reload()', 1000);
                        } else {
                            Public.tips({ content: data.msg, type: 1 });
                        }
                    })
                }
            })
        }

        initEvent();
    })
</script>