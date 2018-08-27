<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>
<body>

	<!-- <div class="tabmenu">
		<ul>
        	<li ><a href="./index.php?ctl=Seller_Shop_Cat&met=cat&typ=e"><?=__('店铺分类')?></a></li>

            <?php if($act == 'add') {?>
            <li  class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('添加店铺分类')?></a></li>
            <?php }
            if($act == 'edit'){?>
            <li class="active bbc_seller_bg"><a href="javascript:void(0);"><?=__('编辑店铺分类')?></a></li>
            <?php }?>
        </ul>

    </div> -->

    <?php if($act == 'add') {?>
    <form id="form" action="#" method="post" >
            <div class="form-style">

        <dl class="dl">
            <dt><i>*</i><?=__('分类名称：')?></dt>
            <dd ><input type="text" class="text w120" name="cat[shop_goods_cat_name]" id="title" value="" /></dd>
        </dl>
         <dl class="dl">
            <dt><?=__('是否显示：')?></dt>
            <dd >  <label class="radio status"><input type="radio"  name="cat[shop_goods_cat_status]" id="status" checked="checked" value="1" /><?=__('是')?></label> <label class="radio status"><input type="radio"  name="cat[shop_goods_cat_status]" id="status" value="0" /><?=__('否')?></label>
          
            </dd>
            
        </dl>
        <dl class="dl">
            <dt><i></i><?=__('上级分类：')?></dt>
            <dd >
                <select name="cat[parent_id]">
                    <option name="" value="0"><?=__('请选择')?></option>
                      <?php if($data) {
                    foreach ($data as $key => $value){ ?>
                    <option  <?php if($pid && $pid==$value['shop_goods_cat_id']){?>selected="selected" <?php }?> value="<?=$value['shop_goods_cat_id']?>"><?=$value['shop_goods_cat_name']?></option>
                      <?php }} ?>
                </select>
                  <p class="hint"><?=__('提示：不选择就是添加一级分类')?></p>
            </dd>
        </dl>    
        <dl class="dl">
            <dt><i></i><?=__('店铺分类')?>排序：</dt>
            <dd ><input type="text" class="text w50" name="cat[shop_goods_cat_displayorder]" id="displayorder" value="" />
                <p class="hint"><?=__('提示：0-255之间，不填默认为0')?></p>
            </dd>
          
        </dl>

        <dl class="dl">
            <dt></dt>
            <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
        </dl>
    </div>
    </form>
    <?php }
    if($act == 'edit' && $data){?>
    <form id="form" action="#" method="post" >

    <div class="form-style">

  
        <input type="hidden" name="shop_goods_cat_id" id="id" value="<?=$data['shop_goods_cat_id']?>" />

        <dl class="dl">
            <dt><i>*</i><?=__('分类名称：')?></dt>
            <dd ><input type="text" class="text w120" name="cat[shop_goods_cat_name]" id="title" value="<?=$data['shop_goods_cat_name']?>" /></dd>
        </dl>
         <dl class="dl">
            <dt><?=__('是否显示：')?></dt>
            <dd >  <label class="radio status"><input type="radio"  <?=($data['shop_goods_cat_status'] ? 'checked' : '') ?> name="cat[shop_goods_cat_status]" id="status"  value="1" /><?=__('是')?></label> <label class="radio status"><input type="radio"  name="cat[shop_goods_cat_status]" <?=(!$data['shop_goods_cat_status'] ? 'checked' : '') ?> value="0" /><?=__('否')?></label></dd>
        </dl>
       <dl class="dl">
            <dt><i></i><?=__('排序：')?></dt>
            <dd ><input type="text" class="text w50" name="cat[shop_goods_cat_displayorder]" id="displayorder" value="<?=$data['shop_goods_cat_displayorder']?>" />
                             <p class="hint"><?=__('提示：0-255之间，不填默认为0')?></p>
            </dd>
        </dl>

    


        <dl class="dl">
            <dt></dt>
            <dd><input type="submit" class="button bbc_seller_submit_btns" value="<?=__('确认提交')?>" /></dd>
        </dl>
    </div>
    </form>
    <?php }?>
<script>
       $(document).ready(function(){
    
        var ajax_url = './index.php?ctl=Seller_Shop_Cat&met=<?=$act?>Cat&typ=json';
         
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            rules: {
              
            },

            fields: {
                'cat[shop_goods_cat_name]': 'required;length[1~10]',
                'cat[shop_goods_cat_displayorder]':'range[0~255];integer',
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
                           setTimeout(' location.href="./index.php?ctl=Seller_Shop_Cat&met=cat&typ=e"',3000); //成功后跳转
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