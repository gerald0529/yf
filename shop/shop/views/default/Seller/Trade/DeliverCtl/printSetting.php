<?php if (!defined('ROOT_PATH')) exit('No Permission');?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
</head>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<body>
	
	<!---  BEGIN 发货单打印设置 --->
	<form id="form" method="post" name="form">
        <div class="form-style">
            
			<dl>
                <dt><i>*</i><?=__('备注信息：')?></dt>
                <dd>
                    <textarea name="shop_print_desc" cols="150" rows="3" class="text textarea w450" id="shop_print_desc"><?=@$data['shop_print_desc']?></textarea>
                    <p class="hint"><?=__('打印备注信息将出现在打印订单的下方位置，用于注明店铺简介或发货、退换货相关规则等；内容不要超过100字。')?></p>
                </dd>
            </dl>
			
			<dl>
                <dt><i>*</i><?=__('印章图片')?>：</dt>
				<dd>
					 <div class="ncsc-upload-thumb voucher-pic">
                        <p><i class="icon-picture"></i></p>
                     </div>
                     <p class="pic image_review" style="width:120px;height:120px;">
                        <img id="image_review" src="<?=@$data['shop_stamp']?>" height="120" width="120" />
                     </p>
                     <p class="upload-button">
                        <input type="hidden" id="shop_stamp" name="shop_stamp" value="<?=@$data['shop_stamp']?>" />
                        <div  id='logo_upload' class="lblock upload-bg" style=""><i class="iconfont icon-tupianshangchuan"></i><?=__('图片上传')?>
                        </div>
                     </p>
					 <p class="hint"><?=__('印章图片将出现在打印订单的右下角位置，请选择120x120px大小透明JPG/GIF/PNG格式图片上传作为您店铺的电子印章使用。')?></p>
				</dd>
            </dl>
			
            <dl>
                <dt></dt>
                <dd>
                    <input name="act" value="quota" type="hidden">
                    <input class="button button_red bbc_seller_submit_btns" value="<?=__('保存')?>" type="submit">
                </dd>
            </dl>
			
        </div>
    </form>
	<!---  END 发货单打印设置 --->

    <!--- 表单验证 --->
    <script type="text/javascript">
        $(document).ready(function(){
            $('#form').validator({
                debug:true,
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,

                fields: {
                    'shop_print_desc': 'required;length[6~100]',
                    'shop_stamp' : 'required',
                },
				valid: function(form){
					var me = this;
					// 提交表单之前，hold住表单，并且在以后每次hold住时执行回调
					me.holdSubmit(function(){
						Public.tips.error('<?=__('正在处理中')?>...');
					});
					$.ajax({
						url: "index.php?ctl=Seller_Trade_Deliver&met=printSetting&op=save&typ=json",
						data: $(form).serialize(),
						type: "POST",
						success:function(e){
							if(e.status == 200)
							{
								Public.tips.success('<?=__('操作成功')?>!');
								setTimeout('location.href="index.php?ctl=Seller_Trade_Deliver&met=printSetting&typ=e"',3000);//成功后跳转
							}
							else
							{
								Public.tips.error('<?=__('操作失败')?>！');
							}
							me.holdSubmit(false);
						}
					});
				}
             });

             //图片上传
            $('#logo_upload').on('click', function () {
				$.dialog({
					   title: '<?=__('图片裁剪')?>',
					   content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
					   data: {width:120,height:120 , callback: callback1 },    // 需要截取图片的宽高比例
					   width: '800px',
					   lock: true
				})
			});

			function callback1( respone , api ) {
				$('#image_review').attr('src', respone.url);
				$('.image_review').show();
				$('#shop_stamp').attr('value', respone.url);
				api.close();
			}

			if ( window.isIE8 ) {
				$('#logo_upload').off('click');

				logo_uploadss = new UploadImage({
					thumbnailWidth: 200,
					thumbnailHeight: 60,
					imageContainer: '#image_review',
					uploadButton: '#logo_upload',
					inputHidden: '#shop_stamp'
				});
			}
        });
    </script>
    <!--- END 表单验证 --->
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>