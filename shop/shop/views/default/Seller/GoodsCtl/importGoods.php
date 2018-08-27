<!DOCTYPE HTML>
<html>
<head>
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
    <script src="<?=$this->view->js_com?>/webuploader.js"></script>

    <style>
        body {
            background-color: hsl(0, 0%, 96%);
        }
        ul, ol {
             list-style: none;
        }
        a {
            text-decoration: none;
            color: hsl(0, 0%, 33%);
            cursor: pointer;
        }
        a {
            outline: none;
        }
        a:hover {
            color: hsl(0, 17%, 87%);
        }
        .mod-steps li {
            display: inline;
            font-size: 14px;
            margin-right: 12px;
            color: hsl(0, 0%, 53%);
        }
        .mod-steps {
            font-size: 0;
            margin-bottom: 20px;
        }
        .mod-steps .current {
            color: hsl(201, 55%, 42%);
            font-weight: bold;
        }
        .mod-steps span {
            margin-right: 12px;
        }
        .wrapper {
            padding: 15px 0 0 18px;
            min-width: 0;
        }
        #import-wrap {
            font-size: 14px;
            line-height: 1.8;
        }
        #import-wrap .ctn {
            margin-bottom: 8px;
        }
        .mod-inner h3 {
            margin-bottom: 20px;
            font-size: 14px;
        }
        #import-wrap a.link {
            color: hsl(201, 50%, 46%);
            text-decoration: underline;
        }
        #import-wrap .step-btns {
            margin: 5px -10px 0 0;
            text-align: right;
        }
        #import-wrap {
            font-size: 14px;
            line-height: 1.8;
        }
        .ui-btn {
            display: inline-block;
            padding: 0 13px;
            height: 28px;
            border: 1px solid hsl(0, 0%, 76%);
            border-radius: 2px;
            box-shadow: 0 1px 1px hsla(0, 0%, 0%, 0.15);
            background: hsl(0, 0%, 100%);
            background: -moz-linear-gradient(top,#fff,#f4f4f4);
            background: -webkit-gradient(linear,0 0,0 100%,from(hsl(0, 0%, 100%)),to(hsl(0, 0%, 96%)));
            background: -o-linear-gradient(top,#fff,#f4f4f4);
            background: -ms-linear-gradient(top,#fff 0,#f4f4f4 100%);
            background: linear-gradient(top,#fff,#f4f4f4);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f4f4f4');
            font: 14px/2 \5b8b\4f53;
            color: hsl(0, 0%, 33%);
            vertical-align: middle;
            cursor: pointer;
        }
        .ui-btn-sp {
            padding: 0 16px;
            border: 1px solid hsl(31, 99%, 42%);
            box-shadow: 0 1px 1px hsla(35, 33%, 66%, 0.8);
            background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJod…EiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
            background: -moz-linear-gradient(top,#f67f00 0,#ea7800 100%);
            background: -webkit-gradient(linear,left top,left bottom,color-stop(0%,hsl(31, 100%, 48%)),color-stop(100%,hsl(31, 100%, 46%)));
            background: -webkit-linear-gradient(top,hsl(31, 100%, 48%) 0,hsl(31, 100%, 46%) 100%);
            background: -o-linear-gradient(top,#f67f00 0,#ea7800 100%);
            background: -ms-linear-gradient(top,#f67f00 0,#ea7800 100%);
            background: linear-gradient(to bottom,hsl(31, 100%, 48%) 0,hsl(31, 100%, 46%) 100%);
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#f67f00', endColorstr='#ea7800', GradientType=0);
            font-weight: 700;
            color: hsl(0, 0%, 100%);
            text-shadow: 0 2px 2px hsla(0, 0%, 0%, 0.22);
        }

        #import-wrap .step-btns {
            margin: 5px -10px 0 0;
            text-align: right;
        }

        .mrb {
            margin-right: 10px;
        }

        #file-path {
            width: 200px;
        }

        .ui-input {
            padding: 6px 5px;
            width: 100px;
            height: 16px;
            line-height: 16px;
            border: 1px solid hsl(0, 0%, 87%);
            color: hsl(0, 0%, 33%);
            vertical-align: middle;
            outline: 0;
        }

        #import-wrap .file-import-ctn {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

<body>
<div class="wrapper" style="width: 541px">
    <div class="mod-inner"  style="width:480px; ">
        <h3><?=__('批量导入商品信息')?></h3>
        <ul class="mod-steps" id="import-steps">
            <li><span class="current">1.<?=__('下载模版')?></span>&gt;</li>
            <li><span>2.<?=__('导入Excel')?></span>&gt;</li>
            <li><span>3.<?=__('导入完毕')?></span></li>
        </ul>
        <div id="import-wrap" class="cf">
            <div id="import-step1" class="step-item">
                <div class="ctn">
                    <h3 class="tit"><?=__('温馨提示')?>：</h3>
                    <p><?=__('导入模板的格式不能修改，录入方法请参考演示模板。')?></p>
                </div>
                <p><a href="<?= Yf_Registry::get('index_page') ?>?ctl=Seller_Goods&met=downloadTemplate&typ=e" class="link"><?=__('下载导入模板（适合大部分行业）')?></a></p>
                <div class="step-btns">
                    <a href="#" class="ui-btn ui-btn-sp" rel="step2"><?=__('下一步')?></a>
                </div>
            </div>

            <div id="import-step2" class="step-item" style="display:none;">
                <div class="ctn file-import-ctn">
                    <span class="tit"><?=__('请选择要导入文件')?>：</span>
                    <input type="text" name="file-path" id="file-path" class="ui-input" readonly autocomplete="false" />
                    <span style="top: 10px;" id="import-btn" class="mrb"><?=__('浏览')?></span>
                </div>
                <div class="step-btns">
                    <a href="#" class="ui-btn mrb" rel="step1"><?=__('上一步')?></a><a href="#" class="ui-btn ui-btn-sp" id="btn-import"><?=__('导入')?></a>
                </div>
            </div>

            <div id="import-step3" class="step-item" style="display:none;">
                <div class="ctn file-import-ctn" id="import-result"></div>

                <div class="step-btns">
                    <a href="#" class="link ui-btn mrb" id="a_step3"><?=__('上一步')?></a><a href="#" class="ui-btn ui-btn-sp" id="btn-complete"><?=__('完成')?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function (){
        var SITE_URL = "<?=Yf_Registry::get('url')?>";
        var progressPop;
        var importDialog;//导入商品的弹窗
        var outTime;//定时器对象
        var firstCheck = true;//是否第一次，第一次的时候是检查是否有其他人在导入
        var uploadInstance = WebUploader.create({

            auto: false,

            pick: "#import-btn",

            swf: '<?=Yf_Registry::get('base_url')?>' + '/shop/static/common/js/Uploader.swf',

            server: SITE_URL + "?&ctl=Upload&met=uploadGoodsExcel&typ=json",

            fileVal: 'upfile',

            fileNumLimit: 10
        });
        uploadInstance.on( 'beforeFileQueued', function( file ) {
            var self = this;
            var files = this.getFiles();
            for (var i = 0; i < files.length; i++) {
                if (files[i] != file) this.removeFile(files[i].id);
            }

            $('#file-path').val(file.name);
            $('#file-path').data('uploaddata', file);
        });
        uploadInstance.on( 'uploadComplete', function( file ) {
            uploadInstance.reset();
        });
        uploadInstance.on( 'uploadProgress', function( file ) {
            try {
                var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
                if($('#upload-progress .progress-bar > span').length > 0){
                    $('#upload-progress .progress-bar > span').width(percent + '%');
                }
            } catch (e) {
            }
        });
        // 当有文件添加进来的时候
        uploadInstance.on( 'startUpload', function( file ) {
        });
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploadInstance.on( 'uploadSuccess', function(file, response) {alert(123);
            progressPop.close();
            var data = response.data;
            if ( data.state != "SUCCESS" ) {
                return parent.Public.tips.error(response.state);
            }
            uploadSuccess(data);
        });
        // 文件上传失败，显示上传出错。
        uploadInstance.on( 'error', function(file, reason) {
            try {
                progressPop.close();
                parent.Public.tips({content : "<?=__('导入失败，请重试')?>！", type : 2});
            } catch (e) {
            }
        });

        $('#a_step3').bind('click',function(e){
            $('#import-wrap .step-item').eq(1).show().siblings().hide();
            $('#import-steps2 >li >span').removeClass('current');
            $('#import-steps2 >li >span').eq(1).addClass('current');
            e.preventDefault();
        });
        $('#resultInfo').click(function(){
            var _pop = parent.$.dialog({
                width: 460,
                height: 300,
                title: "<?=__('导入信息')?>",
                content: 'url:/resultInfo.jsp',
                data: {
                    callback: function(row){
                        _pop.close();
                    }
                },
                lock: true,
                parent:frameElement.api
            });
        });
        $('#import-wrap .step-btns a[rel]').bind('click',function(e){
            var step = $(this).attr('rel').substr(4,1)-1;
            if(step < 2){
                $('#import-wrap .step-item').eq(step).show().siblings().hide();
                $('#import-steps >li >span').removeClass('current');
                $('#import-steps >li >span').eq(step).addClass('current');
            } else {

            }
            e.preventDefault();
        });
        $("#import-wrap").on("click", '#resultInfo2', function() {
            parent.$.dialog({
                width: 460,
                height: 300,
                title: "<?=__('导入信息')?>",
                content: 'url:/resultInfo.jsp',
                data: {
                    callback: function(row){
                    }
                },
                lock: true
            });
        });
        $('#btn-import').on('click',function(e){
            e.preventDefault();
            if(!$('#file-path').val()){
                parent.Public.tips.error({content :"<?=__('请选择要上传的文件')?>！", type : 2});
                return ;
            }
            progressPop = parent.$.dialog.tips("<?=__('正在导入凭证，请耐心等待')?>...",1000,'loading.gif',true).show();
            uploadInstance.upload();
            console.info(uploadInstance.getStats());
        });
        $('#btn-complete').on('click',function(e){
            frameElement.api.close();
        });
        function uploadSuccess(data){
            importDialog = parent.$.dialog.tips("<?=__('正在导入商品，请耐心等待')?>...",1000,'loading.gif',true).show(); //导入商品的时候
            $.post( SITE_URL + "?ctl=Seller_Goods&met=importGoods&typ=json", {'url_path': data.url_path},
                function ( data ) {
                    importDialog.close();
                    parent.Public.tips.success("<?=__('导入完成')?>！"");
                    $('#import-wrap .step-item').eq(2).show().siblings().hide();
                    $('#import-steps >li >span').removeClass('current');
                    $('#import-steps >li >span').eq(2).addClass('current');
                }, 'json'
            );
        }
    })(jQuery)
</script>


