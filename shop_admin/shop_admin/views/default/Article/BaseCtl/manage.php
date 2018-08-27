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
          <label for="article_title"><em>*</em>标题</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="article_title" id="article_title" class="ui-input">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
            <label><em>*</em>文章分类:</label>
        </dt>
        <dd class="opt">
            <div class="ctn-wrap"><span id="type"></span></div>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="article_url">链接</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="article_url" id="article_url" class="ui-input">
          <span class="err"></span>
          <p class="notic">当填写&quot;链接&quot;后点击文章标题将直接跳转至链接地址，不显示文章内容。链接格式请以http://开头</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="if_show">是否启用:</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="article_status1" class="cb-enable  ">启用</label>
            <label for="article_status2" class="cb-disable  selected">关闭</label>
            <input id="article_status1"  name ="article_status"  value="1" type="radio">
            <input id="article_status2"  name ="article_status"  checked="checked"  value="2" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
	  <dl class="row">
        <dt class="tit">
          <label for="if_show">是否公告:</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="article_type1" class="cb-enable  ">是</label>
            <label for="article_type2" class="cb-disable  selected">否</label>
            <input id="article_type1"  name ="article_type"  value="1" type="radio">
            <input id="article_type2"  name ="article_type"  checked="checked"  value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">排序</dt>
        <dd class="opt">
          <input type="text" value="" name="article_sort" id="article_sort" class="ui-input">
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
            <textarea id="article_desc" style="width:700px;height:300px;" name="content" type="text/plain">

            </textarea>
        </dd>
      </dl>
    </div>
  </form>
<!-- 实例化编辑器 -->
<script type="text/javascript">
    var ue = UE.getEditor('article_desc', {
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
<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/article/base_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>