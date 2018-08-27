<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>

    <link href="<?= $this->view->css_com ?>/jquery/plugins/lightbox/css/jquery.lightbox.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.lightbox.min.js"></script>

    <div class="aright">
        <div class="member_infor_content">
            <div class="div_head  tabmenu clearfix">
                <ul class="tab pngFix clearfix">
                    <li class="active">
                        <a><?=__('举报详情')?></a>
                    </li>
                </ul>
            </div>
        <div class="ncm-flow-layout" id="ncmComplainFlow">
            <div class="ncm-flow-container" style="width: 100%;">
                <div class="ncm-flow-step" style="text-align: center;">
                    <dl id="state_new" class="step-first current1">
                        <dt><?=__('填写举报内容')?></dt>
                        <dd class="bg"></dd>
                    </dl>
                    <dl id="state_new" class="current1">
                        <dt><?=__('平台审核处理')?></dt>
                        <dd class="bg"></dd>
                    </dl>
                    <dl id="state_new" class="<?php if($data['state_etext']=="done"){echo "current1";}?>">
                        <dt><?=__('举报完成')?></dt>
                        <dd class="bg"></dd>
                    </dl>
                </div>
                <div class="ncm-default-form">
                    <h3><?=__('举报信息')?></h3>
                    <dl>
                        <dt><?=__('被举报店铺：')?></dt>
                        <dd><?= $data['shop_name'] ?></dd>
                        </dl>
                    <dl>
                        <dt><?=__('被举报商品：')?></dt>
                        <dd><a target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $data['goods_id'] ?>"><?= $data['goods_name'] ?></a></dd>
                        </dl>
                    <dl>
                        <dt><?=__('举报类别：')?></dt>
                        <dd><?= $data['report_type_name'] ?></dd>
                        </dl>
                    <dl>
                        <dt><?=__('举报主题：')?></dt>
                        <dd><?= $data['report_subject_name'] ?></dd>
                        </dl>
                    <dl>
                        <dt><?=__('举报内容：')?></dt>
                        <dd><?= $data['report_message'] ?></dd>
                        </dl>
                    <dl>
                        <dt><?=__('举报时间：')?></dt>
                        <dd><?= $data['report_date'] ?></dd>
                        </dl>
                    <dl>
                        <dt><?=__('投诉证据：')?></dt>
                        <dd>
                            <?php if (empty($data['pic']))
                            { ?>
                                <?=__('暂无图片')?>
                            <?php }
                            else
                            { ?>
                                <?php foreach ($data['pic'] as $v)
                            { ?>
                                <a href="<?= $v ?>" title="" class="lightbox"><img src="<?= $v ?>" width="72"
                                                                                   height="72"></a>
                            <?php } ?>
                            <?php } ?>
                        </dd>
                    </dl>

                    <?php if ($data['state_etext'] == "done")
                    { ?>
                        <h3><?=__('处理结果')?></h3>
                        <dl>
                            <dt><?=__('处理结果：')?></dt>
                            <dd><?= $data['handle_text'] ?></dd>
                            </dl>
                        <dl>
                            <dt><?=__('备注：')?></dt>
                            <dd><?= $data['report_handle_message'] ?></dd>
                        </dl>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <link href="./shop/static/common/css/jquery/plugins/dialog/green.css" rel="stylesheet">
        <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.dialog.js"></script>
        <script>
            $(document).ready(function () {
                $('a.lightbox').lightBox();
            });
        </script>
    </div>
    </div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>