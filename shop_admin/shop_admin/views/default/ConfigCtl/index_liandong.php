<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
    include $this->view->getTplPath() . '/' . 'header.php';
?>
    <link href="<?= $this->view->css ?>/index.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    <link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
    <style>
        .webuploader-pick {
            padding: 1px;
        }
    </style>
    </head>
    <body>
    <div class="wrapper page">
        <div class="fixed-bar">
            <div class="item-title">
                <div class="subject">
                    <h3>模板风格</h3>
                    <h5>首页幻灯将在首页展示</h5>
                </div>
                <ul class="tab-base nc-row">
                    <?php
                        $data_theme = $this->getUrl('Config', 'siteTheme', 'json', null, array('config_type' => array('site')));
                        
                        $theme_id = $data_theme['theme_id']['config_value'];
                        
                        foreach ($data_theme['theme_row'] as $k => $theme_row) {
                            if ($theme_id == $theme_row['name']) {
                                $config = $theme_row['config'];
                                break;
                            }
                        }
                    ?>
                    <?php if (isset($config['index_tpl']) && $config['index_tpl']): ?>
                        <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Floor_Adpage&met=adpage"><span>首页模板</span></a></li>
                    <?php endif; ?>
                    <?php if (isset($config['index_slider']) && $config['index_slider']): ?>
                        <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_slider&config_type%5B%5D=index_slider"><span>首页幻灯片</span></a></li>
                    <?php endif; ?>
                    <?php if (isset($config['index_slider_img']) && $config['index_slider_img']): ?>
                        <li><a class="current" href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=index_liandong&config_type%5B%5D=index_liandong"><span>首页联动小图</span></a></li>
                    <?php endif; ?>
                
                
                </ul>
            </div>
        </div>
        <p class="warn_xiaoma"><span></span><em></em></p>
        <div class="explanation" id="explanation">
            <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
                <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
                <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
            </div>
            <ul>
                <li>该组图片应用于首页使用，最多可上传2张图片。</li>
                <li>图片要求使用宽度为236像素，高度为236像素jpg/gif/png格式的图片。</li>
                <li>上传图片后请添加格式为“http://网址...”链接地址。</li>
            </ul>
        </div>
        
        <form method="post" enctype="multipart/form-data" id="index_liandong-setting-form" name="form1">
            <input type="hidden" name="config_type[]" value="index_liandong" />
            <div class="ncap-form-default">
                <dl class="row">
                    <dt class="tit">
                        <label>首页图片1</label>
                    </dt>
                    <dd class="opt">
                        <div>
                            <div class="div-image">
                                <img id="index_liandong1_review" src="
                                    <?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                                        echo @$data[Perm::$row['sub_site_id'] . 'index_liandong_image1']['config_value'];
                                    } else {
                                        echo @$data['index_liandong_image1']['config_value'];
                                    } ?>
                                " width="236" height="236" />
                                <a href="javascript:void(0)" style="display: <?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {echo 'none';}else{echo 'block';}?>" class="del" id="del_btn1" onclick="delImage('index_liandong1_review')" title="移除"><i class="iconfont icon-cancel"></i></a>
                            </div>
                        </div>
                        <input type="hidden" id="index_liandong1_image" name="index_liandong[<?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                            echo Perm::$row['sub_site_id'] . "index_liandong_image1";
                        } else {
                            echo "index_liandong_image1";
                        } ?>]" value="<?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                            echo @$data[Perm::$row['sub_site_id'] . 'index_liandong_image1']['config_value'];
                        } else {
                            echo @$data['index_liandong_image1']['config_value'];
                        } ?>" />
                        <div id='index_liandong1_upload' class="image-line upload-image">图片上传</div>
                        
                        <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                            <input class="ui-input w400" style="margin:5px 0" type="text" name="index_liandong[<?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                                echo Perm::$row['sub_site_id'] . "index_liandong_url1";
                            } else {
                                echo "index_liandong_url1";
                            } ?>]" value="<?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                                echo @$data[Perm::$row['sub_site_id'] . 'index_liandong_url1']['config_value'];
                            } else {
                                echo @$data['index_liandong_url1']['config_value'];
                            } ?>" placeholder="请输入图片要跳转的链接地址">
                        </label>
                        <span class="err"><label for="index_liandong_url1" class="error valid"></label></span>
                        <p class="notic">请使用宽度236像素，高度236像素的jpg/gif/png格式图片作为联动图片上传<br>
                            如需跳转请在后方添加以http://开头的链接地址。</p>
                    </dd>
                </dl>
                
                <dl class="row">
                    <dt class="tit">
                        <label>首页图片2</label>
                    </dt>
                    <dd class="opt">
                        <div>
                            <div class="div-image">
                                <img id="index_liandong2_review" src="<?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                                    echo @$data[Perm::$row['sub_site_id'] . 'index_liandong_image2']['config_value'];
                                } else {
                                    echo @$data['index_liandong_image2']['config_value'];
                                } ?>" width="236" height="236" />
                                <a href="javascript:void(0)" style="display: <?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {echo 'none';}else{echo 'block';}?>" class="del" id="del_btn2" onclick="delImage('index_liandong2_review')" title="移除"><i class="iconfont icon-cancel"></i></a>
                            </div>
                        </div>
                        <input type="hidden" id="index_liandong2_image" name="index_liandong[<?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                            echo Perm::$row['sub_site_id'] . "index_liandong_image2";
                        } else {
                            echo "index_liandong_image2";
                        } ?>]" value="<?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                            echo @$data[Perm::$row['sub_site_id'] . 'index_liandong_image2']['config_value'];
                        } else {
                            echo @$data['index_liandong_image2']['config_value'];
                        } ?>" />
                        <div id='index_liandong2_upload' class="image-line upload-image">图片上传</div>
                        
                        <label title="请输入图片要跳转的链接地址"><i class="fa fa-link"></i>
                            <input class="ui-input w400" style="margin:5px 0" type="text" name="index_liandong[<?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                                echo Perm::$row['sub_site_id'] . "index_liandong_url2";
                            } else {
                                echo "index_liandong_url2";
                            } ?>]" value="<?php if (isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id']) {
                                echo @$data[Perm::$row['sub_site_id'] . 'index_liandong_url2']['config_value'];
                            } else {
                                echo @$data['index_liandong_url2']['config_value'];
                            } ?>" placeholder="请输入图片要跳转的链接地址">
                        </label>
                        <span class="err"><label for="index_liandong_url2" class="error valid"></label></span>
                        <p class="notic">请使用宽度236像素，高度236像素的jpg/gif/png格式图片作为联动图片上传<br>
                            如需跳转请在后方添加以http://开头的链接地址。</p>
                    </dd>
                </dl>
                <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </form>
        
        <script type="text/javascript" src="<?= $this->view->js ?>/controllers/config.js" charset="utf-8"></script>
        
        <script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
        <script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
        <script>
            jQuery(function ($) {
                var sub_site_id =  <?=Perm::$row['sub_site_id']?>;
                if (sub_site_id) {
                    $.post(SITE_URL + '?ctl=Config&met=addLiandong&typ=json', {sub_site_id: sub_site_id}, function (e) {
                        if (200 == e.status) {
                            location.reload()
                        }
                    });
                }
                if (!$('#index_liandong2_image').val()){
                    $('#index_liandong2_review').attr('src', '/shop_admin/static/common/images/image.png')
                    $('#del_btn2').hide();
                }
                if (!$('#index_liandong1_image').val()){
                    $('#index_liandong1_review').attr('src', '/shop_admin/static/common/images/image.png')
                    $('#del_btn1').hide();
                }
            });
            $(function () {
                //图片裁剪
                
                var agent = navigator.userAgent.toLowerCase();
                
                if (agent.indexOf("msie") > -1 && (version = agent.match(/msie [\d]/), (version == "msie 8" || version == "msie 9"))) {
                    index_liandong1_image_upload = new UploadImage({
                        thumbnailWidth: 236,
                        thumbnailHeight: 236,
                        imageContainer: '#index_liandong1_review',
                        uploadButton: '#index_liandong1_upload',
                        inputHidden: '#index_liandong1_image'
                    });
                    
                    index_liandong2_image_upload = new UploadImage({
                        thumbnailWidth: 236,
                        thumbnailHeight: 236,
                        imageContainer: '#index_liandong2_review',
                        uploadButton: '#index_liandong2_upload',
                        inputHidden: '#index_liandong2_image'
                    });
                } else {
                    var $imagePreview, $imageInput, imageWidth, imageHeight;
                    
                    $('#index_liandong1_upload, #index_liandong2_upload').on('click', function () {

                        if (this.id == 'index_liandong1_upload') {
                            $imagePreview = $('#index_liandong1_review');
                            $imageInput = $('#index_liandong1_image');
                            imageWidth = 236, imageHeight = 236;
                            $('#del_btn1').show();
                        } else {
                            $imagePreview = $('#index_liandong2_review');
                            $imageInput = $('#index_liandong2_image');
                            imageWidth = 236, imageHeight = 236;
                            $('#del_btn2').show();
                        }
                        console.info($imagePreview);
                        $.dialog({
                            title: '图片裁剪',
                            content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                            data: {SHOP_URL: SHOP_URL, width: imageWidth, height: imageHeight, callback: callback},    // 需要截取图片的宽高比例
                            width: '800px',
                            height: $(window).height() * 0.9,
                            lock: true
                        })
                    });
                    
                    function callback(respone, api) {
                        console.info($imagePreview);
                        $imagePreview.attr('src', respone.url);
                        $imageInput.attr('value', respone.url);
                        api.close();
                    }
                }
            });

            function delImage(imageId) {
                var $img = $("#" + imageId), $input;
                if (!$img.attr("src")) {
                    return false;
                }
                $.dialog.confirm("是否删除该图片", function () {
                    if (imageId == "index_liandong1_review") {
                        $input = $("#index_liandong1_image");
                        $('#del_btn1').hide();
                    } else {
                        $input = $("#index_liandong2_image");
                        $('#del_btn2').hide();
                    }
                    $img.attr("src", "/shop_admin/static/common/images/image.png"), $input.attr("value", "");
                })
            }
        </script>
<?php
    include $this->view->getTplPath() . '/' . 'footer.php';
?>