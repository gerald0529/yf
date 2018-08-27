<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

    <style xmlns="http://www.w3.org/1999/html">
        .image-list {
            zoom: 1;
            margin: 18px 0 0 0;
            width:85%;
            float:left;
        }

        .image-item {
            float: left;
            width: 150px;
            margin: 0 15px 20px 0px;
        }

        .image-item .image-box {
            width: 150px;
            height: 150px;
            background: #ddd;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 50% 50%;
        }

        .image-item .image-title {
            padding: 8px 0px 0px;
        }

        .image-item .image-title label {
            display: inline-block;
            width: 160px;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .image-item .image-title label input[type="checkbox"] {
            margin: 0 6px 0 0;
            vertical-align: baseline;
        }

        .image-list:after {
            content: "";
            display: table;
            clear: both;
        }

        .action-bar {
            padding: 6px 6px;
            margin-bottom: 20px;
            min-height: 28px;
            line-height: 28px;
        }

        .action-bar label input[type="checkbox"] {
            margin: 0 6px 0 0;
            vertical-align: baseline;
        }

        .action-bar label input[type="checkbox"] {
            margin: 0 6px 0 0;
            vertical-align: middle;
        }

        .pull-right {
            float: right;
            margin-right: 50px;
        }

        .ui-pagination {
            font-size: 12px;
            line-height: 16px;
            text-align: right;
            padding: 20px;
        }

        .ui-pagination .ui-pagination-prev, .ui-pagination .ui-pagination-next, .ui-pagination .ui-pagination-num, .ui-pagination .ui-pagination-goto {
            padding: 5px 8px;
            margin: 0 0 0 2px;
            min-width: 28px;
            border: 1px solid #ddd;
            background: #fff;
            text-align: center;
            border-radius: 2px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            color: #333;
        }

        .ui-pagination .ui-pagination-goto-input, .ui-pagination .ui-pagination-goto-btn {
            display: inline-block;
            color: #333;
        }

        .ui-pagination .ui-pagination-goto-input {
            font-weight: normal;
            border-radius: 2px;
            min-width: 21px;
            border: 1px solid #e5e5e5;
            padding: 0 4px;
        }

        .page-showcase-attachment .category-container {
            float: right;
            min-height: 389px;
            padding: 18px 0 10px 0;
            background: #F8F8F8;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            width:15%;
        }

        .page-showcase-attachment .category-list {
            margin-bottom: 15px;
            max-height: 996px;
            overflow-y: auto;
        }

        ol, ul {
            list-style: none;
        }

        .page-showcase-attachment .category-list li {
            height: 40px;
            line-height: 40px;
            position: relative;
            padding: 0 31px 0 8px;
            margin-right: 1px;
            cursor: pointer;
        }

        .ui-tooltip:after {
            position: absolute;
            display: none;
            bottom: 100%;
            right: 50%;
            margin-bottom: 5px;
            max-width: 260px;
            z-index: 1000000;
            padding: 5px 8px;
            color: #fff;
            font-size: 12px;
            text-align: center;
            text-decoration: none;
            text-shadow: none;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: break-word;
            white-space: pre;
            pointer-events: none;
            content: attr(data-tooltip-title);
            background: rgba(0, 0, 0, 0.8);
            border-radius: 3px;
            line-height: 18px;
            -webkit-transform: translateX(50%);
            -moz-transform: translateX(50%);
            -ms-transform: translateX(50%);
            transform: translateX(50%);
        }

        .ui-tooltip:before {
            position: absolute;
            display: none;
            bottom: 100%;
            right: 50%;
            margin-bottom: -5px;
            z-index: 1000001;
            width: 0;
            height: 0;
            color: rgba(0, 0, 0, 0.8);
            pointer-events: none;
            content: "";
            border: 5px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.8);
            -webkit-transform: translateX(50%);
            -moz-transform: translateX(50%);
            -ms-transform: translateX(50%);
            transform: translateX(50%);
        }

        .page-showcase-attachment .category-list .category-name {
            width: 80px;
            display: inline-block;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .page-showcase-attachment .category-list .category-num {
            position: absolute;
            top: 0;
            right: 8px;
            color: #999;
        }

        .media-title {
            height: 28px;
            margin-top: 15px;
        }

        .media-title-wrap h1 {
            display: inline;
            line-height: 28px;
            font-size: 16px;
        }

        .ui-btn {
            display: inline-block;
            border-radius: 2px;
            height: 26px;
            line-height: 26px;
            padding: 0 12px;
            cursor: pointer;
            color: #333;
            /*background: #f8f8f8;*/
            border: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            -webkit-box-sizing: content-box;
            -moz-box-sizing: content-box;
            box-sizing: content-box;
        }

        .text-center {
            text-align: center;
        }

        .ui-popover {
            position: absolute;
            z-index: 1010;
            border-radius: 2px;
            -webkit-box-shadow: 0px 1px 6px rgba(0, 0, 0, 0.4);
            box-shadow: 0px 1px 6px rgba(0, 0, 0, 0.4);
        }

        .ui-popover .ui-popover-inner {
            position: relative;
            background: #fff;
            border-radius: 2px;
            padding: 10px 20px;
            z-index: 2;
        }

        input[type="text"] {
            font-size: 12px;
        }

        .ui-btn-primary {
            color: #fff;
            background: #07d;
            border-color: #006cc9;;
        }

        .ui-popover.top-center .arrow {
            left: 50%;
            -webkit-transform: rotate(45deg) translateX(-50%) translateY(-50%);
            -moz-transform: rotate(45deg) translateX(-50%) translateY(-50%);
            -ms-transform: rotate(45deg) translateX(-50%) translateY(-50%);
            transform: rotate(45deg) translateX(-50%) translateY(-50%);
            -webkit-transform-origin: 0 0;
            -moz-transform-origin: 0 0;
            -ms-transform-origin: 0 0;
            transform-origin: 0 0;
        }

        .top-center .arrow, .ui-popover.top-right .arrow {
            top: 0;
            -webkit-transform: rotate(45deg) translateY(-50%);
            -moz-transform: rotate(45deg) translateY(-50%);
            -ms-transform: rotate(45deg) translateY(-50%);
            transform: rotate(45deg) translateY(-50%);
            -webkit-transform-origin: 50% 0;
            -moz-transform-origin: 50% 0;
            -ms-transform-origin: 50% 0;
            transform-origin: 50% 0;
        }

        .ui-popover .arrow {
            position: absolute;
            width: 6px;
            height: 6px;
            background: #fff;
            -webkit-box-shadow: 0 1px 4px rgba(0,0,0,0.4);
            box-shadow: 0 1px 4px rgba(0,0,0,0.4);
            z-index: 1;
        }

        .page-showcase-attachment .category-list li.active {
            background: #a5d8bb;
        }

        .ui-pagination .active {
            background: #aaa;
            border-color: #ddd;
        }

        #rename_album, #remove_album {
            margin-left: 10px;
        }

        .js-name-input {
            font: 12px/20px Arial;
            color: #777;
            background-color: #FFF;
            vertical-align: top;
            display: inline-block;
            height: 20px;
            padding: 4px;
            border: solid 1px #E6E9EE;
            outline: 0 none;
        }

        #upload-image {
            position: absolute;
            top: 53px;
            right: 0;
            z-index: 1;
        }

        #category-list {
            height: 315px;
        }

        .ac_btns {
            position: absolute;
            right: 99px;
            top: 53px;
            z-index: 2;
        }

        .ac_btns a {
            line-height: 28px;
            display: inline-block;
            padding: 0 10px;
        }
        

        .ui_dialog .ui_main .ui_content{
            height:100% !important;
        }
        .ui_main .ui_content .js-category-list{
        	max-height:none !important;
        }
    </style>
    <link href="./shop/static/common/css/jquery/plugins/dialog/green.css" rel="stylesheet">
    </head>

    <!--<div class="media-title">
        <span class="media-title-wrap">
            <h1 class="media-title-wrap" id="album_name">未分组</h1>

        </span>
    </div>-->
    <a href="javascript:;" id="upload-image" class="ui-btn ui-btn-success bbc_seller_btns"><i class="rel_top1 iconfont icon-jia"></i><?=__('上传图片')?></a>
    <div class="page-showcase-attachment clearfix">

        <div class="category-container">
            <div>
                <ul class="category-list" id="category-list">
                </ul>
                <div class="text-center">
                    <a href="javascript:;" id="add-album" class="ui-btn text-center">+ <?=__('添加分组')?></a>
                </div>
            </div>
        </div>
        <div class="image-list" id="image-list"></div>

    </div>
    
    <div class="action-bar clearfix">
        <label class="inline"><input type="checkbox" id="select-all"><a class="f12"><?=__('全选')?></a></label><span>|</span>
        <a href="javascript:;" id="bulk-edit" class="batch-opt c-gray" style="cursor: not-allowed;">
            <i class="iconfont icon-zhifutijiao f18 rel_top1"></i><?=__('修改分组')?>
        </a><span>|</span>
        <a href="javascript:;" id="bulk-remove" class="batch-opt c-gray" style="cursor: not-allowed;">
            <i class="iconfont icon-lajitong rel_top1"></i><?=__('删除')?>
        </a>
        <div class="pull-right">
            <div class="ui-pagination page" id="pagination">
            </div>
        </div>
    </div>
    <div id="copy" style="display: none">
        <input type="text" id="imageUrl" value="test" /><button id="copyUrl"></button>
    </div>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/clipboard.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/image_space.js"></script>

<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>