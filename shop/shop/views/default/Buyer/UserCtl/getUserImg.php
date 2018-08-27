<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
</div>
            <form action="" enctype="multipart/form-data" id="form" name="form" method="post">
              <input type="hidden" name="form_submit" value="ok">
              <div class="ncm-default-form">
                <dl>
                  <dt><?=__('头像预览：')?></dt>
                  <dd>
                    <div class="user-avatar"><span><img  id="image_img"  src="<?php if(!empty($this->user['info']['user_logo'])){ echo image_thumb($this->user['info']['user_logo'],120,120);}else{echo image_thumb($this->web['user_logo'],120,120); } ?>" width="120" height="120" nc_type="avatar"></span></div>
                    <p class="hint mt5"><?=__('完善个人信息资料，上传头像图片有助于您结识更多的朋友。')?><br><span style="color:orange;"><?=__('头像默认尺寸为120x120像素，请根据系统操作提示进行裁剪并生效。')?></span></p>
                  </dd>
                </dl>
                <dl>
                  <dt><?=__('更换头像：')?></dt>
                  <dd>
                    <div > <a href="javascript:void(0);"><span>
                     <input name="user_logo" id="user_logo" type="hidden" value="<?=$this->user['info']['user_logo']?>" />
                      </span>
                      <p id='user_upload' style="float:left;" class="bbc_btns"><i class="iconfont icon-upload-alt"></i><?=__('图片上传')?></p>
                      
                      </a> </div>
                  </dd>
                </dl>
				<dl class="bottom">
                      <dt></dt>
                      <dd>
                        <label class="submit-border">
                          <input type="submit" class="submit bbc_btns" value="<?=__('保存修改')?>">
                        </label>
                      </dd>
                </dl>
              </div>
            </form>
        </div>
      </div>
    </div>
  
</div>
</div>
</div>
</div>
  </div>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script>

    //图片上传
    $(function(){
		$('#user_upload').on('click', function () {
			$.dialog({
				title: "<?=__('图片裁剪')?>",
				content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
				data: { width: 120, height: 120, callback: callback },    // 需要截取图片的宽高比例
				width: '800px',
				/*height: '310px',*/
				lock: true
			})
		});

		function callback ( respone , api ) {
			$('#image_img').attr('src', respone.url);
			$('#user_logo').attr('value', respone.url);
			api.close();
		}

        if ( window.isIE8 ) {
            $('#user_upload').off("click");
            new UploadImage({
                 thumbnailWidth: 120,
                 thumbnailHeight: 120,
                 imageContainer: '#image_img',
                 uploadButton: '#user_upload',
                 inputHidden: '#user_logo'
             });
        }

    })
	//表单提交
	$(document).ready(function(){      
        var ajax_url = SITE_URL +'?ctl=Buyer_User&met=editUserImg&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {               
            },
            valid:function(form){
                //表单验证通过，提交表单
                $.ajax({
                    url: ajax_url,
                    data:$("#form").serialize(),
                    success:function(a){
                        if(a.status == 200)
                        {
							Public.tips.success("<?=__('操作成功')?>");
                            location.href= SITE_URL +"?ctl=Buyer_User&met=getUserImg";
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
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>