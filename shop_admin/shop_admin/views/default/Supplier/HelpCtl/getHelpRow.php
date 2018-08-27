<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<div class="wrapper">
        <form method="post" enctype="multipart/form-data" id="shop_help_form" name="form1">
        <input type="hidden" name="shop_help_id" value="<?=($data['shop_help_id'])?>"/>

        <div class="ncap-form-default">
         <dl class="row">
                <dt class="tit">
                    <label for="help_title">*帮助标题</label>
                </dt>
                <dd class="opt">
                    <input id="help_title" name="help[help_title]" value="<?=($data['help_title'])?>" class="ui-input w200" type="text"/>
                </dd>
            </dl>
                  <dl class="row">
                <dt class="tit">
                    <label for="help_sort">*排序</label>
                </dt>
                <dd class="opt">
                    <input id="help_sort" name="help[help_sort]" value="<?=($data['help_sort'])?>" class="ui-input w200" type="text"/>
                </dd>
            </dl>
              <dl class="row">
                <dt class="tit">
                    <label for="help_info">*帮助内容</label>
                </dt>
                <dd class="opt">
                    <textarea id="help_info" name="help[help_info]" style="width:800px;height: 300px;"><?=($data['help_info'])?></textarea>
                     <!-- 配置文件 -->
                    <script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.config.js"></script>
                    <!-- 编辑器源码文件 -->
                    <script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.all.js"></script>
                    <script type="text/javascript" src="<?= $this->view->js_com ?>/upload/addCustomizeButton.js"></script>
                    <!-- 实例化编辑器 -->
                    <script type="text/javascript">
                        var ue = UE.getEditor('help_info', {
                            toolbars: [
                                [
                                    'bold', 'italic', 'underline', 'forecolor', 'backcolor', 'justifyleft', 'justifycenter', 'justifyright', 'insertunorderedlist', 'insertorderedlist', 'blockquote',
                                    'emotion', 'insertvideo', 'link', 'removeformat', 'rowspacingtop', 'rowspacingbottom', 'lineheight', 'paragraph', 'fontsize', 'inserttable', 'deletetable', 'insertparagraphbeforetable',
                                    'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols'
                                ]
                            ],
                            autoClearinitialContent: false,
                            //关闭字数统计
                            wordCount: false,
                            //关闭elementPath
                            elementPathEnabled: false
                        });
                    </script>
                    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
                    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>

                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
 <script>
//按钮先执行验证再提交表单
	$(function(){

         $('#shop_help_form').validator({
            ignore: ':hidden',
            theme: 'yellow_bottom',
            timely: 1,
            stopOnError: true,
          fields: {
                'help[help_sort]': 'required;integer[+];range[0~255 ]',
                'help[help_info]': 'required;',
                'help[help_title]': 'required;'
            },
            valid: function (form)
            {
                parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
                    {
                        Public.ajaxPost(SITE_URL + '?ctl=Shop_Help&met=editHelp&typ=json', $('#shop_help_form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                parent.Public.tips({content: '修改操作成功！'});
                                 var callback = frameElement.api.data.callback;
                                   callback();
                            }
                            else
                            {
                                parent.Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                            }
                        });
                    },
                    function ()
                    {

                    });
            },
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
});
</script> 
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>