<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>
<body>

<div class="freight">
	<div class="tabmenu">
		<ul>
        	<li><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Transport&met=tplarea&typ=e"><?=__('售卖区域模板设置')?></a></li>
            <?php if(!$data['data']['id']) {?>
            <li  class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('添加售卖区域')?></a></li>
            <?php }else { ?>
            <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('编辑售卖区域')?></a></li>
            <?php }?>
        </ul>

    </div>


    <form id="form" action="#" method="post" >
    <div class="form-style">

    <input type="hidden" name="data" id="data" value="<?php $data['data'];?>">
    <input type="hidden" name="area_id" id="transport_area_id" value="<?=$data['data']['id']?>" />
        <dl class="dl">
            <dt><i><?=__('*')?></i><?=__('模板名称：')?></dt>
            <dd style="width:25%"><input type="text" class="text w120" name="area_name" id="area_name" value="<?=$data['data']['name']?>" /></dd>
        </dl>
        <dl class="dl">
            <dt><i><?=__('*')?></i><?=__('选择区域：')?></dt>
            <dd style="width:25%"><input type="radio" name="all_city" value="0" onclick="choose_area(0)" <?php if($data['data']['all_city'] == 0){?>checked="checked"<?php }?> />全国</dd>
            <dd style="width:25%"><input type="radio" name="all_city" value="1"  onclick="choose_area(1)" <?php if($data['data']['all_city'] == 1){?>checked="checked"<?php }?> />自定义</dd>
        </dl>
        <div id="address_ctiy_list" <?php if($data['data']['all_city'] == 0){?>style="display:none;"<?php }?>>
        <dl class="address_ctiy_list">
            <dt>
                <label class="checkbox"><input type="checkbox" name="selectAll" onclick="select_all()" id="selectAll" /><?=__('全选')?></label>
            </dt>
        </dl>
        <?php foreach($data['district'] as $key => $val){?>
        <?php if(isset($val['city'])){?>
        <dl class="address_ctiy_list">
            <dt>
                <label class="checkbox province"><input type="checkbox" name="province[]" value="<?=($val['district_id'])?>" <?php if($data['data']['area_ids_arr'] && in_array($val['district_id'],$data['data']['area_ids_arr'])){?>checked="checked"<?php }?> /><?=($val['district_name'])?></label>
            </dt>
            <dd>
                <ul class="area">
                <?php foreach($val['city'] as $citykey => $cityval){?>

                        <li><label class="checkbox city"><input <?php if($data['data']['area_ids_arr'] && in_array($cityval['district_id'],$data['data']['area_ids_arr'])){?>checked="checked"<?php }?> type="checkbox"  name="city[]" data-province="<?=($val['district_id'])?>" value="<?=($cityval['district_id'])?>" /><?=($cityval['district_name'])?></label></li>

                <?php }?>
                </ul>
            </dd>
        </dl>
        <?php }}?>
        </div>
        <dl>
            <dt></dt>
            <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
        </dl>
    </div>
    </form>

    </table>
    </form>
</div>

<script type="text/javascript">

    function choose_area(type){

        if(type == 0){
            $('#address_ctiy_list').hide();
        }else{
            $('#address_ctiy_list').show();
        }

    }
    
     function select_all() {
        var obj = $('#selectAll');
        var cks = $("input");
        var ckslen = cks.length;
        for(var i=0;i<ckslen;i++) {
            if(cks[i].type === 'checkbox') {
                cks[i].checked = obj[0].checked;
            }
        }
    }

    $(document).ready(function(){
        var ajax_url = '<?= Yf_Registry::get('url') ?>?ctl=Seller_Transport&met=areaSubmit&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                'area_name':'required',
                'city[]':'checked'
            },
            valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                            window.location.href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Transport&met=tplarea&typ=e";
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
                        $(this).prop('checked', _self.checked);
                });
            }else{
                $('input[data-province="' + $(this).val() + '"]').each(function(){
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
                }
            } else {
                $('input[value="'+$(this).attr('data-province')+'"]').prop('checked', false);
            }
        });
    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>