<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
    <style>
        .webuploader-pick{background:none !important;color:#fff !important;}
    </style>
 <div class="alert">
        <ul>
            <li><?=__('1、最多可上传5张幻灯片图片。')?></li>
            <li><?=__('2、支持jpg、jpeg、gif、png格式上传，建议图片宽度1200px、高度在300px到500px之间、大小1.00M以内的图片。提交2~5张图片可以进行幻灯片播放，一张图片没有幻灯片播放效果。')?></li>
            <li><?=__('3、操作完成以后，按"提交"按钮，可以在当前页面进行幻灯片展示。')?></li>
            <li><?=__('4、跳转链接必须带有')?><b style="color:red;"><?=__('"http://"')?></b></li>
        </ul>
    </div>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.slideBox.min.js" charset="utf-8"></script>
    <div class="slide" id="slides">
     <ul class="items fn-clear">
         
                   
                <?php if(!empty($de['slide'])){
                    foreach ($de['slide'] as $key => $value) {
                        if($value){
                   
                  ?>
                <li><img src="<?=$value?>"></li>
                        <?php } }}else{ ?>
                <li><img src="<?=$this->view->img?>/default/seller/f01.jpg"></li>
                <li><img src="<?=$this->view->img?>/default/seller/f02.jpg"></li>
                <li><img src="<?=$this->view->img?>/default/seller/f03.jpg"></li>
                <?php } ?>
          
        </ul>
    </div>
    <form method="post" id="form">
       
        <div class="handle_pic fn-clear clearfix">
       <?php foreach ($array as $key => $value) {
                       
                    ?>
        <table width="20%">
            <tr>
                <td>
                    <div class="picture">
                        <img id="slide_img<?=$key?>" src="<?php if(!empty($de['slide'][$key])){echo $de['slide'][$key]; }?>" />
                    </div>
                    <input type="hidden" value="<?php if(!empty($de['slide'][$key])){echo $de['slide'][$key]; }?>" name="slide[]" id="slide_input<?=$key?>" class="text w145">
                </td>
            </tr>
            <tr>
                <td>
                 <p><?=__('跳转URL...')?></p>
                 <input id="url<?=$key?>" type="text" value="<?php if(!empty($de['slideurl'][$key])){ echo $de['slideurl'][$key];}else{ ?>http://<?php }?>" name="slideurl[]" class="text">   
                </td>
            </tr>
            <tr>
                <td>
                <a class="del button" href="javascript:del(<?=$value?>);"><?=__('删除')?></a>
                <a  id="slide_upload<?=$key?>" class="lblock bbc_img_btns"><i class="iconfont icon-tupianshangchuan"></i><?=__('图片上传')?></a>
                </td>
            </tr>
        </table>
       <?php  } ?>
        </div>
        <dl class="handle_pic_foot">
            <dd><input type="submit" value="<?=__('提交')?>" class="button bbc_seller_submit_btns"></dd>
        </dl>
    </form>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
		$("#slides").slideBox();
		function del(id)
		{
			$("#slide_img"+id).attr("src","");
			$("#slide_input"+id).val("");
			$("#url"+id).val("http://");	
		}
   
    </script>
     <script>

    //图片上传
    $(function(){

         slide_upload1 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#slide_img1',
                            uploadButton: '#slide_upload1',
                            inputHidden: '#slide_input1'
              });
      slide_upload2 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#slide_img2',
                            uploadButton: '#slide_upload2',
                            inputHidden: '#slide_input2'
              });
      slide_upload3 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#slide_img3',
                            uploadButton: '#slide_upload3',
                            inputHidden: '#slide_input3'
              });
      slide_upload4 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#slide_img4',
                            uploadButton: '#slide_upload4',
                            inputHidden: '#slide_input4'
              });
      slide_upload0 = new UploadImage({
                            thumbnailWidth: 1200,
                            thumbnailHeight: 360,
                            imageContainer: '#slide_img0',
                            uploadButton: '#slide_upload0',
                            inputHidden: '#slide_input0'
              });

      
    
    })
    
    
</script>
<script>
    $(document).ready(function(){
         var ajax_url = './index.php?ctl=Seller_Shop_Setshop&met=editSlide&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
           valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
                            Public.tips.success("<?=__('操作成功！')?>");
                            setTimeout(function(){
                                window.location.reload();
                            },3000); //成功后跳转
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