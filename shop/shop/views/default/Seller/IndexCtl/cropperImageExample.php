<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?=__('WebUploader演示 - 带裁剪功能')?> </title>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css?ver=<?=VER?>" rel="stylesheet">
</head>
<body>
<div id="wrapper">
    <div class="uploader-container">
        <input type="button" id="filePicker" value="<?=__('选择文件')?>" />
    </div>

</div>
</body>
</html>
<script src="<?= $this->view->js_com ?>/jquery.js"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
<script>

    $('#filePicker').click(function () {
        $.dialog({
            title: "<?=__('图片裁剪')?>",
            content: "url: <?= Yf_Registry::get('url') ?>?ctl=Upload&met=cropperImage&typ=e",
            data: { filePicker: '#filePicker', uploadaspectRatio: 200 / 60 , callback: callback },    // 需要截取图片的宽高比例
            width: '773px',
            height: '500px',
            lock: true,
        })
    });

    function callback ( respone , api ) {
        $('#wrapper').append('<img src="' + respone.url + '" />');
        api.close();
    }
</script>