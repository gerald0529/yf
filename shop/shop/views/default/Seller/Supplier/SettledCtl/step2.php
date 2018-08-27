
<?php
include $this->view->getTplPath() . '/' . 'supplier_join_header.php';

?>
<style>.select_2.hidden,.select_3.hidden,.select_4.hidden{display: none;}</style>
<div class="breadcrumb">
	<span class="icon-home iconfont icon-tabhome"></span>
	<span><a href="index.php"><?=__('首页')?></a></span>
	<span class="arrow iconfont icon-btnrightarrow"></span> 
	<span><?=__('供应商入驻申请')?></span>
</div>

<div class="main">
    <div class="sidebar">
		<div class="title">
      <h3><?=__('商家入驻申请')?></h3>
		</div>
		<div class="content">
            <dl show_id="99">
				<dt onclick="show_list('99');" style="cursor: pointer;"> <i class="hide"></i><?=__('入驻流程')?></dt>
				<dd style="display:none;">
					<ul>
                                    <li> <i></i>
                            <a href="" target="_blank"><?=__('签署入驻协议')?></a>
                          </li>
                        <li> <i></i>
                            <a href="" target="_blank"><?=__('商家信息提交')?></a>
                          </li>
                        <li> <i></i>
                            <a href="" target="_blank"><?=__('平台审核资质')?></a>
                          </li>
                        <li> <i></i>
                            <a href="" target="_blank"><?=__('商家缴纳费用')?></a>
                          </li>
                        <li> <i></i>
                            <a href="" target="_blank"><?=__('店铺开通')?></a>
                          </li>
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
            <li class="bbc_bg_col"><i></i><?=__('公司资质信息')?></li>
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
        <li class="current"><span><?=__('公司资质信息')?></span></li>
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
      <input type="hidden" value="<?=$apply?>" name="apply" />
    <table class="all" border="0" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th colspan="2"><?=__('公司及联系人信息')?></th>
        </tr>
						</thead>
						<tbody>
							<tr>
          <th><i>*</i><?=__('公司名称')?>：</th>
          <td><input name="shop_company_name" class="w200" type="text" value="<?php echo isset($shop_company['shop_company_name']) ? $shop_company['shop_company_name'] : '';?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('公司所在地')?>：</th>
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
                <select id="select_3" class="select_2" name="company_2" onChange="district(this);" >
                    
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
          <th><i>*</i><?=__('公司详细地址')?>：</th>
          <td><input name="company_address_detail" class="w200" type="text" value="<?php echo isset($shop_company['company_address_detail']) ? $shop_company['company_address_detail'] : '';?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('公司电话')?>：</th>
          <td><input name="company_phone" class="w100" type="tel" value="<?php echo isset($shop_company['company_phone']) ? $shop_company['company_phone'] : '';?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('员工总数')?>：</th>
          <td><input name="company_employee_count" class="w50" type="number" value="<?php echo isset($shop_company['company_employee_count']) ? $shop_company['company_employee_count'] : '';?>">
            &nbsp;人 <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('注册资金')?>：</th>
          <td><input name="company_registered_capital" class="w50" type="number" value="<?php echo isset($shop_company['company_registered_capital']) ? $shop_company['company_registered_capital'] : '';?>">
            &nbsp;万元<span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('联系人姓名')?>：</th>
          <td><input name="contacts_name" class="w100" type="text" value="<?php echo isset($shop_company['contacts_name']) ? $shop_company['contacts_name'] : '';?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('联系人号码')?>：</th>
          <td><input name="contacts_phone" class="w100 bor1" type="tel" maxlength="11" value="<?php echo isset($shop_company['contacts_phone']) ? $shop_company['contacts_phone'] : '';?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('联系人邮箱')?>：</th>
          <td><input name="contacts_email" class="w200 bor1" type="email" value="<?php echo isset($shop_company['contacts_email']) ? $shop_company['contacts_email'] : '';?>">
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
          <th colspan="20"><?=__('营业执照信息（副本）')?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <th><i>*</i><?=__('是否多证合一')?>：</th>
            <td><input type="radio" name="threeinone" value="1" onclick="is_threeinone(1)" <?php echo !$shop_company['organization_code_electronic'] && !$shop_company['tax_registration_certificate_electronic'] ? 'checked="checked"' : '';?>/><?=__('是')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="threeinone" value="0" onclick="is_threeinone(0)" <?php echo $shop_company['organization_code_electronic'] || $shop_company['tax_registration_certificate_electronic'] ? 'checked="checked"' : '';?>/><?=__('否')?>
              <span class="block"><?=__('如果营业执照、组织机构代码证和税务登记证已经多证合为一证，仅需填写营业执照')?></span>
            </td>
        </tr>
        <tr>
          <th><i>*</i><?=__('营业执照号')?>：</th>
          <td><input name="business_id" class="w200" type="text" value="<?php echo isset($shop_company['business_id']) ? $shop_company['business_id'] : '';?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('营业执照所在地')?>：</th>
          <td>
               <input type="hidden" name="business_license_location" class="t" value="<?php echo isset($shop_company['business_license_location']) ? $shop_company['business_license_location'] : '';?>" />	
              <div id="d_2">
            
            <select id="select_1" name="yingye_1" onChange="district(this);" class="select_1">
                <option value="">--<?=__('请选择')?>--</option>
                <?php foreach($district['items'] as $key=>$val){ ?>
                <option value="<?=$val['district_id']?>|1" <?php if(isset($yingye_district_info['district_info'][0]['district_id']) && $val['district_id'] == $yingye_district_info['district_info'][0]['district_id']){echo 'selected="selected"';} ?> ><?=$val['district_name']?></option>
                <?php } ?>
            </select>

            <?php if(isset($yingye_district_info['district_list'][2])){?>
            <select id="select_2" class="select_2" name="yingye_2" onChange="district(this);" >

                    <?php foreach($yingye_district_info['district_list'][2] as $key=>$val){ ?>
                    <option value="<?php echo $val['district_id'];?>|2" <?php if(isset($yingye_district_info['district_info'][1]['district_id']) && $val['district_id'] == $yingye_district_info['district_info'][1]['district_id']){echo 'selected="selected"';} ?> > <?=$val['district_name']?></option>
                    <?php } ?>
            </select>
            <?php }else{ ?>
                <select id="select_2" name="yingye_2" onChange="district(this);" class="select_2 hidden"></select>
            <?php } ?>

            <?php if(isset($yingye_district_info['district_list'][3])){?>
            <select id="select_3" class="select_3" name="yingye_3" onChange="district(this);" >

                    <?php foreach($yingye_district_info['district_list'][3] as $key=>$val){ ?>
                    <option value="<?php echo $val['district_id'];?>|2" <?php if(isset($yingye_district_info['district_info'][2]['district_id']) && $val['district_id'] == $yingye_district_info['district_info'][2]['district_id']){echo 'selected="selected"';} ?> > <?=$val['district_name']?></option>
                    <?php } ?>
            </select>
            <?php }else{ ?>
                <select id="select_3" name="yingye_3" onChange="district(this);" class="select_3 hidden"></select>
            <?php } ?>
            
		</div>
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('营业执照有效期')?>：</th>
          <td><input readonly="readonly"  id="start_time"  name="business_licence_start"  class="w90 hasDatepicker" type="text" value="<?php echo isset($shop_company['business_licence_start']) ? $shop_company['business_licence_start'] : '';?>"><em><i class="iconfont icon-rili"></i></em>
            <span></span>-
            <input readonly="readonly" id="end_time" name="business_licence_end"    class="w90 hasDatepicker" type="text" value="<?php echo isset($shop_company['business_licence_end']) ? $shop_company['business_licence_end'] : '';?>"><em><i class="iconfont icon-rili"></i></em>
             <span class="block"><?=__('结束时间不填代表永久')?>。</span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('营业执照电子版')?>：</th>
          <td><input  style="float:left;"id="business_logo" readonly="readonly"  name="business_license_electronic" type="text" class="mr10" value="<?php echo isset($shop_company['business_license_electronic']) ? $shop_company['business_license_electronic'] : '';?>">   <p style="float:left; width:70px" id="business_upload" ><?=__('上传图片')?></p>
            <span class="block"><?=__('请确保图片清晰，文字可辨并有清晰的红色公章。')?></span>
            </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table class="all threeinone_table" border="0" cellpadding="0" cellspacing="0" <?php echo !$shop_company['organization_code_electronic'] && !$shop_company['tax_registration_certificate_electronic'] ? 'style="display:none;"' : '';?>>
      <thead>
        <tr>
          <th colspan="20"><?=__('组织机构代码证')?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th><i>*</i><?=__('组织机构代码')?>：</th>
          <td><input name="organization_code" class="w200" type="text" value="<?php echo isset($shop_company['organization_code']) ? $shop_company['organization_code'] : '';?>">
            <span></span></td>
        </tr>
        <tr>
          <th><i>*</i><?=__('组织机构代码证电子版')?>：</th>
          

          <td><input  style="float:left;"id="organization_logo" readonly="readonly" name="organization_code_electronic" type="text" class="mr10" value="<?php echo isset($shop_company['organization_code_electronic']) ? $shop_company['organization_code_electronic'] : '';?>">   <p style="float:left; width:70px" id="organization_upload" ><?=__('上传图片')?></p>
            <span class="block"><?=__('请确保图片清晰，文字可辨并有清晰的红色公章。')?></span>
            </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
    <table class="all threeinone_table" border="0" cellpadding="0" cellspacing="0" <?php echo !$shop_company['organization_code_electronic'] && !$shop_company['tax_registration_certificate_electronic'] ? 'style="display:none;"' : '';?>>
        <thead>
          <tr>
            <th colspan="20"><?=__('税务登记证')?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th><i>*</i><?=__('纳税人识别号')?>：</th>
            <td><input name="taxpayer_id" class="w200" type="text" value="<?php echo isset($shop_company['taxpayer_id']) ? $shop_company['taxpayer_id'] : '';?>">
              <span></span></td>
          </tr>
          <tr>
            <th><i>*</i><?=__('税务登记证号')?>：</th>
            <td><input name="tax_registration_certificate" class="w200" type="text" value="<?php echo isset($shop_company['tax_registration_certificate']) ? $shop_company['tax_registration_certificate'] : '';?>">
              <span></span></td>
          </tr>
          <tr>
            <th><i>*</i><?=__('税务登记证号电子版')?>：</th>

            <td><input class="text w250 mr10" style="float: left;" id="tax_registration_certificate_electronic" name="tax_registration_certificate_electronic" readonly="readonly" type="text" value="<?php echo isset($shop_company['tax_registration_certificate_electronic']) ? $shop_company['tax_registration_certificate_electronic'] : '';?>">
            <p style="float:left; width:70px"  id="tax_registration_certificate_upload" ><i class="iconfont icon-upload-alt"></i><?=__('图片上传')?></p>
            <p  style="clear:both" class="hint"><?=__('请确保图片清晰，文字可辨并有清晰的红色公章。')?></p>
              </td>
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
          <th colspan="20"><?=__('其他证明')?><em><?=__('注：食品安全证等，此项为非必填。')?></em></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150"><?=__('其他证明1')?>：</th>
            <?php @$other_image = explode(',',$shop_company['company_apply_image']); ?>
            <td><input  style="float:left;"id="other_image1" readonly="readonly" name="other_image[]" type="text" class="mr10" value="<?=@$other_image[0]?>">   <p style="float:left; width:70px;height:30px !important;" id="other_upload1" ><?=__('上传图片')?></p>
            <span class="block"><?=__('请确保图片清晰，文字可辨并有清晰的红色公章。')?></span>
            </td>
        </tr>
        <tr>
          <th class="w150"><?=__('其他证明2：')?></th>

            <td><input  style="float:left;"id="other_image2" readonly="readonly" name="other_image[]" type="text" class="mr10" value="<?=@$other_image[1]?>">   <p style="float:left; width:70px;height:30px !important;" id="other_upload2" ><?=__('上传图片')?></p>
            <span class="block"><?=__('请确保图片清晰，文字可辨并有清晰的红色公章。')?></span>
            </td>
        </tr>
        <tr>
          <th class="w150"><?=__('其他证明3')?>：</th>

            <td><input  style="float:left;"id="other_image3" readonly="readonly" name="other_image[]" type="text" class="mr10" value="<?=@$other_image[2]?>">   <p style="float:left; width:70px;height:30px !important;" id="other_upload3" ><?=__('上传图片')?></p>
            <span class="block"><?=__('请确保图片清晰，文字可辨并有清晰的红色公章。')?></span>
            </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>
</form>
    
    <div class="bottom"><?php if(!isset($shop_company['shop_id'])){?><a  href="<?= Yf_Registry::get('base_url')?>/index.php?ctl=Seller_Supplier_Settled&met=index&op=step1&rp=step1&apply=2" class="btn bbc_btns"><?=__('上一步')?></a>&nbsp;&nbsp;&nbsp;<?php } ?><a id="btn_apply_company_next" href="javascript:;" class="btn bbc_btns"><?=__('下一步，提交财务资质信息')?></a></div>
</div>
<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css?ver=<?= VER ?>" rel="stylesheet"
          type="text/css">
<script type="text/javascript" src="<?=$this->view->js?>/districtTow.js"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.datetimepicker.js"></script>
<script type="text/javascript">
$(document).ready(function(){
         $('#start_time').datetimepicker({
            controlType: 'select',
            format:"Y-m-d",
            timepicker:false,
            
		});

        $('#end_time').datetimepicker({
            controlType: 'select',
             format:"Y-m-d",
             timepicker:false,
             onShow:function( ct ){
			this.setOptions({
				minDate:($('#start_time').val())
				})
			}
        });
 
    
         var ajax_url = "./index.php?ctl=Seller_Supplier_Settled&met=addShopCompany&typ=json&apply=<?=$apply?>"; 
        $('#form_company_info').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
               tel:[/^[1][0-9]{10}$/,'<?=__('请输入正确的手机号码')?>'],
               zjtel:[/(^0\d{2,3}[-]?\d{5,9}$)|(^[1][0-9]{10}$)/,'<?=__('请输入正确的电话号码')?>'],
               daima:[/^[a-zA-Z0-9]{8}-[a-zA-Z0-9]$/,'<?=__('请输入正确的组织机构代码')?>'],
               times:function(element, params){
                     var start_time = $('#start_time').val();
                     var end_time = $('#end_time').val();
                   if(start_time>end_time && end_time){
                       return '<?=__('不能小于起始时间')?>';
                   }
               }
            },

            fields: {
                'shop_company_name': 'required;',
                'shop_company_address':'required;length[1~40]',
                'company_address_detail':'required;',
                'company_phone':'required;zjtel',
                'company_employee_count':'required;integer[+0]',
                'company_registered_capital':'required;range[10~]',
                'contacts_name':'required;', 
                'contacts_phone':'required;tel', 
                'contacts_email':'required;email', 
                'business_id':'required;', 
                'business_license_location':'required;',
                'business_licence_start':'required;', 
                'business_licence_end':'times;', 
                 'business_license_electronic':'required;', 
                'organization_code':'required;', 
                'organization_code_electronic':'required;',
                'taxpayer_id':'required;', 
                'tax_registration_certificate':'required;', 
                'tax_registration_certificate_electronic':'required;', 
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
		$("#form_company_info").submit();
});
function is_threeinone($value){
    if($value == 1){
        $("input[name='organization_code']").val('');
        $("input[name='organization_code_electronic']").val('');
        $("input[name='taxpayer_id']").val('');
        $("input[name='tax_registration_certificate']").val('');
        $("input[name='tax_registration_certificate_electronic']").val('');
        
        $('.threeinone_table').hide();
    }else{
        $('.threeinone_table').show();
    }
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
 <script>
	function show_d(type)
	{
		if(type == 'shop_company_address')
		{
			$('.shop_company_address').attr('class','hidden');
			$('#d_2').removeClass('hidden');
			$('#shop_company_address').val('');
		}else{
			$('.business_license_location').attr('class','hidden');
			$('#d_3').removeClass('hidden');
			$('#business_license_location').val('');
		}
	}
    //图片上传
    $(function(){

        business_uploads = new UploadImage({
            uploadButton: '#business_upload',
            inputHidden: '#business_logo'
        });

        organization_upload = new UploadImage({
            uploadButton: '#organization_upload',
            inputHidden: '#organization_logo'
        });
        
        general_upload = new UploadImage({
            uploadButton: '#general_upload',
            inputHidden: '#general_logo'
        });
		
		other_upload1 = new UploadImage({
            uploadButton: '#other_upload1',
            inputHidden: '#other_image1'
        });
		other_upload2 = new UploadImage({
            uploadButton: '#other_upload2',
            inputHidden: '#other_image2'
        });
		other_upload3 = new UploadImage({
            uploadButton: '#other_upload3',
            inputHidden: '#other_image3'
        });
    })
</script>
    </div>
  </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>