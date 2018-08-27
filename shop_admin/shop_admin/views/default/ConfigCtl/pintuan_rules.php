<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<div class="wrapper page" id="wrapper_page" style="display:none">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?=$menus['father_menu']['menu_name']?></h3>
                <h5><?=$menus['father_menu']['menu_url_note']?></h5>
            </div>
            <ul class="tab-base nc-row">
                <?php
                foreach($menus['brother_menu'] as $key=>$val){
                    if(in_array($val['rights_id'],$admin_rights)||$val['rights_id']==0){
                        ?>
                        <li><a <?php if(!array_diff($menus['this_menu'], $val)){?> class="current"<?php }?> href="<?= Yf_Registry::get('url') ?>?ctl=<?=$val['menu_url_ctl']?>&met=<?=$val['menu_url_met']?><?php if($val['menu_url_parem']){?>&<?=$val['menu_url_parem']?><?php }?>"><span><?=$val['menu_name']?></span></a></li>
                        <?php
                    }
                }
                ?>

            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
        </div>
        <ul>
            <?=$menus['this_menu']['menu_url_note']?>
        </ul>
    </div>

    <div class="wrapper">
        <form method="post" enctype="multipart/form-data" id="pintuan_rules_form" name="form1">
            <input type="hidden" name="config_type[]" value="pintuan_rules"/>

            <div class="ncap-form-default">
                <dl class="row">
                    <dt class="tit">
                        <label for="help_info">*拼团规则描述</label>
                    </dt>
                    <dd class="opt">
                        <textarea id="help_info" name="pintuan_rules[pintuan_rule_description]" style="width:800px;height: 300px!important;"><?= $data['pintuan_rule_description']['config_value']; ?></textarea>
                        <!-- 配置文件 -->
                        <script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.config.js"></script>
                        <!-- 编辑器源码文件 -->
                        <script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.all.js"></script>
                        <script type="text/javascript" src="<?= $this->view->js_com ?>/ueditor/ueditor.parse.js"></script>
                        <script type="text/javascript" src="<?= $this->view->js_com ?>/upload/addCustomizeButton.js"></script>
                        <!-- 实例化编辑器 -->
                        <script type="text/javascript">
                            var ue = UE.getEditor('help_info', {
                                toolbars: [
                                    [
                                        'bold', 'italic', 'underline', 'forecolor', 'justifyleft', 'justifycenter', 'justifyright','blockquote','link', 'removeformat','fontsize','paragraph'
                                    ]
                                ],
                                autoClearinitialContent: false,
                                //关闭字数统计
                                wordCount: false,
                                //关闭elementPath
                                elementPathEnabled: false,
                                initialFrameHeight:500,
                                autoHeightEnabled: false,
                                autoFloatEnabled: true
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
</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

<script>

    function show()
    {
        document.getElementById("wrapper_page").style.display="";
    }


    setTimeout("show()",300);    //设置页面加载后1秒显示内容层


    //按钮先执行验证再提交表单
    $(function(){

        $('#pintuan_rules_form').validator({
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
                        Public.ajaxPost(SITE_URL + '?ctl=Config&met=edit&typ=json', $('#pintuan_rules_form').serialize(), function (data)
                        {
                            if (data.status == 200)
                            {
                                Public.tips({content: '修改操作成功！'});
                            }
                            else
                            {
                                Public.tips({type: 1, content: data.msg || '操作无法成功，请稍后重试！'});
                            }
                        });
                    }
                )
            },
        }).on("click", "a.submit-btn", function (e)
        {
            $(e.delegateTarget).trigger("validate");
        });
    });
</script>
