<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>
</div>
<form action="" enctype="multipart/form-data" id="form" name="form" method="post">
    <!--S  店铺评价  -->
    <div class="order_content">
        <div class="logistics_mes clearfix">
            <div class="logistics_mes_left">
                <img src="<?= ($data['shop_base']['shop_logo']) ?>" />
                <div class="distribution_personnel">
                    <p class="distribution_personnel_name">
                        <span class="shop_name"><?= ($data['shop_base']['shop_name']) ?></span>
                    </p>
                    <p class="dis_personal_mes" style="width: 132px;"><?= ($data['shop_base']['shop_region']) ?> <?= ($data['shop_base']['shop_address']) ?></p>
                    <!--<p class="dis_personal_mes"><?= __('电话：') ?><?= ($data['shop_base']['shop_tel']) ?></p>-->
                </div>
            </div>
            <div class="logistics_mes_right">
                <dl>
                    <dt><?= __('描述相符') ?></dt>
                    <dd>
                        <i onClick="select_sort('a','1')" id="star_a1" class="iconfont icon-icostar star_a1"></i>
                        <i onClick="select_sort('a','2')" id="star_a2" class="iconfont icon-icostar star_a2"></i>
                        <i onClick="select_sort('a','3')" id="star_a3" class="iconfont icon-icostar star_a3"></i>
                        <i onClick="select_sort('a','4')" id="star_a4" class="iconfont icon-icostar star_a4"></i>
                        <i onClick="select_sort('a','5')" id="star_a5" class="iconfont icon-icostar star_a5"></i>
                    </dd>
                    <span id="stexta"><b><?= __('5分') ?></b></span>
                    <input type="hidden" id="snuma" class="package_scores" name="package_scores" value="5">
                </dl>
                <dl>
                    <dt><?= __('发货速度') ?></dt>
                    <dd>
                        <i onClick="select_sort('b','1')" id="star_b1" class="iconfont icon-icostar star_b1"></i>
                        <i onClick="select_sort('b','2')" id="star_b2" class="iconfont icon-icostar star_b2"></i>
                        <i onClick="select_sort('b','3')" id="star_b3" class="iconfont icon-icostar star_b3"></i>
                        <i onClick="select_sort('b','4')" id="star_b4" class="iconfont icon-icostar star_b4"></i>
                        <i onClick="select_sort('b','5')" id="star_b5" class="iconfont icon-icostar star_b5"></i>
                    </dd>
                    <span id="stextb"><b><?= __('5分') ?></b></span>
                    <input type="hidden" id="snumb" class="send_scores" name="send_scores" value="5">
                </dl>
                <dl>
                    <dt><?= __('服务态度') ?></dt>
                    <dd>
                        <i onClick="select_sort('c','1')" id="star_c1" class="iconfont icon-icostar star_c1"></i>
                        <i onClick="select_sort('c','2')" id="star_c2" class="iconfont icon-icostar star_c2"></i>
                        <i onClick="select_sort('c','3')" id="star_c3" class="iconfont icon-icostar star_c3"></i>
                        <i onClick="select_sort('c','4')" id="star_c4" class="iconfont icon-icostar star_c4"></i>
                        <i onClick="select_sort('c','5')" id="star_c5" class="iconfont icon-icostar star_c5"></i>
                    </dd>
                    <span id="stextc"><b><?= __('5分') ?></b></span>
                    <input type="hidden" id="snumc" class="service_scores" name="service_scores" value="5">
                </dl>
            </div>
        </div>
        <!--E  店铺评价  -->
        
        <!--S 循环评价商品 -->
        <div class="evaluation-list">
            <?php $i = 0;
                foreach ($data['order_goods'] as $ogkey => $ogval) { ?>
                    <div class="evaluation-timeline view_mes clearfix">
                        <input type="hidden" class="order_goods_id" name="order_goods_id" value="<?= ($ogval['order_goods_id']) ?>">
                        <!--S  商家信息 -->
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
                        <!--E 商家信息 -->
                        
                        <!--S 评论内容  -->
                        <dl class="detail detail_dls">
                            <dt class="clearfix  detail_first">
                                <!-- 商品图片 -->
                                <?php if (!empty($ogval['goods_image'])) {
                                    $goods_image = $ogval['goods_image'];
                                } else {
                                    $goods_image = $this->web['goods_logo'];
                                }
                                ?>
                                <img src="<?= image_thumb($goods_image, 100, 100) ?>" />
                                <!-- 商品名称 -->
                                <span><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= ($ogval['goods_id']) ?>"><?= ($ogval['goods_name']) ?></a></span>
                                <!-- 商品价格 -->
                                <span><?= format_money($ogval['goods_price']) ?></span>
                            </dt>
                            
                            <div class="pingfen clearfix">
                                <em class="iconfont"></em>
                                <span><?= __('评分') ?></span>
                                <p>
                                    <i onClick="select_sort('d<?= ($i) ?>','1')" id="star_d<?= ($i) ?>1" class="iconfont icon-icostar star_d<?= ($i) ?>1"></i>
                                    <i onClick="select_sort('d<?= ($i) ?>','2')" id="star_d<?= ($i) ?>2" class="iconfont icon-icostar star_d<?= ($i) ?>2"></i>
                                    <i onClick="select_sort('d<?= ($i) ?>','3')" id="star_d<?= ($i) ?>3" class="iconfont icon-icostar star_d<?= ($i) ?>3"></i>
                                    <i onClick="select_sort('d<?= ($i) ?>','4')" id="star_d<?= ($i) ?>4" class="iconfont icon-icostar star_d<?= ($i) ?>4"></i>
                                    <i onClick="select_sort('d<?= ($i) ?>','5')" id="star_d<?= ($i) ?>5" class="iconfont icon-icostar star_d<?= ($i) ?>5"></i>
                                </p>
                                <p>
                                    <span id="stextd<?= ($i) ?>" class="stextd<?= ($i) ?>"><b><?= __('5分') ?></b></span>
                                </p>
                                <input type="hidden" id="snumd<?= ($i) ?>" class="snumd" name="goods_scores" value="5">
                                <input type="hidden" id="resultd<?= ($i) ?>" class="result" name="result" value="good">
                            </div>
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
                                <a id="filePicker" class="js-file-picker add_img add_box filePicker" style="height: 43px;height: 57px;border: none;"><i class="iconfont icon-jia"></i><?= __('晒单') ?></a>
                                <!--<div id="filePicker">选择图片</div>-->
                            
                            </div>
                        
                        </dl>
                    </div>
                    <?php $i++;
                } ?>
        </div>
        <!--E 循环评价商品 -->
        <div class="publish_eval">
            <p>
                <a class="up_view submit bbc_btns"><?= __('发布评价') ?></a>
                <input name="isanonymous" class="isanonymous" type="checkbox" value="1">
                <span><?= __('匿名评价') ?></span>
            </p>
        </div>
    </div>
</form>
<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/upload/upload_image.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script src="<?= $this->view->js ?>/upload.js"></script>

<script>
    /*//控制评论的字数-------李宇
        $(function(){
            $(".content").val("");
            $(".content").keyup(function(){
                var len = $(this).val().length;
                if(len > 199){
                    $(this).val($(this).val().substring(0,200));
                }
                var num = 200 - len;
                if(num <= 0)
                {
                    num = 0
                }
                $(this).parent().find("#word").text(num);
            });
        });
    */
    
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
            $(this).parent().next().find("#words").text(lengths);
        });
    });
    
    $(function () {
        //评论选择小星星
        window.select_sort = function (s, v) {
            for (i = 1; i <= 5; i++) {
                if (i <= v) {
                    $('' + '#star_' + s + i).css('color', 'red');
                } else {
                    $('#star_' + s + i).css('color', '#7A8591');
                }
                
                $('#stext' + s).html("<b>" + v + "<?=__('分')?></b>");
                $('#snum' + s).val(v);
            }
            if (v == 1) {
                $("#result" + s).val('bad');
            } else if (v == 2 || v == 3) {
                $("#result" + s).val('neutral');
            } else {
                $("#result" + s).val('good');
            }
        }
    });
    
    //提交表单
    var evaluation = [],flag = true;
    $(".submit").click(function () {
        $(".view_mes").each(function () {
            var evaltation_goods = [];
            evaltation_goods.push($(this).find(".order_goods_id").val());//商品id
            evaltation_goods.push($(this).find(".snumd").val());  //商品评分
            evaltation_goods.push($(this).find(".result").val());  //good,bad,middle
            evaltation_goods.push($(this).find(".content").val());  //评价内容 
            var img = '';
            $(this).find(".file-item").each(function () {
                div_data = $(this).data();
                img += div_data.img_src + ',';
            });
            evaltation_goods.push(img);
            evaluation.push(evaltation_goods);
        });
        $("#form").submit();
    });
    
    
    //表单提交
    $(document).ready(function () {
        var ajax_url = 'index.php?ctl=Goods_Evaluation&met=addGoodsEvaluation&typ=json';
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {},
            valid: function (form) {
                var me = this;
                // Before submitting the form, hold form, to prevent duplicate submission.
                me.holdSubmit();
                //表单验证通过，提交表单
                package_scores = $(".package_scores").val();
                send_scores = $(".send_scores").val();
                service_scores = $(".service_scores").val();
                if ($(".isanonymous").is(':checked')) {
                    isanonymous = 1;
                } else {
                    isanonymous = 0;
                }
                console.info(evaluation);
                if (flag){
                    $.ajax({
                        url: ajax_url,
                        data: {package_scores: package_scores, send_scores: send_scores, service_scores: service_scores, isanonymous: isanonymous, evaluation: evaluation},
                        success: function (a) {
                            console.info(a);
                            if (a.status == 200) {
                                flag = false;
                                //$.dialog.alert('操作成功');
                                Public.tips.success('<?=__('评论发表成功！')?>');
                                location.href = "<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=evaluation";
                            } else {
                                if (a.msg != 'failure') {
                                    Public.tips.success(a.msg);
                                } else {
                                    Public.tips.error('<?=__('评论发表失败！')?>');
                                }
                            }
                        },
                        complete: function () {
                            me.holdSubmit(false);
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
