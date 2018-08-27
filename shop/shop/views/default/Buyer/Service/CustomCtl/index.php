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
<div class="aright">
    <div class="member_infor_content">
<div class="order_content">
    <div class="div_head tabmenu clearfix">
        <ul class="tab pngFix clearfix">
            <li class="active"><a><?=__('平台客服咨询列表')?></a></li>

        </ul>
    </div>

    <div class="order_content_title clearfix">
        <div style="margin-top: 10px;" class="clearfix">
            <div class="ptkf bbc_btns">
                <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Custom&met=index&act=add&typ=e"><?=__('平台客服')?></a>
            </div>
        </div>
    </div>

        <table style="width: 100%;" class="icos bort">
            <tr>
                <td class="td_1" style="text-align: center;" width="415"><p><?=__('咨询内容')?></p></td>
                <td class="td_2" width="150"><p><?=__('咨询时间')?></p></td>
                <td class="td_3" width="100"><p><?=__('状态')?></p></td>
                <td class="td_3" width="150"><p><?=__('最后回复时间')?></p></td>
                <td class="td_4" width="150"><p><?=__('操作')?></p></td>
            </tr>
            <?php
            if (!empty($data['items']))
            {
            ?>
            <?php
            foreach ($data['items'] as $key => $value)
            {
                ?>
                <tr>
                    <td class="td_1" <?php if(strlen($value['custom_service_question'])>117){echo "style='text-align:left;'";}?>><p><?= $value['custom_service_question'] ?></p></td>
                    <td class="td_2"><p><?= $value['custom_service_question_time'] ?></p></td>
                    <td class="td_3"><p><?= $value['custom_service_status_text'] ?></p></td>
                    <?php if($value['custom_service_status_etext']=="reply"){ ?>
                        <td class="td_3"><p><?= $value['custom_service_answer_time'] ?></p></td>
                    <?php }else{ ?>
                        <td class="td_3"><p>无</p></td>
                    <?php } ?>
                    <td class="td_4"><p><span class="edit"><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Custom&met=index&act=detail&id=<?= $value['custom_service_id'] ?>"><i class="iconfont icon-chakan"></i><?=__('查看')?></a></span>
                            <span class="del del_line"><a
                                    data-param="{'ctl':'Buyer_Service_Custom','met':'delService','id':'<?= $value['custom_service_id'] ?>'}"
                                    href="javascript:void(0)"><i class="iconfont icon-lajitong"></i><?=__('删除')?></a></span></p></td>
                </tr>
            <?php } ?>
            <?php }
            else
            {
                ?>
                <tr>
                    <td colspan="99">
                        <div class="no_account">
                            <img src="<?= $this->view->img ?>/ico_none.png"/>
                            <p><?= __('暂无符合条件的数据记录') ?></p>
                        </div>
                    </td>
                </tr>
            <?php } ?>
           
        </table>


    <?php if($page_nav){ ?>
        <div class="page"><?=$page_nav?></div>
    <?php } ?>

</div>
    </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>



