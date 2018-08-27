<!DOCTYPE HTML>
<html>
<head>
    <link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css ?>/seller_center.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet">
    <script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <style>
        .goods-category .item_list {
            background: #FFF;
            border: 1px solid #E6E6E6;
            height: 340px;
            width: 78%;
            overflow: auto;
            padding: 10px;
            margin: 20px auto;
            float:none;
        }

    </style>
</head>
<body>
<div class="goods-category">


    <div class="goods-category-list fn-clear clearfix">
        <div class="item_list">
            <ul id="class_div_4">
            </ul>
        </div>
    </div>

</div>
</body>
</html>

<script>

    api = frameElement.api,
    data = api.data.data,
    callback = api.data.callback;

    $(function () {

        var list = new String();

        $.each(data, function(index, element) {
            list += '<li id="'+ element.cat_id +'"  class=""><a href="javascript:void(0)" class=""><i class="iconfont icon-angle-right"></i>' + element.cat_name + '</a></li>'
        });

        $('#class_div_4').append(list);

        $('#class_div_4').on('click', 'li', function () {
            $('#class_div_4').find('a').removeClass("selected");
            $(this).children("a").addClass("selected");
        })
    })
</script>