<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
</style>
</head>
<body>
<form id="article_form" method="post">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="position_title"><em>*</em>标题</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="position_title" id="position_title" class="ui-input">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label><em>*</em>类型:</label>
        </dt>
        <dd class="opt">
            <div class="onoff">
                <label for="position_type1" class="cb-enable  ">文字</label>
                <label for="position_type0" class="cb-disable  selected">图片</label>
                <input id="position_type1"  name ="position_type"  value="1" type="radio">
                <input id="position_type0"  name ="position_type"  checked="checked"  value="0" type="radio">
            </div>
            <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row" id="add_pic">
        <div>
            <dt class="tit">
              <label for="position_url">添加图片</label>
            </dt>
            <dd class="opt">
                <img id="position_image" name="setting[position_logo]" alt="选择图片" src="./shop_admin/static/common/images/image.png" class="image-line" />
                <div class="image-line"  id="position_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>
                <input id="position_logo" name="setting[position_logo]" value="" class="ui-input w400" type="hidden"/>
                跳转网址:<input type="text" value="http://" name="position_url" id="position_url_pic" class="ui-input">
            </dd>
        </div>
      </dl>
      <dl class="row" id="add_content" style="display: none;">
        <div>
            <dt class="tit">
              <label for="position_url">添加文字:</label>
            </dt>
            <dd class="opt">
                <input type="text" value="" name="position_content" id="position_content" class="ui-input">
                跳转网址:<input type="text" value="http://" name="position_url" id="position_url_con" class="ui-input">
            </dd>
        </div>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>代码内容</label>
        </dt>
        <dd class="opt">
            <textarea class="ui-input" id="position_code" name="position_code"></textarea>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="if_show">弹出方式:</label>
        </dt>
        <dd class="opt">
          <div class="">
            <input  id="position_alert_type0"  name ="position_alert_type"  value="0" type="radio">当前窗口
            <input  id="position_alert_type1"  name ="position_alert_type"  checked="checked"  value="1" type="radio">新窗口
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
    </div>
  </form>
<script>
//图片上传
$(function(){
    position_logo_upload = new UploadImage({
        thumbnailWidth: 240,
        thumbnailHeight: 60,
        imageContainer: '#position_image',
        uploadButton: '#position_upload',
        inputHidden: '#position_logo'
    });
})
$("#position_type1").click(function(){
    $("#add_content").show();
    $("#add_pic").hide();
});
$("#position_type0").click(function(){
    $("#add_content").hide();
    $("#add_pic").show();
});
</script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/rec/position_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>