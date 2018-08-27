<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php include $this->view->getTplPath() . '/' . 'seller_header.php'; ?>
<br/>
	<div class="tabmenu">
        <a class="button bbc_seller_btns" href="<?=Yf_Registry::get('url')?>?ctl=Seller_Trade_Deliver&met=deliverSetting&typ=e&act=addAddress"><i class="iconfont icon-jia bbc_seller_btns"></i><?=__('新增地址')?></a>

    </div>
	<!---  BEGIN 地址列表 -->
    <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
		<tbody>
		
			<tr>
			    <th class="tl" width="100"><?=__('是否默认')?></th>
				<th class="tl" width="100"><?=__('联系人')?></th>
				<th class="tl"><?=__('发货地址')?></th>
				<th width="120"><?=__('联系方式')?></th>
				<th width="120"><?=__('操作')?></th>
			</tr>

               <?php foreach($data['items'] as $key => $val){ ?>
			<tr>
			    <td class="tl">
			        <label for="is_default_<?php echo $val['shipping_address_id'];?>">
			            <input type="radio" class="rel_top1" id="is_default_<?php echo $val['shipping_address_id'];?>" name="is_default" <?php if($val['shipping_address_default']==1) echo 'checked'; ?> value="<?php echo $val['shipping_address_id'];?>">
                            <?=__('默认')?>
                       </label>
                   </td>
			    <!--- 联系人 -->
				<td class="tl"><?=$val['shipping_address_contact']?></td>
				<!--- 发货地址 -->
				<td class="tl word-b"><?=sprintf('%s%s%s',$val['shipping_address_area'],' ',$val['shipping_address_address'])?></td>
				<!--- 联系方式 -->
				<td><?=$val['shipping_address_phone']?></td>
				<!--- 操作 -->
				<td>
					<span class="edit"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Trade_Deliver&met=deliverSetting&act=addAddress&op=edit&shipping_address_id=<?=($val['shipping_address_id'])?>"><i class="iconfont icon-zhifutijiao"></i><?=__('编辑')?></a></span>
					<span class="del"><a data-param="{'ctl':'Seller_Trade_Deliver','met':'delAddress','id':'<?= $val['shipping_address_id'] ?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
				</td>
			</tr>
			<?php } ?>
               
			<!--- 分页 -->
			<?php if(!empty($page_nav)){ ?>
			<tr>
				<td colspan="99">
					<div class="page">
						<?=$page_nav?>
					</div>
				</td>
			</tr>
			<?php } ?>
		
		</tbody>	
	</table>
	<!---  END 地址列表 -->

    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
    <script>
		//更改默认发货地址
        $(function (){
            $('input[name="is_default"]').on('click',function(){
                $.get('index.php?ctl=Seller_Trade_Deliver&met=setDefaultAddress&shipping_address_id='+$(this).val(),function(result){})
            });
        });
    </script>
<?php include $this->view->getTplPath() . '/' . 'seller_footer.php'; ?>