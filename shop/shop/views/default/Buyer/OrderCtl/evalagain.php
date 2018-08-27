<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
    </div>
    <style>
        div.zoomDiv {
            z-index: 999;
            position: absolute;
            top: 0px;
            left: 0px;
            width: 200px;
            height: 200px;
            background: #ffffff;
            border: 1px solid #CCCCCC;
            display: none;
            text-align: center;
            overflow: hidden;
        }
        
        div.zoomMask {
            position: absolute;
            background: url("http://demo.lanrenzhijia.com/2015/jqzoom0225/images/mask.png") repeat scroll 0 0 transparent;
            cursor: move;
            z-index: 1;
        }
    </style>
    <script src="<?= $this->view->js_com ?>/plugins/jquery.imagezoom.small.js"></script>
    <script>
        $(function () {
            $(".jqzoom").simagezoom();
        });
    </script>
    <form action="" enctype="multipart/form-data" id="form" name="form" method="post">
        <input type="hidden" name="evaluation_goods_id" value="<?= ($data['evaluation_goods_id']) ?>">
        <input type="hidden" name="order_goods_id" value="<?= ($data['goods_base']['order_goods_id']) ?>">
        
        <div class="order_content">
            <div class="evaluation-timeline logistics_mes clearfix" style="margin-bottom: 50px;">
                <!--S 商品信息 -->
                <div class="date clearfix">
                    <!-- 商品图片 -->
                    <div class="goods_image">
                        <?php if (!empty($data['goods_base']['goods_image'])) {
                            $goods_image = $data['goods_base']['goods_image'];
                        } else {
                            $goods_image = $this->web['goods_logo'];
                        }
                        ?>
                        <a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= ($data['goods_base']['goods_id']) ?>">
                            <img src="<?= image_thumb($goods_image, 86, 86) ?>" />
                        </a>
                    </div>
                    
                    <div class="order_goods">
                        <!-- 商品名称 -->
                        <a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= ($data['goods_base']['goods_id']) ?>"><?= ($data['goods_base']['goods_name']) ?></a>
                        <!-- 商品价格 -->
                        <p class="bbc_color price_pad"><?= format_money($data['goods_base']['goods_price']) ?></p>
                    </div>
                </div>
                <!--E 商品信息  -->
                
                <!--S  首次评价内容-->
                <div class="view_mes clearfix">
                    <div class="goods-thumb">
                        <!-- 用户头像 -->
                        <?php if (!empty($data['user_info']['user_logo'])) {
                            $user_logo = $data['user_info']['user_logo'];
                        } else {
                            $user_logo = $this->web['user_logo'];
                        }
                        ?>
                        <img src="<?= image_thumb($user_logo, 60, 60) ?>">
                        <!-- 用户名称 -->
                        <p><?= ($data['user_info']['user_name']) ?></p>
                    </div>
                    <dl class="detail detail_dls">
                        <dt class="clearfix">
                            <span><?= __('评论时间：') ?><?= ($data['create_time']) ?></span>
                            <span class="ml30">&nbsp;&nbsp;&nbsp;<?= __('商品评分：') ?>
                                <em style="width: 100px;" title="<?= __('很满意') ?>" class="raty" data-score="5">
                                    <?php for ($i = 1; $i <= $data['scores']; $i++): ?>
                                        <i class="iconfont icon-xingxing"></i>
                                    <?php endfor; ?>
                                    <input readonly value="<?= ($data['scores']) ?>" name="score" type="hidden">
                                </em>
                            </span>
                        </dt>
                        
                        <!-- 评价内容 -->
                        <?php Text_Filter::filterWords($data['content']); ?>
                        <dd><?= ($data['content']) ?></dd>
                        
                        <!-- 评价图片 -->
                        <div class="evaluate_img">
                            <?php if ($data['image']): foreach ($data['image_row'] as $img1key => $img1val): ?>
                                <img src="<?= image_thumb($img1val, 100, 100) ?>" class="jqzoom" rel="<?= image_thumb($img1val, 200, 200) ?>">
                            <?php endforeach;endif; ?>
                        </div>
                        
                        <!--S 解释内容  -->
                        <?php if ($data['explain_content']): ?>
                            <div style="clear: both;"></div>
                            <dl class="detail detail_dls">
                                <dt class="clearfix">
                                    <p><?= __('解释时间：') ?><?= ($data['update_time']) ?></p>
                                </dt>
                                
                                <?php Text_Filter::filterWords($data['explain_content']); ?>
                                <dd><?= ($data['explain_content']) ?></dd>
                            </dl>
                        <?php endif; ?>
                        <!--E 解释内容  -->
                    </dl>
                </div>
                <!--E  首次评价内容-->
                
                
                <!--S  追加评价内容-->
                <div class="order_myview">
                    
                    <div class="view_mes clearfix">
                        <div class="goods-thumb">
                            <!-- 用户头像 -->
                            <img src="<?= image_thumb($user_logo, 60, 60) ?>">
                            <!-- 用户名称 -->
                            <p><?= ($data['user_info']['user_name']) ?></p>
                        </div>
                        
                        <!--S 追加评论内容  -->
                        <div class="detail detail_dls">
                            <div class="feeling clearfix">
                                <!--先统计 字符数 限制评论字数200字以内-->
                                <p class="inp_warn">
                                    <textarea name="content" class="content" placeholder="<?= __('留下您的评论，分享给想买的他们吧 ') ?>" maxlength="200" style="resize: vertical; position: relative;"></textarea>
                                <div style="position: absolute;right: 405px;">
                                    <span id="words">0</span>
                                    <span>/ 200</span>
                                </div>
                                </p>
                            </div>
                            <div class="clearfix show_goods_mar">
                                <ul>
                                    <div id="fileList" class="uploader-list"></div>
                                </ul>
                                <input name="evaluate_img" id="evaluate_img" type="hidden" value="" />
                                <a id="filePicker" class="js-file-picker add_img add_box" style="height: 43px;height: 57px;border: none;"><i class="iconfont icon-jia"></i><?= __('晒单') ?></a>
                            </div>
                        </div>
                        <!-- E 追加评论内容 -->
                    
                    </div>
                    <div class="publish_eval">
                        <p>
                            <a class="up_view submit bbc_btns"><?= __('追加评价') ?></a>
                            <!--<input name="isanonymous" type="checkbox" value="1"><span><? /*=__('匿名评价')*/ ?></span>-->
                        </p>
                    </div>
                </div>
                <!--E  追加评价内容-->
            </div>
    </form>
    </div>
    </div>
    
    </div>
    </div>
    
    </div>
    
    </div>
    </div>
    </div>
    
    <script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/upload/upload_image.js" charset="utf-8"></script>
    <link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
    <script src="<?= $this->view->js ?>/upload.js"></script>
    
    <script>
        /*//控制心得的字数------李宇
             $(function(){
             $("#content").val("");
              $("#content").keyup(function(){
               var len = $(this).val().length;
               if(len > 199){
                $(this).val($(this).val().substring(0,200));
               }
               var num = 200 - len;
               if(num <= 0)
               {
                num = 0
               }
               $("#word").text(num);
              });
             });*/
        
        /*评论字数控制在200字数以内
         *马成军
         *2018年2月10日15:28:21
         */
        $(function () {
            $(".content").keyup(function () {
                var lengths = $(this).val().length;
                if (lengths > 199) {
                    $(this).val($(this).val().substring(0, 200));
                }
                if (lengths <= 0) {
                    lengths = 0;
                }
                $("#words").text(lengths);
            });
        });
        
        //提交表单
        $(".submit").click(function () {
            img = '';
            $(".file-item").each(function () {
                div_data = $(this).data();
                img += div_data.img_src + ',';
            });
            $("#evaluate_img").val(img);
            $("#form").submit();
        });

        var flag = true;
        //表单提交
        $(document).ready(function () {
            var ajax_url = 'index.php?ctl=Goods_Evaluation&met=againGoodsEvaluation&typ=json';
            $('#form').validator({
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                fields: {},
                valid: function (form) {
                    //表单验证通过，提交表单
                    if (flag){
                        $.ajax({
                            url: ajax_url,
                            data: $("#form").serialize(),
                            success: function (a) {
                                if (a.status == 200) {
                                    flag = false;
                                    //$.dialog.alert('操作成功');
                                    Public.tips.success('<?=__('操作成功！')?>');
                                    location.href = "<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation";
                                } else {
                                    if (a.msg != 'failure') {
                                        Public.tips.success(a.msg);
                                    } else {
                                        Public.tips.error('<?=__('操作失败！')?>');
                                    }
                                }
                            }
                        });
                    }
                }
            });
            
        });
    
    </script>

<?php
    include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>