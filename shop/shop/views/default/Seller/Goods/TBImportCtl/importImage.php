<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
    <?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
        <style>
            .step {
                border: 0px;
            }
            
            .ncsc-form-goods {
                border: solid hsl(0, 0%, 90%);
                border-width: 1px 1px 0 1px;
            }
            
            .ncsc-form-goods dl {
                font-size: 0;
                line-height: 20px;
                clear: both;
                padding: 0;
                margin: 0;
                border-bottom: solid 1px hsl(0, 0%, 90%);
                overflow: hidden;
            }
            
            .ncsc-form-goods dl dt {
                font-size: 12px;
                line-height: 30px;
                color: hsl(0, 0%, 20%);
                vertical-align: top;
                letter-spacing: normal;
                word-spacing: normal;
                text-align: right;
                display: inline-block;
                width: 13%;
                padding: 8px 1% 8px 0;
                margin: 0;
            }
            
            .ncsc-form-goods dl dd {
                font-size: 12px;
                line-height: 30px;
                vertical-align: top;
                letter-spacing: normal;
                word-spacing: normal;
                display: inline-block;
                width: 84%;
                padding: 8px 0 8px 1%;
                border-left: solid 1px hsl(0, 0%, 90%);
            }
            
            .ncsc-form-goods dl dt i.required {
                font: 12px/16px Tahoma;
                color: hsl(12, 100%, 50%);
                vertical-align: middle;
                margin-right: 4px;
            }
            
            * {
                padding: 0px;
                margin: 0px;
            }
            
            .webuploader-pick {
                padding:0 10px;
            }
            
            .js-file-name {
                font-weight: bold;
            }
        </style>
        <style>
            /* ----------------Reset Css--------------------- */
            
            html,
            body,
            div,
            span,
            applet,
            object,
            iframe,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6,
            p,
            blockquote,
            pre,
            a,
            abbr,
            acronym,
            address,
            big,
            cite,
            code,
            del,
            dfn,
            em,
            img,
            ins,
            kbd,
            q,
            s,
            samp,
            small,
            strike,
            strong,
            sub,
            sup,
            tt,
            var,
            b,
            u,
            i,
            center,
            dl,
            dt,
            dd,
            ol,
            ul,
            li,
            fieldset,
            form,
            label,
            legend,
            table,
            caption,
            tbody,
            tfoot,
            thead,
            tr,
            th,
            td,
            article,
            aside,
            canvas,
            details,
            figcaption,
            figure,
            footer,
            header,
            hgroup,
            menu,
            nav,
            section,
            summary,
            time,
            mark,
            audio,
            video,
            input {
                margin: 0;
                padding: 0;
                border: none;
                outline: 0;
                font-size: 100%;
                font: inherit;
                vertical-align: baseline;
            }
            
            html,
            body,
            form,
            fieldset,
            p,
            div,
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                -webkit-text-size-adjust: none;
            }
            
            article,
            aside,
            details,
            figcaption,
            figure,
            footer,
            header,
            hgroup,
            menu,
            nav,
            section {
                display: block;
            }
            
            body {
                font-family: Microsoft YaHei, tahoma, arial, Hiragino Sans GB, \\5b8b\4f53, sans-serif;
            }
            
            ol,
            ul {
                list-style: none;
            }
            
            blockquote,
            q {
                quotes: none;
            }
            
            blockquote:before,
            blockquote:after,
            q:before,
            q:after {
                content: '';
                content: none;
            }
            
            ins {
                text-decoration: none;
            }
            
            del {
                text-decoration: line-through;
            }
            
            table {
                border-collapse: collapse;
                border-spacing: 0;
            }
            /* ------------ */
            
            #wrapper {
                width: 980px;
                margin: 0 auto;
                margin: 1em;
                width: auto;
            }
            
            #container {
                border: 1px solid #dadada;
                color: #838383;
                font-size: 12px;
                margin-top: 10px;
                background-color: #FFF;
            }
            
            #uploader .queueList {
                margin: 20px;
            }
            
            .element-invisible {
                position: absolute !important;
                clip: rect(1px 1px 1px 1px);
                /* IE6, IE7 */
                clip: rect(1px, 1px, 1px, 1px);
            }
            
            #uploader .placeholder {
                border: 3px dashed #e6e6e6;
                min-height: 238px;
                padding-top: 158px;
                text-align: center;
                background: url(<?= $this->view->img_com ?>/image.png) center 93px no-repeat;
                color: #cccccc;
                font-size: 18px;
                position: relative;
            }
            
            #uploader .placeholder .webuploader-pick {
                font-size: 14px;
                background: #00b7ee;
                border-radius: 3px;
                line-height: 44px;
                padding: 0 30px;
                color: #fff;
                display: inline-block;
                margin: 20px auto;
                cursor: pointer;
                width:auto;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
            }
            
            #uploader .placeholder .webuploader-pick-hover {
                background: #00a2d4;
            }
            
            #uploader .placeholder .flashTip {
                color: #666666;
                font-size: 12px;
                position: absolute;
                width: 100%;
                text-align: center;
                bottom: 20px;
            }
            
            #uploader .placeholder .flashTip a {
                color: #0785d1;
                text-decoration: none;
            }
            
            #uploader .placeholder .flashTip a:hover {
                text-decoration: underline;
            }
            
            #uploader .placeholder.webuploader-dnd-over {
                border-color: #999999;
            }
            
            #uploader .placeholder.webuploader-dnd-over.webuploader-dnd-denied {
                border-color: red;
            }
            
            #uploader .filelist {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            
            #uploader .filelist:after {
                content: '';
                display: block;
                width: 0;
                height: 0;
                overflow: hidden;
                clear: both;
            }
            
            #uploader .filelist li {
                width: 110px;
                height: 110px;
                background: url(<?= $this->view->com_img ?>/image.png) no-repeat;
                text-align: center;
                margin: 0 8px 20px 0;
                position: relative;
                display: inline;
                float: left;
                overflow: hidden;
                font-size: 12px;
            }
            
            #uploader .filelist li p.log {
                position: relative;
                top: -45px;
            }
            
            #uploader .filelist li p.title {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                overflow: hidden;
                white-space: nowrap;
                text-overflow: ellipsis;
                top: 5px;
                text-indent: 5px;
                text-align: left;
            }
            
            #uploader .filelist li p.progress {
                position: absolute;
                width: 100%;
                bottom: 0;
                left: 0;
                height: 8px;
                overflow: hidden;
                z-index: 50;
            }
            
            #uploader .filelist li p.progress span {
                display: none;
                overflow: hidden;
                width: 0;
                height: 100%;
                background: #1483d8 url(<?= $this->view->img_com ?>/progress.png) repeat-x;
                -webit-transition: width 200ms linear;
                -moz-transition: width 200ms linear;
                -o-transition: width 200ms linear;
                -ms-transition: width 200ms linear;
                transition: width 200ms linear;
                -webkit-animation: progressmove 2s linear infinite;
                -moz-animation: progressmove 2s linear infinite;
                -o-animation: progressmove 2s linear infinite;
                -ms-animation: progressmove 2s linear infinite;
                animation: progressmove 2s linear infinite;
                -webkit-transform: translateZ(0);
            }
            
            @-webkit-keyframes progressmove {
                0% {
                    background-position: 0 0;
                }
                100% {
                    background-position: 17px 0;
                }
            }
            
            @-moz-keyframes progressmove {
                0% {
                    background-position: 0 0;
                }
                100% {
                    background-position: 17px 0;
                }
            }
            
            @keyframes progressmove {
                0% {
                    background-position: 0 0;
                }
                100% {
                    background-position: 17px 0;
                }
            }
            
            #uploader .filelist li p.imgWrap {
                position: relative;
                z-index: 2;
                line-height: 110px;
                vertical-align: middle;
                overflow: hidden;
                width: 110px;
                height: 110px;
                -webkit-transform-origin: 50% 50%;
                -moz-transform-origin: 50% 50%;
                -o-transform-origin: 50% 50%;
                -ms-transform-origin: 50% 50%;
                transform-origin: 50% 50%;
                -webit-transition: 200ms ease-out;
                -moz-transition: 200ms ease-out;
                -o-transition: 200ms ease-out;
                -ms-transition: 200ms ease-out;
                transition: 200ms ease-out;
            }
            
            #uploader .filelist li img {
                width: 100%;
            }
            
            #uploader .filelist li p.error {
                background: #f43838;
                color: #fff;
                position: absolute;
                bottom: 0;
                left: 0;
                height: 28px;
                line-height: 28px;
                width: 100%;
                z-index: 100;
            }
            
            #uploader .filelist li .success {
                display: block;
                position: absolute;
                left: 0;
                bottom: 0;
                height: 40px;
                width: 100%;
                z-index: 200;
                background: url(<?= $this->view->com_img ?>/success.png) no-repeat right bottom;
            }
            
            #uploader .filelist div.file-panel {
                position: absolute;
                height: 0;
                filter: progid:DXImageTransform.Microsoft.gradient(GradientType=0, startColorstr='#80000000', endColorstr='#80000000')\0;
                background: rgba( 0, 0, 0, 0.5);
                width: 100%;
                top: 0;
                left: 0;
                overflow: hidden;
                z-index: 300;
            }
            
            #uploader .filelist div.file-panel span {
                width: 24px;
                height: 24px;
                display: inline;
                float: right;
                text-indent: -9999px;
                overflow: hidden;
                background: url(<?= $this->view->img_com ?>/icons.png) no-repeat;
                margin: 5px 1px 1px;
                cursor: pointer;
            }
            
            #uploader .filelist div.file-panel span.rotateLeft {
                background-position: 0 -24px;
            }
            
            #uploader .filelist div.file-panel span.rotateLeft:hover {
                background-position: 0 0;
            }
            
            #uploader .filelist div.file-panel span.rotateRight {
                background-position: -24px -24px;
            }
            
            #uploader .filelist div.file-panel span.rotateRight:hover {
                background-position: -24px 0;
            }
            
            #uploader .filelist div.file-panel span.cancel {
                background-position: -48px -24px;
            }
            
            #uploader .filelist div.file-panel span.cancel:hover {
                background-position: -48px 0;
            }
            
            #uploader .statusBar {
                height: 63px;
                border-top: 1px solid #dadada;
                padding: 0 20px;
                line-height: 63px;
                vertical-align: middle;
                position: relative;
            }
            
            #uploader .statusBar .progress {
                border: 1px solid #1483d8;
                width: 198px;
                background: #fff;
                height: 18px;
                position: relative;
                display: inline-block;
                text-align: center;
                line-height: 20px;
                color: #6dbfff;
                position: relative;
                margin-right: 10px;
            }
            
            #uploader .statusBar .progress span.percentage {
                width: 0;
                height: 100%;
                left: 0;
                top: 0;
                background: #1483d8;
                position: absolute;
            }
            
            #uploader .statusBar .progress span.text {
                position: relative;
                z-index: 10;
            }
            
            #uploader .statusBar .info {
                display: inline-block;
                font-size: 14px;
                color: #666666;
            }
            
            #uploader .statusBar .btns {
                position: absolute;
                top: 10px;
                right: 20px;
                line-height: 40px;
            }
            
            #filePicker2 {
                display: inline-block;
                float: left;
            }
            
            #uploader .statusBar .btns .webuploader-pick,
            #uploader .statusBar .btns .uploadBtn,
            #uploader .statusBar .btns .uploadBtn.state-uploading,
            #uploader .statusBar .btns .uploadBtn.state-paused {
                background: #ffffff;
                border: 1px solid #cfcfcf;
                color: #565656;
                padding: 0 18px;
                display: inline-block;
                border-radius: 3px;
                margin-left: 10px;
                cursor: pointer;
                font-size: 14px;
                float: left;
            }
            
            #uploader .statusBar .btns .webuploader-pick-hover,
            #uploader .statusBar .btns .uploadBtn:hover,
            #uploader .statusBar .btns .uploadBtn.state-uploading:hover,
            #uploader .statusBar .btns .uploadBtn.state-paused:hover {
                background: #f0f0f0;
            }
            
            #uploader .statusBar .btns .uploadBtn {
                background: #00b7ee;
                color: #fff;
                border-color: transparent;
            }
            
            #uploader .statusBar .btns .uploadBtn:hover {
                background: #00a2d4;
            }
            
            #uploader .statusBar .btns .uploadBtn.disabled {
                pointer-events: none;
                opacity: 0.6;
            }
            
            .webuploader-pick {
                height: 40px !important;
                line-height: 40px !important;
            }
        </style>
        <link href="<?=$this->view->css_com?>/webuploader.css" rel="stylesheet">
        <script src="<?=$this->view->js_com?>/webuploader.js"></script>
        <script src="<?=$this->view->js?>/taobao_import_image.js"></script>

        <ol class="step fn-clear add-goods-step clearfix">
            <li style="width:32%;">
                <i class="icon iconfont icon-shangjiaruzhushenqing"></i>
                <h6><?=__('STEP 1')?></h6>

                <a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Goods_TBImport&met=importFile&typ=e"><h2><?=__('第一步')?>：<?=__('导入CSV文件')?></h2></a>
                <i class="arrow iconfont icon-btnrightarrow"></i>
            </li>
            <li style="width:32%;">
                <i class="icon iconfont icon-zhaoxiangji bbc_seller_color"></i>
                <h6 class="bbc_seller_color"><?=__('STEP 2')?></h6>

                <h2 class="bbc_seller_color"><?=__('第二步')?>：<?=__('上传商品图片')?></h2>
            </li>
        </ol>
        <div class="alert mt15 mb5"><strong><?=__('操作提示')?>：</strong>
            <ul>
                <li>1、<?=__('如果修改CSV文件请务必使用微软excel软件，且必须保证第一行表头名称含有如下项目: 宝贝名称、宝贝价格、宝贝数量、运费承担、平邮、EMS、快递、橱窗推荐、宝贝描述、新图片。')?></li>
                <li>2、<?=__('如果因为淘宝助理版本差异表头名称有出入，请先修改成上述的名称方可导入，不区分全新、二手、闲置等新旧程度，导入后商品类型都是全新。')?></li>
                <li>3、<?=__('如果CSV文件超过8M请通过excel软件编辑拆成多个文件进行导入')?></li>
                <li>4、<?=__('每个商品最多支持导入5张图片,暂不支持JIF格式。')?></li>
                <li>5、<?=__('必须保证文件编码为UTF-8。')?></li>
            </ul>
        </div>

        <div id="wrapper">
            <div id="container">
                <!--头部，相册选择和格式选择-->

                <div id="uploader">
                    <div class="queueList">
                        <div id="dndArea" class="placeholder">
                            <div id="filePicker" class="webuploader-container">
                                <div class="webuploader-pick"><?=__('点击选择图片')?></div>
                                <div id="rt_rt_1b20iklu81mp75ij1qem1q9v1nsb1" style="position: absolute; top: 20px; left: 836px; width: 168px; height: 44px; overflow: hidden; bottom: auto; right: auto;">
                                    <input type="file" capture="camera" name="file" class="webuploader-element-invisible" multiple="multiple">
                                    <label style="opacity: 0; width: 100%; height: 100%; display: block; cursor: pointer; background: rgb(255, 255, 255);"></label>
                                </div>
                            </div>
                            <p class="f14"><?=__('或将照片拖到这里')?>，<?=__('单次最多可选300张')?></p>
                        </div>
                        <ul class="filelist"></ul>
                    </div>
                    <div class="statusBar" style="display:none;">
                        <div class="progress" style="display: none;">
                            <span class="text">0%</span>
                            <span class="percentage" style="width: 0%;"></span>
                        </div>
                        <div class="info"><?=__('共0张')?>（0B），<?=__('已上传0张')?></div>
                        <div class="btns">
                            <div id="filePicker2" class="webuploader-container">
                                <div class="webuploader-pick"><?=__('继续添加')?></div>
                                <div id="rt_rt_1b20ikluc1sh41u3u1361ffa1cfq6" style="position: absolute; top: 0px; left: 0px; width: 38px; height: 2px; overflow: hidden; bottom: auto; right: auto;">
                                    <input type="file" capture="camera" name="file" class="webuploader-element-invisible" multiple="multiple">
                                    <label style="opacity: 0; width: 100%; height: 100%; display: block; cursor: pointer; background: rgb(255, 255, 255);"></label>
                                </div>
                            </div>
                            <div class="uploadBtn state-pedding"><?=__('开始上传')?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

            <script>
                $(function () {
                    // $('div.tabmenu').remove();

                })
            </script>