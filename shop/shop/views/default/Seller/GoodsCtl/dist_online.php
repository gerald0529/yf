<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<script type="text/javascript">
    $(function ()
    {
        $('.tabmenu').append($('#thrid_opt').html());
    });
</script>
<div class="search fn-clear">
    <form id="search_form" class="search_form_reset" method="get" action="<?= Yf_Registry::get('url') ?>">
        <input class="text w150" type="text" name="goods_key" value="<?=($goods_key?$goods_key:'');?>" placeholder="<?=__('请输入商品名称')?>"/>
        <input type="hidden" name="ctl" value="Seller_Goods">
        <input type="hidden" name="met" value="<?= $met ? $met : 'dist_online'; ?>">
        <a class="button refresh" href="index.php?ctl=Seller_Goods&met=<?= $met ? $met : 'dist_online'; ?>&typ=e"><i
                class="iconfont icon-huanyipi"></i></a>
        <a class="button btn_search_goods" href="javascript:void(0);"><i
                class="iconfont icon-btnsearch"></i><?= __('搜索') ?></a>
    </form>
</div>
<script type="text/javascript">
    $(".search").on("click", "a.button", function ()
    {
        $("#search_form").submit();
    });
</script>
<?php $ctl ='Goods_Goods' ?>
<?php
if (!empty($goods)){?>
    <form id="form" method="post" action="index.php?ctl=Seller_Goods&met=editGoodsCommon&typ=json">
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th class="tl">
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?=__('商品名称')?>
                </th>
                <th width="80"><?=__('价格')?></th>
                <th width="80"><?=__('库存')?></th>
                <th width="80"><?=__('发布时间')?></th>
                <th width="120"><?=__('操作')?></th>
            </tr>
            <?php
            foreach ($goods as $item){
                ?>
                <tr id="tr_common_id_<?= $item['common_id']; ?>">
                    <td class="tl th" colspan="99">
                        <label class="checkbox"><input class="checkitem" type="checkbox" name="chk[]"
                                                       value="<?= $item['common_id'] ?>"></label><?=__('平台货号')?>：<?= $item['common_id']; ?>
                        <!--<a class="fr" target="_blank"
                           href="<?= Yf_Registry::get('index_page') ?>?ctl=Seller_Goods&met=stat&typ=e&op=flow&id=<?= $item['goods_id'] ?>"><?= __('流量</') ?> </a>
                        <a class="fr mr10" target="_blank"
                           href="<?= Yf_Registry::get('index_page') ?>?ctl=Seller_Goods&met=stat&typ=e&op=sales&id=<?= $item['goods_id'] ?>"><?= __('销量') ?></a>-->
                    </td>
                </tr>
                <tr>
                    <td class="tl">
                        <dl class="fn-clear fn_dl">
                            <dt>
                                <i date-type="ajax_goods_list" data-id="237" class="iconfont icon-jia disb"></i>
                                <a href="index.php?ctl=<?=$ctl?>&met=goods&gid=<?= $item['goods_id'] ?>"
                                   target="_blank"><img width="60" src="<?= $item['common_image'] ?>"></a>
                            </dt>
                            <dd>
                                <h3 style="width: 310px;"><a href="index.php?ctl=<?=$ctl?>&met=goods&gid=<?= $item['goods_id'] ?>"
                                       target="_blank"><?= $item['common_name'] ?></a></h3>

                                <p><?= $item['cat_name'] ?></p>

                                <p><?= ($item['common_code'] ? sprintf(__('商家货号').'：%s', $item['common_code']) : '') ?></p>
                            </dd>
                        </dl>
                    </td>
                    <td><?= format_money($item['common_price']); ?></td>
                    <td><?= $item['common_stock'] ?> <?=__('件')?></td>
                    <td><?php $item['common_sell_time']!=='0000-00-00 00:00:00' ?  print($item['common_sell_time']) : print($item['common_add_time']); ?></td>
                    <td>
                        <span class="edit"><a
                                href="<?php echo Yf_Registry::get('url'); ?>?ctl=Seller_Goods&met=online&typ=e&common_id=<?= $item['common_id'] ?>&action=edit_goods"><i
                                    class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
                        <span class="del"><a
                                data-param="{'id':'<?= $item['common_id'] ?>','ctl':'Seller_Goods','met':'deleteGoodsCommon'}"
                                href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
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
                                            <a herf="" target="_blank"><img width="100"
                                                                            src="<?= $g_v['goods_image']; ?>"></a>
                                        </div>
                                        <?php if (!empty($g_v['spec']))
                                        {
                                            foreach ($g_v['spec'] as $ks => $vs):?>
                                                <div class="goods_spec"><?= $ks; ?>：<span><?= $vs ?></span></div>
                                            <?php endforeach;
                                        } ?>
                                        <div class="goods-price">
                                            <?=__('价格')?>：<span><?= format_money($g_v['goods_price']); ?></span></div>
                                        <div class="goods-stock"><?=__('库存')?>：<span><?= $g_v['goods_stock'] ?> <?=__('件')?></span></div>
                                        <a href="index.php?ctl=<?=$ctl?>&met=goods&gid=<?= $g_v['goods_id'] ?>"
                                           target="_blank"><?=__('查看商品详情')?></a>
                                    </li>
                                <?php
                                endforeach;
                            endif;
                            ?>

                        </ul>
                    </td>
                </tr>

            <?php } ?>
            <tr>
                <td class="toolBar" colspan="1">
                    <input type="hidden" name="act" value="del"/>
                    <label class="checkbox"><input class="checkall" type="checkbox"/></label><?=__('全选')?>
                    <span>|</span>
                    <!--<label class="del"><i class="iconfont icon-trash"></i>删除</label>-->
                    <label class="del" data-param="{'ctl':'Seller_Goods','met':'deleteGoodsCommonRows'}"><i
                            class="iconfont icon-lajitong"></i><?=__('删除')?></label>
                    
                    <?php if ($met == 'dist_online')
                    { ?>
						<span>|</span>
                        <label class="down"><i class="iconfont icon-xiajia"></i><?= __('下架') ?></label>
                    <?php }
                    elseif($met != 'verify')
                    { ?>
						<span>|</span>
                        <label class="up"><i class="iconfont icon-shangjia1"></i><?= __('上架') ?></label>
                    <?php } ?>
                </td>
                <td colspan="99">
                    <div class="page">
                        <?= $page_nav ?>
                    </div>
                </td>
            </tr>
        </table>
    </form>
<?php }else{ ?>
<form id="form" method="post" action="index.php?ctl=Seller_Goods&met=editGoodsCommon&typ=json">
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th class="tl">
                <label class="checkbox"><input class="checkall" type="checkbox"/></label><?=__('商品名称')?>
            </th>
            <th width="80"><?=__('价格')?></th>
            <th width="80"><?=__('库存')?></th>
            <th width="80"><?=__('发布时间')?></th>
            <th width="120"><?=__('操作')?></th>
        </tr>
    </table>
</form>
    <div class="no_account">
        <img src="<?=$this->view->img?>/ico_none.png">
        <p><?= __('暂无符合条件的数据记录'); ?></p>
    </div>
<?php } ?>


<script type="text/javascript">
    $('label.down').click(function ()
    {
        var length = $('.checkitem:checked').length;
        if (length > 0)
        {
            var chk_value = [];//定义一个数组
            $("input[name='chk[]']:checked").each(function ()
            {
                chk_value.push($(this).val());//将选中的值添加到数组chk_value中
            });
            $.dialog.confirm('<?=__('您确定要下架吗')?>?', function ()
            {
                $.post(SITE_URL + '?ctl=Seller_Goods&met=editGoodsCommon&typ=json&act=down', {chk: chk_value}, function (data)
                {
                    if (data && 200 == data.status)
                    {
                        //$.dialog.alert('删除成功',function(){location.reload();});
                        Public.tips({type: 3, content: "<?=__('下架成功')?>！"});
                        location.reload();
                    }
                    else
                    {
                        //$.dialog.alert('删除失败');
                        Public.tips({type: 1, content: "<?=__('下架失败')?>！"});
                    }
                });
            });
        }
        else
        {
            $.dialog.alert('<?=__('请选择需要操作的记录')?>');
        }
    });
    $('label.up').click(function ()
    {
		var me = '<?php echo $met?>';
        var length = $('.checkitem:checked').length;
        if (length > 0)
        {
            var chk_value = [];//定义一个数组
            $("input[name='chk[]']:checked").each(function ()
            {
                chk_value.push($(this).val());//将选中的值添加到数组chk_value中
            });
            $.dialog.confirm('<?=__('您确定要上架吗')?>?', function ()
            {
                $.post(SITE_URL + '?ctl=Seller_Goods&met=editGoodsCommon&typ=json&act=up&me='+me, {chk: chk_value}, function (data)
                {
                    if (data && 200 == data.status)
                    {
                        //$.dialog.alert('删除成功',function(){location.reload();});
                        Public.tips({type: 3, content: "<?=__('上架成功')?>！"});
                        location.reload();
                    }
                    else
                    {
                        //$.dialog.alert('删除失败');
                        Public.tips({type: 1, content: "<?=__('上架失败')?>！"});
                    }
                });
            });
        }
        else
        {
            $.dialog.alert('<?=__('请选择需要操作的记录')?>');
        }
    });
</script>
<script type="text/javascript">
    var offt = true;
    $(document).ready(function ()
    {
        $(".table-list-style .disb").click(function ()
        {
            if (offt)
            {
                $(this).parent().parent().parent().parent().next().css("display", "table-row");
                $(this).removeClass("icon-jia");
                $(this).addClass("icon-jian");
                offt = false;
            }
            else
            {
                $(this).parent().parent().parent().parent().next().css("display", "none");
                $(this).removeClass("icon-jian");
                $(this).addClass("icon-jia");
                offt = true;
            }

        })
    })
</script>
<script type="text/javascript">
    $('.dropdown').hover(function ()
    {
        $(this).addClass("hover");
    }, function ()
    {
        $(this).removeClass("hover");
    });
</script>

<script type="text/javascript">

    $(function (){
        $('#import_goods').on('click', function (){
            $.dialog({
                width: 560,
                height: 300,
                title: "<?=__('批量导入')?>",
                content: "url:" + SITE_URL + '?ctl=Seller_Goods&met=importGoods&typ=e',
                lock: !0
            })
        })
    })

</script>


<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>