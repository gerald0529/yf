<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<style>
body{min-width:200px;}
.manage-wrap{margin: 0px auto 10px;width:100%;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">

        <form method="post" enctype="multipart/form-data" id="manage-form" name="manage-form">
		 <input id="search_id" name="search_id" value="" class="ui-input w200" type="hidden"/>
        <div class="ncap-form-default">

              <dl class="row">
                <dt class="tit">
                    <label for="search_keyword"><em>*</em>搜索词: </label>
                </dt>
                <dd class="opt">
                    <input id="search_keyword" name="search_keyword" value="" class="ui-input w200" type="text"/>
					<p class="notic"> 搜索词参于搜索，例：童装。</p>
                </dd>
              </dl>
             <dl class="row">
                <dt class="tit">
                    <label for="search_char_index"><em>*</em>显示词 : </label>
                </dt>
                <dd class="opt">
                    <input id="search_char_index" name="search_char_index" value="" class="ui-input w200" type="text"/>
                     <span class="err"></span>
                     <p class="notic">显示词不参于搜索，只起显示作用，例：61儿童节</p>
                </dd>
              </dl>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/search/search_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>