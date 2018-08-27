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
                    <a><?=__('咨询详情')?></a>
                </li>
            </ul>
        </div>
    <div class="ncm-flow-layout" id="ncmComplainFlow">
        <div class="ncm-flow-container" style="width: 100%;">
            <div class="ncm-flow-step" style="text-align: center;">
                <dl id="state_new" class="step-first current1">
                    <dt><?=__('填写咨询内容')?></dt>
                    <dd class="bg"></dd>
                </dl>
                <dl id="state_new" <?php if($data['custom_service_status']==Platform_CustomServiceModel::SERVICE_REPLY){echo "class='current1'";}?>>
                    <dt><?=__('平台客服回复')?></dt>
                    <dd class="bg"></dd>
                </dl>
                <dl id="state_new" <?php if($data['custom_service_status']==Platform_CustomServiceModel::SERVICE_REPLY){echo "class='current1'";}?>>
                    <dt><?=__('咨询完成')?></dt>
                    <dd class="bg"></dd>
                </dl>
            </div>
            <div class="ncm-default-form">
                <h3><?=__('咨询信息')?></h3>
                <dl>
                    <dt><?=__('咨询类型：')?></dt>
                    <dd><?=$data['type']['custom_service_type_name']?></dd>
                </dl>
                <dl>
                    <dt><?=__('咨询内容：')?></dt>
                    <dd><?=$data['custom_service_question']?></dd>
                    </dl>
                <dl>
                    <dt><?=__('咨询时间：')?></dt>
                    <dd><?= $data['custom_service_question_time'] ?></dd>
                    </dl>
                <dl>
                    <dt><?=__('咨询状态：')?></dt>
                    <dd><?= $data['custom_service_status_text'] ?></dd>
                    </dl>
                    <?php if($data['custom_service_status_etext']=="reply"){ ?>
                <dl>
                        <dt><?=__('最后回复时间：')?></dt>
                        <dd><?= $data['custom_service_answer_time'] ?></dd>
                    </dl>
                    <?php } ?>
                    <?php if($data['custom_service_status']==Platform_CustomServiceModel::SERVICE_REPLY){?>
                        <h3><?=__('回复信息')?></h3>
                        <dl>
                            <dt><?=__('回复内容：')?></dt>
                            <dd><?=$data['custom_service_answer']?></dd>
                            </dl>
                        <dl>
                            <dt><?=__('回复时间：')?></dt>
                            <dd><?=$data['custom_service_answer_time']?></dd>
                        </dl>
                    <?php } ?>
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