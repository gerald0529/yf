<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style type="text/css">
	#apply-disagree{
		display:none;
	    background-color: #FFF;
    	border: solid 1px #E6E6E6;
	}
	.dialog_close_button{
		float: right;
		/*margin-left: 310px;*/
		cursor:pointer;
	}
	.ui_title{
		width: 95%;
	}
	.dialog_title{
		width: 100px;
		float:left;
	}
	textarea{
		width: 91%;
		margin: 20px 10px;
	}
	
	#add_rate{
		display:none;
	    background-color: #FFF;
    	border: solid 1px #E6E6E6;
	}
	#level_list{
		margin-top: 20px;
		margin-left: 20px;
	}
	.mb10{
		margin-top: 10px;
	}
</style>

<!--申请分销商不通过原因提交-->
<div id="apply-disagree" class="eject_con" >
	<input type="hidden" name="shop_grade_id" id="shop_grade_id" value="" />
	<textarea id="reason"></textarea>
	<div class="eject_con mb10">
        <div class="bottom"><a id="btn_apply_submit" class="button bbc_seller_submit_btns" href="javascript:void(0);"><?=__('提交')?></a></div>
    </div>
</div>

<!--设置分销商折扣-->
<div id="add_rate" class="eject_con">
	<input type="hidden" name="shop_distributor_id" id="shop_distributor_id" value="" />
	<div id="level_list">
		<span>
			<?=__('设置分销商等级：')?>
		</span>
		<select name="level_id" id="level_id">
			<?php if(isset($level_list)){
					foreach ($level_list as $key => $value) {
			?>
				<option value="<?=$value['distributor_level_id']?>"><?=$value['distributor_leve_name']?>：<?=$value['distributor_leve_discount_rate']?> %</option>
			<?php }}?>
		</select>
	</div>
	<div class="eject_con mb10">
        <div class="bottom"><a id="btn_grade_submit" class="button bbc_seller_submit_btns" href="javascript:void(0);"><?=__('提交')?></a></div>
    </div>
</div>

	
<div class="exchange">
	    <div class="fn-clear" style="padding:10px 0 10px;">
	        <form method="get" id="search_form" action="index.php" >
	            <input type="hidden" name="ctl" value="<?=$_GET['ctl']?>">
	            <input type="hidden" name="met" value="<?=$_GET['met']?>">
	            <input type="hidden" name="typ" value="e">
	
				<!--<span>店铺名</span><input type="text" name="shop_name" class="text w70">-->
	            <span><?=__('创建时间')?></span><input type="text" autocomplete="off" name="start_date" id="start_date" class="text w70" value="<?=request_string('start_date')?>" placeholder="<?=__('开始时间')?>"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
	            &nbsp;-&nbsp;
	            <input type="text" autocomplete="off" name="end_date" id="end_date" class="text w70" value="<?=request_string('end_date')?>" placeholder="<?=__('结束时间')?>"/><em class="add-on add-on2"><i class="iconfont icon-rili"></i></em>
	            <span><?=__('状态')?></span>
	            <select name="state">
	                <option value=""><?=__('请选择')?></option>、
	                <option value="1" <?=request_int('state')==1?'selected':''?> ><?=__('通过')?></option>
	                <option value="2" <?=request_int('state')==2?'selected':''?> ><?=__('未审核')?></option>
	                <option value="3" <?=request_int('state')==3?'selected':''?> ><?=__('未通过')?></option>
	            </select>
	 
	            <label class="search" >&nbsp;&nbsp;
	                <a class="button refresh" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Supplier_Distributor&met=index&typ=e"><i class="iconfont icon-huanyipi"></i></a>
	
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
				<th width="100"><?=__('联系方式')?></th>
				<th width="120"><?=__('小店名称')?></th>
				<th width="60"><?=__('状态')?></th>
				<th width="120"><?=__('分销等级')?></th>
				<th width="100"><?=__('分销折扣')?></th>
				<th width="120"><?=__('创建时间')?></th>
				<th width="300"><?=__('操作')?></th>
			</tr>
	        <?php
	        if($data['items'])
	        {
	            foreach($data['items'] as $key=>$val)
	            {
	        ?>
	        <tr class="row_line">
	            <td><?=($val['user_name'])?></td>
	            <td><?=($val['mobile'])?></td>
	            <td><?=($val['shop_name'])?></td>
	            <td>
					<?php 
						if($val['distributor_enable']==1){
							echo __('通过');
						}elseif($val['distributor_enable']==0){
							echo __('待审核');
						}elseif($val['distributor_enable']==-1){
							echo __("未通过");
						}
					?>
				</td>
	            <td><?php if(isset($val['level_name'])) echo $val['level_name'];?></td>
	            <td><?php if(isset($val['level_rate'])) echo $val['level_rate'].' %';?></td>
	            <td><?=($val['shop_distributor_time'])?></td>
	            <td class="nscs-table-handle" style="padding:8px">
	                <?php if($val['distributor_enable'] == 0){?>
						<span><a href="javascript:void(0);" data-id='<?=$val['shop_distributor_id']?>' data-type="agree" class="audit"><i class="iconfont icon-btnsetting"></i><?=__('通过')?></a></span>
						<span style="border-left: solid 1px #E6E6E6"><a href="javascript:void(0);" data-id='<?=$val['shop_distributor_id']?>' id="disagree"><i class="iconfont icon-btnsetting"></i><?=__('不通过')?></a></span>
						<span style="border-left: solid 1px #E6E6E6"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Supplier_Distributor&met=index&distributor_id=<?=$val['shop_distributor_id']?>&typ=e" data-id='<?=$val['shop_distributor_id']?>'> <i class="iconfont icon-btnclassify2"></i><?=__('查看')?></a></span>
						<span style="border-left: solid 1px #E6E6E6"><a href="javascript:void(0);" data-id='<?=$val['shop_distributor_id']?>' data-type="del" class="audit"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
				    <?php }elseif($val['distributor_enable'] == -1){?>
				    	<span><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Supplier_Distributor&met=index&distributor_id=<?=$val['shop_distributor_id']?>&typ=e" data-id='<?=$val['shop_distributor_id']?>'> <i class="iconfont icon-btnclassify2"></i><?=__('查看')?></a></span>
				    	<span style="border-left: solid 1px #E6E6E6"><a href="javascript:void(0);" data-id='<?=$val['shop_distributor_id']?>' data-type="del" class="audit"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
			    	<?php }elseif($val['distributor_enable'] == 1){?>
			    		<span><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Supplier_Distributor&met=distributor_salenum&distributor_id=<?=$val['shop_distributor_id']?>&typ=e"><i class="iconfont icon-btnclassify2"></i><?=__('业绩')?></a></span>
			    		<span style="border-left: solid 1px #E6E6E6"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Supplier_Distributor&met=index&distributor_id=<?=$val['shop_distributor_id']?>&typ=e" data-id='<?=$val['shop_distributor_id']?>'> <i class="iconfont icon-btnclassify2"></i><?=__('查看')?></a></span>
			    		<span style="border-left: solid 1px #E6E6E6"><a href="javascript:void(0);" data-id='<?=$val['shop_distributor_id']?>' class="set_rate"><i class="iconfont icon-zhifutijiao"></i><?=__('设置')?></a></span>
		    			<span style="border-left: solid 1px #E6E6E6"><a href="javascript:void(0);" data-id='<?=$val['shop_distributor_id']?>' data-type="del" class="audit"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
		    		<?php }?>
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

//设置分销商折扣
$('.set_rate').click(function (){
	var len = $('#level_id').children('option').length;
	if(len == 0){
		window.location.href = SITE_URL+'?ctl=Seller_Supplier_Distributor&met=addGrade';
	}else{
		$("#shop_grade_id").val($(this).attr('data-id'));
		$('#add_rate').yf_show_dialog({width: 450, title: '设置分销商折扣'});
	}
})
$("#btn_grade_submit").click(function (){
	var shop_distributor_id = $("#shop_grade_id").val();
	var grade_id = $('#level_id').val();
	var ajax_url = './index.php?ctl=Seller_Supplier_Distributor&met=add_shop_rate&typ=json';
	$.ajax({
		url:ajax_url,
		data:{shop_distributor_id:shop_distributor_id,grade_id:grade_id},
		success:function(a){
			if(a.status == 200)
            {
               Public.tips.success('<?=__('操作成功！')?>');
               location.reload();
            }
            else
            {
                Public.tips.error('<?=__('操作失败！')?>');
            }
		}
	})
})



//申请不通过弹出框、原因提交
$('#disagree').click(function (){
	$("#shop_distributor_id").val($(this).attr('data-id'));
	$('#apply-disagree').yf_show_dialog({width: 450, title: '<?=__('原因')?>'});
})
$("#btn_apply_submit").click(function (){
	var shop_distributor_id = $("#shop_distributor_id").val();
	var reason = $("#reason").val();
	
	if(reason.length == 0){
		Public.tips.error('<?=__('请填写原因!')?>');
		return false;
	}
	var ajax_url = './index.php?ctl=Seller_Supplier_Distributor&met=apply_disagree&typ=json';
	$.ajax({
		url:ajax_url,
		data:{shop_distributor_id:shop_distributor_id,reason:reason},
		success:function(a){
			if(a.status == 200)
            {
               Public.tips.success('<?=__('操作成功！')?>');
               location.reload();
            }
            else
            {
                Public.tips.error('<?=__('操作失败！')?>');
            }
		}
	})
})



$(document).ready(function(){
	var ajax_url = './index.php?ctl=Seller_Supplier_Distributor&met=edit_statu&typ=json';
	$(".audit").click(function(){
		var shop_distributor_id = $(this).attr('data-id');
		var act    =   $(this).attr('data-type');
		$.ajax({
            url: ajax_url,
            data:{shop_distributor_id:shop_distributor_id,act:act},
            success:function(a){
                if(a.status == 200)
                {
                   Public.tips.success('<?=__('操作成功！')?>');
                   location.reload();
                }
                else
                {
                    Public.tips.error('<?=__('操作失败！')?>');
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