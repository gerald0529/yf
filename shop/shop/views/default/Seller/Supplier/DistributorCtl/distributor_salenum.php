<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<!--<script type="text/javascript">
    $(function ()
    {
        $('.tabmenu').append($('#thrid_opt').html());
    });
</script>-->
<div class="search fn-clear">
    <form id="search_form" class="search_form_reset" method="get" action="<?= Yf_Registry::get('url') ?>">
    	<input class="text w150" type="text" name="shop_name" value="" placeholder="<?=__('请输入分销商名称')?>"/>
        <input class="text w150" type="text" name="goods_key" value="" placeholder="<?=__('请输入商品名称')?>"/>
        <input type="hidden" name="ctl" value="Seller_Supplier_Distributor">
        <input type="hidden" name="met" value="distributor_salenum">
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
<?php
if (!empty($data['items'])){
    ?>
        <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <th class="tl"><?=__('商品')?></th>
                <th width="120"><?=__('分销店铺')?></th>
                <th width="80"><?=__('价格')?></th>
                <th width="80"><?=__('销量')?></th>
                <th width="80"><?=__('发布时间')?></th>
            </tr>
            <?php
            foreach ($data['items'] as $item){
                ?>
                <tr id="tr_common_id_<?= $item['common_id']; ?>">
                    <td class="tl th" colspan="99">
                        <?=__('平台货号：')?><?= $item['common_id']; ?>
                    </td>
                </tr>
                <tr>
                    <td class="tl">
                        <dl class="fn-clear fn_dl">
                            <dt>
                                <i date-type="ajax_goods_list" data-id="237" class="iconfont icon-jia disb"></i>
                                <a href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $item['goods_id'] ?>"  target="_blank"><img width="60" src="<?= $item['common_image'] ?>"></a>
                            </dt>
                            <dd>
                                <h3><a href="index.php?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $item['goods_id'] ?>" target="_blank"><?= $item['common_name'] ?></a></h3>
                                <p><?= $item['cat_name'] ?></p>
                                <p><?= ($item['common_code'] ? sprintf('__("商家货号")：%s', $item['common_code']) : '') ?></p>
                            </dd>
                        </dl>
                    </td>
                    <td><?= $item['shop_name']?></td>
                    <td><?= format_money($item['common_price']); ?></td>
                    <td><?= $item['common_salenum'] ?> <?=__('件')?></td>
                    <td><?php print($item['common_add_time']); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="99">
                    <div class="page">
                        <?= $page_nav ?>
                    </div>
                </td>
            </tr>
        </table>
<?php }else{ ?>
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th class="tl"><?=__('商品')?></th>
            <th width="80"><?=__('价格')?></th>
            <th width="80"><?=__('销量')?></th>
            <th width="100"><?=__('发布时间')?></th>
            <th width="80"></th>
        </tr>
    </table>
    <div class="no_account">
        <img src="<?=$this->view->img?>/ico_none.png">
        <p><?= __('暂无符合条件的数据记录'); ?></p>
    </div>
<?php } ?>
	
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>