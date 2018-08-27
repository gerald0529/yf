
<?php
include $this->view->getTplPath() . '/' . 'supplier_join_header.php';
?>



<style>.select_2.hidden,.select_3.hidden,.select_4.hidden{display: none;}</style>
<div class="breadcrumb"><span class="icon-home iconfont icon-tabhome"></span><span><a href="index.php"><?=__('首页')?></a></span> <span class="arrow iconfont icon-btnrightarrow"></span> <span><?=$apply_tips['0']?></span> </div>
<div class="main">
    <div class="sidebar">
    <div class="title">
      <h3><?=__('个人入驻申请')?></h3>
    </div>
    <div class="content">
                  <dl show_id="99">
        <dt onclick="show_list('99');" style="cursor: pointer;"> <i class="hide"></i><?=__('入驻流程')?></dt>
        <dd style="display:none;">
        <ul>
            <li> <i></i> <a href="" target="_blank"><?=__('签署入驻协议')?></a> </li>
            <li> <i></i> <a href="" target="_blank"><?=__('个人信息提交')?></a> </li>
            <li> <i></i> <a href="" target="_blank"><?=__('平台审核资质')?></a> </li>
            <li> <i></i> <a href="" target="_blank"><?=__('个人缴纳费用')?></a> </li>
            <li> <i></i> <a href="" target="_blank"><?=__('店铺开通')?></a> </li>
          </ul>
        </dd>
      </dl>
                  <dl>
        <dt class=""> <i class="hide"></i><?=__('签订入驻协议')?></dt>
      </dl>
      <dl show_id="0">
        <dt onclick="show_list('0');" style="cursor: pointer;"> <i class="show"></i><?=__('提交申请')?></dt>
        <dd>
          <ul>
            <li class="bbc_bg_col"><i></i><?=__('个人实名信息')?></li>
            <li class=""><i></i><?=__('财务资质信息')?></li>
            <li class=""><i></i><?=__('店铺经营信息')?></li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt class=""> <i class="hide"></i><?=__('合同签订及缴费')?></dt>
      </dl>
      <dl>
        <dt> <i class="hide"></i><?=__('店铺开通')?></dt>
      </dl>
    </div>

        <div class="title">
            <h3><?=__('平台联系方式')?></h3>
        </div>
        <div class="content">
            <ul>
                <?php
                $phone = Web_ConfigModel::value("setting_phone");
                if ($phone)
                {
                    $phone = explode(',', $phone);//电话
                }
                ?>
                <?php foreach($phone as $k=>$v){?>
                    <li><?=__('电话')?>：<?=$v;?></li>
                <?php }?>

                <li><?=__('邮箱')?>：<?=Web_ConfigModel::value('setting_email')?></li>
            </ul>
        </div>

  </div>
    
    
  <div class="right-layout">
    <div class="joinin-step">
      <ul>
        <li class="step1 current"><span><?=__('签订入驻协议')?></span></li>
        <li class="current"><span><?=__('个人实名信息')?></span></li>
        <li class=""><span><?=__('财务资质信息')?></span></li>
        <li class=""><span><?=__('店铺经营信息')?></span></li>
        <li class=""><span><?=__('合同签订及缴费')?></span></li>
        <li class="step6"><span><?=__('店铺开通')?></span></li>
      </ul>
    </div>
    <div class="joinin-concrete">

<!-- 公司信息 -->

<div id="apply_company_info" class="apply-company-info">
  <div class="alert">
    <h4><?=__('注意事项')?>：</h4>
    <?=__('以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。')?>
    <br/>
    <span style="color:red;"><?php echo isset($shop_company['shop_verify_reason']) && $shop_company['shop_status'] == 4 ? $shop_company['shop_verify_reason'] : '';?></span>
  </div>
  <form id="form_company_info"  method="post">
    <table class="all" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th colspan="2"><?=__('个人实名信息')?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
          <th><i>*</i><?=__('手机号码')?>：</th>
          <td><input name="contacts_phone" class="w200 bor1" type="tel" maxlength="11" id="contacts_phone" value="<?php echo isset($shop_company['contacts_phone']) ? $shop_company['contacts_phone'] : '';?>" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('图形验证码')?>：</th>
          <td>
              <input type="text"  name="img_yzm" id="img_yzm" maxlength="6" class="w200" placeholder="请输入验证码" default="<i class=&quot;i-def&quot;></i>看不清？点击图片更换验证码" />
              <img onClick="get_randfunc(this);" title="换一换" class="img-code" style="cursor:pointer;" src='./libraries/rand_func.php'/>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('手机验证码')?>：</th>
          <td><input name="phone_verify" class="w200" type="text" maxlength="6">
            <span></span><input type="button" onclick="getyzm('phone')" data-type="phone" value="<?=__('获取手机验证码')?>" id ='phone_yzm_bn'/></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('邮箱')?>：</th>
          <td><input name="contacts_email" class="w200 bor1" type="email" id='contacts_email' value="<?php echo isset($shop_company['contacts_email']) ? $shop_company['contacts_email'] : '';?>" />
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('邮箱验证码')?>：</th>
          <td><input name="email_verify" class="w200" type="text">
            <span></span><input type="button" onclick="getyzm('email')" data-type="email" value="<?=__('获取邮箱验证码')?>" id ='email_yzm_bn' /></td>
        </tr>
        <tr>
            <th><i>*</i><?=__('店铺所在地')?>：</th>
            <td>
                <input type="hidden" name="shop_company_address" class="t" value="<?php echo isset($shop_company['shop_company_address']) ? $shop_company['shop_company_address'] : '';?>" />	
            <input type="hidden" name="province_id" class="id_1" value="<?php echo isset($district_info['district_info'][0]['district_id']) ? $district_info['district_info'][0]['district_id'] : '';?>" />
            <input type="hidden" name="city_id" class="id_2" value="<?php echo isset($district_info['district_info'][1]['district_id']) ? $district_info['district_info'][1]['district_id'] : '';?>" />
            <input type="hidden" name="area_id" class="id_3" value="<?php echo isset($district_info['district_info'][2]['district_id']) ? $district_info['district_info'][2]['district_id'] : '';?>" />
            <input type="hidden" name="street_id" class="id_4" value="<?php echo isset($district_info['district_info'][3]['district_id']) ? $district_info['district_info'][3]['district_id'] : '';?>" />
            <div id="d_2">
                <select id="select_1" class="select_1" name="company_1" onChange="district(this);">
                <option value="">--<?=__('请选择')?>--</option>
                <?php foreach($district['items'] as $key=>$val){ ?>
                <option value="<?php echo $val['district_id'];?>|1" <?php if(isset($district_info['district_info'][0]['district_id']) && $val['district_id'] == $district_info['district_info'][0]['district_id']){echo 'selected="selected"';} ?> > <?=$val['district_name']?></option>
                <?php } ?>
                </select>
                <?php if(isset($district_info['district_list'][2])){?>
                <select id="select_2" class="select_2" name="company_2" onChange="district(this);" >
                    
                        <?php foreach($district_info['district_list'][2] as $key=>$val){ ?>
                        <option value="<?php echo $val['district_id'];?>|2" <?php if(isset($district_info['district_info'][1]['district_id']) && $val['district_id'] == $district_info['district_info'][1]['district_id']){echo 'selected="selected"';} ?> > <?=$val['district_name']?></option>
                        <?php } ?>
                </select>
                <?php }else{ ?>
                    <select id="select_2" class="select_2 hidden" name="company_2" onChange="district(this);" ></select>
                <?php } ?>
                <?php if(isset($district_info['district_list'][3])){?>
                <select id="select_3" class="select_3" name="company_3" onChange="district(this);" >
                    
                        <?php foreach($district_info['district_list'][3] as $key=>$val){ ?>
                        <option value="<?php echo $val['district_id'];?>|3" <?php if(isset($district_info['district_info'][2]['district_id']) && $val['district_id'] == $district_info['district_info'][2]['district_id']){echo 'selected="selected"';} ?> > <?=$val['district_name']?></option>
                        <?php } ?>
                  
                </select>
                <?php }else{ ?>
                    <select id="select_3" class="select_3 hidden" name="company_3" onChange="district(this);" ></select>
                <?php } ?>
                <?php if(isset($district_info['district_list'][4])){?>
                <select id="select_4" class="select_4" name="company_4" onChange="district(this);" >
                    
                        <?php foreach($district_info['district_list'][4] as $key=>$val){ ?>
                        <option value="<?php echo $val['district_id'];?>|4" <?php if(isset($district_info['district_info'][3]['district_id']) && $val['district_id'] == $district_info['district_info'][3]['district_id']){echo 'selected="selected"';} ?> > <?=$val['district_name']?></option>
                        <?php } ?>
                </select>
                <?php }else{ ?>
                    <select id="select_4" class="select_4 hidden" name="company_4" onChange="district(this);" ></select>
                <?php } ?>
                
            </div>
            <span></span></td>
        </tr>
        <tr>
            <th><i>*</i><?=__('详细地址')?>：</th>
            <td><input name="company_address_detail" value="<?php echo isset($shop_company['company_address_detail']) ? $shop_company['company_address_detail'] : '';?>" class="w200" type="text">
            <span></span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table class="all" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th colspan="20"><?=__('证件信息')?></th>
            <th><a href="<?=Yf_Registry::get('paycenter_api_url')?>?ctl=Info&met=account&typ=e"><?=__('去网付宝修改证件信息')?></a></th>
        </tr>
      </thead>
      <tbody>
          <tr>
              <th><i>*</i><?=__('真实姓名')?>：</th>
              <td><?php echo isset($user_info['user_realname']) ? $user_info['user_realname'] : '';?>
                  <input name="contacts_name" class="w200" type="hidden" id="contacts_name" value="<?php echo isset($user_info['user_realname']) ? $user_info['user_realname'] : '';?>" />
                  <span></span></td>
          </tr>
        <tr>
          <th><i>*</i><?=__('证件类型')?>：</th>
            <td>
                <?php
                if(isset($user_info['user_identity_type']))
                {
                    switch($user_info['user_identity_type']){
                        case 1:
                            echo __('身份证');
                            break;
                        case 2:
                            echo __('护照');
                            break;
                        case 3:
                            echo __('军官证');
                            break;
                    }
                }
                ?>
                <input type="hidden" name="legal_identity_type" id="legal_identity_type" value="<?php echo isset($user_info['user_identity_type']) ? $user_info['user_identity_type'] : '';?>">
            </td>
        </tr>
        <tr>
          <th><i>*</i><?=__('证件号码')?>：</th>
          <td><?php echo isset($user_info['user_identity_card']) ? $user_info['user_identity_card'] : '';?>
              <input name="business_id" class="w200" type="hidden" id="business_id" value="<?php echo isset($user_info['user_identity_card']) ? $user_info['user_identity_card'] : '';?>" />
            <span></span></td>
        </tr>
        <tr>
            <th><i>*</i><?=__('正面照预览：')?></th>
            <td>
            <div class="user-avatar"><span><img  id="image_img"  src="<?php echo isset($user_info['user_identity_face_logo']) ? $user_info['user_identity_face_logo'] : '';?>" width="300" height="200" nc_type="avatar"></span></div>
            <input name="user_identity_face_logo" id="user_identity_face_logo" type="hidden" value="<?php echo isset($user_info['user_identity_face_logo']) ? $user_info['user_identity_face_logo'] : '';?>" />
            </td>
        </tr>
        <tr>
          <th><i>*</i><?=__('反面照预览')?>：</th>
          <td>
            <div class="user-avatar"><span><img  id="image_font_img"  src="<?php echo isset($user_info['user_identity_font_logo']) ? $user_info['user_identity_font_logo'] : '';?>" width="300" height="200" nc_type="avatar"></span></div>
            <input name="user_identity_font_logo" id="user_identity_font_logo" type="hidden" value="<?php echo isset($user_info['user_identity_font_logo']) ? $user_info['user_identity_font_logo'] : '';?>" />
            </td>
        </tr>
        <tr>
          <th><i>*</i><?=__('证件有效期')?>：</th>
          <td>
              <input readonly="readonly"  id="start_time"  name="business_licence_start"  class="w90 hasDatepicker" type="text" value="<?php echo (isset($user_info['user_identity_start_time']) &&$user_info['user_identity_start_time'] > 0) ? $user_info['user_identity_start_time'] : '';?>" /><em><i class="iconfont icon-rili"></i></em>
            <span></span>-
            <input readonly="readonly" id="end_time" name="business_licence_end"  class="w90 hasDatepicker" type="text" value="<?php echo (isset($user_info['user_identity_end_time']) && $user_info['user_identity_end_time'] > 0) ? $user_info['user_identity_end_time'] : '';?>" /><em><i class="iconfont icon-rili"></i></em>
            <span class="block"><?=__('结束时间不填代表永久。')?></span></td>
        </tr>

      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
</form>
    
    <div class="bottom"><?php if(!isset($shop_company['shop_id'])){?><a href="<?= Yf_Registry::get('base_url')?>/index.php?ctl=Seller_Supplier_Settled&met=index&op=step1&rp=step1&apply=1" class="btn bbc_btns"><?=__('上一步')?></a>&nbsp;&nbsp;&nbsp;<?php } ?><a id="btn_apply_company_next" href="javascript:;" class="btn bbc_btns"><?=__('下一步，提交财务资质信息')?></a></div>
</div>
<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js?>/districtTow.js"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
$(document).ready(function(){
    var ajax_url = './index.php?ctl=Seller_Supplier_Settled&met=addShopCompany&typ=json&apply=<?=$apply?>';
    $('#form_company_info').validator({
        ignore: ':hidden',
        theme: 'yellow_right',
        timely: 1,
        stopOnError: false,
        rules: {
            tel:[/^[1][0-9]{10}$/,'<?=__('请输入正确的手机号码')?>'],
            times:function(element, params){
                var start_time = $('#start_time').val();
                var end_time = $('#end_time').val();
                if(start_time>end_time && end_time){
                    return '<?=__('不能小于起始时间')?>';
                }
            },
            check_identity_card:function(element, params){
                var legal_identity_type = $('#legal_identity_type option:selected').val();
                var business_id = $('#business_id').val();
                if((legal_identity_type == 1 && !checkCard(business_id)) || !business_id){
                    return "<?=__('身份证号格式有误')?>";
                }
            }
        },

        fields: {
            'shop_company_address':'required;length[1~40]',
            'company_address_detail':'required;',
            'company_phone':'required;zjtel',
            'contacts_name':'required;', 
            'contacts_phone':'required;tel', 
            'contacts_email':'required;email', 
            'business_id':'required;check_identity_card;', 
            'business_licence_start':'required;', 
            'business_licence_end':'times;', 
            'general_taxpayer':'required;', 
            'yingye_1':'required;',
            'yingye_2':'required;',
            'yingye_3':'required;',
            'company_1':'required;',
            'company_2':'required;',
            'company_3':'required;',
            'company_4':'required;'
        },
        valid:function(form){
             //表单验证通过，提交表单
             $.ajax({
                 url: ajax_url,
                 data:$("#form_company_info").serialize(),
                 success:function(a){
                     if(a.status == 200)
                     {
                        location.href="./index.php?ctl=Seller_Supplier_Settled&met=index&op=step3&apply=<?=$apply?>";
                     }
                     else
                     {
                        if(typeof(a.msg) == 'undefined' || !a.msg){
                            Public.tips.error("<?=__('操作失败')?>"); 
                        }else{
                            Public.tips.error(a.msg); 
                        }
                        return false;
                     }
                 }
             });
         }
    });
})
$('#btn_apply_company_next').click(function() {
    var pars = $("#form_company_info").submit();
});

//图片上传
$(function(){
    $('#user_upload').on('click', function () {
        $.dialog({
            title: "<?=__('图片裁剪')?>",
            content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
            data: { width: 85.6, height: 54, callback: callback },    // 需要截取图片的宽高比例
            width: '800px',
            /*height: '310px',*/
            lock: true
        })
    });

    function callback ( respone , api ) {
        $('#image_img').attr('src', respone.url);
        $('#user_identity_face_logo').attr('value', respone.url);
        api.close();
    }

})
//图片上传
$(function(){
    $('#user_font_upload').on('click', function () {
        $.dialog({
            title: "<?=__('图片裁剪')?>",
            content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
            data: { width: 85.6, height: 54, callback: callback },    // 需要截取图片的宽高比例
            width: '800px',
            /*height: '310px',*/
            lock: true
        })
    });

    function callback ( respone , api ) {
        $('#image_font_img').attr('src', respone.url);
        $('#user_identity_font_logo').attr('value', respone.url);
        api.close();
    }
})


var icon = '<i class="iconfont icon-exclamation-sign"></i>';
function getyzm(type){
    var img_yzm = $('#img_yzm').val();
	$("label.error").remove();
    if(type === 'phone'){
        var types = "<?=__('手机号码有误')?>"
        var obj = $("#contacts_phone");
        var val = obj.val();
        var patrn = /^1\d{10}$/;
        pars = 'mobile='+val+'&yzm='+img_yzm;
    }
    if(type === 'email'){
        var types = "<?=__('邮箱格式错误')?>"
        var obj = $("#contacts_email");
        var val = obj.val()+'&yzm='+img_yzm;
        var patrn =  /\w+[@]{1}\w+[.]\w+/;
        
        pars = 'email='+val;
    }
	if(!val){
        return false;
	}else if(!patrn.test(val)){  
        alert(types);
        return false;
	}else{
		var url = SITE_URL +'?ctl=Login&met=getYzm&typ=json';
		$.post(url, pars, function (data)
		{
			if(data && 200 == data.status){
                if(type === 'phone'){
                    $("#phone_yzm_bn").attr("disabled", "disabled");
                    $("#phone_yzm_bn").attr("readonly", "readonly");
                    $("#contacts_phone").attr("readonly", "readonly");
                    t = setTimeout(phone_yzm_count,1000);
                }
				if(type === 'email'){
                    $("#email_yzm_bn").attr("disabled", "disabled");
                    $("#email_yzm_bn").attr("readonly", "readonly");
                    $("#contacts_email").attr("readonly", "readonly");
                    t = setTimeout(email_yzm_count,1000);
                }
			}else{		
                $('.img-code').click();
                $('#img_yzm').val('');
				alert(data.msg);
                return false;
			}
		},'json');
	}
}
var delayTimeEmail = 60;
function email_yzm_count()
{
	delayTimeEmail--;
	$("#email_yzm_bn").val(delayTimeEmail + "<?=__('秒后重新获取')?>");
	if (delayTimeEmail == 0) {
		delayTimeEmail = 60;
		$("#email_yzm_bn").val("<?=__('获取邮箱验证码')?>");
		$("#email_yzm_bn").removeAttr("disabled");
		$("#email_yzm_bn").removeAttr("readonly");
		$("#contacts_email").removeAttr("disabled");
		$("#contacts_email").removeAttr("readonly");
		clearTimeout(t_email);
	}
	else
	{
		t_email=setTimeout(email_yzm_count,1000);
	}
}
var delayTime = 60;
function phone_yzm_count()
{
	delayTime--;
	$("#phone_yzm_bn").val(delayTime + "<?=__('秒后重新获取')?>");
	if (delayTime == 0) {
		delayTime = 60;
		$("#phone_yzm_bn").val("<?=__('获取手机验证码')?>");
		$("#phone_yzm_bn").removeAttr("disabled");
		$("#phone_yzm_bn").removeAttr("readonly");
		$("#contacts_phone").removeAttr("disabled");
		$("#contacts_phone").removeAttr("readonly");
		clearTimeout(t);
	}
	else
	{
		t=setTimeout(phone_yzm_count,1000);
	}
}


var vcity={ 11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",
    21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",
    33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",
    42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",
    51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",
    63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"
};
checkCard = function(obj)
{
    //var card = document.getElementById('card_no').value;
    //是否为空
    // if(card === '')
    // {
    //  return false;
    //}
    //校验长度，类型
    if(isCardNo(obj) === false)
    {
        return false;
    }
    //检查省份
    if(checkProvince(obj) === false)
    {
        return false;
    }
    //校验生日
    if(checkBirthday(obj) === false)
    {
        return false;
    }
    //检验位的检测
    if(checkParity(obj) === false)
    {
        return false;
    }
    return true;
};
//检查号码是否符合规范，包括长度，类型
isCardNo = function(obj)
{
    //身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X
    var reg = /(^\d{15}$)|(^\d{17}(\d|X)$)/;
    if(reg.test(obj) === false)
    {
        return false;
    }
    return true;
};
//取身份证前两位,校验省份
checkProvince = function(obj)
{
    var province = obj.substr(0,2);
    if(vcity[province] == undefined)
    {
        return false;
    }
    return true;
};
//检查生日是否正确
checkBirthday = function(obj)
{
    var len = obj.length;
    //身份证15位时，次序为省（3位）市（3位）年（2位）月（2位）日（2位）校验位（3位），皆为数字
    if(len == '15')
    {
        var re_fifteen = /^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/;
        var arr_data = obj.match(re_fifteen);
        var year = arr_data[2];
        var month = arr_data[3];
        var day = arr_data[4];
        var birthday = new Date('19'+year+'/'+month+'/'+day);
        return verifyBirthday('19'+year,month,day,birthday);
    }
    //身份证18位时，次序为省（3位）市（3位）年（4位）月（2位）日（2位）校验位（4位），校验位末尾可能为X
    if(len == '18')
    {
        var re_eighteen = /^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/;
        var arr_data = obj.match(re_eighteen);
        var year = arr_data[2];
        var month = arr_data[3];
        var day = arr_data[4];
        var birthday = new Date(year+'/'+month+'/'+day);
        return verifyBirthday(year,month,day,birthday);
    }
    return false;
};
//校验日期
verifyBirthday = function(year,month,day,birthday)
{
    var now = new Date();
    var now_year = now.getFullYear();
    //年月日是否合理
    if(birthday.getFullYear() == year && (birthday.getMonth() + 1) == month && birthday.getDate() == day)
    {
        //判断年份的范围（3岁到100岁之间)
        var time = now_year - year;
        if(time >= 0 && time <= 130)
        {
            return true;
        }
        return false;
    }
    return false;
};
//校验位的检测
checkParity = function(obj)
{
    //15位转18位
    obj = changeFivteenToEighteen(obj);
    var len = obj.length;
    if(len == '18')
    {
        var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        var cardTemp = 0, i, valnum;
        for(i = 0; i < 17; i ++)
        {
            cardTemp += obj.substr(i, 1) * arrInt[i];
        }
        valnum = arrCh[cardTemp % 11];
        if (valnum == obj.substr(17, 1))
        {
            return true;
        }
        return false;
    }
    return false;
};
//15位转18位身份证号
changeFivteenToEighteen = function(obj)
{
    if(obj.length == '15')
    {
        var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        var cardTemp = 0, i;
        obj = obj.substr(0, 6) + '19' + obj.substr(6, obj.length - 6);
        for(i = 0; i < 17; i ++)
        {
            cardTemp += obj.substr(i, 1) * arrInt[i];
        }
        obj += arrCh[cardTemp % 11];
        return obj;
    }
    return obj;
};
//点击验证码
function get_randfunc(obj)
{
    var sj = new Date();
    url = obj.src;
    obj.src = url + '?' + sj;
}
</script> 
 <script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
 <script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?= VER ?>" rel="stylesheet"
          type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js"
            charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js"
            charset="utf-8"></script>

    </div>
  </div>
</div>







<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
