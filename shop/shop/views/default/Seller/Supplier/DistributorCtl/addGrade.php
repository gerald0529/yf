<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="content">
	    <form id="grade_form" action="#" method="post" >
	    	<?php if(isset($grade_info)){?>
	    		<input type="hidden" name="act" value="edit">
	    		<input type="hidden" name="distributor_level_id" value="<?=$grade_info['distributor_level_id']?>">	
	    	<?php }?>	
            <div class="form-style">
	        <dl class="dl">
	            <dt><i>*</i><?=__('等级名称：')?></dt>
	            <dd ><input type="text" class="text w400" name="grade_name" id="grade_name" value="<?php if(isset($grade_info)){ echo $grade_info['distributor_leve_name'];}?>" /></dd>
	        </dl>
	         <dl class="dl">
	            <dt><i>*</i><?=__('等级折扣：')?></dt>
	            <dd >
	            		<input type="text" class="text w120"  name="grade_rate" id="grade_rate" value="<?php if(isset($grade_info)){ echo $grade_info['distributor_leve_discount_rate'];}?>" /> %
	            		<p class="hint">*<?=__('等级折扣为对应分销商进货时的进货折扣')?></p>
	            </dd>
	        </dl>
	        <dl class="dl">
	            <dt><i></i><?=__('排序：')?></dt>
	            <dd><input type="text" class="text w50" name="leve_order" id="leve_order" value="<?php if(isset($grade_info)){ echo $grade_info['distributor_leve_order'];}?>" /></dd>
	        </dl>
        <dl>
            <dt></dt>
            <dd><a class="button bbc_seller_submit_btns"><?=__('确认提交')?></a></dd>
        </dl>
    </div>
    </form>
</div>

<script>
$('.bbc_seller_submit_btns').click(function (){
	var data = $("#grade_form").serialize();
	if($('#grade_name').val() == ''){
		Public.tips.error('<?=__('等级名称不能为空！')?>');
		return false;
	}
	if($('#grade_rate').val() == ''){
		Public.tips.error('<?=__('请设置折扣！')?>');
		return false;
	}
	$.post(SITE_URL  + '?ctl=Seller_Supplier_Distributor&met=editGrade&typ=json',data,function (redata){
			if(redata.status == 200)
            {
            	Public.tips.success('<?=__('操作成功！')?>');
                location.href="index.php?ctl=Seller_Supplier_Distributor&met=setGrade";
            }
            else
            {
                   Public.tips.error('<?=__('操作失败！')?>');
            }
	})
})
</script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>