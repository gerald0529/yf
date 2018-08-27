<?php if (!defined('ROOT_PATH')) exit('No Permission'); ?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
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

<form method="post" id="form">
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tbody>

        <tr>
            <th class="w30"></th>
            <th class="w180 tl"><?=__('模板名称')?></th>
            <th class="w180 tl"><?=__('物流公司')?></th>
            <th class="tl"><?=__('运单图例')?></th>
            <th class="w100 tl"><?=__('模板类型')?></th>
            <th class="w150"><?=__('操作')?></th>
        </tr>
        <?php if ( !empty($waybill_data) ){ ?>
        <?php foreach ( $waybill_data as $key => $val ){ ?>
        <tr class="bd-line">
            <td></td>
            <td class="tl"><?php echo $val['waybill_tpl_name']; ?></td>
            <td class="tl"><?php echo $val['express_name']; ?></td>
            <td class="tl">
                <div class="waybill-img-thumb">
                    <a class="nyroModal" rel="gal" href="<?php echo $val['waybill_tpl_image']; ?>" >
                        <img src="<?php echo $val['waybill_tpl_image']; ?>" >
                    </a></div>
                <div class="waybill-img-size">
                    <p><?=__('宽度')?>：<?php echo $val['waybill_tpl_width']; ?>(mm)</p>
                    <p><?=__('高度')?>：<?php echo $val['waybill_tpl_height']; ?>(mm)</p>
            </div></td>
            <td class="tl"><?=__('用户模板')?></td>
            <td class="nscs-table-handle">
                <span>
                    <a href="<?php echo Yf_Registry::get('url') . '?ctl=Seller_Trade_Waybill&met=testTpl&typ=e&waybill_tpl_id=' . $val['waybill_tpl_id']; ?>" class="btn-aqua" target="_blank">
                        <i class="iconfont icon-icontianping"></i><p><?=__('测试')?></p>
                    </a>
                </span>
                <span>
                    <a href="javascript:;" nctype="btn_bind" class="btn-mint" data-waybill_tpl_id="<?php echo $val['waybill_tpl_id']; ?>">
                        <i class="iconfont icon-banshou"></i><p><?=__('绑定')?></p>
                    </a>
                </span>
            </td>
        </tr>
        <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="20" class="norecord">
                    <!-- <div class="warning-option">
                        <i class="icon-warning-sign"></i><span style="color: #27A9E3;">还没有可用的运单模板，<a href="<?php echo Yf_Registry::get('url') . '?ctl=Seller_Trade_Waybill&met=waybillIndex&typ=e'; ?>">去建立模板</a></span>
                    </div> -->
                    <div class="no_account">
                        <img src="<?=$this->view->img?>/ico_none.png">
                        <p><?=__('暂无符合条件的数据记录')?></p>
                    </div>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</form>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
<script>

    $('.tabmenu').children().children('li:gt(2)').hide();
    
    $(function () {

        var user_express_id = <?php if ( !empty($user_express_id) ) { echo $user_express_id; } else { echo 0; }; ?>;

        $('a[nctype="btn_bind"]').on('click', function () {

            var waybill_tpl_id = $(this).data('waybill_tpl_id');
            $.post(SITE_URL + '?ctl=Seller_Trade_Waybill&met=waybillBind&typ=json', {waybill_tpl_id: waybill_tpl_id, user_express_id: user_express_id}, function (data) {
                if ( data.status == 200 ) {
                    Public.tips( { content: data.msg, type: 3 } );
                    setTimeout(function(){
                        window.location.href = SITE_URL + "?ctl=Seller_Trade_Waybill&met=waybillManage&typ=e";
                    }, 1000);
                } else {
                    Public.tips( { content: data.msg, type: 1 } );
                }
            })
        })
    })
</script>