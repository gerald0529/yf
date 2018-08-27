
<?php
include $this->view->getTplPath() . '/' . 'supplier_join_header.php';
?>

<style>
    .select_category .ui-tree-wrap{
        margin: 0px;
    }
</style>

<div class="header_line"><span></span></div>
<div class="breadcrumb"><span class="icon-home iconfont icon-tabhome"></span><span><a href=""><?=__('首页')?></a></span> <span class="arrow iconfont icon-btnrightarrow"></span> <span><?=__($apply_tips['0'])?></span> </div>
<div class="main">
  <div class="sidebar">
    <div class="title">
      <h3><?=__($apply_tips['0'])?></h3>
    </div>
    <div class="content">
      <dl show_id="99">
        <dt onclick="show_list('99');" style="cursor: pointer;"> <i class="hide"></i><?=__('入驻流程')?></dt>
        <dd style="display:none;">
          <ul>
            <li> <i></i> <a href="" target="_blank"><?=__('签署入驻协议')?></a> </li>
            <li> <i></i> <a href="" target="_blank"><?=__($apply_tips['1'])?></a> </li>
            <li> <i></i> <a href="" target="_blank"><?=__('平台审核资质')?></a> </li>
            <li> <i></i> <a href="" target="_blank"><?=__($apply_tips['2'])?></a> </li>
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
            <li class=""><i></i><?=__($apply_tips['3'])?></li>
            <li class=""><i></i><?=__('财务资质信息')?></li>
            <li class="bbc_bg_col"><i></i><?=__('店铺经营信息')?></li>
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
  </div>
  <div class="right-layout">
    <div class="w fn-clear">
      <div class="joinin-step">
        <ul>
          <li class="step1 current"><span><?=__('签订入驻协议')?></span></li>
          <li class="current"><span><?=__($apply_tips['3'])?></span></li>
          <li class="current"><span><?=__('财务资质信息')?></span></li>
          <li class="current"><span><?=__('店铺经营信息')?></span></li>
          <li class=""><span><?=__('合同签订及缴费')?></span></li>
          <li class="step6"><span><?=__('店铺开通')?></span></li>
        </ul>
      </div>
      
      
      <div class="joinin-concrete form-style">
       <div class="alert">
        <h4><?=__('注意事项')?>：</h4>
        <?=__('以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。')?>
       <br/>
        <span style="color:red;"><?php echo isset($shop_company['shop_verify_reason']) && $shop_company['shop_status'] == 6 ? $shop_company['shop_verify_reason'] : '';?></span>
      </div>   
    <form id="form_company_info" method="post">
       <input name="shop_id" value="<?=$shop_company['shop_id']?>" type="hidden">
    <fieldset>
        <h4><em><i>*</i><?=__('表示该项必填')?></em><?=__('店铺经营信息')?></h4>
        <dl>
            <dt><i>*</i><?=__('店铺名称')?>：</dt>
            <dd>
                <input class="text w250" name="shop_name" type="text" value="<?php echo isset($shop_company['shop_name']) ? $shop_company['shop_name'] : '';?>">
                <p class="hint red"><?=__('店铺名称注册后不可修改，请认真填写。')?></p>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('店铺等级')?>：</dt>
            <dd>
            <select name="shop_grade_id">
                <option selected="selected" value=""><?=__('请选择')?></option>
                                 <?php if(!empty($shop_grade)){
                                        foreach ($shop_grade as $key => $value) {?>
                
                                    <option value="<?php echo $value['shop_grade_id'];?>"  <?php if(isset($shop_company['shop_grade_id'])  && $value['shop_grade_id'] == $shop_company['shop_grade_id']){echo 'selected="selected"';}?> ><?=$value['shop_grade_name']?> (<?=__('收费标准')?>：<?=$value['shop_grade_fee']?> <?=__('元')?>)</option>
                                
                                      <?php }}?>
                            </select>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('开店时长')?>：</dt>
            <dd>
            <select name="joinin_year">
                <option selected="selected" value="1" <?php if(isset($shop_company['joinin_year'])  && $shop_company['joinin_year'] == 1){echo 'selected="selected"';}?> ><?=__('1年')?></option>
                <option value="2" <?php if(isset($shop_company['joinin_year'])  && $shop_company['joinin_year'] == 2){echo 'selected="selected"';}?>  ><?=__('2年')?></option>
                <option value="5" <?php if(isset($shop_company['joinin_year'])  && $shop_company['joinin_year'] == 5){echo 'selected="selected"';}?>  ><?=__('5年')?></option>
                <option value="10" <?php if(isset($shop_company['joinin_year'])  && $shop_company['joinin_year'] == 10){echo 'selected="selected"';}?>  ><?=__('10年')?></option>
            </select>
            </dd>
        </dl>
        <dl>
            <dt><i>*</i><?=__('店铺分类')?>：</dt>
            <dd>
            <select name="shop_class_id">
                <option selected="selected" value=""><?=__('请选择')?></option>
                                    <?php if(!empty($shop_class)){
                                        foreach ($shop_class as $key => $value) {

                                    ?>
                                     <option value="<?=$value['shop_class_id']?>" <?php if(isset($shop_company['shop_class_id'])  && $value['shop_class_id'] == $shop_company['shop_class_id']){echo 'selected="selected"';}?> ><?=$value['shop_class_name']?> (<?=__('保证金')?>：<?=$value['shop_class_deposit']?><?=__('元')?>)</option>
                                    <?php }}?>
                            </select>
            <p class="hint red"><?=__('请根据您所经营的内容认真选择店铺分类，注册后商家不可自行修改。')?></p>
            </dd>
        </dl>
        <dl>
            <dt><?=__('经营类目')?>：</dt>
            <dd>
            <div class="select_category" >
                <select id="select" name="shop_class_ids">
                  
                </select>
            </div>
            <table class="table_category" border="0" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                    <th><?=__('一级类目')?></th>
                    <th><?=__('二级类目')?></th>
                    <th><?=__('三级类目')?></th>
                    <th><?=__('四级类目')?></th>
                    <th><?=__('操作')?></th>
                 </tr>        
            </tbody>
   
            </table>
        </dd>
        </dl>
    </fieldset>
    <div class="next"><a href="<?= Yf_Registry::get('base_url')?>/index.php?ctl=Seller_Supplier_Settled&met=index&op=step3&rp=step3&apply=<?=$apply?>" class="btn bbc_btns"><?=__('上一步')?></a>&nbsp;&nbsp;&nbsp;<a id="btn_apply_company_next" class="button bbc_btns" href="javascript:void(0);"><?=__('提交申请')?></a></div>
</form>    
<link href="<?= $this->view->css_com ?>/ztree.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.combo.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.ztree.all.js"></script> 
<script type="text/javascript" src="<?=$this->view->js?>/common.js" charset="utf-8"></script>


<script>
	$(function() {

		//商品类别
		var opts = {
			width : 160,
			//inputWidth : (SYSTEM.enableStorage ? 145 : 208),
			inputWidth : 180,
			defaultSelectValue : '-1',
			//defaultSelectValue : rowData.categoryId || '',
			showRoot : true,
                        rootTxt: '<?=__('添加经营类目')?>',
		}

		categoryTree = Public.categoryTree($('#select'), opts);
                

		$('#select').change(function(){
			var i = $(this).data("id");
            if(i != -1){
                var ajax_url = "./index.php?ctl=Seller_Supplier_Settled&met=shopCatBind&typ=json&apply=<?=$apply?>"; 
                $.ajax({
                     url: ajax_url,
                     data:"cat_id="+i,
                     success:function(a){                                   
                         if(a.status == 200)
                         {
                             var tr = '<tr class="shop-item">';
                             var catid = catname = commission = '';
                             var data = a.data;
                             var len = data.length;

                             $.each(data, function(i, cat_row)
                             {
                                 if(i==len-1)
                                 {
                                     tr += '<td>'+cat_row.cat_name+'(<?=__('分佣比例')?>：'+cat_row.cat_commission+'%)</td>';
                                 }else{
                                     tr += '<td>'+cat_row.cat_name+'</td>';
                                 }

                                 catid = cat_row.cat_id ;
                                 commission = cat_row.cat_commission;
                             });

                             //等级不足时
                             for(i=0;i<4-len;i++)
                             {
                                 tr +='<td></td>';
                             }

                             tr += '<td><a data-type="delete" href="javascript:void(0);"><?=__('删除')?></a>';
                             tr += '<input type="hidden" name="product_class_id[]" value="' + catid + '" />';
                             tr += '<input type="hidden" name="commission_rate[]" value="' + commission + '" />';
                             tr += '</td></tr>';

                             $('.table_category').append(tr);									
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
            }else{
              Public.tips.error("<?=__('请选择类目！')?>");  return false;
            }     
		});
		
		$('.table_category').on('click', '[data-type="delete"]', function() {
			$(this).parent('td').parent('tr').remove();
		});

	});
</script>
<script type="text/javascript">
$(document).ready(function(){
         var ajax_url = "./index.php?ctl=Seller_Supplier_Settled&met=editShopBase&typ=json&apply=<?=$apply?>"; 
        $('#form_company_info').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
                checkName:function(element){
                    if(element.value.length > 15){
                        return '店铺名称不能超过15个字符';
                    }
                }
            },

            fields: {
                'shop_name': 'required;checkName;',
                'shop_grade_id':'required;',
                'joinin_year':'required;',
                'shop_class_id':'required;',
                'shop_class_bind_id':'required;',
            },
           valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form_company_info").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                           location.href="./index.php?ctl=Seller_Supplier_Settled&met=index&op=step5&apply=<?=$apply?>"; 
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
</script> 
    <link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?= VER ?>" rel="stylesheet"
          type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js"
            charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js"
            charset="utf-8"></script>
</div>
    </div>
  </div>
</div>



<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>