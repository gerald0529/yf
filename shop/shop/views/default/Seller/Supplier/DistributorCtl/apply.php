<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>
.table-list-style{border:0px;}
.table-list-style td{border:0px;}
.table-list-style th{border:0px;}
</style>
<div class="exchange">
	<?php if(isset($distributor_info)){?>
		<div class="form-style">
			<dl class="dl">
	            <dt><?=__('店铺名称：')?></dt>
	            <dd ><?=$distributor_shop_info['shop_name']?></dd>
        	</dl>
        	<dl class="dl">
	            <dt><?=__('店主姓名：')?></dt>
	            <dd ><?=$distributor_shop_info['user_name']?></dd>
        	</dl>
        	<dl class="dl">
	            <dt><?=__('联系方式：')?></dt>
	            <dd ><?=$distributor_shop_info['shop_tel']?></dd>
        	</dl>
        	<dl class="dl">
	            <dt><?=__('已通过分类：')?></dt>
	            <dd ><?=@$distributor_cat_name?></dd>
        	</dl>
			<dl class="dl">
				<dt><?=__('未审核分类：')?></dt>
				<dd><?=@$distributor_new_cat_name?></dd>
			</dl>
			<form id="supplier_form">
			<input type="hidden" name="shop_distributor_id" value="<?=request_int('distributor_id')?>" />
        	<dl>
	            <dt><?=__('商品分类：')?></dt>
	            <dd>				
	            	<table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
						<tr>
							<th class="tl"><label class="checkbox"><input class="checkall" type="checkbox" /></label><?=__('分类名称')?></th>
						</tr>
						<?php 
							if(!empty($shop_cat)) 
							{
								foreach ($shop_cat as $key => $value)
								{ 
						?>
							<tr class="row_line">
								<td class="tl fbold">
									<label class="checkbox">
										<input class="checkitem" type="checkbox" name="chk[]" value="<?=$value['shop_goods_cat_id']?>" <?php if((isset($distributor_cat) && in_array($value['shop_goods_cat_id'],$distributor_cat))||(isset($distributor_new_cat) && in_array($value['shop_goods_cat_id'],$distributor_new_cat))){?> checked="checked"  
										<?php }?> />
									</label>
									<?=$value['shop_goods_cat_name']?>
								</td>
							</tr>
							<?php 
								if(!empty($value['subclass'])) 
								{
									foreach ($value['subclass'] as $keys => $values)
									{
						   ?>
							<tr class="row_line row_line_dash sub<?=$value['shop_goods_cat_id']?>" >
								<td class="tl">
									<label class="checkbox">
										<input class="checkitem" type="checkbox" name="chk[]" value="<?=$values['shop_goods_cat_id']?>" <?php if((isset($distributor_cat) && in_array($values['shop_goods_cat_id'],$distributor_cat))||(isset($distributor_new_cat) && in_array($values['shop_goods_cat_id'],$distributor_new_cat))){?> checked="checked" <?php }?> style="margin-left: 20px;"/>
									</label>
									<span class="span_speical" style="line-height: 32px;margin-left: 1px;"><?=$values['shop_goods_cat_name']?></span>
								</td>
							</tr>
					<?php 			}
								}
							} 
					?>
					<!--- 分页 --->
			
					<?php 	}else{
					?>
						<tr>
							<td colspan="99">
								<p><?=__('暂无符合条件的数据记录')?></p>
							</td>
						</tr>
					<?php }?>	
						
						<tr>
							<td class="toolBar" colspan="99">
							<label class="checkbox"><input class="checkall" type="checkbox" /></label><?=__('全选')?>
							</td>
						</tr>
			
					</table>
	                
	            </dd>
	        </dl>
			
			<dl>
	            <dt></dt>
	            <dd>
	            <a class="button bbc_seller_submit_btns"><?=__('确认提交')?></a>
	            </dd>
	        </dl>
			
			</form>
		</div>
	<?php }?> 	
</div>
<script>	
	$('.bbc_seller_submit_btns').click(function ()
	{
		var data = $("#supplier_form").serialize();
		$.post(SITE_URL  + '?ctl=Seller_Supplier_Distributor&met=editDistributorCat&typ=json',data,function (redata)
		{
			if(redata.status == 200)
			{
				Public.tips.success('<?=__('操作成功!')?>');
				location.href="index.php?ctl=Seller_Supplier_Distributor&met=index";
			}
			else
			{
				   Public.tips.error('<?=__('操作失败！')?>');
			}
		})
	});

	$(".fbold .checkbox").click(function()
	{
		var chk = $(this).find("input[type='checkbox']").attr('checked');
		var id  = $(this).find("input[type='checkbox']").val();

		if(chk)
		{
			$(this).parent().parent().siblings(".sub"+id).each(function(){
				$(this).find("input[type='checkbox']").attr('checked',true);
			});
		}else{
			$(this).parent().parent().siblings(".sub"+id).each(function(){
				$(this).find("input[type='checkbox']").removeAttr("checked");
			});
		}
	});
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>