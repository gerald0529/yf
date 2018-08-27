<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
<style>#select_2.hidden,#select_3.hidden{display:none;}</style>
</head>
<body>
	
	<!---  BEGIN 默认配送地区 --->
	<form id="form" method="post" name="form">
        <div class="form-style">
            <dl>
                <dt><i>*</i><?=__('默认配送地区')?>：</dt>
                <dd>
					<input type="hidden" name="shop_region" id="t" value="<?=@$data['shop_region']?>" />
					<input type="hidden" name="province_id" id="id_1" value="<??>" />
					<input type="hidden" name="city_id" id="id_2" value="<??>" />
					<input type="hidden" name="area_id" id="id_3" value="<??>" />
                    <?php if(@$data['shop_region']){ ?>
						<div id="d_1"><?=@$data['shop_region'] ?>&nbsp;&nbsp;<a href="javascript:sd();"><?=__('编辑')?></a></div>
					<?php } ?>
					
					<div id="d_2"  class="<?php if(@$data['shop_region']) echo 'hidden';?>">
						<select id="select_1" name="select_1" onChange="district(this);">
							<option value=""><?=__('--请选择--')?></option>
							<?php foreach($district['items'] as $key=>$val){ ?>
							<option value="<?=$val['district_id']?>|1"><?=$val['district_name']?></option>
							<?php } ?>
						</select>
						<select id="select_2" name="select_2" onChange="district(this);" class="hidden"></select>
						<select id="select_3" name="select_3" onChange="district(this);" class="hidden"></select>
					</div>
                    <p class="hint"><?=__('此处设置的地区将作为商品详情页面默认的配送地区显示')?></p>
                </dd>
            </dl>
            <dl>
                <dt></dt>
                <dd>
                    <input name="act" value="quota" type="hidden">
                    <input class="button button_red bbc_seller_submit_btns" value="<?=__('保存')?>" type="submit">
                </dd>
            </dl>
        </div>
    </form>
	<!---  END 默认配送地区 --->
	<script type="text/javascript" src="<?=$this->view->js?>/district.js"></script>
    <!--- 表单验证 --->
    <script type="text/javascript">
        $(document).ready(function(){
            $('#form').validator({
                debug:true,
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,

                fields: {
					'select_1':'required',
					'select_2':'required',
					'select_3':'required'
                },
				valid: function(form){
					var me = this;
					// 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
					me.holdSubmit(function(){
						Public.tips.error('<?=__('正在处理中')?>...');
					});
					$.ajax({
						url: "index.php?ctl=Seller_Trade_Deliver&met=deliverArea&op=save&typ=json",
						data: $(form).serialize(),
						type: "POST",
						success:function(e){
							if(e.status == 200)
							{
								Public.tips.success('<?=__('操作成功')?>!');
								setTimeout('location.href="index.php?ctl=Seller_Trade_Deliver&met=deliverArea&typ=e"',3000);//成功后跳转
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
    <!--- END 表单验证 --->
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>