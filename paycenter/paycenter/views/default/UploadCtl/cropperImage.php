<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>WebUploader演示 - 带裁剪功能 </title>
    <link href="<?= $this->view->css?>/webuploader.css" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css?>/cropper.css" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css?>/cropperStyle.css" rel="stylesheet" type="text/css">
    <style>
        .modal-body {
            position: relative;
            max-height: 400px;
            padding: 5px;
            overflow-y: auto;
        }
        .image-first {
            border: 5px dashed #ddd;
            border-radius: 4px;
            height: 300px;
            text-align: center;
        }

        .cropper-container .point-se {
            width: 5px !important;
            height: 5px !important;
        }
        .cropper-container{
            top:0 !important;
            left:0 !important;
        }
        .upload-btn2{

            margin:10px auto;
        }
        .cropper-wraper{
            text-align:center;
        }
        .cropper-container{
            margin:0 auto;
        }

        .upload_div{
            height:60px;
            width:100%;
            background:#fff;
            position: fixed;
            bottom:0;
            text-align:center;
            left:0;

        }
    </style>
    <script type="text/javascript">
        var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
        var SITE_URL = "<?=Yf_Registry::get('url')?>";
        var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
        var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";
    </script>
</head>
<body>
<div class="modal-body">
    <div class="image-first js-origin-select">
        <div class="uploader-container">
            <div id="filePicker" style="top: 125px;">选择文件</div>
        </div>
    </div>
</div>

<div id="wrapper" style="display: none">
    <!-- Croper container -->
    <div class="cropper-wraper webuploader-element-invisible">
        <div class="img-container">
            <img src="" alt="" width="100%" height="100%" />
        </div>
        <div class="upload_div"><div class="upload-btn upload-btn2">上传所选区域</div></div>
    </div>

</div>
</body>
</html>
<script type="text/javascript" src="<?=$this->view->js?>/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/cropper.js" charset="utf-8"></script>
<script src="<?= $this->view->js ?>/upload/cropper_image.js"></script>