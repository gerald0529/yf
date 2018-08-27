<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css?>/page.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/layer/layer.min.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validation.min.js" charset="utf-8"></script>
</head>
<body>
    <style>
    .webuploader-pick{ padding:1px; }
    
</style>
<div class="dialog_frame">
  <ul class="ul fn-clear" style="<?php if(!empty($data['adimgtext'])){ ?>display: none <?php } ?>">
     <li class="cur">修改</li>
     <li>新增</li>
</ul>
   
<form id="form" method="post">
       <?php
         
//var_dump($data);exit;
        
          ?>

  
    
    <div class="form-table-style" style="clear:both">
       <?php
         if(!empty($data["ad"])){
            foreach ($data["ad"] as $key =>$val)
            {
              
            foreach ($data["ad"][$key]["pic"] as $keys =>$vals)
            {
                
              ?>
        <div class="item_num<?php echo $vals['item_id'] ?>" style="float: left;position: relative;z-index: 90;">
        <a  href="<?=Yf_Registry::get('base_url')?>?ctl=Floor_Adposition&met=ad&item_id=<?php echo $vals['item_id'] ?>&page_id=<?php echo $data['page_id'] ?>&layout_id=<?php echo $data['layout_id'] ?>&widget_name=<?php echo $data['widget_name'] ?>&width=<?php echo $data['width'] ?>&height=<?php echo $data['height'] ?>&met=<?php echo $data['met'] ?>"><img style="width:140px;height:140px;margin: 10px;" src="<?php echo $vals['item_img_url'] ?>">
        </a>
        <span class="item_id" item_id="<?php echo $vals['item_id'] ?>" style="position:absolute; width: 15px;height:20px;top:2px;right:5px;font-size: 14px;display: block-inline;color:#000000;z-index: 99;cursor: pointer;">x</span>
        </div>
     
      <?php }} } ?>
    </div>
           
    
     <table class="form-table-style" style=" <?php if(empty($data['adimgtext'])){ ?>display: none <?php } ?>">
    
    <?php 
        if(empty($data['adimgtext'])){ 
           
        
        ?>
     <input type="hidden" name="act" value="ad_add" id="act"/>
     <input type="hidden" name="widget_width" value="<?php echo $data["width"] ?>" id="widget_width"/>
     <input type="hidden" name="widget_height" value="<?php echo $data["height"] ?>" id="widget_height"/>

    <input type="hidden" name="widget_id" value="" id="widget_id"/>
    <input type="hidden" name="layout_id" value="<?php echo $data["layout_id"] ?>" id="layout_id"/>
    <input type="hidden" name="widget_cat" value="<?php echo $data["met"] ?>" id="widget_cat"/>
    <input type="hidden" name="widget_name" id="widget_name"value="<?php echo $data["widget_name"] ?>" />
    <input type="hidden" name="page_id" id="page_id"  value="<?php echo $data["page_id"] ?>" />
    <div class="tips"><i class="iconfont"></i>请上传宽度<?php echo $data["width"] ?>像素、高度<?php echo $data["height"] ?>像素的图片，过大或过小的图像都将影响显示效果正常</div>

        <tr>
            <th colspan="2">文字标题：</th>
        </tr>
        <tr>
            <td width="260"><input type="text" class="text w250" name="item_name"  id= "item_name" value="" /></td>
            <td></td>
        </tr>
        <tr>
            <th colspan="2">图片跳转链接：</th>
        </tr>
        <tr>
            <td><input type="text"  class="text w250" name="item_url" id= "item_url" value="" /></td>
            <td></td>
        </tr>
        <tr>
            <th colspan="2"><em>*</em>广告图片上传：</th>
        </tr>
        <tr>  <th colspan="2"> <img id ="item_img" src="" alt="" /></th></tr>
        <tr>
            
            <td colspan="4">
              
                    
                        <div id="filePicker" class="image-line upload-image">选择文件</div>
                    

                    <!-- Croper container -->
                  
                    <img id ="item_img" src="" alt="" />
                       

                       
              
                    <input type="hidden"  name="item_img_url" value=""  id="item_img_url"/>

                </div>
            </td>
        </tr>    
    <?php 
    
            
            
           }else{
   
      
        ?>
            <input type="hidden" name="act" value="ad_add" id="act"/>
     <input type="hidden" name="widget_width" value="<?php echo $data["width"] ?>" id="widget_width"/>
     <input type="hidden" name="widget_height" value="<?php echo $data["height"] ?>" id="widget_height"/>

    <input type="hidden" name="layout_id" value="<?php echo $data["layout_id"] ?>" id="layout_id"/>
    <input type="hidden" name="widget_cat" value="<?php echo $data["met"] ?>" id="widget_cat"/>
    <input type="hidden" name="widget_name" id="widget_name"value="<?php echo $data["widget_name"] ?>" />
    <input type="hidden" name="page_id" id="page_id"  value="<?php echo $data["page_id"] ?>" />
    
    <input type="hidden" name="item_id" value="<?php echo $data['adimgtext']['item_id'] ?>" id="item_id"/>
    <input type="hidden" name="widget_id" value="<?php echo $data['adimgtext']['widget_id'] ?>" id="widget_id"/>
    
     <div class="tips"><i class="iconfont"></i>请上传宽度<?php echo $data["width"] ?>像素、高度<?php echo $data["height"] ?>像素的图片，过大或过小的图像都将影响显示效果正常</div>
        <tr>
            <th colspan="2">文字标题：</th>
        </tr>
        <tr>
            <td width="260"><input type="text" class="text w250" name="item_name"  id= "item_name" value="<?php echo $data['adimgtext']["item_name"]?>" /></td>
            <td></td>
        </tr>
        <tr>
            <th colspan="2">图片跳转链接：</th>
        </tr>
        <tr>
            <td><input type="text"  class="text w250" name="item_url" id= "item_url" value="<?php echo $data['adimgtext']["item_url"]?>" /></td>
            <td></td>
        </tr>
        <tr>
            <th colspan="2"><em>*</em>广告图片上传：</th>
        </tr>
         <tr>  <th colspan="2"> <img id ="item_img" src="<?php echo $data['adimgtext']["item_img_url"]?>" alt="" /></th></tr>
        <tr>
            
            <td colspan="4">
              
                    
                        <div id="filePicker" class="image-line upload-image">选择文件</div>
                    

                    <!-- Croper container -->
                  
                    <input type="hidden"  name="item_img_url" value="<?php echo $data['adimgtext']["item_img_url"]?>"  id="item_img_url"/>

                </div>
            </td>
        </tr>    

            <?php   }?>
        <tr>    
            <td class="foot" colspan="2"><input type="submit" value="保存" /></td>
        </tr>
    </table>
   <script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>

    <script>
       
        setting_logo_upload = new UploadImage({
            thumbnailWidth: <?php echo substr($data["width"],0,strlen($data["width"])-2); ?>,
            thumbnailHeight: <?php echo substr($data["height"],0,strlen($data["height"])-2); ?>,
            imageContainer: '#item_img',
            uploadButton: '#filePicker',
            inputHidden: '#item_img_url'
        });
      
      $(".item_id").click(function(){
            var item_id = $(this).attr("item_id");
            var item_parent = $(".item_num"+item_id);

            item =  {item_id:item_id} 
                          
            Public.ajaxPost(SITE_URL +"?ctl=Floor_Adposition&typ=json&met=delitem" ,item, function (e)
             {
                                    
                if (200 == e.status)
                {
                   item_parent.hide(); 
                }
                else
                {
                    alert("删除失败");
                }
            })
      })
//                   $(function(){
//				 //图片裁剪
//
//                var $imagePreview, $imageInput, imageWidth, imageHeight;
//
//                $('#filePicker').on('click', function () {
//
//
//                        $imagePreview = $('#item_img');
//                        $imageInput = $('#item_img_url');
//                        imageWidth = <--?php echo round(substr($data["width"],0,strlen($data["width"])-2)); ?>, imageHeight = <--?php echo round(substr($data["height"],0,strlen($data["height"])-2)); ?>;
//
//                    console.info($imagePreview);
//                    $.dialog({
//                        title: '图片裁剪',
//                        content: "url: <--?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
//                        data: { SHOP_URL: SHOP_URL, width: imageWidth, height: imageHeight, callback: callback },    // 需要截取图片的宽高比例
//                        width: '800px',
//                        lock: true,
//                        zIndex:2001
//                    })
//                });
//
//                function callback ( respone , api ) {
//                    console.info($imagePreview);
//                    $imagePreview.attr('src', respone.url);
//                    $imageInput.attr('value', respone.url);
//                    api.close();
//                } 
//   })
        
        
           var icon = '<i class="iconfont icon-exclamation-sign"></i>';
        $(".ul li").click(function(){
            $(this).addClass('cur').siblings().removeClass('cur');
            $("#form").find(".form-table-style").eq($(this).index()).show().siblings(".form-table-style").hide();
	
});
   $('#form').validate({
	errorPlacement: function(error, element){
		error.appendTo(element.parents('tr').prev().children('th'))
	},
	rules : {
		pic_url : {
			required : true
		}
	},
	messages : {
		pic_url : {
			required : icon + '请上传广告图片'
		}
	},
	submitHandler:function(form){ 
            var  item_img_url = $.trim($("#item_img_url").val());
            var  act = $.trim($("#act").val());
            var  widget_width = $.trim($("#widget_width").val());
            var  widget_height = $.trim($("#widget_height").val());
            var  item_url = $.trim($("#item_url").val());
            var  item_name = $.trim($("#item_name").val());
            var  widget_id = $.trim($("#widget_id").val());
            var  page_id = $.trim($("#page_id").val());
            var  widget_name = $.trim($("#widget_name").val());
            var  widget_cat = $.trim($("#widget_cat").val());
             var  layout_id = $.trim($("#layout_id").val());
             if($("#item_id").val()){
              var  item_id = $.trim($("#item_id").val());
             }else{
              var  item_id = null; 
             }

                                params =  {
                                        page_id: page_id, 
                                        item_img_url: item_img_url, 
                                        item_url: item_url,
                                        item_name: item_name,
                                        widget_id:widget_id,
                                        layout_id:layout_id,
                                        widget_name:widget_name,
                                        widget_cat:widget_cat,
                                        widget_width:widget_width,
                                        widget_height:widget_height,
                                        item_id:item_id,
                                } 
                               
                                Public.ajaxPost(SITE_URL +"?ctl=Floor_Adposition&typ=json&met="+act , params, function (e)
                                {
                                    
                                        if (200 == e.status)
                                        {
                                            var callback = frameElement.api.data.callback;
                                            callback();
//                                                parent.parent.Public.tips({content: n + "成功！"});
//                                                callback && "function" == typeof callback && callback(e.data, t, window)
                                        }
                                        else
                                        {
                                            var callback = frameElement.api.data.callback;
                                            callback();
//                                                parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
                                                    
                                        }
                                })
	}
});
</script>
    
          
    
    
       
</form>
</div>
  

<?php
include $this->view->getTplPath() . '/'  . 'footer.php';
?>