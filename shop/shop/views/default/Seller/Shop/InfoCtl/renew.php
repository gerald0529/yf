<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
          <link href="<?= $this->view->css ?>/seller_conter.css?ver=<?=VER?>" rel="stylesheet">


	<div class="tabmenu">
	<ul>
  	<li ><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=category"><?=__('经营类目')?></a></li>
    <?php if($shop['shop_self_support']=="false"){ ?> 
    <li ><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=info"><?=__('店铺信息')?></a></li>
    <li class="active bbc_seller_bg"><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=renew"><?=__('续签申请')?></a></li>
    <?php } ?>
    <li><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=createQrCode"><?=__('收款码')?></a></li>
  </ul>
     
     
        </div>
        <div class="alert">
            <ul>
                <li><?=__('1、店铺到期前 30 天可以申请店铺续签。')?></li>
                <li><?=__('1、店铺到期')?> <?=$shop["shop_end_time"]?> <?=__('可以在')?> <?=$frontmonth?> <?=__('开始申请店铺续签')?>。</li>
            </ul>
        </div>
<div>
  
     
       <table class="table-list-style" width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<th ><?=__('申请时间')?></th>
                <th ><?=__('收费标准（元/年）')?></th>
                <th ><?=__('续签时长（年）')?></th>
        	<th ><?=__('付款金额 （元）')?></th>
        	<th ><?=__('截止有效期')?></th>
        	<th ><?=__('状态')?></th>
                <th><?=__('操作')?></th>
        </tr>
   
     <?php if($data['items'] ){
            foreach ($data['items'] as $key => $value) {
          ?>
        <tr class="row_line">
           
            <td><?=$value['create_time']?></td>
            
        
            <td><?=$value['shop_grade_fee']?></td>
            <td><?=$value['renew_time']?></td>
           
            
            <td><?=$value['renew_cost']?></td>
            <td><?=$value['end_time']?></td>
            <td><?=$value['renewal_status_cha']?></td>
            <td class="nscs-table-handle">
              <?php if($value['status'] != 2){?>
                <span class="del"><a data-param="{'ctl':'Seller_Shop_Info','met':'delRenew','id':'<?=$value['id']?>'}" href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span>
            <?php }?>
            </td>
           
        </tr>
              
     <?php }}else{ ?>
      <tr class="row_line">
                <td colspan="99">
                    <div class="no_account">
                        <img src="<?=$this->view->img?>/ico_none.png">
                        <p><?=__('暂无符合条件的数据记录')?></p>
                    </div>
                </td>
            </tr>
     <?php }?>
    </table>
    
    <form id="form" method="post" >
       <div class="form-style">
           <?php if($shop["shop_end_time"] >$date && $date > $frontmonth && !$data['items']){?>
           <dl>
                <dt><i>*</i><?=__('店铺等级：')?></dt>
                <dd>
                <select name="shop_grade">
                        <?php if($grade){
                               foreach ($grade as $key => $value) {
                             ?>
                       
                        <option  value="<?=$value['shop_grade_id']?>"><?=$value['shop_grade_name']?>（<?=__('费用：')?><?=$value['shop_grade_fee']?>）</option>
                    
                        <?php 
                               }
                               
                               }
                             ?>
                     </select>
                </dd>
            </dl>
            <dl>
                <dt><i>*</i><?=__('开店时长：')?></dt>
                <dd>
                <select name="renew_time">
                    <option value="1"><?=__('1年')?></option>
                    <option value="2"><?=__('2年')?></option>
                    <option value="5"><?=__('5年')?></option>
                    <option value="10"><?=__('10年')?></option>
                </select>
                </dd>
            </dl>
                
            <dl>
            	<dt></dt>
                <dd>
                <input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>">
                </dd>
            </dl>
                   <?php } ?>
           

        </div> 
    </form>
</div>


  <script>
      function submitBtn()
    {
        $("#form").ajaxSubmit(function(message){
            if(message.status == 200)
            {
               
               Public.tips.success("<?=__('操作成功！')?>");
               window.setTimeout(location.reload(),3000);

            }
            else
            {
               Public.tips.error("<?=__('操作失败！')?>");
            }
        });
        return false;
    }
    
    
        
 $(document).ready(function(){
         var ajax_url = './index.php?ctl=Seller_Shop_Info&met=addRenew&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
              
            },
            fields: {
          
            },
           valid:function(form){
                 var me = this;
                // 提交表单之前，hold住表单，防止重复提交
                me.holdSubmit();
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                           Public.tips.success("<?=__('操作成功！')?>");
                           window.setTimeout(location.reload(),3000);
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

