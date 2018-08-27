<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>

<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>
    <link rel="stylesheet" type="text/css" media="screen and (min-width: 1200px)"
          href="<?= $this->view->css ?>/exchange-max.css"/>
    <link rel="stylesheet" type="text/css" media="screen and (max-width: 1200px)"
          href="<?= $this->view->css ?>/exchange-min.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/seller_center.css"/>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/lightbox/css/jquery.lightbox.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.lightbox.min.js"></script>
    <style>
        #saleRefundReturn .ncsc-flow-step dl {
            width: 13%;
        }
    </style>
    <div class="tabmenu">
        <ul>
            <li class="active bbc_seller_bg"><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Service_Complain&met=index"><?=__('投诉管理')?></a></li>
            <li class="active"><a href="javascript:void(0);"><?=__('查看详情')?></a></li>
        </ul>

    </div>


    <div class="ncsc-flow-layout">
        <div class="title">
            <h3><?=__('投诉服务')?></h3>
        </div>
        <div id="saleRefundReturn">
            <div class="ncsc-flow-step">
                <dl class="step-first current">
                    <dt><?=__('新投诉')?></dt>
                    <dd class="bg"></dd>
                </dl>
                <dl <?php if ($data['complain_state'] >= 2)
                {
                    echo 'class="current"';
                } ?>>
                    <dt><?=__('待申诉')?></dt>
                    <dd class="bg"></dd>
                </dl>
                <dl <?php if ($data['complain_state'] >= 3)
                {
                    echo 'class="current"';
                } ?>>
                    <dt><?=__('对话中')?></dt>
                    <dd class="bg"></dd>
                </dl>
                <dl <?php if ($data['complain_state'] >= 4)
                {
                    echo 'class="current"';
                } ?>>
                    <dt><?=__('待仲裁')?></dt>
                    <dd class="bg"></dd>
                </dl>
                <dl <?php if ($data['complain_state'] >= 5)
                {
                    echo 'class="current"';
                } ?>>
                    <dt><?=__('已关闭')?></dt>
                    <dd class="bg"></dd>
                </dl>
            </div>
            <div class="ncsc-form-default">
                <h3><?=__('投诉详情')?><?=$data['complain_state_etext']?></h3>
                <dl>
                    <dt><?=__('商品名称：')?></dt>
                    <dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $data['good']['goods_id'] ?>" target="_blank"><?= $data['good']['goods_name'] ?></a></dd>
                </dl>
                <dl>
                    <dt><?=__('订单编号：')?></dt>
                    <dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&act=details&order_id=<?= $data['order_id'] ?>" target="_blank"><?= $data['order_id'] ?></a></dd>
                </dl>
                <dl>
                    <dt><?=__('投诉人：')?></dt>
                    <dd><?= $data['user_account_accuser'] ?></dd>
                </dl>
                <dl>
                    <dt><?=__('投诉主题：')?></dt>
                    <dd><?= $data['complain_subject_content'] ?></dd>
                </dl>
                <dl>
                    <dt><?=__('投诉时间：')?></dt>
                    <dd><?= $data['complain_datetime'] ?></dd>
                </dl>
                <dl>
                    <dt><?=__('投诉内容：')?></dt>
                    <dd><?= $data['complain_content'] ?></dd>
                </dl>
                <dl>
                    <dt><?=__('投诉证据：')?></dt>
                    <dd>
                        <?php if (empty($data['complain_pic_content']))
                        { ?>
                            <?=__('暂无图片')?>
                        <?php }
                        else
                        { ?>
                            <?php foreach ($data['complain_pic_content'] as $v)
                        { ?>
                            <a href="<?= $v ?>" title="" class="lightbox"><img src="<?= $v ?>" width="72"
                                                                               height="72"></a>
                        <?php } ?>
                        <?php } ?>
                    </dd>
                </dl>
                <input type="hidden" name="complain_id" id="complain_id" value="<?= $data['complain_id'] ?>">
                <?php
                if ($data['complain_state_etext'] == "appeal")
                { ?>
                    <form id="form" action="#" method="post">
                        <input type="hidden" name="complain_id" id="complain_id"
                               value="<?= $data['complain_id'] ?>">
                        <h3><?=__('申诉详情')?></h3>
                        <dl>
                            <dt><?=__('申诉内容：')?></dt>
                            <dd><textarea name="appeal_message" id="appeal_message" class="textarea_text"></textarea></dd>
                        </dl>
                        <dl>
                            <dt><?=__('申诉证据：')?></dt>
                            <dd>
                                <input id="inputHidden" value="" type="hidden"/>
                                <div id="uploader-demo">
                                    <div id="fileList" class="uploader-list"></div>
                                    <div id="filePicker" class="bbc_seller_btns"><?=__('选择图片')?></div>
                                </div>
                            </dd>
                        </dl>
                        <dl class="foot">
                            <dt></dt>
                            <dd>
                                <input id="handle_appeal" type="button" class="button button_red bbc_seller_submit_btns" value="<?=__('确认提交')?>">
                                <input id="handle_close" type="button" class="button button_red bbc_seller_submit_btns" value="<?=__('关闭投诉')?>"/>
                            </dd>
                        </dl>
                    </form>
                <?php }
                elseif ($data['complain_state_etext'] == "talk" || $data['complain_state_etext'] == "handle")
                { ?>

                    <form id="form" action="#" method="post">
                        <h3><?=__('申诉详情')?></h3>
                        <dl class="dl">
                            <dt><?=__('申诉内容：')?></dt>
                            <dd><?= $data['appeal_message'] ?></dd>
                        </dl>
                        <dl class="dl">
                            <dt><?=__('申诉证据：')?></dt>
                            <dd><?php if (empty($data['appeal_pic_content']))
                                { ?>
                                    <?=__('暂无图片')?>
                                <?php }
                                else
                                { ?>
                                    <?php foreach ($data['appeal_pic_content'] as $v)
                                { ?>
                                    <a href="<?= $v ?>" title="" class="lightbox"><img src="<?= $v ?>" width="72"
                                                                                       height="72"></a>
                                <?php } ?>
                                <?php } ?></dd>
                        </dl>
                        <h3><?=__('申诉对话')?></h3>
                        <!-- 对话详情（对话中，待仲裁，已关闭-对话记录） -->
                        <dl>
                            <dt class="tit"><?=__('对话记录：')?></dt>
                            <dd class="opt">
                                <div id="div_talk" class="ncm-complain-talk">
                                </div>
                            </dd>
                        </dl>
                        <dl>
                            <dt class="tit"><?=__('发布对话：')?></dt>
                            <dd class="opt">
                                <textarea id="complain_talk" class="w400 textarea_text"></textarea>
                                <div>
                                    <label class="submit-border"><input id="btn_publish" type="button" class="submit"
                                                                        value="<?=__('发布对话')?>"></label>
                                    <label class="submit-border"><input id="btn_refresh" type="button" class="submit"
                                                                        value="<?=__('刷新对话')?>"></label>
                                </div>
                            </dd>
                        </dl>

                        <dl class="foot">
                            <dt>&nbsp;</dt>
                            <?php if ($data['complain_state_etext'] == "talk")
                            { ?>
                                <dd>
                                    <input id="handle_submit" type="button" class="button button_red bbc_seller_submit_btns" value="<?=__('提交仲裁')?>">
                                    <input id="handle_close" type="button" class="button button_red bbc_seller_submit_btns" value="<?=__('关闭投诉')?>"/>
                                </dd>
                            <?php } ?>
                        </dl>
                    </form>
                <?php }else{ ?>
					<h3><?=__('申诉详情')?></h3>
                    <dl>
                        <dt><?=__('申诉内容：')?></dt>
                        <dd><?= $data['appeal_message'] ?></dd>
                    </dl>
                    <dl>
                        <dt><?=__('申诉证据：')?></dt>
                        <dd><?php if (empty($data['appeal_pic_content']))
                            { ?>
                                <?=__('暂无图片')?>
                            <?php }
                            else
                            { ?>
                                <?php foreach ($data['appeal_pic_content'] as $v)
                            { ?>
                                <a href="<?= $v ?>" title="" class="lightbox"><img src="<?= $v ?>" width="72"
                                                                                   height="72"></a>
                            <?php } ?>
                            <?php } ?></dd>
                    </dl>

                    <h3><?=__('申诉对话')?></h3>
                    <!-- 对话详情（对话中，待仲裁，已关闭-对话记录） -->
                    <dl>
                        <dt class="tit"><?=__('对话记录：')?></dt>
                        <dd class="opt">
                            <div class="ncm-complain-talk">
                            </div>
                        </dd>
                    </dl>
				<?php } ?>
            </div>
        </div>
    </div>
    <link href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
    <script>

        $('a.lightbox').lightBox();

        var complain_id = $('#complain_id').val();

        $('#filePicker').click(function ()
        {
            $(function ()
            {
                aloneImage = $.dialog({
                    content: 'url: ' + SITE_URL + '?ctl=Upload&met=image&typ=e',
                    data: {callback: getImageList},
                    height: 460
                })
            })

            function getImageList(imageList)
            {
                for (i = 0; i < imageList.length; i++)
                {
                    $('#fileList').append('<li><img src="' + imageList[i].src + '" /><input type="hidden" name="appeal_pic[]" value="' + imageList[i].src + '"></li>')
                }
            }
        })
        $(document).ready(function ()
        {
            var act = "<?=$data['complain_state_etext']?>";

            if (act == 'appeal')
            {
                var ajax_url = 'index.php?ctl=Seller_Service_Complain&met=appealComplain&typ=json';
                var field = {
                    'appeal_message': 'required',
                }
            }
            if (act == 'talk')
            {
                var ajax_url = 'index.php?ctl=Seller_Service_Complain&met=submitComplain&typ=json';
            }

            $('#form').validator({
                ignore: ':hidden',
                theme: 'yellow_right',
                timely: 1,
                stopOnError: false,
                fields: field,
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
                                location.href = "./index.php?ctl=Seller_Service_Complain&met=index&act=detail&id=<?=$data['id']?>";
                            }
                            else
                            {
                                Public.tips.error('<?=__('操作失败！')?>');
                            }
                        }
                    });
                }

            });

            $("#handle_appeal").click(function ()
            {
                $('#form').submit();
            });

            $("#handle_submit").click(function ()
            {
                $.ajax({
                    type: 'POST',
                    url: "index.php?ctl=Seller_Service_Complain&met=submitComplain&typ=json",
                    data: "complain_id=" + complain_id,
                    success: function (a)
                    {
                        if (a.status == 200)
                        {
                            location.href = "./index.php?ctl=Seller_Service_Complain&met=index&act=detail&id=<?=$data['id']?>";
                        }
                        else
                        {
                            Public.tips.error('<?=__('操作失败！')?>');
                        }
                    }
                });
            });

            $("#handle_close").click(function ()
            {
                $.ajax({
                    type: 'POST',
                    url: "index.php?ctl=Seller_Service_Complain&met=closeComplain&typ=json",
                    data: "complain_id=" + complain_id,
                    success: function (a)
                    {
                        if (a.status == 200)
                        {
                            location.href = "./index.php?ctl=Seller_Service_Complain&met=index&act=detail&id=<?=$data['id']?>";
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
            $(".ncm-complain-talk").empty();
            $.ajax({
                type: 'POST',
                url: SITE_URL + '?ctl=Seller_Service_Complain&met=getComplainTalk&typ=json',
                cache: false,
                data: "complain_id=" + complain_id,
                dataType: 'json',
                success: function (data)
                {
                    if (Object.getOwnPropertyNames(data.data).length >= 1)
                    {
                        for (var i = 0; i < Object.getOwnPropertyNames(data.data).length - 1; i++)
                        {   console.log(data.data[i]);
                            var link = "<p class=''>" + data.data[i].talk_datetime + data.data[i].member_type + '(' + data.data[i].user_name + ')说：' + data.data[i].talk_content + "</p>";
                            $(".ncm-complain-talk").append(link);
                        }
                    }
                    else
                    {
                        $(".ncm-complain-talk").append("<p class='placeholder'>" + "<?=__('目前没有对话')?>" + "</p>");
                    }
                },
                error: function ()
                {
                    $(".ncm-complain-talk").append("<p class='placeholder'>" + "<?=__('目前没有对话')?>" + "</p>");
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
                url: SITE_URL + '?ctl=Seller_Service_Complain&met=publishComplainTalk&typ=json',
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
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>