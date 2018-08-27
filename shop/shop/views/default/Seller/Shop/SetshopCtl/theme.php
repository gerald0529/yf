<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
  <link href="<?= $this->view->css ?>/seller_center.css?ver=<?=VER?>" rel="stylesheet">
  <script type="text/javascript" src="<?=$this->view->js?>/decoration/decoration/dialog.js" id="dialog_js"></script>
  <style>

      .header {
  
    margin-bottom: 0px;
    margin-top: 0px;
   
}
#fwin_preview_image {
    top: 70px !important;
}
.tabmenu {
    background-color: #fff;
    display: block;
    height: 38px;
    margin-bottom: 10px;
    position: relative;
    width: 100%;
    z-index: 1;
}
  </style>
<div class="ncsc-store-templet">
    <?php if($default_temp){ ?>
        <dl class="current-style">
            <dt class="templet-thumb"><img width="200" height="200"src="<?= $this->view->img ?>/template/<?=$default_temp['shop_temp_name']?>.jpg" id="current_theme_img" /></dt>
          <dd><?=__('店铺模版名称：')?><strong id="current_template"><?=$default_temp['shop_style_name']?></strong></dd>
          <dd><?=__('店铺风格名称：')?><strong id="current_style"><?=$default_temp['shop_temp_name']?></strong></dd>
          <dd><?=__('店铺名称：')?><strong><?=$re['shop_name']?></strong></dd>
          <dd><a href="index.php?ctl=Shop&met=index&id=<?=$re['shop_id']?>" class="yfbtn bbc_seller_btns"><?=__('店铺首页')?></a></dd>
        </dl>
    <?php }?>
  <h3><?=__('图片上传')?><?=__('可用主题')?></h3>
  <div class="templet-list">
    <ul>
        <?php if($grade_temp){
               foreach ($grade_temp as $key => $value) {
         
            ?>
       <li>
        <dl>
          <dt><a href="javascript:void(0)" onclick="preview_theme('<?=$value['shop_temp_name']?>');"><img   width="200" height="200" id="themeimg_default" src="<?= $this->view->img ?>/template/<?=$value['shop_temp_name']?>.jpg"></a></dt>
          <dd><?=__('模版名称：')?><?=$value['shop_temp_name']?></dd>
          <dd><?=__('风格名称：')?><?=$value['shop_style_name']?></dd>
          <dd class="btn"> <a href="javascript:use_theme('<?=$value['shop_temp_name']?>');" class="yfbtn bbc_seller_btns"><i class="icon-cogs"></i><?=__('使用')?></a> <a href="javascript:preview_theme('<?=$value['shop_temp_name']?>');" class="yfbtn bbc_seller_btns"><i class="icon-zoom-in "></i><?=__('预览')?></a> </dd>
        </dl>
      </li>
               <?php }}?>
          </ul>
  </div>
</div>
<script>
    function refreshPage() 
    { 
     location.reload();
    } 

var curr_template_name = 'style2';
var curr_style_name    = 'style2';
var preview_img = new Image();
preview_img.onload = function(){
    var d = DialogManager.get('preview_image');
    if (!d)
    {
        return;
    }

    if (d.getStatus() != 'loading')
    {

        return;
    }

    d.setWidth(this.width + 50);
    d.setPosition('center');
    d.setContents($('<img src="' + this.src + '" alt="" />'));
    ScreenLocker.lock();
};
preview_img.onerror= function(){
    alert("<?=__('加载预览失败')?>");
    DialogManager.close('preview_image');
};
function preview_theme(style_name){
    var screenshot = '<?= $this->view->img ?>/template/' + style_name + '.jpg';

    var d = DialogManager.create('preview_image');
    d.setTitle("<?=__('效果预览')?>");
    d.setContents('loading', {'text':"<?=__('加载中...')?>"});
    d.setWidth(270);
    d.show('center');
    preview_img.src = screenshot;

}
function use_theme(style){
    
            
            $.post(SITE_URL  + '?ctl=Seller_Shop_Setshop&met=setShopTemp&typ=json',{shop_temp_name:style},function(data)
                {
                    if(data && 200 == data.status) {
                        Public.tips.success("<?=__('设置成功！')?>");
                        refreshPage();
                        // window.setTimeout("refreshPage()",500);
                    } else {
                          Public.tips.error("<?=__('设置失败！')?>");
                    }
                }
            );
    
}
</script>
 
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>