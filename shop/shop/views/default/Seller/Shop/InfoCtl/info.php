<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
          <link href="<?= $this->view->css ?>/seller_center.css?ver=<?=VER?>" rel="stylesheet">


<div class="tabmenu">
	<ul>
  	<li ><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=category"><?=__('经营类目')?></a></li>
    <?php if($shop['shop_self_support']=="false"){ ?> 
    <li class="active bbc_seller_bg"><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=info"><?=__('店铺信息')?></a></li>
    <li ><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=renew"><?=__('续签申请')?></a></li>
    <?php } ?>
    <li><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=createQrCode"><?=__('收款码')?></a></li>
  </ul>
</div>
<div>

    <?php 
    if($data){
    foreach ($data['base'] as $key => $value) {
        
   
    ?>

<?php if($shop['shop_business'] == 1){ ?> 
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20"><?=__('公司及联系人信息')?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150"><?=__('公司名称：')?></th>
        <td colspan="20"><?=$value['shop_company_name']?></td>
      </tr>
      <tr>
        <th><?=__('公司所在地：')?></th>
        <td><?=$value['shop_company_address']?></td>
        <th><?=__('公司详细地址：')?></th>
        <td colspan="20"><?=$value['company_address_detail']?></td>
      </tr>
      <tr>
        <th><?=__('公司电话：')?></th>
        <td><?=$value['company_phone']?></td>
        <th><?=__('员工总数：')?></th>
        <td><?=$value['company_employee_count']?>&nbsp;<?=__('人')?></td>
        <th><?=__('注册资金：')?></th>
        <td><?=$value['company_registered_capital']?>&nbsp;<?=__('万元')?> </td>
      </tr>
      <tr>
        <th><?=__('联系人姓名：')?></th>
        <td><?=$value['contacts_name']?></td>
        <th><?=__('联系人电话：')?></th>
        <td><?=$value['contacts_phone']?></td>
        <th><?=__('电子邮箱：')?></th>
        <td><?=$value['contacts_email']?></td>
        <th><?=__('所在地区：')?></th>
        <td><?=$value['bank_address']?></td>

      </tr>
    </tbody>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20"><?=__('营业执照信息（副本）')?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150"><?=__('营业执照号：')?></th>
        <td><?=$value['business_id']?></td>
      </tr>
      <tr>
        <th><?=__('营业执照所在地：')?></th>
        <td><?=$value['business_license_location']?></td>
      </tr>
      <tr>
        <th><?=__('营业执照有效期：')?></th>
        <td> <?=$value['business_licence_start']?> - <?=$value['business_licence_end']?></td>
      </tr>
      <tr>
        <th><?=__('营业执照')?><br />
          <?=__('电子版：')?></th>
        <td colspan="20"> <img src="<?=$value['business_license_electronic']?>" alt="" /></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin" <?php echo !$value['organization_code_electronic'] && !$value['tax_registration_certificate_electronic'] ? 'style="display:none;"' : '';?> >
    <thead>
      <tr>
        <th colspan="20"><?=__('组织机构代码证')?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th><?=__('组织机构代码：')?></th>
        <td colspan="20"><?=$value['organization_code']?></td>
      </tr>
      <tr>
        <th><?=__('组织机构代码证')?><br/>
          <?=__('电子版：')?></th>
        <td colspan="20"> <img src="<?=$value['organization_code_electronic']?>" alt="" /> </td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20"><?=__('一般纳税人证明：')?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th><?=__('一般纳税人证明：')?></th>
        <td colspan="20"><img src="<?=$value['general_taxpayer']?>" alt="" /></td>
      </tr>
    </tbody>
  </table>
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20"><?=__('开户银行信息：')?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150"><?=__('银行开户名：')?></th>
        <td><?=$value['bank_account_name']?></td>
      </tr>
      <tr>
        <th><?=__('银行账号：')?></th>
        <td><?=$value['bank_account_number']?></td>
      </tr>
      <tr>
        <th><?=__('开户银行支行名称：')?></th>
        <td><?=$value['bank_name']?></td>
      </tr>
      <tr>
        <th><?=__('支行联行号：')?></th>
        <td><?=$value['bank_code']?></td>
      </tr>
      <tr>
        <th><?=__('开户银行所在地：')?></th>
        <td colspan="20"><?=$value['bank_address']?></td>
      </tr>
      <tr>
        <th><?=__('开户银行许可证')?><br/>
          <?=__('电子版：')?></th>
        <td colspan="20"><img src="<?=$value['bank_licence_electronic']?>" alt="" /></td>
      </tr>
    </tbody>
  </table>

  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin"  <?php echo !$value['organization_code_electronic'] && !$value['tax_registration_certificate_electronic'] ? 'style="display:none;"' : '';?>>
    <thead>
      <tr>
        <th colspan="20"><?=__('税务登记证')?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150"><?=__('税务登记证号：')?></th>
        <td><?=$value['tax_registration_certificate']?></td>
      </tr>
      <tr>
        <th><?=__('纳税人识别号：')?></th>
        <td><?=$value['taxpayer_id']?></td>
      </tr>
      <tr>
        <th><?=__('税务登记证号')?><br />
          <?=__('电子版：')?></th>
        <td> <img src="<?=$value['tax_registration_certificate_electronic']?>" alt="" /> </td>
      </tr>
    </tbody>
  </table>
<?php }else{ ?>
    
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20"><?=__('个人实名信息')?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150"><?=__('用户名：')?></th>
        <td colspan="20"><?=$shop['user_name']?></td>
      </tr>
      <tr>
        <th><?=__('用户真实姓名：')?></th>
        <td colspan="20"><?=$value['contacts_name']?></td>
      </tr>
        <th><?=__('手机号码：')?></th>
        <td><?=$value['contacts_phone']?></td>
        
      </tr>
      </tr>
        <th><?=__('电子邮箱：')?></th>
        <td><?=$value['contacts_email']?></td>
      </tr>
      </tr>
        <th><?=__('所在地区：')?></th>
        <td><?=$value['bank_address']?></td>
      </tr>
      
    </tbody>
  </table>
  
<table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20"><?=__('个人证件信息')?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150"><?=__('证件类型：')?></th>
    <td><?php if($value['legal_identity_type']==1){ echo __('身份证：');}else if($value['legal_identity_type']==2){echo __('护照');}else if($value['legal_identity_type']==3){echo __('军官证');}else{ echo '';} ?></td>
      </tr>
      <tr>
        <th><?=__('证件号码：')?></th>
        <td><?=$value['legal_person_number']?></td>
      </tr>
      <tr>
        <th><?=__('证件照正面：')?></th>
        <td><img src="<?=$value['legal_person_electronic']?>" alt="" /></td>
      </tr>
      <tr>
        <th><?=__('证件照反面：')?></th>
        <td><img src="<?=$value['legal_person_electronic2']?>" alt="" /></td>
      </tr>
      <tr>
        <th><?=__('证件有效期：')?></th>
        <td><?=$value['business_licence_start']?> - <?=$value['business_licence_end']?></td>
      </tr>
    </tbody>
  </table>
  
  <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20"><?=__('开户银行信息')?></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th class="w150"><?=__('银行开户名：')?></th>
        <td><?=$value['bank_account_name']?></td>
      </tr>
      <tr>
        <th><?=__('银行账号：')?></th>
        <td><?=$value['bank_account_number']?></td>
      </tr>
      <tr>
        <th><?=__('开户银行支行名称：')?></th>
        <td><?=$value['bank_name']?></td>
      </tr>
      <tr>
        <th><?=__('开户银行所在地：')?></th>
        <td colspan="20"><?=$value['bank_address']?></td>
      </tr>
    </tbody>
  </table>

<?php } ?>  
  <form id="form_store_verify" action="index.php?act=store&op=store_joinin_verify" method="post">
    <input id="verify_type" name="verify_type" type="hidden" />
    <input name="member_id" type="hidden" value="2" />
    <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
      <thead>
        <tr>
          <th colspan="20"><?=__('店铺经营信息')?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="w150"><?=__('商家账号：')?></th>
          <td><?=$value['user_name']?></td>
        </tr>
        <tr>
          <th class="w150"><?=__('店铺名称：')?></th>
          <td><?=$value['shop_name']?></td>
        </tr>
        <tr>
          <th><?=__('店铺等级：')?></th>
          <?php if($value['shop_grade']){ foreach ($value['shop_grade'] as $keys=>$val){ ?>
          
          <td><?=$val['shop_grade_name']?>（<?=__('开店费用：')?><?=$val['shop_grade_fee']?> 元/年）</td>
          
        
        </tr>
        <tr>
          <th class="w150"><?=__('开店时长：')?></th>
          <td><?=$value['joinin_year']?> 年</td>
        </tr>
        <tr>
          <th><?=__('店铺分类：')?></th>
           <?php foreach ($value['shop_class'] as $keyss=>$vals){ ?>
          <td><?=$vals['shop_class_name']?>（<?=__('开店保证金：')?><?=$vals['shop_class_deposit']?> 元）</td>
  
        </tr>
        <tr>
          <th><?=__('应付总金额：')?></th>
          <td>    <?=$val['shop_grade_fee']*$value['joinin_year']+$vals['shop_class_deposit'] ?> <?=__('元')?>
            </td>
        </tr>
          <?php }}} ?>
        <tr>
          <th><?=__('经营类目：')?></th>
          <td colspan="2"><table border="0" cellpadding="0" cellspacing="0" id="table_category" class="type">
              <thead>
                <tr>
                  <th width="150"><?=__('分类1')?></th>
                  <th width="150"><?=__('分类2')?></th>
                  <th width="150"><?=__('分类3')?></th>
                  <th width="150"><?=__('分类4')?></th>
                  <th width="100"><?=__('比例')?></th>
                </tr>
              </thead>
              <tbody>
              <?php if(!empty($value["classbind"]['items']['product_parent_name'])){ foreach($value["classbind"]['items']['product_parent_name'] as $keys => $vals){
                  ?>
                  <tr>
                  <?php $i=0; foreach ($vals as $keyss => $valss) { ?>
                        <td><?=$valss['cat_name']?></td>
                   <?php $i++; }?>
                  <?php if($i==1){ ?>
                        <td></td>
                        <td></td>
                        <td></td>
                  <?php }elseif($i==2){?>
                        <td></td>
                        <td></td>
                  <?php }elseif($i==3){ ?>
                         <td></td>
                    <?php }else{}?>  
                  <td><?=$value["classbind"]['items']['commission_rate'][$keys]?>%</td>
                  
                </tr>
              <?php } }?>
                </tbody>
              </table>
                
          </td>
        </tr>
                <tr>
          <th><?=__('付款凭证：')?></th>
          <td><img src="<?=$value['payment_voucher']?>" alt="" /></td>
        </tr>
        <tr>
          <th><?=__('付款凭证说明：')?></th>
          <td><?=$value['payment_voucher_explain']?></td>
        </tr>
                      </tbody>
    </table>
      </form>
    
    <?php } }?>
</div>




<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

