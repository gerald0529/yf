<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
	<style>#select_2.hidden,#select_3.hidden{display:none;}</style>
	
	<!---  BEGIN 新增地址 -->
	<form id="form" method="post" name="form">
        <div class="form-style">
			<dl>
                <dt><i>*</i><?=__('联系人')?>：</dt>
                <dd>
                    <input class="text w150" name="shipping_address_contact" type="text" value="<?=@$data['shipping_address_contact']?>" />
                </dd>
            </dl>
			
			<dl>
                <dt><i>*</i><?=__('所在地区')?>：</dt>
                <dd>
					<input type="hidden" name="address_area" id="t" value="<?=@$data['shipping_address_area']?>" />
					<input type="hidden" name="province_id" id="id_1" value="<?=@$data['shipping_address_province_id']?>" />
					<input type="hidden" name="city_id" id="id_2" value="<?=@$data['shipping_address_city_id']?>" />
					<input type="hidden" name="area_id" id="id_3" value="<?=@$data['shipping_address_area_id']?>" />
					
					<?php if(@$data['shipping_address_area']){ ?>
						<div id="d_1"><?=@$data['shipping_address_area'] ?>&nbsp;&nbsp;<a href="javascript:sd();"><?=__('编辑')?></a></div>
					<?php } ?>
					
					<div id="d_2"  class="<?php if(@$data['shipping_address_area']) echo 'hidden';?>">
						<select id="select_1" name="select_1" onChange="district(this);">
							<option value=""><?=__('--请选择--')?></option>
							<?php foreach($district['items'] as $key=>$val){ ?>
							<option value="<?=$val['district_id']?>|1"><?=$val['district_name']?></option>
							<?php } ?>
						</select>
						<select id="select_2" name="select_2" onChange="district(this);" class="hidden"></select>
						<select id="select_3" name="select_3" onChange="district(this);" class="hidden"></select>
					</div>
                </dd>
            </dl>
			
			<dl>
                <dt><i>*</i><?=__('街道地址')?>：</dt>
                <dd>
                    <input class="text w450"  name="shipping_address_address" maxlength="50" type="text" value="<?=@$data['shipping_address_address']?>" />
                    <p class="hint"><?=__('不必重复填写地区')?></p>
                </dd>
            </dl>
			
			<dl>
                <dt><i>*</i><?=__('电话')?>：</dt>
                <dd>
                    <input class="text w200" maxlength="11" name="shipping_address_phone" type="text" value="<?=@$data['shipping_address_phone']?>" />
                </dd>
            </dl>
			
			<dl>
                <dt><?=__('公司')?>：</dt>
                <dd>
                    <input class="text w200" maxlength="50" name="shipping_address_company" type="text" value="<?=@$data['shipping_address_company']?>" />
                </dd>
            </dl>
			
            <dl>
                <dt></dt>
                <dd>
                    <input name="id" value="<?=@$data['shipping_address_id']?>" type="hidden">
                    <input class="button button_red bbc_seller_submit_btns" value="<?=__('保存')?>" type="submit">
                </dd>
            </dl>
        </div>
    </form>
	<!---  END 新增地址 -->
	<script type="text/javascript" src="<?=$this->view->js?>/district.js"></script>
    <!--- 表单验证 -->
    <script type="text/javascript">
        $(document).ready(function()
		{	
			var type = '<?=@$data['shipping_address_id']?>';
			if(type!='')
			{
				op = 'edit';
			}else{
				op = 'save';
			}
			var ajax_url = 'index.php?ctl=Seller_Trade_Deliver&met=addAddress&op='+op+'&typ=json';
            $('#form').validator({
                debug:true,
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                rules: {
                    phone: [/^1[\d]{10}$/, '<?=__('请输入正确的手机号')?>']
                },
                fields: {
                    'shipping_address_contact': 'required;length[2~10]',
					'select_1':'required',
					'select_2':'required',
					'select_3':'required',
                    'shipping_address_address' : 'required',
                    'shipping_address_phone':'required;phone'
                },
				valid: function(form){
					var me = this;
					// 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
					me.holdSubmit(function(){
						Public.tips.error('<?=__('正在处理中')?>...');
					});
					$.ajax({
						url: ajax_url,
						data: $(form).serialize(),
						type: "POST",
						success:function(e){
							if(e.status == 200)
							{
								Public.tips.success('<?=__('操作成功')?>!');
								setTimeout('location.href="index.php?ctl=Seller_Trade_Deliver&met=deliverSetting&typ=e"',3000); //成功后跳转
							}
							else
							{
								Public.tips.error('<?=__('操作失败')?>！');
							}
							me.holdSubmit(false);
						}
					});
				}
             });
        });
    </script>
    <!--- END 表单验证 -->
 
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>