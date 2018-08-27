<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<!-- 配置文件 -->
<script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.all.js"></script>

<script type="text/javascript" src="<?= $this->view->js_com ?>/upload/addCustomizeButton.js"></script>
<style>
</style>
</head>
<body>
<form id="article_form" method="post">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="member_agreement_title"><em>*</em>标题</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="member_agreement_title" id="member_agreement_title" class="ui-input">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>文章内容</label>
        </dt>
        <dd class="opt">
            <!-- 加载编辑器的容器 -->
            <textarea id="member_agreement_content" style="width:700px;height:300px;" name="content" type="text/plain">

            </textarea>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">图片上传</dt>
        <dd class="opt">
            <img id="member_agreement_image" name="setting[member_agreement_logo]" alt="选择图片" src="" class="image-line" />
            <div class="image-line"  id="member_agreement_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>
            <input id="member_agreement_logo" name="setting[member_agreement_logo]" value="" class="ui-input w400" type="hidden"/>
        </dd>
      </dl>
    </div>
  </form>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('member_agreement_content', {
        toolbars: [
            [
             'bold', 'italic', 'underline', 'forecolor', 'backcolor', 'justifyleft', 'justifycenter', 'justifyright', 'insertunorderedlist', 'insertorderedlist', 'blockquote',
             'emotion', 'insertvideo', 'link', 'removeformat', 'rowspacingtop', 'rowspacingbottom', 'lineheight', 'paragraph', 'fontsize', 'inserttable', 'deletetable', 'insertparagraphbeforetable',
             'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols'
            ]
        ],
        autoClearinitialContent: true,
        //关闭字数统计
        wordCount: false,
        //关闭elementPath
        elementPathEnabled: false
    });
</script>
<script>
//图片上传
$(function(){
    member_agreement_pic_upload = new UploadImage({
        thumbnailWidth: 240,
        thumbnailHeight: 60,
        imageContainer: '#member_agreement_image',
        uploadButton: '#member_agreement_upload',
        inputHidden: '#member_agreement_logo'
    });
})
</script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/member/agreement_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>