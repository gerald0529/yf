<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="exchange">
    <div class="fn-clear" style="padding:10px 0 10px;">
        <form method="get" id="search_form" action="index.php" >
            <input type="hidden" name="ctl" value="<?=$_GET['ctl']?>">
            <input type="hidden" name="met" value="<?=$_GET['met']?>">
            <input type="hidden" name="typ" value="e">

            <span><?=__('创建时间')?></span><input type="text" autocomplete="off" name="start_date" id="start_date" class="text w70" value="<?=request_string('start_date')?>" placeholder="<?=__('开始时间')?>"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
            &nbsp;-&nbsp;
            <input type="text" autocomplete="off" name="end_date" id="end_date" class="text w70" value="<?=request_string('end_date')?>" placeholder="<?=__('结束时间')?>"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
            <span><?=__('状态')?></span>
            <select name="state">
                <option value=""><?=__('请选择')?></option>、
                <option value="1" <?=request_int('state')==1?'selected':''?> ><?=__('未审核')?></option>
                <option value="2" <?=request_int('state')==2?'selected':''?> ><?=__('已审核')?></option>
            </select>
 
            <label class="search" >&nbsp;&nbsp;
                <!-- <a class="button ml10" href="<?=Yf_Registry::get('url')?>?index.php?ctl=Distribution_Seller_Setting&met=addDirectsellerGoods&typ=e&">添加分销商品</a> -->
                <a class="button refresh" href="<?=Yf_Registry::get('url')?>?ctl=Distribution_Seller_Setting&met=directseller&typ=e"><i class="iconfont icon-huanyipi"></i></a>

                <a class="button btn_search_goods"  href="javascript:void(0);"><i class="iconfont icon-btnsearch"></i><?=__('搜索')?></a>

            </label>

            <script type="text/javascript">
                $("a.btn_search_goods").on("click",function(){
                    $("#search_form").submit();
                });
            </script>
        </form>
    </div>

	<table class="table-list-style table-promotion-list" id="table_list" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<th class="tc" width="150"><?=__('用户名')?></th>
			<th width="100"><?=__('手机号')?></th>
			<th width="200"><?=__('小店名称')?></th>
			<th width="100"><?=__('上级ID')?></th>
			<th width="60"><?=__('状态')?></th>
			<th width="200"><?=__('创建时间')?></th>
			<th width="240"><?=__('操作')?></th>
		</tr>
        <?php
        if($data['items'])
        {
            foreach($data['items'] as $key=>$val)
            {
        ?>
        <tr class="row_line">
            <td><?=($val['info']['user_name'])?></td>
            <td><?=($val['info']['user_mobile'])?></td>
            <td><?=($val['info']['user_directseller_shop'])?></td>
            <td><?=($val['info']['user_parent_id'])?></td>
            <td><?=($val['directseller_enable_text'])?></td>
            <td><?=($val['directseller_create_time'])?></td>
            <td class="nscs-table-handle">             
			<span <?php if($val['directseller_enable']){?>class="unclick"<?php } ?>><a href="javascript:void(0);" data-id='<?=$val['shop_directseller_id']?>' <?php if(!$val['directseller_enable']){ echo 'class="audit"';}?>><i class="iconfont icon-success"></i><?=__('通过')?></a></span>
			<span style="border-left: solid 1px #E6E6E6;"><a href="<?=Yf_Registry::get('url')?>?ctl=Distribution_Seller_Setting&met=directsellerDetail&directseller_id=<?=$val['directseller_id']?>&typ=e"><i class="iconfont icon-btnclassify2"></i><?=__('业绩')?></a></span>
				<span class="del"><a data-param="{'ctl':'Distribution_Seller_Setting','met':'delDirectseller','id':'<?=$val['shop_directseller_id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
            </td>
        </tr>
        <?php } }else{ ?>
            <tr class="row_line">
                <td colspan="99">
                    <div class="no_account">
                        <img src="<?=$this->view->img?>/ico_none.png">
                        <p><?=__('暂无符合条件的数据记录')?></p>
                    </div>
                </td>
            </tr>
        <?php } ?>
	</table>
        <?php if($page_nav){ ?>
            <div class="mm">
                <div class="page"><?=$page_nav?></div>
            </div>
        <?php }?>
</div>

<link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js" charset="utf-8"></script>

<script>
$(document).ready(function(){
	var ajax_url = './index.php?ctl=Distribution_Seller_Setting&met=directseller&typ=json';
	$(".audit").click(function(){
		var id = $(this).attr('data-id');
		$.ajax({
            url: ajax_url,
            data:{id:id,op:'audit'},
            success:function(a){
                if(a.status == 200)
                {
                   Public.tips.success("<?=__('操作成功！')?>");
				   location.href = SITE_URL + '?ctl=Distribution_Seller_Setting&met=directseller&typ=e';
                }
                else
                {
                    Public.tips.error("<?=__('操作失败！')?>");
                }
            }
        });
	});
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