<?php if (!defined('ROOT_PATH')){exit('No Permission');}

include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
<link rel="stylesheet" href="<?= $this->view->css ?>/resetAddr1.css">
<style>

</style>
<?php if($act == 'add' || $act == 'edit'){?>
<div class="aright">
      <div class="member_infor_content">
        <div class="tabmenu">
          <ul class="tab">
			<li class="active"><a><?php if($act=='add'){?><?=__('添加新')?><?php }else{?><?=__('编辑')?><?php }?><?=__('地址')?></a></li>
			</ul>
</div>
<div class="form-style-layout">
	
    <div class="form-style">
    <form id="form" name="form" action="" method="post">
		<input type="hidden" name="data" id="data" value="<?php $data;?>">
		<input type="hidden" name="user_id" id="user_id" value="<?=$userId ?>" />
		<input type="hidden" name="user_address_id" id="user_address_id" value="" />
      
        <dl>
            <dt><em>*</em><?=__('收货人：')?></dt>
            <dd><input name="user_address_contact" id="user_address_contact" value="" class="text"></dd>
        </dl>
        <dl>
            <dt><em>*</em><?=__('所在区域：')?></dt>
            <dd><input type="hidden" name="address_area" id="t" value="<?=@$data['user_address_area']?>" />
					<input type="hidden" name="province_id" id="id_1" value="<?=@$data['user_address_province_id']?>" />
					<input type="hidden" name="city_id" id="id_2" value="<?=@$data['user_address_city_id']?>" />
					<input type="hidden" name="area_id" id="id_3" value="<?=@$data['user_address_area_id']?>" />
					
					<?php if(@$data['user_address_area']){ ?>
						<div id="d_1"><?=@$data['user_address_area'] ?>&nbsp;&nbsp;<a href="javascript:sd();"><?=__('编辑')?></a></div>
					<?php } ?>
					
					<div id="d_2"  class="<?php if(@$data['user_address_area']) echo 'hidden';?>">
						<select id="select_1" name="select_1" onChange="district(this);">
							<option value="">--<?=__('请选择')?>--</option>
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
            <dt><em>*</em><?=__('街道地址：')?></dt>
            <dd><input type="text" value="" name="user_address_address" id="user_address_address" class="w400 text"></dd>
        </dl>
        <dl>
            <dt><em>*</em><?=__('联系电话：')?></dt>
            <dd><input type="text" name="user_address_phone" id="user_address_phone" class="text" value=""></dd>
        </dl>
		<dl>
            <dt><?=__('设为默认地址：')?></dt>
            <dd><input type="checkbox" name="user_address_default" id="user_address_default" class="checkbox" value="1" <?php if(!empty($data)){?> <?php if($data['user_address_default'] == '1'){?>checked <?php }?><?php }?>>&nbsp;<?=__('设置为默认收货地址')?></dd>
        </dl>
        <dl class="foot">
            <dt>&nbsp;</dt>
            <dd><input class="save bbc_btns" type="submit" value="<?=__('提交')?>" class="submit"></dd>
        </dl>
    </form>
    </div>
<script type="text/javascript" src="<?=$this->view->js?>/district.js"></script>
<script type="text/javascript">
//$('#location').area('<{$de.area}>');
var data    = eval(<?php echo json_encode($data);?>);
    console.info(data);
    if(data !="")
    {
        console.info(data.user_address_id);
        $("#user_address_id").val(data.user_address_id);
        $("#user_address_contact").val(data.user_address_contact);
        $("#t").val(data.user_address_area);
        $("#user_address_address").val(data.user_address_address);
        $("#user_address_phone").val(data.user_address_phone);     
        $("#id_1").val(data.user_address_province_id);     
        $("#id_2").val(data.user_address_city_id);     
        $("#id_3").val(data.user_address_area_id);     
    }

    $(document).ready(function(){
        var act    = eval('"<?php echo $act;?>"');
        if(act == 'add')
        {
            var ajax_url = SITE_URL+'?ctl=Buyer_User&met=addaddressInfo&typ=json';
        }
        if(act == 'edit')
        {
            var ajax_url = SITE_URL+'?ctl=Buyer_User&met=editaddressInfo&typ=json';
        }

        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                'user_address_contact': 'required;',
				'select_1':'required',
				'select_2':'required',
				'select_3':'required',
                'user_address_area': 'required;',
                'user_address_address':'required;',
                'user_address_phone':'required;mobile',
            },
            valid:function(form){
                //表单验证通过，提交表单
				var me = this;
				// 提交表单之前，hold住表单，防止重复提交
				me.holdSubmit();
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
							Public.tips.success("<?=__('操作成功！')?>");
                            location.href= SITE_URL+"?ctl=Buyer_User&met=address";
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
</div>  
<?php }elseif($act == 'edit_delivery'){?>
</div>
<div class="form-style-layout">
	<h2><?=__('使用代收货（自提）')?></h2>
    <div class="form-style">
    <form id="form" name="form" action="" method="post">
        <input type="hidden" name="user_id" id="user_id" value="<?=$userId ?>" />
        <dl>
            <dt><em>*</em><?=__('地区选择：')?></dt>
            <dd><input id="location" type="hidden" name="location" value="" class="text"></dd>
        </dl>
        <dl>
        	<dt><em>*</em><?=__('自提点选择:')?></dt>
            <dd class="delivery"><input type='hidden' name="delivery"></dd>
        </dl>
        <dl>
            <dt><em>*</em><?=__('收货人：')?></dt>
            <dd><input name="name" value="" class="text"></dd>
        </dl>
        <dl>
            <dt><em>*</em><?=__('联系电话：')?></dt>
            <dd><input type="text" name="contact" class="text" value=""></dd>
        </dl>
        <dl class="foot">
            <dt>&nbsp;</dt>
            <dd><input class="save" type="submit" value="<?=__('提交')?>" class="submit"></dd>
        </dl>
    </form>
    </div>
<script type="text/javascript" src="<?=$this->view->js?>/district.js"></script>
<script type="text/javascript">
//$('#location').area('<{$de.area}>');
var data    = eval(<?php echo json_encode($data);?>);
    console.info(data);
    if(data !="")
    {
        console.info(data.user_address_id);
        $("#user_address_id").val(data.user_address_id);
        $("#user_address_contact").val(data.user_address_contact);
        $("#user_address_area").val(data.user_address_area);
        $("#user_address_address").val(data.user_address_address);
        $("#user_address_phone").val(data.user_address_phone);     
    }

    $(document).ready(function(){     
        var ajax_url = SITE_URL+'?ctl=Buyer_User&met=addaddressDelivery&typ=json';   
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                'user_address_contact': 'required;',
                //'user_address_area': 'required;',
                'user_address_address':'required;',
                'user_address_phone':'required;mobile',
            },
            valid:function(form){
                //表单验证通过，提交表单
				var me = this;
				// 提交表单之前，hold住表单，防止重复提交
				me.holdSubmit();
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
							Public.tips.success("<?=__('操作成功！')?>");
                            location.href= SITE_URL+"?ctl=Buyer_User&met=address";
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
</div>
<?php }else{ ?>
<div class="aright">
      <div class="member_infor_content">
        <div class="tabmenu">
          <ul class="tab">
			<li class="active"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=address"><?=__('收货地址')?></a></li>
			</ul>
</div>
<div class="order_content_title clearfix">
<div class="clearfix" style="margin-top: 10px;">
<div class="ptkf bbc_btns">
 <!-- <a class="bbc_bg" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=address&act=edit_delivery" class="ncbtn ncbtn-bittersweet" style="right: 100px;" nc_type="dialog" dialog_title="<?=__('使用代收货（自提）')?>" dialog_id="daisou" uri="index.php?act=member_address&amp;op=delivery_add" dialog_width="900" title="<?=__('使用自提服务站')?>"><?=__('使用自提服务站')?></a>-->
<a class="" href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=address&act=add" nc_type="dialog" dialog_title="<?=__('新增地址')?>" dialog_id="my_address_edit" uri="index.php?act=member_address&amp;op=address&amp;type=add" dialog_width="550" title="<?=__('新增地址')?>"><?=__('新增地址')?></a>
</div>
</div>
</div>
<table class="ncm-default-table annoc_con">
    <thead>
    <tr class="bortop">
        <th class="w80"><?=__('收货人')?></th>
        <th class="w200"><?=__('所在地区')?></th>
        <th class="w200"><?=__('街道地址')?></th>
        <th class="w120"><?=__('电话/手机')?></th>
        <th class="w100"></th>
        <th class="w110"><?=__('操作')?></th>
    </tr>
    </thead>
    <tbody>
	<?php if(!empty($data)){ ?>
    <?php foreach($data as $key=>$val){?>
    <tr class="bd-line">
        <td><?=$val['user_address_contact']?></td>
        <td><?=$val['user_address_area']?></td>
        <td class="one-overflow w200 mwidth200"><?=$val['user_address_address']?></td>
        <td><?=$val['user_address_phone']?></td>
	<td><?php if($val['user_address_default']=='1'){?><i class="iconfont icon-icoselectturn greenxue" style="font-size: 18px;"></i><?=__('默认地址')?><?php } ?></td>
        <td class="ncm-table-handle">
        <span class="edit"><a  class="btn-bluejeans" dialog_id="my_address_edit" dialog_width="550" dialog_title="<?=__('编辑地址')?>"  href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=address&id=<?=$val['user_address_id'] ?>&act=edit"><i class="iconfont icon-zhifutijiao"></i><p><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=address&id=<?=$val['user_address_id'] ?>&act=edit"><?=__('编辑')?></a></p></a></span>
		<span class="del"><a class="btn-grapefruit" data-param="{'ctl':'Buyer_User','met':'delAddress','id':'<?=$val['user_address_id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><p><a data-param="{'ctl':'Buyer_User','met':'delAddress','id':'<?=$val['user_address_id']?>'}" href="javascript:void(0)"><?=__('删除')?></a></p></a></span>
        </td>
    </tr>
    <?php }?>
    <?php }else{ ?>
    <tr id="list_norecord">
        <td colspan="20" class="norecord">
              <div class="no_account">
				<img src="<?= $this->view->img ?>/ico_none.png"/>
				<p><?=__('暂无符合条件的数据记录')?></p>
			 </div>  	
        </td>
    </tr>
     <?php }?>
    </tbody>
</table>
<?php } ?>
</div>
</div>
</div>
</div>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>