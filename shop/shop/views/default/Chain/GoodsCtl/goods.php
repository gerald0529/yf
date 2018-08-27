<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'chain_header.php';
?>
<style>
    .nscs-table-handle a.disabled {
        background: #999999;
    }
</style>
<div class="wrapper fn-clear">
    <div class="content">
        <div class="alert mt15 mb5"><strong><?=__('操作提示：')?></strong>
            <ul>
                <li><?=__('1、根据线上在售商品列表内容设置门店库存量；门店库存默认值为“0”时，该商品详情页面“门店自提”选项将不会出现您的门店信息，只有当您按所在门店的实际库存情况与线上商品对照设置库存后，才可作为线上销售门店自取点候选。')?></li>
                <li><?=__('2、选择“库存设置”按钮，如该商品具有多项规格值，请根据规格值内容进行逐一“门店库存”设置，并保存提交。')?></li>
                <li><?=__('3、如您的门店某商品线下销售引起库存不足，请及时手动调整该商品的库存量，以免消费者在线上下单后到门店自提时产生交易纠纷。')?></li>
                <li><?=__('4、特殊商品不能设置为门店自提商品（如：虚拟商品）。')?></li>
            </ul>
        </div>
    </div>
    <div class="search fn-clear">
        <form id="search_form" class="search_form_reset" method="get" action="#">
            <table class="search-form">
                <tbody><tr>
                    <td>&nbsp;</td>
                    <th> <select name="search_type" class="h28">
                            <option value="1" selected=""><?=__('商品名称')?></option>
                            <!-- <option value="2"><?=__('商家货号')?></option> -->
                            <option value="3">平台货号</option>
                        </select>
                    </th>
                    <td class="w160"><input class="text w150 z-box2" name="keyword" value="" type="text"></td>
                    <td class="tc w70">
                        <label class="submit-border">
                            <input class="submit z-box" value="<?=__('搜索')?>" type="submit">
                        </label>
                       <!--  <a onclick="formSub()" class="button btn_search_goods" href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i>搜索</a> -->
                    </td>
                </tr>
                </tbody></table>
        </form>
    </div>
    <table class="ncsc-default-table table-list-style">
        <thead>
        <tr nc_type="table_header">
            <th>&nbsp;</th>
            <th class="w50">&nbsp;</th>
            <th coltype="editable" column="goods_name" checker="check_required" inputwidth="230px"><?=__('商品名称')?></th>
            <th class="w150">平台货号</th>
            <!-- <th class="w150"><?=__('商家货号')?></th> -->
            <th class="w150"><?=__('商品状态')?></th>
            <th class="w150"><?=__('商品价格')?></th>
            <th class="w120"><?=__('门店库存')?></th>
            <th class="w120"><?=__('操作')?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($goods)){
            foreach($goods as $key => $val){?>
                <tr>
                    <td></td>
                    <td><div class="pic-thumb"><a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['goods_id']?>" target="_blank"><img src="<?=$val['goods_image']?>"></a></div></td>
                    <td class="tl"><dl class="goods-name">
                            <dt style="max-width: 450px !important;"> <a href="<?=Yf_Registry::get('url')?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=$val['goods_id']?>" target="_blank"><?=$val['goods_name']?></a></dt>
                        </dl></td>
                    <td><?=$val['SPU']?></td>
                    <!-- <td><?=$val['goods_code']?></td> -->
                    <td><?= $val['isValid'] ? "出售中" : "已下架"; ?></td>
                    <td><span><?=format_money($val['goods_price'])?></span></td>
                    <td><span><?=$val['goods_stock']?><?=__('件')?></span></td>
                    <td class="nscs-table-handle"><span><a href="javascript:void(0);" class="bbc_seller_btns js-edit-goods button button_blue <?= $val['isValid'] ? "" : "disabled"; ?>" nctype="set_stock" data-commonid="<?= $val['SPU'] ?>">
                                <p><?= __('设置库存') ?></p>
                            </a></span></td>
                </tr>
            <?php }}else{ ?>
            <tr class="row_line">
                <td colspan="99">
                    <div class="no_account">
                        <img src="<?=$this->view->img?>/ico_none.png">
                        <p><?=__('暂无符合条件的数据记录')?></p>
                    </div>
                </td>
            </tr>
        <?php }?>
        <tr style="display:none;">
            <td colspan="20"><div class="ncsc-goods-sku ps-container"></div></td>
        </tr>
        </tbody>
        <tfoot>
        <?php if(!empty($page_nav)){?>
            <tr>
                <td colspan="99">
                    <div class="page">
                        <?=$page_nav?>
                    </div>
                </td>
            </tr>
        <?php }?>
        </tfoot>
    </table>
</div>
    <script>
        $(function(){
            $('a[nctype="set_stock"]').click(function(){
                if($(this).hasClass("disabled")) {
                    return false;
                }
                var common_id = $(this).attr('data-commonid');
                $.dialog({
                    title: "<?=__('设置库存')?>",
                    content: "url: <?= Yf_Registry::get('url') ?>?ctl=Chain_Goods&met=goodsStock&common_id="+common_id+"&typ=e",
                    data: { callback: callback},
                    width: 800,
                    lock: true
                })
                //
                function callback ( api ) {
                    api.close();
                    window.location.reload();
                }
            });
        });
        $(document).ready(function(){
            $('#search_form').validator({
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                valid:function(form){
                    window.location.href="<?= Yf_Registry::get('url') ?>?ctl=Chain_Goods&met=goods&"+$("#search_form").serialize()+"&typ=e";
                }

            });
        });
    </script>
<?php
include $this->view->getTplPath() . '/' . 'chain_footer.php';
?>