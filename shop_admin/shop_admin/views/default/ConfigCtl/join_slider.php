<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
    include $this -> view -> getTplPath() . '/' . 'header.php';
    // 当前管理员权限
    $admin_rights = $this -> getAdminRights();
    // 当前页父级菜单 同级菜单 当前菜单
    $menus = $this -> getThisMenus();
?>
<link href="<?= $this -> view -> css ?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?= $this -> view -> css_com ?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?= $this -> view -> js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this -> view -> js_com ?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link href="<?= $this -> view -> css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
</head>
<body>
<style>
    .image-line {
        height: 23px;
        width: 50px;
    }
</style>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?= $menus['father_menu']['menu_name'] ?></h3>
                <h5><?= $menus['father_menu']['menu_url_note'] ?></h5>
            </div>
            <ul class="tab-base nc-row">
                <?php
                    foreach ($menus['brother_menu'] as $key => $val) {
                        if (in_array($val['rights_id'], $admin_rights) || $val['rights_id'] == 0) {
                            ?>
                            <li><a <?php if (!array_diff($menus['this_menu'], $val)) { ?> class="current"<?php } ?> href="<?= Yf_Registry ::get('url') ?>?ctl=<?= $val['menu_url_ctl'] ?>&met=<?= $val['menu_url_met'] ?><?php if ($val['menu_url_parem']) { ?>&<?= $val['menu_url_parem'] ?><?php } ?>"><span><?= $val['menu_name'] ?></span></a></li>
                            <?php
                        }
                    }
                ?>
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
            <?= $menus['this_menu']['menu_url_note'] ?>
        </ul>
    </div>
    
    <form method="post" enctype="multipart/form-data" id="join_slider-setting-form" name="form1">
        <input type="hidden" name="config_type[]" value="join_slider"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label>滚动图片1</label>
                </dt>
                <dd class="opt">
                    <div class="div-image">
                        <img id="join_slider1_review" src="<?= @($data['join_slider1_image']['config_value']);?>" width="760" height="140"/>
                        <a href="javascript:void(0)" class="del" onclick="delImage('join_slider1_review')" title="移除"  id="del_btn1" >
                            <i class="iconfont icon-cancel"></i>
                        </a>
                    </div>
                    <br>
                    <input type="hidden" id="join_slider1_image" name="join_slider[join_slider1_image]" value="<?= @($data['join_slider1_image']['config_value']) ?>"/>
                    <div id='join_slider1_upload' class="image-line upload-image">图片上传</div>
                    <label title="请输入图片要跳转的链接地址">
                        <i class="fa fa-link"></i>
                        <input class="ui-input w400" style="margin:8px 0" type="text" name="join_slider[join_live_link1]" value="<?= @($data['join_live_link1']['config_value']) ?>" placeholder="请输入图片要跳转的链接地址"> </label>
                    <span class="err">
                        <label for="join_live_link1" class="error valid"></label>
                    </span>
                    <p class="notic">请使用宽度1900像素，高度350像素的jpg/gif/png格式图片作为幻灯片banner上传，<br> 如需跳转请在后方添加以http://开头的链接地址。</p>
                </dd>
            </dl>
            
            <dl class="row">
                <dt class="tit">
                    <label>滚动图片2</label>
                </dt>
                <dd class="opt">
                    <div class="div-image">
                        <img id="join_slider2_review" src="<?= @($data['join_slider2_image']['config_value']) ?>" width="760" height="140"/>
                        <a href="javascript:void(0)" class="del" onclick="delImage('join_slider2_review')" title="移除"  id="del_btn2" >
                            <i class="iconfont icon-cancel"></i>
                        </a>
                    </div>
                    <br> <input type="hidden" id="join_slider2_image" name="join_slider[join_slider2_image]" value="<?= @($data['join_slider2_image']['config_value']) ?>"/>
                    <div id='join_slider2_upload' class="image-line upload-image">图片上传</div>
                    <label title="请输入图片要跳转的链接地址" class=""><i class="fa fa-link"></i> <input class="ui-input  w400" type="text" name="join_slider[join_live_link2]" value="<?= @($data['join_live_link2']['config_value']) ?>" placeholder="请输入图片要跳转的链接地址"> </label> <span class="err"><label for="join_live_link2" class="error valid"></label></span>
                    <p class="notic">请使用宽度1900像素，高度350像素的jpg/gif/png格式图片作为幻灯片banner上传，<br> 如需跳转请在后方添加以http://开头的链接地址。</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>贴心提示</label>
                </dt>
                <dd class="opt">
                    <textarea style="width: 500px;height: 200px;" name="join_slider[join_tip]"><?= @($data['join_tip']['config_value']) ?></textarea>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
    </form>
    <script type="text/javascript" src="<?= $this -> view -> js ?>/controllers/config.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this -> view -> js_com ?>/webuploader.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this -> view -> js ?>/models/upload_image.js" charset="utf-8"></script>
    <script>
        $(function () {
            if (!$("#join_slider1_review").attr("src")) {
                $("#join_slider1_review").attr("src", "/shop_admin/static/common/images/image.png");
                $("#del_btn1").hide();
            }
            if (!$("#join_slider2_review").attr("src")) {
                $("#join_slider2_review").attr("src", "/shop_admin/static/common/images/image.png");
                $("#del_btn2").hide();
            }
            
            var agent = navigator.userAgent.toLowerCase();
            if (agent.indexOf("msie") > -1 && (version = agent.match(/msie [\d]/), (version == "msie 8" || version == "msie 9"))) {
                join_slider1_image_upload = new UploadImage({
                    thumbnailWidth: 1900,
                    thumbnailHeight: 350,
                    imageContainer: "#join_slider1_review",
                    uploadButton: "#join_slider1_upload",
                    inputHidden: "#join_slider1_image"
                });
                //图片上传
                join_slider2_image_upload = new UploadImage({
                    thumbnailWidth: 1900,
                    thumbnailHeight: 350,
                    imageContainer: "#join_slider2_review",
                    uploadButton: "#join_slider2_upload",
                    inputHidden: "#join_slider2_image"
                });
            } else {
                var $imagePreview, $imageInput, imageWidth, imageHeight, shopWidth;
                $("#join_slider1_upload, #join_slider2_upload").on("click", function () {
                    if (this.id == "join_slider1_upload") {
                        $imagePreview = $("#join_slider1_review");
                        $imageInput = $("#join_slider1_image");
                        imageWidth = 1900, imageHeight = 350, shopWidth = 1900;
                    } else if (this.id == "join_slider2_upload") {
                        $imagePreview = $("#join_slider2_review");
                        $imageInput = $("#join_slider2_image");
                        imageWidth = 1900, imageHeight = 350, shopWidth = 1900;
                    }
                    $.dialog({
                        title: "图片裁剪",
                        content: "url: <?= Yf_Registry ::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
                        data: {SHOP_URL: SHOP_URL, width: imageWidth, height: imageHeight, callback: callback},    // 需要截取图片的宽高比例
                        width: 800,
                        height: $(window).height() * 0.9,
                        lock: true
                    });
                });
                function callback(respone, api) {
                    $imagePreview.attr("src", respone.url);
                    $imageInput.attr("value", respone.url);
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
                if (imageId == "join_slider1_review") {
                    $input = $("#join_slider1_image");
                    $("#del_btn1").hide();
                }
                if (imageId == "join_slider2_review") {
                    $input = $("#join_slider2_image");
                    $("#del_btn2").hide();
                }
                $img.attr("src", "/shop_admin/static/common/images/image.png"), $input.attr("value", "");
            });
        }
    </script>
    <?php
        include $this -> view -> getTplPath() . '/' . 'footer.php';
    ?>
