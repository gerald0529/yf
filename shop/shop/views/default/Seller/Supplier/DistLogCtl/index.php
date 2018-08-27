<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="fn-clear" style="padding:10px 0 10px;">
	<span style="line-height: 30px;"><?=__('订单总额：')?><?php if(isset($dist_total) && $dist_total){echo $dist_total;}else{echo 0;}?> 元</span>
    <form method="get" id="search_form" action="index.php" >
        <input type="hidden" name="ctl" value="<?=$_GET['ctl']?>">
        <input type="hidden" name="met" value="<?=$_GET['met']?>">
        <input type="hidden" name="typ" value="e">
		<input type="text" name="order_id" class="text w150" placeholder="<?=__('请输入订单号')?>">
		<input type="text" name="goods_name" class="text w150" placeholder="<?=__('请输入商品名')?>">
        <input type="text" autocomplete="off" name="start_date" id="start_date" class="text w70" value="<?=request_string('start_date')?>" placeholder="<?=__('开始时间')?>"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
        &nbsp;-&nbsp;
        <input type="text" autocomplete="off" name="end_date" id="end_date" class="text w70" value="<?=request_string('end_date')?>" placeholder="<?=__('结束时间')?>"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
        <label class="search" >&nbsp;&nbsp;
            <a class="button refresh" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Supplier_DistLog&met=index&typ=e"><i class="iconfont icon-huanyipi"></i></a>

            <a class="button btn_search_goods"  href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>

        </label>

        <script type="text/javascript">
            $("a.btn_search_goods").on("click",function(){
                $("#search_form").submit();
            });
        </script>
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
                <th width="100"><?=__('订单号')?></th>
                <th width="100"><?=__('商品名')?></th>
                <th width="100"><?=__('商品图片')?></th>
                <th width="80"><?=__('商品价格')?></th>
                <th width="80"><?=__('订单日期')?></th>
            </tr>
		    <?php foreach ($data['items'] as $item){ ?>
                <tr>
                    <td><?=$item['order_id'] ?></td>
                    <td><?=$item['goods_name'] ?></td>
                    <td><img src="<?=$item['goods_image'] ?>" width="80px" /></td>
                    <td><?=$item['goods_price'] ?></td>
                    <td><?=$item['order_goods_time'] ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="99">
                    <div class="page">
                        <?=$page_nav ?>
                    </div>
                </td>
            </tr>
        </table>
<?php }else{ ?>
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <th width="100"><?=__('订单号')?></th>
            <th width="100"><?=__('商品名')?></th>
            <th width="80"><?=__('商品图片')?></th>
            <th width="80"><?=__('商品价格')?></th>
            <th width="80"><?=__('订单日期')?></th>
        </tr>
    </table>
    <div class="no_account">
        <img src="<?=$this->view->img?>/ico_none.png">
        <p><?=__('暂无符合条件的数据记录') ?></p>
    </div>
<?php } ?>
	
<script>
$(document).ready(function(){
	$('#start_date').datetimepicker({
        controlType: 'select',
        timepicker:false,
        format:'Y-m-d'
    });

    $('#end_date').datetimepicker({
    controlType: 'select',
    timepicker:false,
    format:'Y-m-d'
    });
});  
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>