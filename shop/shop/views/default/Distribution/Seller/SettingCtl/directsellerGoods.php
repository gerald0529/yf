<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>
    .dialog_close_button {
        font-family: Verdana;
        font-size: 14px;
        line-height: 20px;
        font-weight: 700;
        color: #999;
        text-align: center;
        display: block;
        width: 20px;
        height: 20px;
        position: absolute;
        z-index: 1;
        top: 5px;
        right: 5px;
        cursor: pointer;
    }

    .dialog_close_button:hover {
        text-decoration: none;
        color: #333;
    }

    #dialog_directseller {
        border: 1px solid #ccc;
    }

    #dialog_directseller dl {
        padding: 5px;
    }
</style>
<script type='text/jade' id='thrid_opt'>
	<a class="bbc_seller_btns button add button_blue" href="<?= Yf_Registry::get('index_page') ?>?ctl=Distribution_Seller_Setting&met=addDirectsellerGoods&typ=e"><i class="iconfont icon-jia"></i><?= __('添加分销商品') ?></a>
</script>
<script type="text/javascript">
    $(function () {
        $('.tabmenu').append($('#thrid_opt').html());
    });
</script>
<div class="search fn-clear">
    <form id="search_form" class="search_form_reset" method="get" action="<?= Yf_Registry::get('url') ?>">
        <input class="text w150" type="text" name="common_name" value="<?= request_string('common_name') ?>"
               placeholder="<?= __('请输入商品名称') ?>"/>
        <input type="hidden" name="ctl" value="Distribution_Seller_Setting">
        <input type="hidden" name="met" value="<?= $met ? $met : 'directsellerGoods'; ?>">
        <!---<a class="button ml10" href="<?= Yf_Registry::get('index_page') ?>?ctl=Distribution_Seller_Setting&met=addDirectsellerGoods&typ=e">添加分销商品</a>--->
        <a class="button refresh" onclick="location.reload()"><i class="iconfont icon-huanyipi"></i></a>
        <a class="button btn_search_goods" href="javascript:void(0);"><i
                    class="iconfont icon-btnsearch"></i><?= __('搜索') ?></a>
    </form>
</div>
<script type="text/javascript">
    $(".search").on("click", "a.button", function () {
        $("#search_form").submit();
    });
</script>
<?php if (!empty($data['items'])) { ?>
    <form id="form" method="post" action="">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th class="tl">
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?= __('商品名称') ?>
                </th>
                <th width="80"><?= __('价格') ?></th>
                <th width="80"><?= __('库存') ?></th>
                <th width="100"><?= __('佣金比例') ?></th>
                <th width="120"><?= __('操作') ?></th>
            </tr>
            <?php foreach ($data['items'] as $item) { ?>
                <?php if($item['common_is_directseller']) {?>
                <tr id="tr_common_id_<?= $item['common_id']; ?>">
                    <td class="tl th" colspan="99">
                        <label class="checkbox">
                            <input class="checkitem" type="checkbox" name="chk[]" value="<?= $item['common_id'] ?>">
                        </label>
                        <?= __('平台货号：') ?><?= $item['common_id']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="tl">
                        <dl class="fn-clear fn_dl">
                            <dt>
                                <i date-type="ajax_goods_list" data-id="237" class="iconfont icon-jia disb"></i>
                                <a href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $item['goods_id'] ?>" target="_blank"><img width="60" src="<?= $item['common_image'] ?>"></a>
                            </dt>
                            <dd>
                                <h3>
                                    <a href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $item['goods_id'] ?>" target="_blank"><?= $item['common_name'] ?></a>
                                </h3>
                                <p><?= $item['cat_name'] ?></p>
                                <p><?= ($item['common_code'] ? sprintf("商家货号：%s", $item['common_code']) : '') ?></p>
                            </dd>
                        </dl>
                    </td>
                    <td><?= format_money($item['common_price']); ?></td>
                    <td><?= $item['common_stock'] ?> <?= __('件') ?></td>
                    <td>
                        <p><?= __('一级：') ?><?= $item['common_cps_rate'] ?> %</p>
                        <p><?= __('二级：') ?><?= $item['common_second_cps_rate'] ?> %</p>
                        <p><?= __('三级：') ?><?= $item['common_third_cps_rate'] ?> %</p>
                    </td>
                    <td>
                        <span class="edit">
							<a href="javascript:void(0)" common-id='<?= $item['common_id'] ?>'
                               first_rate='<?= $item['common_cps_rate'] ?>'
                               second_rate='<?= $item['common_second_cps_rate'] ?>'
                               third_rate='<?= $item['common_third_cps_rate'] ?>' id="set_commission"><i
                                        class="iconfont icon-setting"></i><?= __('设置') ?></a></span>
                        <span class="del">
                            <a data-param="{'id':'<?= $item['common_id'] ?>','ctl':'Distribution_Seller_Setting','met':'delGoods'}"
                               href="javascript:void(0)">
                                <i class="iconfont icon-lajitong"></i>
                                <?= __('删除') ?>
                            </a>
                        </span>
                    </td>
                </tr>
                <tr class="tr-goods-list" style="display: none;">
                    <td colspan="5" class="tl">
                        <ul class="fn-clear">
                            <?php if (!empty($goods_detail_rows[$item['common_id']])):
                                foreach ($goods_detail_rows[$item['common_id']] as $g_k => $g_v):
                                    ?>
                                    <li>
                                        <div class="goods-image">
                                            <a herf="" target="_blank">
                                                <img width="100" src="<?= $g_v['goods_image']; ?>">
                                            </a>
                                        </div>
                                        <?php if (!empty($g_v['spec'])) {
                                            foreach ($g_v['spec'] as $ks => $vs):?>
                                                <div class="goods_spec"><?= $ks; ?>：<span><?= $vs ?></span></div>
                                            <?php endforeach;
                                        } ?>
                                        <div class="goods-price">
                                            <?= __('价格：') ?><span><?= format_money($g_v['goods_price']); ?></span></div>
                                        <div class="goods-stock"><?= __('库存：') ?>
                                            <span><?= $g_v['goods_stock'] ?> <?= __('件') ?></span></div>
                                        <a href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $g_v['goods_id'] ?>"
                                           target="_blank"><?= __('查看商品详情') ?></a>
                                    </li>
                                <?php
                                endforeach;
                            endif;
                            ?>

                        </ul>
                    </td>
                </tr>
                <?php } ?>
            <?php } ?>
            <tr>
                <td class="toolBar" colspan="1">
                    <input type="hidden" name="act" value="del"/>
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?= __('全选') ?>
                    <span>|</span>
                    <label class="del" data-param="{'ctl':'Distribution_Seller_Setting','met':'delGoods'}">
                        <i class="iconfont icon-lajitong"></i><?= __('删除') ?>
                    </label>
                    <span>|</span>
                    <label class="down"><i class="iconfont icon-xiajia"></i><?= __('下架') ?></label>
                </td>
                <td colspan="99">
                    <div class="page">
                        <?= $page_nav ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
<?php } else { ?>
    <form id="form" method="post" action="">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th class="tl">
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?= __('商品名称') ?>
                </th>
                <th width="80"><?= __('价格') ?></th>
                <th width="80"><?= __('库存') ?></th>
                <th width="80"><?= __('佣金比例') ?></th>
                <th width="120"><?= __('操作') ?></th>
            </tr>
        </table>
    </form>
    <div class="no_account">
        <img src="<?= $this->view->img ?>/ico_none.png">
        <p><?= __('暂无符合条件的数据记录'); ?></p>
    </div>
<?php } ?>

<!--设置分佣比例-->
<div id="dialog_directseller" class="eject_con" style="display:none;">
    <input id="common_id" type="hidden" value=""/>
    <dl style="margin-top:15px;">
        <dt><?= __('一级分佣比例') ?>：</dt>
        <dd><input id="common_cps_rate" name='common_cps_rate' type="text" class="text w70"> %</dd>
    </dl>
    <dl>
        <dt><?= __('二级分佣比例') ?>：</dt>
        <dd><input id="common_second_cps_rate" name='common_second_cps_rate' type="text" class="text w70"> %</dd>
    </dl>
    <dl>
        <dt><?= __('三级分佣比例') ?>：</dt>
        <dd><input id="common_third_cps_rate" name='common_third_cps_rate' type="text" class="text w70"> %</dd>
    </dl>
    <div class="eject_con mb10">
        <div class="bottom"><a id="btn_directseller_shop_name" class="button bbc_seller_submit_btns"
                               href="javascript:void(0);"><?= __('提交') ?></a></div>
    </div>
</div>
<script>
    //设置分佣比例
    $('.table-list-style').on('click', '#set_commission', function () {
        var common_id = $(this).attr('common-id');
        $('#common_id').val(common_id);

        var first_rate = $(this).attr('first_rate');
        $('#common_cps_rate').val(first_rate);
        var second_rate = $(this).attr('second_rate');
        $('#common_second_cps_rate').val(second_rate);
        var third_rate = $(this).attr('third_rate');
        $('#common_third_cps_rate').val(third_rate);

        $('#dialog_directseller').yf_show_dialog({width: 450, title: "<?=__('设置分佣比例')?>"});
    });

    //提交小店名称
    $('#btn_directseller_shop_name').on('click', function () {
        var common_id = $('#common_id').val();
        var common_cps_rate = $('#common_cps_rate').val();
        var common_second_cps_rate = $('#common_second_cps_rate').val();
        var common_third_cps_rate = $('#common_third_cps_rate').val();
        if (common_cps_rate != '') {
            $.post(SITE_URL + '?ctl=Distribution_Seller_Setting&met=editDirectsellerGoods&typ=json',
                {
                    common_id: common_id,
                    common_cps_rate: common_cps_rate,
                    common_second_cps_rate: common_second_cps_rate,
                    common_third_cps_rate: common_third_cps_rate
                },
                function (d) {
                    if (d.status == 200) {
                        var data = d.data;
                        Public.tips.success("<?=__('修改成功!')?>");
                        location.href = SITE_URL + '?ctl=Distribution_Seller_Setting&met=directsellerGoods';
                    } else {
                        Public.tips.error("<?=__('操作失败！')?>");
                        location.href = SITE_URL + '?ctl=Distribution_Seller_Setting&met=directsellerGoods';
                    }
                }, 'json'
            );
        } else {
            $('#dialog_directseller_shop_name_error').show();
        }
    });
</script>

<script type="text/javascript">
    $('label.down').click(function () {
        var length = $('.checkitem:checked').length;
        if (length > 0) {
            var chk_value = [];//定义一个数组
            $("input[name='chk[]']:checked").each(function () {
                chk_value.push($(this).val());//将选中的值添加到数组chk_value中
            });
            $.dialog.confirm("<?=__('您确定要下架吗?')?>", function () {
                $.post(SITE_URL + '?ctl=Seller_Goods&met=editGoodsCommon&typ=json&act=down', {chk: chk_value}, function (data) {
                    if (data && 200 == data.status) {
                        //$.dialog.alert('删除成功',function(){location.reload();});
                        Public.tips({type: 3, content: "<?=__('下架成功！')?>"});
                        location.reload();
                    }
                    else {
                        //$.dialog.alert('删除失败');
                        Public.tips({type: 1, content: "<?=__('下架失败！')?>"});
                    }
                });
            });
        }
        else {
            $.dialog.alert("<?=__('请选择需要操作的记录')?>");
        }
    });
</script>
<script type="text/javascript">
    var offt = true;
    $(document).ready(function () {
        $(".table-list-style .disb").click(function () {
            if (offt) {
                $(this).parent().parent().parent().parent().next().css("display", "table-row");
                $(this).removeClass("icon-jia");
                $(this).addClass("icon-jian");
                offt = false;
            }
            else {
                $(this).parent().parent().parent().parent().next().css("display", "none");
                $(this).removeClass("icon-jian");
                $(this).addClass("icon-jia");
                offt = true;
            }

        })
    })
</script>
<script type="text/javascript">
    $('.dropdown').hover(function () {
        $(this).addClass("hover");
    }, function () {
        $(this).removeClass("hover");
    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>
