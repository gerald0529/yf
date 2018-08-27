<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
if(isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id'] > 0){
    $subsite_suffix = '_'.Perm::$row['sub_site_id'];
}else{
    $subsite_suffix = '';
}
// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();
?>
    <link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    <link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
    </head>
    <style>
        .image-line {
            margin-bottom:5px;
        }
    </style>
    <body>
    <div class="wrapper page">
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
        <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
            <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
                <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
            </div>
            <ul>
                <?=$menus['this_menu']['menu_url_note']?>
            </ul>
        </div>

        <form method="post" enctype="multipart/form-data" id="slider-setting-form" name="form1">
            <input type="hidden" name="config_type[]" value="pintuan_slider"/>
            <div class="ncap-form-default">
                <dl class="row">
                    <dt class="tit">
                        <label>滚动图片1</label>
                    </dt>
                    <dd class="opt">
                        <img id="slider1_review" src="<?=@($data['pintuan_slider_image1'.$subsite_suffix]['config_value'])?>" width="400"/>
                        <input type="hidden" id="pintuan_slider_image1" name="pintuan_slider<?=$subsite_suffix?>[pintuan_slider_image1<?=$subsite_suffix?>]" value="<?=@($data['pintuan_slider_image1'.$subsite_suffix]['config_value'])?>" />
                        <div  id='slider1_upload' class="image-line upload-image" >图片上传</div>

                        <label title="请输入图片要跳转的链接地址" class="mt10"><i class="fa fa-link"></i>
                            <input class="ui-input w400" type="text" name="pintuan_slider<?=$subsite_suffix?>[pintuan_slider_link1<?=$subsite_suffix?>]" value="<?=@($data['pintuan_slider_link1'.$subsite_suffix]['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
                        </label>
                        <span class="err"><label for="pintuan_slider_link1" class="error valid"></label></span>
                        <p class="notic">请使用宽度1043像素，高度396像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
                            如需跳转请在后方添加以http://开头的链接地址。</p>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit">
                        <label>滚动图片2</label>
                    </dt>
                    <dd class="opt">
                        <img id="slider2_review" src="<?=@($data['pintuan_slider_image2'.$subsite_suffix]['config_value'])?>" width="400"/>
                        <input type="hidden" id="pintuan_slider_image2" name="pintuan_slider<?=$subsite_suffix?>[pintuan_slider_image2<?=$subsite_suffix?>]" value="<?=@($data['pintuan_slider_image2'.$subsite_suffix]['config_value'])?>" />
                        <div  id='slider2_upload' class="image-line upload-image" >图片上传</div>

                        <label title="请输入图片要跳转的链接地址" class="mt10"><i class="fa fa-link"></i>
                            <input class="ui-input w400" type="text" name="pintuan_slider<?=$subsite_suffix?>[pintuan_slider_link2<?=$subsite_suffix?>]" value="<?=@($data['pintuan_slider_link2'.$subsite_suffix]['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
                        </label>
                        <span class="err"><label for="pintuan_slider_link2" class="error valid"></label></span>
                        <p class="notic">请使用宽度1043像素，高度396像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
                            如需跳转请在后方添加以http://开头的链接地址。</p>
                    </dd>
                </dl>


                <dl class="row">
                    <dt class="tit">
                        <label>滚动图片3</label>
                    </dt>
                    <dd class="opt">
                        <img id="slider3_review" src="<?=@($data['pintuan_slider_image3'.$subsite_suffix]['config_value'])?>" width="400"/>
                        <input type="hidden" id="pintuan_slider_image3" name="pintuan_slider<?=$subsite_suffix?>[pintuan_slider_image3<?=$subsite_suffix?>]" value="<?=@($data['pintuan_slider_image3'.$subsite_suffix]['config_value'])?>" />
                        <div  id='slider3_upload' class="image-line upload-image" >图片上传</div>

                        <label title="请输入图片要跳转的链接地址" class="mt10"><i class="fa fa-link"></i>
                            <input class="ui-input w400" type="text" name="pintuan_slider<?=$subsite_suffix?>[pintuan_slider_link3<?=$subsite_suffix?>]" value="<?=@($data['pintuan_slider_link3'.$subsite_suffix]['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
                        </label>
                        <span class="err"><label for="pintuan_slider_link3" class="error valid"></label></span>
                        <p class="notic">请使用宽度1043像素，高度396像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
                            如需跳转请在后方添加以http://开头的链接地址。</p>
                    </dd>
                </dl>

                <dl class="row">
                    <dt class="tit">
                        <label>滚动图片4</label>
                    </dt>
                    <dd class="opt">
                        <img id="slider4_review" src="<?=@($data['pintuan_slider_image4'.$subsite_suffix]['config_value'])?>" width="400" />
                        <input type="hidden" id="pintuan_slider_image4" name="pintuan_slider<?=$subsite_suffix?>[pintuan_slider_image4<?=$subsite_suffix?>]" value="<?=@($data['pintuan_slider_image4'.$subsite_suffix]['config_value'])?>" />
                        <div  id='slider4_upload' class="image-line upload-image" >图片上传</div>

                        <label title="请输入图片要跳转的链接地址" class="mt10"><i class="fa fa-link"></i>
                            <input class="ui-input w400" type="text" name="pintuan_slider<?=$subsite_suffix?>[pintuan_slider_link4<?=$subsite_suffix?>]" value="<?=@($data['pintuan_slider_link4'.$subsite_suffix]['config_value'])?>" placeholder="请输入图片要跳转的链接地址">
                        </label>
                        <span class="err"><label for="pintuan_slider_link4" class="error valid"></label></span>
                        <p class="notic">请使用宽度1043像素，高度396像素的jpg/gif/png格式图片作为幻灯片banner上传，<br>
                            如需跳转请在后方添加以http://开头的链接地址。</p>
                    </dd>
                </dl>

                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </form>

        <script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>

        <script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
        <script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
        <script>
            $(function(){

                var agent = navigator.userAgent.toLowerCase();

                if ( agent.indexOf("msie") > -1 && (version = agent.match(/msie [\d]/), ( version == "msie 8" || version == "msie 9" )) ) {

                    new UploadImage({
                        thumbnailWidth: 1043,
                        thumbnailHeight: 396,
                        imageContainer: '#slider1_review',
                        uploadButton: '#slider1_upload',
                        inputHidden: '#pintuan_slider_image1'
                    });

                    new UploadImage({
                        thumbnailWidth: 1043,
                        thumbnailHeight: 396,
                        imageContainer: '#slider2_review',
                        uploadButton: '#slider2_upload',
                        inputHidden: '#pintuan_slider_image2'
                    });

                    new UploadImage({
                        thumbnailWidth: 1043,
                        thumbnailHeight: 396,
                        imageContainer: '#slider3_review',
                        uploadButton: '#slider3_upload',
                        inputHidden: '#pintuan_slider_image3'
                    });

                    new UploadImage({
                        thumbnailWidth: 1043,
                        thumbnailHeight: 396,
                        imageContainer: '#slider4_review',
                        uploadButton: '#slider4_upload',
                        inputHidden: '#pintuan_slider_image4'
                    });

                } else {
                    //图片上传
                    $('#slider1_upload').on('click', function () {
                        $.dialog({
                            title: '图片裁剪',
                            content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                            data: {SHOP_URL:SHOP_URL,width:1043,height:396 , callback: callback1 },    // 需要截取图片的宽高比例
                            width: '800px',
                            height:$(window).height()*0.9,
                            lock: true
                        })
                    });

                    function callback1 ( respone , api ) {
                        $('#slider1_review').attr('src', respone.url);
                        $('#pintuan_slider_image1').attr('value', respone.url);
                        api.close();
                    }

                    $('#slider2_upload').on('click', function () {
                        $.dialog({
                            title: '图片裁剪',
                            content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                            data: {SHOP_URL:SHOP_URL,width:1043,height:396 , callback: callback2 },    // 需要截取图片的宽高比例
                            width: '800px',
                            height:$(window).height()*0.9,
                            lock: true
                        })
                    });

                    function callback2 ( respone , api ) {
                        $('#slider2_review').attr('src', respone.url);
                        $('#pintuan_slider_image2').attr('value', respone.url);
                        api.close();
                    }

                    $('#slider3_upload').on('click', function () {
                        $.dialog({
                            title: '图片裁剪',
                            content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                            data: {SHOP_URL:SHOP_URL,width:1043,height:396 , callback: callback3 },    // 需要截取图片的宽高比例
                            width: '800px',
                            height:$(window).height()*0.9,
                            lock: true
                        })
                    });

                    function callback3 ( respone , api ) {
                        $('#slider3_review').attr('src', respone.url);
                        $('#pintuan_slider_image3').attr('value', respone.url);
                        api.close();
                    }


                    $('#slider4_upload').on('click', function () {
                        $.dialog({
                            title: '图片裁剪',
                            content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                            data: {SHOP_URL:SHOP_URL,width:1043,height:396 , callback: callback4 },    // 需要截取图片的宽高比例
                            width: '800px',
                            height:$(window).height()*0.9,
                            lock: true
                        })
                    });

                    function callback4 ( respone , api ) {
                        $('#slider4_review').attr('src', respone.url);
                        $('#pintuan_slider_image4').attr('value', respone.url);
                        api.close();
                    }
                }
            })
        </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>