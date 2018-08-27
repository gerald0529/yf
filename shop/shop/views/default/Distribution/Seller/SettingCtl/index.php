<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="exchange">
	<div class="alert">
        <ul>
			<li><?=__('1、消费限制：用户必须在本店铺消费并且满足设置的消费金额限制时，才能申请成为该店铺的分销员。')?></li>
			<li><?=__('2、消费金额限制设置为0，表示消费金额不做限制，用户只要在本店铺成功消费，即可申请成为该店铺的分销员。')?></li>
        </ul>
	</div>

	<form  method="post" id="form" >
		<div class="form-style">
			<dl>
				<dt><?=__('消费金额限制：')?></dt>
				<dd><input type="text" class="text w60 n-valid" name="directseller[expenditure]" value="<?=@$data['expenditure']?>"><em><?=Web_ConfigModel::value('monetary_unit')?></em></dd>
			</dl>		
			
			<dl>
				<dt></dt>
				<dd>
				<input type="hidden" name="op" value="edit" />
				<input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" />
				</dd>
			</dl>
		</div>
    </form>
</div>
<script>
    $(document).ready(function(){
         var ajax_url = './index.php?ctl=Distribution_Seller_Setting&met=edit&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
           rules: {
                expenditure: 'required;integer[+0]',
            },
            fields: {
                'directseller[expenditure]': 'expenditure',
            },
           valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                           Public.tips.success("<?=__('操作成功！')?>");
                        }
                        else
                        {
                            Public.tips.error("<?=__('操作失败！')?>");
                        }
                    }
                });
            }

        });
    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>