<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>

    <link href="<?= $this->view->css_com ?>/jquery/plugins/lightbox/css/jquery.lightbox.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.lightbox.min.js"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/upload/upload_image.js" charset="utf-8"></script>

    <div class="aright">
        <div class="member_infor_content">
            <div class="div_head  tabmenu clearfix">
                <ul class="tab pngFix clearfix">
                    <li class="active">
                        <a><?= __('交易投诉申请') ?></a>
                    </li>
                </ul>
            </div>
            <div class="ncm-flow-layout" id="ncmComplainFlow">
                <div class="ncm-flow-container">
                    <div class="ncm-flow-step">
                        <dl id="state_new" class="step-first current1">
                            <dt><?= __('新投诉') ?></dt>
                            <dd class="bg"></dd>
                        </dl>
                        <dl id="state_appeal" <?php if ($data['complain_state'] >= 2)
                        {
                            echo 'class="current1"';
                        } ?>>
                            <dt><?= __('待申诉') ?></dt>
                            <dd class="bg"></dd>
                        </dl>
                        <dl id="state_talk" <?php if ($data['complain_state'] >= 3)
                        {
                            echo 'class="current1"';
                        } ?>>
                            <dt><?= __('对话中') ?></dt>
                            <dd class="bg"></dd>
                        </dl>
                        <dl id="state_handle" <?php if ($data['complain_state'] >= 4)
                        {
                            echo 'class="current1"';
                        } ?>>
                            <dt><?= __('待仲裁') ?></dt>
                            <dd class="bg"></dd>
                        </dl>
                        <dl id="state_finish" <?php if ($data['complain_state'] >= 5)
                        {
                            echo 'class="current1"';
                        } ?>>
                            <dt><?= __('已完成') ?></dt>
                            <dd class="bg"></dd>
                        </dl>
                    </div>
                    <div class="ncm-default-form">
                        <h3><?= __('投诉信息') ?></h3>
                        <dl>
                            <dt><?= __('被投诉店铺：') ?></dt>
                            <dd><?= $data['order']['shop_name'] ?></dd>
                        </dl>
                        <dl>
                            <dt><?= __('投诉主题：') ?></dt>
                            <dd><?= $data['complain_subject_content'] ?></dd>
                        </dl>
                        <dl>
                            <dt><?= __('投诉时间：') ?></dt>
                            <dd><?= $data['complain_datetime'] ?></dd>
                        </dl>
                        <dl>
                            <dt><?= __('投诉内容：') ?></dt>
                            <dd><?= $data['complain_content'] ?></dd>
                        </dl>
                        <dl>
                            <dt><?= __('投诉证据：') ?></dt>
                            <dd>
                                <?php if (empty($data['complain_pic_content']))
                                { ?>
                                  
                                        <input id="complain_pic_1" name="complain_pic[]" type="hidden" />
                                        <div id="user_upload_1" style="width:65px"><?=__('图片上传')?></div>
                                    </label>
                                <?php }
                                else
                                { ?>
                                    <?php foreach ($data['complain_pic_content'] as $v)
                                { ?>
                                    <a href="<?= $v ?>" title="" class="lightbox"><img  style='width:200px;' src="<?= $v ?>"></a>
                                <?php } ?>
                                <?php } ?>
                            </dd>
                        </dl>

                        <?php if ($data['complain_state'] >= 3)
                        { ?>
                            <h3><?= __('申诉详情') ?></h3>
                            <dl>
                                <dt><?= __('申诉内容：') ?></dt>
                                <dd><?= $data['appeal_message'] ?></dd>
                            </dl>
                            <dl>
                                <dt><?= __('申诉证据截图：') ?></dt>
                                <dd><?php if (empty($data['appeal_pic_content']))
                                    { ?>
                                        <?= __('暂无图片') ?>
                                    <div id='img_show_1' style='display: none;' onmouseout="javascript:$('#img_del_1').hide();" onmouseover="javascript:$('#img_del_1').show();"><span><a href="##" id='image_img_href_1' target="_black" class='lightbox_1' ><img id="image_img_1" src="" style="width:120px;height:120px;"></a><span id='img_del_1' style="display:none;cursor: pointer;" onclick="delete_img(1)">删除</span></div>
                                    <?php }
                                    else
                                    { ?>
                                        <?php foreach ($data['appeal_pic_content'] as $v)
                                    { ?>

                                        <a href="<?= $v ?>" title="" class="lightbox"><img style='width:200px;' src="<?= $v ?>"></a>
                                    <?php } ?>
                                    <?php } ?></dd>
                            </dl>



                            <h3><?= __('对话详情') ?></h3>
                            <dl>
                                <dt><?= __('对话记录：') ?></dt>
                                <dd>
                                    <div id="div_talk" class="ncm-complain-talk"></div>
                                </dd>
                            </dl>
                        <?php } ?>
                        <?php if ($data['complain_state'] >= 3 && $data['complain_state'] < 5)
                        { ?>
                            <dl>
                                <dt><?= __('发布对话：') ?></dt>
                                <dd>
                                    <textarea id="complain_talk" class="w400 textarea_text"></textarea>
                                </dd>
                            </dl>
                            <dl>
                                <dt>&nbsp;</dt>
                                <dd>
                                    <label class="submit-border bbc_btns"><input id="btn_publish" type="button" class="submit bbc_btns"
                                                                        value="<?= __('发布对话') ?>"></label>
                                    <label class="submit-border bbc_btns"><input id="btn_refresh" type="button" class="submit bbc_btns"
                                                                        value="<?= __('刷新对话') ?>"></label>
                                </dd>
                            </dl>
                            <?php
                        }
                        ?>
                        <!-- 新增投诉结果 -->
                         <h3><?= __('投诉结果') ?></h3>
                         <p class="pl20 pr20 pt10">卖家已关闭投诉</p>
                         <p class="pl20 pr20 pt10">如仍有疑问请拨打平台电话：<a href="javascript:;" class="tel-call">400-1234-1234</a></p>

                        <dl class="foot">
                            <form id="form" action="#" method="post">
                                <input type="hidden" name="complain_id" id="complain_id"
                                       value="<?= $data['complain_id'] ?>">
                                <dt>&nbsp;</dt>
                                <?php if ($data['complain_state_etext'] == "talk")
                                { ?>
                                    <dd>
                                        <label id="handle_submit" class="submit-border">
                                            <input type="button" value="<?= __('提交仲裁') ?>" class="submit">
                                        </label>
                                        <label id="handle_close" class="submit-border">
                                            <input type="button" value="<?= __('关闭投诉') ?>" class="submit">
                                        </label>
                                    </dd>
                                <?php } ?>
                            </form>
                        </dl>
                    </div>
                </div>
                <div class="ncm-flow-item">
                    <div class="title"><?= __('相关商品交易') ?></div>
     
                    <div class="item-order">
                        <dl>
                            <dt><?= __('商品名称：') ?></dt>
                            <dd><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $data['ordergoods']['goods_id'] ?>"><?= $data['ordergoods']['goods_name'] ?></a></dd>
                        </dl>
                        <dl>
                            <dt><?= __('单价：') ?></dt>
                            <dd><?=format_money($data['ordergoods']['goods_price'])?></dd>
                        </dl>
                        <dl>
                            <dt><?= __('数量：') ?></dt>
                            <dd><?=$data['ordergoods']['order_goods_num']?></dd>
                        </dl>
                        <dl>
                            <dt><?= __('运费：') ?></dt>
                            <dd><?= format_money($data['order']['order_shipping_fee']) ?></dd>
                        </dl>
                        <dl>
                            <dt><?= __('订单总额：') ?></dt>
                            <dd><strong><?= format_money($data['order']['order_payment_amount']) ?></strong></dd>
                        </dl>
                        <dl class="line">
                            <dt><?= __('订单编号：') ?></dt>
                            <dd>
                                <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?= $data['order']['order_id'] ?>"
                                   target="_blank"> <?= $data['order']['order_id'] ?> </a> <a
                                    href="javascript:void(0);" class="a"><?= __('更多') ?><i
                                        class="iconfont icon-iconjiantouxia"></i>
                                    <div class="more"><span class="arrow"></span>
                                        <ul>
                                            <li><?= __('支付方式：') ?><span><?= $data['order']['payment_name'] ?></span></li>
                                            <li><?= __('下单时间：') ?><span><?= $data['order']['order_create_time'] ?></span>
                                            </li>
                                            <li><?= __('付款时间：') ?><span><?= $data['order']['payment_time'] ?></span></li>
                                            <li><?= __('发货时间：') ?><span><?= $data['order']['order_shipping_time'] ?></span></li>
                                            <li><?= __('完成时间：') ?><span><?= $data['order']['order_finished_time'] ?></span></li>
                                        </ul>
                                    </div>
                                </a></dd>
                        </dl>
                        <dl class="line">
                            <dt><?= __('商家：') ?></dt>
                            <dd><?= $data['order']['shop_name'] ?><a
                                    href="javascript:void(0);" class="a"><?= __('更多') ?><i
                                        class="iconfont icon-iconjiantouxia"></i>
                                    <div class="more"><span class="arrow"></span>
                                        <ul>
                                            <li><?= __('所在地区：') ?><span><?= $data['shop']['shop_company_address'] ?></span></li>
                                            <li><?= __('联系电话：') ?><span><?= $data['shop']['shop_tel'] ?></span></li>
                                        </ul>
                                    </div>
                                </a>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <link href="./shop/static/common/css/jquery/plugins/dialog/green.css" rel="stylesheet">
            <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
            <script>
                var complain_id = $('#complain_id').val();
                var user_upload = new UploadImage({
                    uploadButton: '#user_upload_1',
                    inputHidden: '#complain_pic_1',
                    callback:getImageList
                });

                function getImageList(result) {
                    var complain_pic = result.url;
                    $.ajax({
                        url: SITE_URL + '?ctl=Buyer_Service_Complain&met=addPic&typ=json',
                        data: {complain_pic: complain_pic, complain_id: '<?=$data['id']?>'},
                        success: function (a)
                        {
                            if (a.status == 200)
                            {
                                location.reload();
                            }
                            else
                            {
                                Public.tips.error('<?=__('操作失败！')?>');
                            }
                        }
                    });
                }
                


                $(document).ready(function ()
                {
                    $('a.lightbox').lightBox();
                    var act = "<?=$data['complain_state_etext']?>";

                    if (act == 'talk')
                    {
                        var ajax_url = 'index.php?ctl=Buyer_Service_Complain&met=submitComplain&typ=json';
                    }

                    $('#form').validator({
                        ignore: ':hidden',
                        theme: 'yellow_right',
                        timely: 1,
                        stopOnError: false,
                        fields: {},
                        valid: function (form)
                        {
                            //表单验证通过，提交表单
                            $.ajax({
                                url: ajax_url,
                                data: $("#form").serialize(),
                                success: function (a)
                                {
                                    if (a.status == 200)
                                    {
                                        location.href = "./index.php?ctl=Buyer_Service_Complain&met=index&act=detail&id=<?=$data['id']?>";
                                    }
                                    else
                                    {
                                        Public.tips.error('<?=__('操作失败！')?>');
                                    }
                                }
                            });
                        }

                    }).on("click", "#handle_submit", function (e)
                    {
                        $(e.delegateTarget).trigger("validate");
                    });

                    $("#handle_close").click(function ()
                    {
                        $.ajax({
                            type: 'POST',
                            url: "index.php?ctl=Buyer_Service_Complain&met=closeComplain&typ=json",
                            data: "complain_id=" + complain_id,
                            success: function (a)
                            {
                                if (a.status == 200)
                                {
                                    location.href = "./index.php?ctl=Buyer_Service_Complain&met=index&act=detail&id=<?=$data['id']?>";
                                }
                                else
                                {
                                    Public.tips.error('<?=__('操作失败！')?>');
                                }
                            }
                        });
                    });

                });


                //刷新对话
                function get_complain_talk()
                {
                    $("#div_talk").empty();
                    $.ajax({
                        type: 'POST',
                        url: SITE_URL + '?ctl=Buyer_Service_Complain&met=getComplainTalk&typ=json',
                        cache: false,
                        data: "complain_id=" + complain_id,
                        dataType: 'json',
                        success: function (data)
                        {
                            if (Object.getOwnPropertyNames(data.data).length >= 1)
                            {
                                for (var i = 0; i < Object.getOwnPropertyNames(data.data).length - 1; i++)
                                {
                                    var link = "<p class='" + data.data[i].acc_type + "'>" + data.data[i].talk_datetime + data.data[i].member_type + '(' + data.data[i].user_name + ')说：' + data.data[i].talk_content + "</p>";
                                    $("#div_talk").append(link);
                                }
                            }
                            else
                            {
                                $("#div_talk").append("<p class='placeholder'>" + "<?=__('目前没有对话')?>" + "</p>");
                            }
                        },
                        error: function ()
                        {
                            $("#div_talk").append("<p class='placeholder'>" + "<?=__('目前没有对话')?>" + "</p>");
                        }
                    });
                }

                get_complain_talk();
                $("#btn_publish").click(function ()
                {
                    if ($("#complain_talk").val() == '')
                    {
                        Public.tips.error("<?=__('对话不能为空')?>");
                    }
                    else
                    {
                        publish_complain_talk();
                    }
                });
                $("#btn_refresh").click(function ()
                {
                    get_complain_talk();
                });

                //发布对话
                function publish_complain_talk()
                {
                    $.ajax({
                        type: 'POST',
                        url: SITE_URL + '?ctl=Buyer_Service_Complain&met=publishComplainTalk&typ=json',
                        cache: false,
                        data: "complain_id=" + complain_id + "&complain_talk=" + encodeURIComponent($("#complain_talk").val()),
                        dataType: 'json',
                        error: function ()
                        {
                            Public.tips.error("<?=__('对话发送失败')?>");
                        },
                        success: function (d)
                        {
                            if (d.msg == 'success')
                            {
                                $("#complain_talk").val('');
                                Public.tips.success("<?=__('对话发送成功')?>");
                                get_complain_talk();
                            }
                            else
                            {
                                Public.tips.error("<?=__('对话发送失败')?>");
                            }
                        }
                    });
                }
            </script>
        </div>
    </div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>