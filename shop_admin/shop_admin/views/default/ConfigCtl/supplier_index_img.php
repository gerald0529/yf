<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<style>
    .webuploader-pick{ padding:1px; }
    
</style>
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>模板风格</h3>
                <h5>首页幻灯将在首页展示</h5>
            </div>
            <ul class="tab-base nc-row">
                <?php
                $data_theme = $this->getUrl('Config', 'siteTheme', 'json', null, array('config_type'=>array('site')));
    
                $theme_id = $data_theme['theme_id']['config_value'];
    
                foreach ($data_theme['theme_row'] as $k => $theme_row)
                {
                    if ($theme_id == $theme_row['name'])
                    {
                        $config = $theme_row['config'];
                        break;
                    }
                }
                ?>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=supplier_index&config_type%5B%5D=supplier_index"><span>供应商首页幻灯片</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Supplier_Adpage&met=adpage"><span>供应商首页模板</span></a></li>
                <li><a class="current" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=supplier_index_img&config_type%5B%5D=supplier_index_img"><span>供应商首页小图</span></a></li>
            </ul>
        </div>
    </div>
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
          <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
          <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
        </div>
        <ul>
              <li>该组图片应用于供应商首页使用，最多可上传2张图片。</li>
              <li>图片要求使用宽度为236像素，高度为236像素jpg/gif/png格式的图片。</li>
              <li>上传图片后请添加格式为“http://网址...”链接地址。</li>
        </ul>
    </div>

   <form method="post" enctype="multipart/form-data" id="supplier_index_img-setting-form" name="form1">
    <input type="hidden" name="config_type[]" value="supplier_index_img"/>
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label>首页图片1</label>
        </dt>
        <dd class="opt">
            <div class="div-image">
                <img id="supplier_index_img1_review" src="<?php echo $data['supplier_index_img1']['config_value'];?>" width="236" height="236"/>
                <a href="javascript:void(0)" class="del" onclick="delImage('supplier_index_img1_review')" title="移除"><i class="iconfont icon-cancel"></i></a>
            </div>
            <br/>
            <input type="hidden" id="supplier_index_img1_image" name="supplier_index_img[supplier_index_img1]" value="<?php echo $data['supplier_index_img1']['config_value'];?>" />
            <div  id='supplier_index_img1_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="supplier_index_img[supplier_index_img_link1]" value="<?php echo $data['supplier_index_img_link1']['config_value'];?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="supplier_index_img_url1" class="error valid"></label></span>
           <p class="notic">请使用宽度236像素，高度236像素的jpg/gif/png格式图片作为联动图片上传<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>

     <dl class="row">
        <dt class="tit">
          <label>首页图片2</label>
        </dt>
        <dd class="opt">
            <div class="div-image">
                <img id="supplier_index_img2_review" src="<?php echo $data['supplier_index_img2']['config_value'];?>" width="236" height="236"/>
                <a href="javascript:void(0)" class="del" onclick="delImage('supplier_index_img2_review')" title="移除"><i class="iconfont icon-cancel"></i></a>
            </div>
            <br/>
                
            <input type="hidden" id="supplier_index_img2_image" name="supplier_index_img[supplier_index_img2]" value="<?php echo $data['supplier_index_img2']['config_value'];?>" />
            <div  id='supplier_index_img2_upload' class="image-line upload-image" >图片上传</div>

           <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                <input class="ui-input w400" style="margin:5px 0" type="text" name="supplier_index_img[supplier_index_img_link2]" value="<?php echo $data['supplier_index_img_link2']['config_value'];?>" placeholder="请输入图片要跳转的链接地址">
           </label>
           <span class="err"><label for="supplier_index_img_url2" class="error valid"></label></span>
           <p class="notic">请使用宽度236像素，高度236像素的jpg/gif/png格式图片作为联动图片上传<br>
            如需跳转请在后方添加以http://开头的链接地址。</p>
        </dd>
      </dl>
     <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
  </form>

    <script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>

    <script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
    <script>
        jQuery(function($){ 
            var sub_site_id =  <?=Perm::$row['sub_site_id']?>;
                if(sub_site_id){
                    $.post(SITE_URL  + '?ctl=Config&met=addLiandong&typ=json',{sub_site_id:sub_site_id},function(e){ if(200 == e.status){ location.reload()}});
                }	
            }); 
         $(function(){
              //图片裁剪

             var agent = navigator.userAgent.toLowerCase();

             if ( agent.indexOf("msie") > -1 && (version = agent.match(/msie [\d]/), ( version == "msie 8" || version == "msie 9" )) ) {
                 supplier_index_img1_image_upload= new UploadImage({
                     thumbnailWidth: 236,
                     thumbnailHeight: 236,
                     imageContainer: '#supplier_index_img1_review',
                     uploadButton: '#supplier_index_img1_upload',
                     inputHidden: '#supplier_index_img1_image'
                 });

                 supplier_index_img2_image_upload= new UploadImage({
                     thumbnailWidth: 236,
                     thumbnailHeight: 236,
                     imageContainer: '#supplier_index_img2_review',
                     uploadButton: '#supplier_index_img2_upload',
                     inputHidden: '#supplier_index_img2_image'
                 });
             } else {
                 var $imagePreview, $imageInput, imageWidth, imageHeight;

                 $('#supplier_index_img1_upload, #supplier_index_img2_upload').on('click', function () {

                     if ( this.id == 'supplier_index_img1_upload' ) {
                         $imagePreview = $('#supplier_index_img1_review');
                         $imageInput = $('#supplier_index_img1_image');
                         imageWidth = 236, imageHeight = 236;
                     }else {
                         $imagePreview = $('#supplier_index_img2_review');
                         $imageInput = $('#supplier_index_img2_image');
                         imageWidth = 236, imageHeight = 236;
                     }
                     console.info($imagePreview);
                     $.dialog({
                         title: '图片裁剪',
                         content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                         data: { SHOP_URL: SHOP_URL, width: imageWidth, height: imageHeight, callback: callback },    // 需要截取图片的宽高比例
                         width: '800px',
                         height:$(window).height()*0.9,
                         lock: true
                     })
                 });

                 function callback ( respone , api ) {
                     console.info($imagePreview);
                     $imagePreview.attr('src', respone.url);
                     $imageInput.attr('value', respone.url);
                     api.close();
                 }
             }

        })
        function delImage(imageId) {
            var $img = $("#" + imageId), $input;
            if (!$img.attr("src")) {
                return false;
            }
            $.dialog.confirm("是否删除该图片", function () {
                if (imageId == "supplier_index_img1_review") {
                    $input = $("#supplier_index_img1_image")
                } 
                if(imageId == "supplier_index_img2_review"){
                    $input = $("#supplier_index_img2_image")
                }
                $img.attr("src", ""), $input.attr("value", "");
            })
        }
    </script>
    <?php
include $this->view->getTplPath() . '/' . 'footer.php';
    ?>