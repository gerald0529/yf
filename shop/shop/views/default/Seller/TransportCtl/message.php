<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>
<body>

<div class="freight">
	<div class="tabmenu">
		<ul>
        	<li ><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Transport&met=transport"><?=__('运费设置')?></a></li>

            <?php if($act == 'add') {?>
            <li  class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('添加运费模版')?></a></li>
            <?php }
            if($act == 'edit'){?>
            <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('编辑运费模版')?></a></li>
            <?php }?>
        </ul>

    </div>


    <form id="form" action="#" method="post" >
    <div class="form-style">

    <input type="hidden" name="data" id="data" value="<?php $data;?>">
    <input type="hidden" name="transport_type_id" id="transport_type_id" value="" />
    <input type="hidden" name="transport_item_id" id="transport_item_id" value="" />
    <input type="hidden" name="default" id="default" value=""/>

        <dl class="dl">
            <dt><i><?=__('*')?></i><?=__('模板名称：')?></dt>
            <dd style="width:25%"><input type="text" class="text w120" name="type_name" id="type_name" value="" /></dd>
        </dl>
        <dl class="dl">
            <dt><i><?=__('*')?></i><?=__('首重重量：')?></dt>
            <dd style="width:25%"><input type="text" class="text w60" name="default_num" id="default_num" value="" /><em><?=__('Kg')?></em></dd>
            <dt style="width:8%"><i><?=__('*')?></i><?=__('首重费用：')?></dt>
            <dd><input type="text" class="text w60" name="default_price" id="default_price" value="" /><em><?=(Web_ConfigModel::value('monetary_unit'))?></em></dd>
        </dl>
        <dl class="dl">
            <dt><i><?=__('*')?></i><?=__('续重重量：')?></dt>
            <dd style="width:25%"><input type="text" class="text w60" name="add_num" id="add_num" value="" /><em><?=__('Kg')?></em></dd>
            <dt style="width:8%"><i><?=__('*')?></i><?=__('续重费用：')?></dt>
            <dd><input type="text" class="text w60" name="add_price" id="add_price" value="" /><em><?=(Web_ConfigModel::value('monetary_unit'))?></em></dd>
        </dl>


            <?php foreach($province as $key => $val){?>
            <?php if(isset($val['city'])){?>
            <dl class="address_ctiy_list">
                <dt>
                    <label class="checkbox province"><input type="checkbox" name="province[]" value="<?=($val['district_id'])?>" /><?=($val['district_name'])?></label>
                </dt>
                <dd>
                    <ul class="area">
                    <?php foreach($val['city'] as $citykey => $cityval){?>
                        <li><label class="checkbox city"><input <?php if($shop_transport && in_array($cityval['district_id'],$shop_transport)){?>disabled="disabled"<?php }else{ if($type_city && in_array($cityval['district_id'],$type_city)){?>checked="checked"<?php }}?> type="checkbox"  name="city[]" data-province="<?=($val['district_id'])?>" value="<?=($cityval['district_id'])?>" /><?=($cityval['district_name'])?></label></li>
                    <?php }?>
                    </ul>
                </dd>
            </dl>
            <?php }?>
            <?php }?>
        <dl>
            <dt></dt>
            <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
        </dl>
    </div>
    </form>

    </table>
    </form>
</div>

<script>
    var data    = eval(<?php echo json_encode($data);?>);

    if(data !="")
    {
        console.info(data.transport_type_id);
        $("#transport_type_id").val(data.transport_type_id);
        $("#transport_item_id").val(data.transport_item.transport_item_id);
        $("#type_name").val(data.transport_type_name);
        $("#default_num").val(data.transport_item.transport_item_default_num);
        $("#default_price").val(data.transport_item.transport_item_default_price);
        $("#add_num").val(data.transport_item.transport_item_add_num);
        $("#add_price").val(data.transport_item.transport_item_add_price);
        if(data.transport_item.transport_item_city == 'default')
        {
            $(".address_ctiy_list").hide();
            $("#default").val('default');
        }
        else
        {
            $("#default").val('');
        }

    }

    $(document).ready(function(){
        var act    = eval('"<?php echo $act;?>"');
        if(act == 'add')
        {
            var ajax_url = '<?= Yf_Registry::get('url') ?>?ctl=Seller_Transport&met=addTransport&typ=json';
        }
        if(act == 'edit')
        {
            var ajax_url = '<?= Yf_Registry::get('url') ?>?ctl=Seller_Transport&met=editTransport&typ=json';
        }


        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                'type_name':'required',
                'default_num': 'required;range[0~10000]',
                'default_price': 'required;range[0~100000]',
                'add_num':'range[0~100]',
                'add_price':'range[0~10000]',
                'city[]':'checked',
            },
            valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                            location.href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Transport&met=transport";
                            //Public.tips({content: "操作成功！"});
                        }
                        else
                        {
                            //alert('操作失败！');
                            Public.tips({type: 1, content: "<?=__('操作失败！')?>"});
                        }
                    }
                });
            }

        });


        $('input[name="province[]"]').click(function(){
            var _self=this;
            if ($(this).attr('checked') == true){
                $('input[data-province="' + $(this).val() + '"]').each(function(){
                    if ($(this).attr('disabled') == false)
                        //$(this).attr('checked','checked');
                        $(this).prop('checked', _self.checked);
                });
            }else{
                $('input[data-province="' + $(this).val() + '"]').each(function(){
                    //$(this).attr('checked',false);
                    $(this).prop('checked', _self.checked);
                });
            }
            if($('input[data-province="'+$(this).val()+'"]').size() == $('input[data-province="'+$(this).val()+'"]:checked').size()) {
                $(this).prop('checked', true);
            }else {
                $(this).prop('checked', false);
            }
	});
	$('input[name="city[]"]').click(function(){
	    var _self = this;
		if (_self.checked)
		{
			if ($('input[data-province="'+$(this).attr('data-province')+'"]').size() == $('input[data-province="'+$(this).attr('data-province')+'"]:checked').size()) {
				$('input[value="'+$(this).attr('data-province')+'"]').prop('checked', true);
			} else {
				$('input[value="'+$(this).attr('data-province')+'"]').prop('checked', false);
			}
		} else {
			$('input[value="'+$(this).attr('data-province')+'"]').prop('checked', false);
		}
	});
	$('input[name="city[]"]').each(function(){
		if ($('input[data-province="'+$(this).attr('data-province')+'"]').size() == $('input[data-province="'+$(this).attr('data-province')+'"]:checked').size()) {

			$('input[value="'+$(this).attr('data-province')+'"]').prop('checked', true);
		} else {

			$('input[value="'+$(this).attr('data-province')+'"]').prop('checked', false);
		}
		if ($('input[data-province="'+$(this).attr('data-province')+'"]').size() == $('input[data-province="'+$(this).attr('data-province')+'"]:disabled').size()) {
			$('input[name="province[]"][value="'+$(this).attr('data-province')+'"]').attr('disabled',true);
		} else {
			$('input[name="province[]"][value="'+$(this).attr('data-province')+'"]').attr('disabled',false);
		}
		if($('input[name="city[]"]').size() == $('input[name="city[]"]:disabled').size() && data ==""
    ){
			$("input[type='submit']").attr('disabled',true);
			//$("input[type='submit']").parent().remove();
		}
	});

    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>