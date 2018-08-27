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
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>&nbsp;</h3>
                <h5></h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a class=""><span>上传参数</span></a></li>
                <!--<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=upload&op=default_thumb"><span>默认图片</span></a></li>-->
            </ul>
        </div>
    </div>
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
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <?=$menus['this_menu']['menu_url_note']?>
        </ul>
    </div>
    <?php
  $max_upload_file_size =  $data['sys_max_upload_file_size'];
  ?>
    <form id="upload-setting-form" method="post" enctype="multipart/form-data" name="settingForm">
        <input type="hidden" name="config_type[]" value="upload"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="image_max_filesize">图片文件大小</label>
                </dt>
                <dd class="opt">大小 <input id="image_max_filesize" name="upload[image_max_filesize]" type="text" class="ui-input" style="width:40px !important;"
                                          value="<?=(@$data['image_max_filesize']['config_value'])?>"/>
                    KB&nbsp;(1024 KB = 1MB)
                    <p class="notic">当前服务器环境，最大允许上传 <?=$max_upload_file_size/1024?> MB 的文件，您的设置请勿超过该值。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">图片扩展名</label>
                </dt>
                <dd class="opt">
                    <input id="image_allow_ext" name="upload[image_allow_ext]" value="<?=(@$data['image_allow_ext']['config_value'])?>" class="ui-input w400" type="text"/>

                    <p class="notic">图片扩展名，用于判断上传图片是否为后台允许，多个后缀名间请用半角逗号 "," 隔开。</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="remote_image_url">远程图片上传地址</label>
                </dt>
                <dd class="opt">
                    <input id="remote_image_url" name="upload[remote_image_url]" value="<?=(@$data['remote_image_url']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic">例如:http://static.bbc-builder.com/uploader.php。</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="remote_image_key">远程图片上传Key</label>
                </dt>
                <dd class="opt">
                    <input id="remote_image_key" name="upload[remote_image_key]" value="<?=(@$data['remote_image_key']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic">图片服务器配置后,填写在此处</p>
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit">远程图片状态</dt>
                <dd class="opt">
                    <div class="onoff">
                        <input id="remote_image_status1" name="upload[remote_image_status]"  value="1" type="radio" <?=($data['remote_image_status']['config_value']==1 ? 'checked' : '')?>>
						<label title="开启" class="cb-enable <?=($data['remote_image_status']['config_value']==1 ? 'selected' : '')?> " for="remote_image_status1">开启</label>

                        <input id="remote_image_status0" name="upload[remote_image_status]"  value="0" type="radio" <?=($data['remote_image_status']['config_value']==0 ? 'checked' : '')?>>
						<label title="关闭" class="cb-disable <?=($data['remote_image_status']['config_value']==0 ? 'selected' : '')?>" for="remote_image_status0">关闭</label>
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>

            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var max_upload_file_size = <?= $max_upload_file_size ?>;
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>