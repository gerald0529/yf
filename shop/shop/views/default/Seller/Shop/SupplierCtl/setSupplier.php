<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>
<body>

	<div class="tabmenu">
		<ul>
        	<li ><a href="./index.php?ctl=Seller_Shop_Supplier&met=supplier&typ=e"><?=__('供货商')?></a></li>

            <?php if($act == 'add') {?>
            <li  class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('添加供货商')?></a></li>
            <?php }
            if($act == 'edit'){?>
            <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('编辑供货商')?></a></li>
            <?php }?>
        </ul>
    </div>
    <?php if($act == 'add') {?>
    <form id="form" action="#" method="post" >
            <div class="form-style">



        <dl class="dl">
            <dt><i>*</i><?=__('供货商名称：')?></dt>
            <dd ><input type="text" class="text w120" name="supplier[supplier_name]" id="supplier_name" value="" /></dd>
        </dl>
        
        <dl class="dl">
            <dt><?=__('联系人：')?></dt>
            <dd ><input type="text" class="text w120" name="supplier[contacts]" id="contacts" value="" /></dd>
        </dl>
                
        <dl class="dl">
            <dt><?=__('联系电话：')?></dt>
            <dd ><input type="text" class="text w120" name="supplier[contacts_tel]" id="contacts_tel" value="" /></dd>
        </dl>
                
        <dl class="dl">
            <dt><?=__('备注信息：')?></dt>
            <dd><textarea name="supplier[remarks]" id="detail" style="width:300px;height:100px;"></textarea></dd>
        </dl>
   
        <dl>
            <dt></dt>
            <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
        </dl>
    </div>
    </form>
    <?php }
    if($act == 'edit' && $data){?>
    <form id="form" action="#" method="post" >

    <div class="form-style">

  
        <input type="hidden" name="supplier_id" id="id" value="<?=$data['supplier_id']?>" />

  
        <dl class="dl">
            <dt><i>*</i><?=__('供货商名称：')?></dt>
            <dd ><input type="text" class="text w120" name="supplier[supplier_name]" id="supplier_name" value="<?=$data['supplier_name']?>" /></dd>
        </dl>
        
        <dl class="dl">
            <dt><?=__('联系人：')?></dt>
            <dd ><input type="text" class="text w120" name="supplier[contacts]" id="contacts" value="<?=$data['contacts']?>" /></dd>
        </dl>
                
        <dl class="dl">
            <dt><?=__('联系电话：')?></dt>
            <dd ><input type="text" class="text w120" name="supplier[contacts_tel]" id="contacts_tel" value="<?=$data['contacts_tel']?>" /></dd>
        </dl>
                
        <dl class="dl">
            <dt><?=__('备注信息：')?></dt>
            <dd><textarea name="supplier[remarks]" id="detail" style="width:300px;height:100px;"><?=$data['remarks']?></textarea></dd>
        </dl>
   
        <dl>
            <dt></dt>
            <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
        </dl>
    </div>
    </form>
    <?php }?>

<script>
   

       $(document).ready(function(){
    
        var ajax_url = './index.php?ctl=Seller_Shop_Supplier&met=<?=$act?>Supplier&typ=json';
         
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
                  tel:[/^[1][0-9]{10}$/,'<?=__('请输入正确的手机号码')?>'],
            },

            fields: {
                'supplier[supplier_name]': 'required',
                'supplier[contacts_tel]':'tel',
                'supplier[remarks]':'length[0~60]',       //6-16个字符'
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
                            setTimeout('location.href="./index.php?ctl=Seller_Shop_Supplier&met=supplier&typ=e"',3000); //成功后跳转
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