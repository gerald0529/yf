<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'buyer_header.php';
?>

<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/headfoot.css">
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/iconfont/iconfont.css">
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/index.css">
<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/style1.css">
<div class="aright">
<div class="order_content" id="ncmComplainFlow">
    <div class="member_infor_content">
        <div class="div_head  tabmenu clearfix">
            <ul class="tab pngFix clearfix">
                <li class="active">
                    <a><?=__('咨询平台客服')?></a>
                </li>
            </ul>
        </div>
        <div class="ncm-flow-step" style="text-align: center;">
            <dl class="step-first current">
                <dt><?=__('填写咨询内容')?></dt>
                <dd class="bg"></dd>
            </dl>
            <dl class="">
                <dt><?=__('平台客服回复')?></dt>
                <dd class="bg"></dd>
            </dl>
            <dl class="">
                <dt><?=__('咨询完成')?></dt>
                <dd class="bg"></dd>
            </dl>
    </div>
    <form id="form" action="#" method="post">

        <div class="div_Consultation">
            <div style="margin-left:90px;">
                <table>
                    <tr>
                        <td><?=__('咨询类型：')?></td>
                        <td style="width:88%; text-align: left; ">
                            <select name="custom_service_type_id">
                                <?php foreach ($data as $val)
                                { ?>
                                    <option
                                        value="<?= $val['custom_service_type_id'] ?>"><?= $val['custom_service_type_name'] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="margin-top:-45px;"><?=__('咨询内容:')?></div>
                        </td>
                        <td style="padding-bottom:30px;padding-top: 10px; text-align: left;">
                 <textarea name="custom_service_question" rows="5" cols="40" class="textarea_text"></textarea>
                        </td>
                    </tr>
                </table>
                <div style="margin-left:140px; padding-bottom:100px;margin-top:20px;">
                    <div class="div_abtn bbc_btns" id="handle_submit"><?=__('确认提交')?></div>
                </div>

            </div>
        </div>
    </form>
</div>
    </div>
</div>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js"
        charset="utf-8"></script>
<script>
    $(document).ready(function ()
    {
        $('#form').validator({
            ignore: ':hidden',
            theme: 'yellow_right',
            timely: 1,
            stopOnError: false,
            fields: {
                custom_service_question: "required;length[~255, true];"
            },
            valid: function (form)
            {
                //表单验证通过，提交表单
                $.ajax({
                    url: SITE_URL + '?ctl=Buyer_Service_Custom&met=addService&typ=json',
                    data: $("#form").serialize(),
                    success: function (a)
                    {
                        if (a.status == 200)
                        {
                            location.href = SITE_URL + '?ctl=Buyer_Service_Custom&met=index';

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
    });
</script>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>