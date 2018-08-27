<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
</div>

              <input type="hidden" name="form_submit" value="ok">
              <div class="ncm-default-form">
                <dl>
                  <dt><?=_('头像预览：')?></dt>
                  <dd>
                      <div class="user-avatar">


                          <div class="container" id="crop-avatar" style="    margin-left: 0; width: 220px;">
                              <!-- Current avatar -->
                              <div class="avatar-view" title="修改头像">
                                  <img id='avatarEdit' src="<?php if (!empty($this->user['info']['user_avatar'])) {
                                  echo image_thumb($this->user['info']['user_avatar'], 120, 120);
                              } else {
                                  echo image_thumb($this->web['user_avatar'], 120, 120);
                              } ?>" alt="头像">
                              </div>

                              <!-- Cropping modal -->
                              <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                                  <div class="modal-dialog modal-lg">
                                      <div class="modal-content">
                                          <form class="avatar-form" action="./libraries/crop.php" enctype="multipart/form-data" method="post">
                                              <div class="modal-header">
                                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                  <h4 class="modal-title" id="avatar-modal-label">修改头像</h4>
                                              </div>
                                              <div class="modal-body">
                                                  <div class="avatar-body">

                                                      <!-- Upload image and data -->
                                                      <div class="avatar-upload">
                                                          <input type="hidden" class="avatar-src" name="avatar_src">
                                                          <input type="hidden" class="avatar-data" name="avatar-data">
                                                          <label for="avatarInput">上传</label>
                                                          <input type="file" class="avatar-input" id="avatarInput" name="avatar_file">
                                                      </div>

                                                      <!-- Crop and preview -->
                                                      <div class="row">
                                                          <div class="col-md-9">
                                                              <div class="avatar-wrapper"></div>
                                                          </div>

                                                      </div>

                                                      <div class="row avatar-btns">

                                                          <div class="col-md-3">
                                                              <button type="submit" class="btn btn-primary btn-block avatar-save">上传</button>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                          </form>
                                      </div>
                                  </div>
                              </div><!-- /.modal -->

                              <!-- Loading state -->
                              <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                          </div>

                      </div>
                    <p class="hint mt5"><?=_('完善个人信息资料，上传头像图片有助于您结识更多的朋友。')?><br><span style="color:orange;"><?=_('头像默认尺寸为120x120像素，请根据系统操作提示进行裁剪并生效。')?></span></p>
                  </dd>
                </dl>

				<dl class="bottom">
                      <dt></dt>
                      <dd>
                        <label class="submit-border">
                          <input type="button" id="submit"  class="submit bbc_btns" value="<?=_('保存修改')?>">
                        </label>
                      </dd>
                </dl>
              </div>
            <!--</form>-->
        </div>
      </div>
    </div>

</div>
</div>
</div>
</div>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="<?= $this->view->stc?>/cropper/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?= $this->view->stc?>/cropper/cropper.css" />
<link rel="stylesheet" type="text/css" href="<?= $this->view->stc?>/cropper/main.css" />
<script type="text/javascript" src="<?= $this->view->stc?>/cropper/bootstrap.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->stc?>/cropper/cropper.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->stc?>/cropper/main.js" charset="utf-8"></script>

<script>
//    //图片上传
//    $(function(){
//
//        function upload_image() {
//            var user_upload = new UploadImage({
//                thumbnailWidth: 120,
//                thumbnailHeight: 120,
//                imageContainer: '#image_img',
//                uploadButton: '#user_upload',
//                inputHidden: '#user_avatar'
//            });
//        }
//
//        var agent = navigator.userAgent.toLowerCase();
//        if ( agent.indexOf("msie") > -1 && (version = agent.match(/msie [\d]/), ( version == "msie 8" || version == "msie 9" )) ) {
//            upload_image();
//        } else {
//            cropper_image();
//        }
//
//        function cropper_image() {
//            $('#user_upload').on('click', function () {
//                $.dialog({
//                    title: '图片裁剪',
//                    content: "url: <?//= Yf_Registry::get('url') ?>//?ctl=Upload&met=cropperImage&typ=e",
//                    data: { width: 120, height: 120, callback: callback },    // 需要截取图片的宽高比例
//                    width: '800px',
//                    lock: true
//                })
//            });
//
//            function callback ( respone , api ) {
//                $('#image_img').attr('src', respone.url);
//                $('#user_avatar').attr('value', respone.url);
//                api.close();
//            }
//        }
//    })
//	//表单提交
	$(document).ready(function(){
        $('#submit').click(function(){
            var ajax_url = SITE_URL +'?ctl=User&met=editUserImg&typ=json';
            $.post(ajax_url,{user_avatar: $('#avatarEdit').attr('src')},function (a) {
                if(a.status == 200)
                {
                    Public.tips.success("<?=_('操作成功')?>");
                    setTimeout('location.href= SITE_URL +"?ctl=User&met=getUserImg"',1000);//成功后跳转

                }
                else
                {
                    Public.tips.error("<?=_('操作失败！')?>");
                }
            });
        });


    });
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>